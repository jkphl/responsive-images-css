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

use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;
use Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException;
use Sabberworm\CSS\Parser;

/**
 * Source size factory
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Factory
 */
class SourceSizeFactory extends AbstractLengthFactory
{
    /**
     * Create a source size value from a source size string
     *
     * @param string $sourceSizeStr Source size string
     *
     * @return UnitLengthInterface Source size value
     * @throws \ChrisKonnertz\StringCalc\Exceptions\ContainerException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\InvalidIdentifierException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\NotFoundException
     */
    public function createFromSourceSizeStr(string $sourceSizeStr)
    {
        // Determine the source size value
        $sourceSizeValue = $this->parseSourceSizeValue($sourceSizeStr);

        // Determine the associated media condition (if any)
        $mediaCondition = $this->parseMediaCondition($sourceSizeStr);


        return $sourceSizeValue;
    }

    /**
     * Parse the length component
     *
     * @param string $sourceSizeStr Source size string
     *
     * @return UnitLengthInterface AbstractLength component
     * @throws InvalidArgumentException If the source size string is ill-formatted
     * @throws \ChrisKonnertz\StringCalc\Exceptions\ContainerException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\InvalidIdentifierException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\NotFoundException
     */
    protected function parseSourceSizeValue(string &$sourceSizeStr): UnitLengthInterface
    {
        // If the source size string ends with a parenthesis: Try to parse a calc() base length
        if (substr($sourceSizeStr, -1) === ')') {
            return $this->parseSourceSizeCalculatedValue($sourceSizeStr);
        }

        // If the source size string is ill-formatted
        if (!preg_match('/^(.*\s+)?([^\s]+)$/', $sourceSizeStr, $sourceSizeStrMatch)) {
            throw new InvalidArgumentException(
                sprintf(InvalidArgumentException::ILL_FORMATTED_SOURCE_SIZE_STRING_STR, $sourceSizeStr),
                InvalidArgumentException::ILL_FORMATTED_SOURCE_SIZE_STRING
            );
        }

        // Post-process the remaining string
        $sourceSizeStr = trim($sourceSizeStrMatch[1]);

        // Return the parsed length
        return (new LengthFactory($this->emPixel))->createLengthFromString($sourceSizeStrMatch[2], $this->emPixel);
    }

    /**
     * Parse a calc() based length value
     *
     * @param string $sourceSizeStr Source size string
     *
     * @return UnitLengthInterface AbstractLength component
     * @throws InvalidArgumentException If the source size string is ill-formatted
     * @throws \ChrisKonnertz\StringCalc\Exceptions\ContainerException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\InvalidIdentifierException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\NotFoundException
     */
    protected function parseSourceSizeCalculatedValue(string &$sourceSizeStr): UnitLengthInterface
    {
        // Reverse-consume the source size string
        $sourceSizeRev = strrev($sourceSizeStr);
        $balance       = null;
        for ($pos = 0; $pos < strlen($sourceSizeStr); ++$pos) {
            $balance += $this->getCharacterBalance($sourceSizeRev[$pos]);
            if ($balance === 0) {
                $length        = (new CalcLengthFactory($this->emPixel))->createFromString(
                    substr($sourceSizeStr, -($pos + 5))
                );
                $sourceSizeStr = trim(substr($sourceSizeStr, 0, -($pos + 5)));

                return $length;
            }
        }

        // Else: The source size string is ill-formatted
        throw new InvalidArgumentException(
            sprintf(InvalidArgumentException::ILL_FORMATTED_SOURCE_SIZE_STRING_STR, $sourceSizeStr),
            InvalidArgumentException::ILL_FORMATTED_SOURCE_SIZE_STRING
        );
    }

    /**
     * Return the balance value for a particular value
     *
     * @param string $char Character
     *
     * @return int Balance value
     */
    protected function getCharacterBalance($char): string
    {
        return ($char === ')') ? 1 : (($char === '(') ? -1 : 0);
    }


    /**
     * @param string $mediaConditionString
     * @see https://drafts.csswg.org/mediaqueries-4/#typedef-media-condition
     * @see https://developer.mozilla.org/de/docs/Web/CSS/Media_Queries/Using_media_queries#Pseudo-BNF_(for_those_of_you_that_like_that_kind_of_thing)
     */
    protected function parseMediaCondition(string $mediaConditionString)
    {
        $parser = new Parser('@media screen and (min-width: 10em) {div{display: block}}');
        print_r($parser->parse());
        // width | min-width | max-width
        // device-width | min-device-width | max-device-width
        // resolution | min-resolution | max-resolution
    }
}