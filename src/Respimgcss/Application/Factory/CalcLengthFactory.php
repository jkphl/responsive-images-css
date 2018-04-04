<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Factory
 * @author     Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @copyright  Copyright © 2018 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
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

namespace Jkphl\Respimgcss\Application\Factory;

use ChrisKonnertz\StringCalc\Parser\Nodes\ContainerNode;
use ChrisKonnertz\StringCalc\Tokenizer\Token;
use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;
use Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException;
use Jkphl\Respimgcss\Application\Model\AbsoluteLength;
use Jkphl\Respimgcss\Application\Model\StringCalculator;
use Jkphl\Respimgcss\Application\Model\ViewportLength;

/**
 * AbstractLength factory for calc() based values
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Factory
 */
class CalcLengthFactory extends AbstractLengthFactory
{
    /**
     * Create a unit length from a calc() size string
     *
     * @param string $calcString calc() size string
     *
     * @return UnitLengthInterface
     * @throws \ChrisKonnertz\StringCalc\Exceptions\ContainerException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\InvalidIdentifierException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\NotFoundException
     */
    public function createFromString(string $calcString): UnitLengthInterface
    {
        // If the calc() string is ill-formatted
        if (!preg_match('/^calc\(.+\)$/', $calcString)) {
            throw new InvalidArgumentException(
                sprintf(InvalidArgumentException::ILL_FORMATTED_CALC_LENGTH_STRING_STR, $calcString),
                InvalidArgumentException::ILL_FORMATTED_CALC_LENGTH_STRING
            );
        }

        return $this->createCalculationContainerFromString($calcString);
    }

    /**
     * Parse a calculation string and return a precompiled calculation node container
     *
     * @param string $calcString Calculation string
     *
     * @return ContainerNode Calculation node container
     * @throws \ChrisKonnertz\StringCalc\Exceptions\ContainerException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\InvalidIdentifierException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\NotFoundException
     */
    protected function createCalculationContainerFromString(string $calcString): UnitLengthInterface
    {
        $stringCalc    = new StringCalculator();
        $calcTokens    = $stringCalc->tokenize($calcString);
        $refinedTokens = $this->refineCalculationTokens($calcTokens);

        // If there's the viewport involved in the calculation: Create a relative calculated length
        /** @var Token $token */
        foreach ($refinedTokens as $token) {
            if (($token->getType() == Token::TYPE_WORD) && ($token->getValue() === 'viewport')) {
                return new ViewportLength(
                    $stringCalc->parse($refinedTokens),
                    $this->lengthNormalizerService,
                    $calcString
                );
            }
        }

        // Create and return an absolute length
        return new AbsoluteLength(
            $stringCalc->calculate($refinedTokens),
            UnitLengthInterface::UNIT_PIXEL,
            $this->lengthNormalizerService
        );
    }

    /**
     * Refine a list of symbol tokens
     *
     * @param Token[] $tokens Symbol tokens
     *
     * @return Token[] Refined symbol tokens
     * @throws \ChrisKonnertz\StringCalc\Exceptions\ContainerException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\InvalidIdentifierException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\NotFoundException
     */
    protected function refineCalculationTokens(array $tokens): array
    {
        $refinedTokens = [];
        $previousToken = null;

        // Run through all tokens
        foreach ($tokens as $token) {
            $previousToken = $this->handleToken($refinedTokens, $token, $previousToken);
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
     * @param Token $token              Token
     * @param Token|null $previousToken Previous token
     *
     * @return Token|null               Stash token
     * @throws \ChrisKonnertz\StringCalc\Exceptions\ContainerException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\InvalidIdentifierException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\NotFoundException
     */
    protected function handleToken(array &$refinedTokens, Token $token, Token $previousToken = null): ?Token
    {
        // If it's a word token: Handle individually
        if ($token->getType() == Token::TYPE_WORD) {
            return $this->handleWordToken($refinedTokens, $token, $previousToken);
        }

        // In all other cases: Register the previou token (if any)
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
     * @param Token $token              Token
     * @param Token|null $previousToken Previous token
     *
     * @return Token|null               Stash token
     * @throws InvalidArgumentException If the word token is invalid
     * @throws \ChrisKonnertz\StringCalc\Exceptions\ContainerException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\InvalidIdentifierException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\NotFoundException
     */
    protected function handleWordToken(array &$refinedTokens, Token $token, Token $previousToken = null): ?Token
    {
        // If it's a calc() function call: Add the previous token and skip the current one
        if ($token->getValue() == 'calc') {
            if ($previousToken) {
                array_push($refinedTokens, $previousToken);
            }

            return null;
        }

        // If the previous token is a number: Try to generate a unit length
        if ($previousToken && ($previousToken->getType() == Token::TYPE_NUMBER)) {
            try {
                $unitLength = (new LengthFactory($this->emPixel))->createLengthFromString(
                    $previousToken->getValue().$token->getValue()
                );
                $this->handleUnitLengthToken($refinedTokens, $unitLength);

                return null;
            } catch (InvalidArgumentException $e) {
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
     * Handle a unit length token
     *
     * @param Token[] $refinedTokens          Refined tokens
     * @param UnitLengthInterface $unitLength Unit length
     */
    protected function handleUnitLengthToken(
        array &$refinedTokens,
        UnitLengthInterface $unitLength
    ): void {
        // If it's an absolute value
        if ($unitLength->isAbsolute()) {
            array_push($refinedTokens, new Token(strval($unitLength->getValue()), Token::TYPE_NUMBER, 0));

            return;
        }

        // Else: Substitute with multiplied function expression
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
}