<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Service
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

namespace Jkphl\Respimgcss\Application\Service;

use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;
use Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException;

/**
 * Length normalizer
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application
 */
class LengthNormalizerService
{
    /**
     * EM to pixel ratio
     *
     * @var int
     */
    protected $emPixel;
    /**
     * Inch to Centimeter ratio
     *
     * @var float
     */
    CONST INCH_IN_CM = 2.54;
    /**
     * Default display density
     *
     * @var int
     */
    CONST DEFAULT_DPI = 72;
    /**
     * Pixel to point ratio
     *
     * @var float
     */
    CONST PX_IN_PT = .75;

    /**
     * Length normalizer constructor
     *
     * @param int $emPixel EM to pixel ratio
     */
    public function __construct(int $emPixel)
    {
        $this->emPixel = $emPixel;
    }

    /**
     * Normalize and return a value with unit
     *
     * @param float $value Value
     * @param string $unit Unit
     *
     * @return float Normalized value
     * @throws InvalidArgumentException If the unit is invalid
     */
    public function normalize(float $value, string $unit)
    {
        $normalized = null;

        switch ($unit) {
            case UnitLengthInterface::UNIT_PIXEL:
                $normalized = $value;
                break;
            case UnitLengthInterface::UNIT_REM:
            case UnitLengthInterface::UNIT_EM:
                $normalized = $value * $this->emPixel;
                break;
            case UnitLengthInterface::UNIT_CM:
                $normalized = round($value * static::DEFAULT_DPI / static::INCH_IN_CM);
                break;
            case UnitLengthInterface::UNIT_MM:
                $normalized = round($value * static::DEFAULT_DPI / (static::INCH_IN_CM * 10));
                break;
            case UnitLengthInterface::UNIT_IN:
                $normalized = $value * static::DEFAULT_DPI;
                break;
            case UnitLengthInterface::UNIT_PT:
                $normalized = round($value / static::PX_IN_PT);
                break;
            case UnitLengthInterface::UNIT_PC:
                $normalized = round($value * 12 / static::PX_IN_PT);
                break;
            default:
                throw new InvalidArgumentException(
                    sprintf(InvalidArgumentException::INVALID_UNIT_STR, $unit),
                    InvalidArgumentException::INVALID_UNIT
                );
        }

        return $normalized;
    }
}