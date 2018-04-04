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

use ChrisKonnertz\StringCalc\Tokenizer\Token;
use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;
use Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException;
use Jkphl\Respimgcss\Application\Model\AbsoluteLength;
use Jkphl\Respimgcss\Application\Model\PercentageLength;
use Jkphl\Respimgcss\Application\Model\StringCalculator;
use Jkphl\Respimgcss\Application\Model\ViewportLength;
use Jkphl\Respimgcss\Application\Service\LengthNormalizerService;
use Jkphl\Respimgcss\Domain\Contract\AbsoluteLengthInterface;
use Jkphl\Respimgcss\Domain\Contract\LengthFactoryInterface;

/**
 * AbstractLength Factory
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Factory
 */
class LengthFactory implements LengthFactoryInterface
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
     * EM to pixel ratio
     *
     * @var int
     */
    protected $emPixel;
    /**
     * Length normalizer service
     *
     * @var LengthNormalizerService
     */
    protected $lengthNormalizerService;

    /**
     * Length factory constructor
     *
     * @param int $emPixel EM to pixel ratio
     */
    public function __construct(int $emPixel)
    {
        $this->emPixel                 = $emPixel;
        $this->lengthNormalizerService = new LengthNormalizerService($this->emPixel);
    }

    /**
     * Parse a length string and return a length with unit instance
     *
     * @param string $length AbstractLength string
     * @param int $emPixel   EM to pixel ratio
     *
     * @return UnitLengthInterface AbstractLength with unit
     * @throws \ChrisKonnertz\StringCalc\Exceptions\ContainerException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\InvalidIdentifierException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\NotFoundException
     */
    public static function createLengthFromString(string $length, int $emPixel = 16): UnitLengthInterface
    {
        $valueAndUnit = self::matchValueAndUnit($length);

        return self::makeInstance($valueAndUnit[1], $valueAndUnit[2], $emPixel);
    }

    /**
     * Match the value and unit of a length descriptor
     *
     * @param string $length AbstractLength descriptor
     *
     * @return array Value and unit (PCRE match)
     * @throws InvalidArgumentException If the length string is invalid
     */
    protected static function matchValueAndUnit($length): array
    {
        $length = trim(strtolower($length));
        if (!strlen($length) || !preg_match('/^(\d+(?:\.\d+)?)([pxremcintvw%]+)$/i', $length, $valueAndUnit)) {
            throw new InvalidArgumentException(
                sprintf(InvalidArgumentException::INVALID_LENGTH_STR, $length),
                InvalidArgumentException::INVALID_LENGTH
            );
        }

        return $valueAndUnit;
    }

    /**
     * Create a value with unit instance
     *
     * @param string $value Value
     * @param string $unit  Unit
     * @param int $emPixel  EM to pixel ratio
     *
     * @return UnitLengthInterface AbstractLength with unit
     * @throws InvalidArgumentException If the unit is invalid
     * @throws \ChrisKonnertz\StringCalc\Exceptions\ContainerException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\InvalidIdentifierException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\NotFoundException
     */
    protected static function makeInstance(string $value, string $unit, int $emPixel): UnitLengthInterface
    {
        // If it's an absolute unit
        if (in_array($unit, self::UNITS_ABSOLUTE)) {
            return new AbsoluteLength(floatval($value), $unit, new LengthNormalizerService($emPixel));
        }

        switch ($unit) {
            case UnitLengthInterface::UNIT_VW: // Viewport unit
                $stringCalc = new StringCalculator();

                return new ViewportLength(
                    $stringCalc->parse(
                        [
                            new Token(strval($value / 100), Token::TYPE_NUMBER, 0),
                            new Token('*', Token::TYPE_CHARACTER, 0),
                            new Token('viewport', Token::TYPE_WORD, 0),
                            new Token('(', Token::TYPE_CHARACTER, 0),
                            new Token(')', Token::TYPE_CHARACTER, 0),
                        ]
                    ),
                    new LengthNormalizerService($emPixel),
                    $value
                );

            case UnitLengthInterface::UNIT_PERCENT: // Percentages
                return new PercentageLength(floatval($value));

            default: // Invalid unit
                throw new InvalidArgumentException(
                    sprintf(InvalidArgumentException::INVALID_UNIT_STR, $unit),
                    InvalidArgumentException::INVALID_UNIT
                );
        }
    }

    /**
     * Create an absolute length
     *
     * @param float $value Value
     *
     * @return AbsoluteLengthInterface Absolute length
     */
    public function createAbsoluteLength(float $value): AbsoluteLengthInterface
    {
        return new AbsoluteLength($value, UnitLengthInterface::UNIT_PIXEL, $this->lengthNormalizerService);
    }
}