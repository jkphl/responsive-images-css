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
    const INCH_IN_CM = 2.54;
    /**
     * Default display density
     *
     * @var int
     */
    const DEFAULT_DPI = 72;
    /**
     * Pixel to point ratio
     *
     * @var float
     */
    const PX_IN_PT = .75;

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
        $normalizeMethod = 'normalize'.ucfirst($unit).'Value';
        if (is_callable([$this, $normalizeMethod])) {
            return $this->$normalizeMethod($value);
        }

        throw new InvalidArgumentException(
            sprintf(InvalidArgumentException::INVALID_UNIT_STR, $unit),
            InvalidArgumentException::INVALID_UNIT
        );
    }

    /**
     * Normalize a pixel value
     *
     * @param float $value Value
     *
     * @return float Normalized value
     */
    protected function normalizePxValue($value): float
    {
        return $value;
    }

    /**
     * Normalize a rem value
     *
     * @param float $value Value
     *
     * @return float Normalized value
     */
    protected function normalizeRemValue($value): float
    {
        return $value * $this->emPixel;
    }

    /**
     * Normalize a em value
     *
     * @param float $value Value
     *
     * @return float Normalized value
     */
    protected function normalizeEmValue($value): float
    {
        return $value * $this->emPixel;
    }

    /**
     * Normalize a cm value
     *
     * @param float $value Value
     *
     * @return float Normalized value
     */
    protected function normalizeCmValue($value): float
    {
        return round($value * static::DEFAULT_DPI / static::INCH_IN_CM);
    }

    /**
     * Normalize a mm value
     *
     * @param float $value Value
     *
     * @return float Normalized value
     */
    protected function normalizeMmValue($value): float
    {
        return round($value * static::DEFAULT_DPI / (static::INCH_IN_CM * 10));
    }

    /**
     * Normalize a in value
     *
     * @param float $value Value
     *
     * @return float Normalized value
     */
    protected function normalizeInValue($value): float
    {
        return $value * static::DEFAULT_DPI;
    }

    /**
     * Normalize a pt value
     *
     * @param float $value Value
     *
     * @return float Normalized value
     */
    protected function normalizePtValue($value): float
    {
        return round($value / static::PX_IN_PT);
    }

    /**
     * Normalize a pc value
     *
     * @param float $value Value
     *
     * @return float Normalized value
     */
    protected function normalizePcValue($value): float
    {
        return round($value * 12 / static::PX_IN_PT);
    }
}