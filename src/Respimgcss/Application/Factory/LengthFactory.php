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

use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;
use Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException;
use Jkphl\Respimgcss\Application\Model\AbsoluteLength;
use Jkphl\Respimgcss\Application\Model\PercentageLength;
use Jkphl\Respimgcss\Application\Model\ViewportLength;

/**
 * AbstractLength Factory
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application
 */
class LengthFactory extends AbstractLengthFactory
{
    /**
     * Absolute units
     *
     * @var array
     */
    const UNITS_ABSOLUTE = [
        UnitLengthInterface::UNIT_PIXEL,
        UnitLengthInterface::UNIT_EM,
        UnitLengthInterface::UNIT_REM,
        UnitLengthInterface::UNIT_CM,
        UnitLengthInterface::UNIT_MM,
        UnitLengthInterface::UNIT_IN,
        UnitLengthInterface::UNIT_PC,
        UnitLengthInterface::UNIT_PT,
    ];
    /**
     * Relative units
     *
     * @var array
     */
    const UNITS_RELATIVE = [
        UnitLengthInterface::UNIT_PERCENT,
        UnitLengthInterface::UNIT_VW,
    ];

    /**
     * Parse a length string and return a length with unit instance
     *
     * @param string $length AbstractLength string
     *
     * @return UnitLengthInterface AbstractLength with unit
     */
    public function createLengthFromString(string $length): UnitLengthInterface
    {
        $valueAndUnit = $this->matchValueAndUnit(
            $length,
            array_merge(self::UNITS_ABSOLUTE, self::UNITS_RELATIVE)
        );

        // If it's an absolute unit
        if (in_array($valueAndUnit[2], self::UNITS_ABSOLUTE)) {
            return new AbsoluteLength(floatval($valueAndUnit[1]), $valueAndUnit[2], $this->lengthNormalizerService);
        }

        return $this->makeRelativeInstance($valueAndUnit[1], $valueAndUnit[2]);
    }

    /**
     * Match the value and unit of a length descriptor
     *
     * @param string $length AbstractLength descriptor
     * @param array $units   Allowed units
     *
     * @return array Value and unit (PCRE match)
     */
    protected function matchValueAndUnit(string $length, array $units): array
    {
        $length = trim(strtolower($length));
        if (!strlen($length) || !preg_match('/^(\d+(?:\.\d+)?)('.implode('|', $units).')$/i', $length, $valueAndUnit)) {
            throw new InvalidArgumentException(
                sprintf(InvalidArgumentException::INVALID_LENGTH_STR, $length),
                InvalidArgumentException::INVALID_LENGTH
            );
        }

        return $valueAndUnit;
    }

    /**
     * Create a relative value with unit instance
     *
     * @param string $value Value
     * @param string $unit  Unit
     *
     * @return UnitLengthInterface Relative length with unit
     */
    protected function makeRelativeInstance(string $value, string $unit): UnitLengthInterface
    {
        // Viewport unit
        if ($unit === UnitLengthInterface::UNIT_VW) {
            $calculatorService = $this->calculatorServiceFactory->createCalculatorService();
            $tokens            = $calculatorService->tokenize(strval($value / 100).' *viewport()');

            return new ViewportLength($this->calculatorServiceFactory, $tokens, $value);
        }

        // Percentages
        return new PercentageLength(floatval($value));
    }

    /**
     * Parse a length string and return an absolute length with unit instance
     *
     * @param string $length AbstractLength string
     *
     * @return AbsoluteLength Absolute length with unit
     */
    public function createAbsoluteLengthFromString(string $length): AbsoluteLength
    {
        $valueAndUnit = $this->matchValueAndUnit($length, self::UNITS_ABSOLUTE);

        return new AbsoluteLength(floatval($valueAndUnit[1]), $valueAndUnit[2], $this->lengthNormalizerService);
    }
}
