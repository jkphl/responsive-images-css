<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application
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

use ChrisKonnertz\StringCalc\Tokenizer\Token;
use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;
use Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException;
use Jkphl\Respimgcss\Application\Model\AbsoluteLength;
use Jkphl\Respimgcss\Application\Model\ViewportLength;

/**
 * AbstractLength factory for calc() based values
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application
 */
class CalcLengthFactory extends AbstractLengthFactory
{
    /**
     * Create a unit length from a calc() size string
     *
     * @param string $calcString calc() size string
     *
     * @return UnitLengthInterface
     * @throws InvalidArgumentException If the calc() string is ill-formatted
     */
    public function createLengthFromString(string $calcString): UnitLengthInterface
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
     * @return UnitLengthInterface Calculation node container
     */
    protected function createCalculationContainerFromString(string $calcString): UnitLengthInterface
    {
        $calculatorService = $this->calculatorServiceFactory->createCalculatorService();
        $calculationTokens = $calculatorService->tokenize($calcString);
        $refinedTokens     = $calculatorService->refineCalculationTokens($calculationTokens, $this->emPixel);

        // If there's the viewport involved in the calculation: Create a relative calculated length
        /** @var Token $token */
        foreach ($refinedTokens as $token) {
            if ($calculatorService->isViewportToken($token)) {
                return new ViewportLength(
                    $this->calculatorServiceFactory,
                    $refinedTokens,
                    $calcString
                );
            }
        }

        // Create and return an absolute length
        return new AbsoluteLength(
            $calculatorService->evaluate($refinedTokens),
            UnitLengthInterface::UNIT_PIXEL,
            $this->lengthNormalizerService
        );
    }
}
