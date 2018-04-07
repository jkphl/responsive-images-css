<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Model
 * @author     Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @copyright  Copyright © 2018 Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2018 Joschi Kuphal <joschi@kuphal.net> / @jkphl
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  the Software without restriction, including without limitation the rights to
 *  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 *  the Software, and to permit persons to whom the Software is furnished to do so,
 *  subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 *  FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 *  IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 ***********************************************************************************/

namespace Jkphl\Respimgcss\Infrastructure;

use ChrisKonnertz\StringCalc\Container\ContainerInterface;
use ChrisKonnertz\StringCalc\StringCalc;
use ChrisKonnertz\StringCalc\Tokenizer\Token;
use Jkphl\Respimgcss\Application\Contract\CalculatorServiceInterface;
use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;
use Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException as ApplicationInvalidArgumentException;
use Jkphl\Respimgcss\Application\Factory\LengthFactory;
use Jkphl\Respimgcss\Application\Model\AbsoluteLength;
use Jkphl\Respimgcss\Domain\Contract\AbsoluteLengthInterface;
use Jkphl\Respimgcss\Ports\InvalidArgumentException;

/**
 * Custom string calculator
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Model
 */
class ViewportCalculatorService extends StringCalc implements CalculatorServiceInterface
{
    /**
     * Custom string calculator constructor
     *
     * @param AbsoluteLengthInterface $viewport Viewport width
     * @param ContainerInterface $container     Container
     *
     * @throws \ChrisKonnertz\StringCalc\Exceptions\ContainerException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\InvalidIdentifierException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\NotFoundException
     */
    public function __construct(AbsoluteLengthInterface $viewport = null)
    {
        parent::__construct();
        $stringHelper     = $this->getContainer()->get('stringcalc_stringhelper');
        $viewportFunction = new ViewportFunction(
            $stringHelper,
            $viewport ?: (new LengthFactory(new ViewportCalculatorServiceFactory(), 16))->createAbsoluteLength(0)
        );
        $this->symbolContainer->add($viewportFunction);
    }

    /**
     * Evaluate calculation tokens
     *
     * @param Token[] $tokens Calculation tokens
     *
     * @return float Result
     * @throws \ChrisKonnertz\StringCalc\Exceptions\ContainerException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\NotFoundException
     */
    public function evaluate(array $tokens): float
    {
        $calculationRootNode = $this->parse($tokens);

        return $this->container->get('stringcalc_calculator')->calculate($calculationRootNode);
    }

    /**
     * Refine a list of calculation tokens
     *
     * @param array $tokens Calculation tokens
     * @param int $emPixel  EM to pixel ratio
     *
     * @return array Refined Calculation tokens
     */
    public function refineCalculationTokens(array $tokens, int $emPixel): array
    {
        $refinedTokens = [];
        $previousToken = null;

        // Run through all tokens
        foreach ($tokens as $token) {
            $previousToken = $this->handleToken($refinedTokens, $emPixel, $token, $previousToken);
        }

        // Add the last token
        if ($previousToken) {
            array_push($refinedTokens, $previousToken);
        }

        return $refinedTokens;
    }

    /**
     * Handle a particular token
     *
     * @param Token[] $refinedTokens    Refined tokens
     * @param int $emPixel              EM to pixel ratio
     * @param Token $token              Token
     * @param Token|null $previousToken Previous token
     *
     * @return Token|null               Stash token
     */
    protected function handleToken(
        array &$refinedTokens,
        int $emPixel,
        Token $token,
        Token $previousToken = null
    ): ?Token {
        // If it's a word token: Handle individually
        if ($token->getType() == Token::TYPE_WORD) {
            return $this->handleWordToken($refinedTokens, $emPixel, $token, $previousToken);
        }

        // Handle as simple token
        return $this->handleSimpleToken($refinedTokens, $token, $previousToken);
    }

    /**
     * Handle a simple token
     *
     * @param Token[] $refinedTokens    Refined tokens
     * @param Token $token              Token
     * @param Token|null $previousToken Previous token
     *
     * @return Token|null               Stash token
     */
    protected function handleSimpleToken(
        array &$refinedTokens,
        Token $token,
        Token $previousToken = null
    ): ?Token {
        // In all other cases: Register the previous token (if any)
        if ($previousToken) {
            array_push($refinedTokens, $previousToken);
        }

        // If it's a number token: Stash
        if ($token->getType() == Token::TYPE_NUMBER) {
            return $token;
        }

        array_push($refinedTokens, $token);

        return null;
    }

    /**
     * Handle a particular token
     *
     * The method returns a list of zero or more (possibly refined) tokens to preerve
     *
     * @param Token[] $refinedTokens    Refined tokens
     * @param int $emPixel              EM to pixel ratio
     * @param Token $token              Token
     * @param Token|null $previousToken Previous token
     *
     * @return Token|null               Stash token
     * @throws InvalidArgumentException If the word token is invalid
     */
    protected function handleWordToken(
        array &$refinedTokens,
        int $emPixel,
        Token $token,
        Token $previousToken = null
    ): ?Token {
        // If it's a calc() function call: Add the previous token and skip the current one
        if ($token->getValue() == 'calc') {
            return $this->handleCalcToken($refinedTokens, $previousToken);
        }

        // If the previous token is a number: Try to generate a unit length
        if ($previousToken && ($previousToken->getType() == Token::TYPE_NUMBER)) {
            try {
                $this->createAndHandleUnitLengthToken($refinedTokens, $emPixel, $token, $previousToken);

                return null;
            } catch (ApplicationInvalidArgumentException $e) {
                // Ignore
            }
        }

        // Invalid word token
        throw new InvalidArgumentException(
            sprintf(InvalidArgumentException::INVALID_WORD_TOKEN_IN_SOURCE_SIZE_VALUE_STR, $token->getValue()),
            InvalidArgumentException::INVALID_WORD_TOKEN_IN_SOURCE_SIZE_VALUE
        );
    }

    /**
     * Handle a calc() token
     *
     * @param array $refinedTokens      Refined tokens
     * @param Token|null $previousToken Previous token
     *
     * @return null
     */
    protected function handleCalcToken(array &$refinedTokens, Token $previousToken = null)
    {
        if ($previousToken) {
            array_push($refinedTokens, $previousToken);
        }

        return null;
    }

    /**
     * Create and handle a unit length token
     *
     * @param Token[] $refinedTokens Refined tokens
     * @param int $emPixel           EM to pixel ratio
     * @param Token $token           Token
     * @param Token $previousToken   Previous token
     */
    protected function createAndHandleUnitLengthToken(
        array &$refinedTokens,
        int $emPixel,
        Token $token,
        Token $previousToken
    ): void {
        $unitLength = (new LengthFactory(new ViewportCalculatorServiceFactory(), $emPixel))
            ->createLengthFromString($previousToken->getValue().$token->getValue());

        // If it's an absolute value
        if ($unitLength instanceof AbsoluteLength) {
            array_push($refinedTokens, new Token(strval($unitLength->getValue()), Token::TYPE_NUMBER, 0));

            return;
        }

        $this->handleUnitLengthToken($refinedTokens, $unitLength);
    }

    /**
     * Handle a unit length token
     *
     * @param Token[] $refinedTokens          Refined tokens
     * @param UnitLengthInterface $unitLength Unit length
     */
    protected function handleUnitLengthToken(
        array &$refinedTokens,
        UnitLengthInterface $unitLength
    ): void {
        array_push(
            $refinedTokens,
            new Token('(', Token::TYPE_CHARACTER, 0),
            new Token(strval($unitLength->getOriginalValue() / 100), Token::TYPE_NUMBER, 0),
            new Token('*', Token::TYPE_CHARACTER, 0),
            new Token('viewport', Token::TYPE_WORD, 0),
            new Token('(', Token::TYPE_CHARACTER, 0),
            new Token(')', Token::TYPE_CHARACTER, 0),
            new Token(')', Token::TYPE_CHARACTER, 0)
        );
    }

    /**
     * Test whether a calculation token is a viewport token
     *
     * @param mixed $token Calculation token
     *
     * @return bool Is viewport token
     */
    public function isViewportToken($token): bool
    {
        return ($token instanceof Token)
               && ($token->getType() == Token::TYPE_WORD)
               && ($token->getValue() === 'viewport');
    }
}