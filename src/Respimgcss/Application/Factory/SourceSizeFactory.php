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
use Jkphl\Respimgcss\Application\Model\SourceSize;
use Jkphl\Respimgcss\Application\Model\SourceSizeMediaCondition;
use Jkphl\Respimgcss\Domain\Contract\LengthInterface;
use Jkphl\Respimgcss\Domain\Model\Css\ResolutionMediaCondition;
use Jkphl\Respimgcss\Domain\Model\Css\WidthMediaCondition;

/**
 * Source size factory
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application
 */
class SourceSizeFactory extends AbstractLengthFactory
{
    /**
     * Create a source size value from a source size string
     *
     * @param string $sourceSizeStr Source size string
     *
     * @return SourceSize Source size
     */
    public function createFromSourceSizeStr(string $sourceSizeStr): SourceSize
    {
        // Determine the source size value
        $sourceSizeValue = $this->parseSourceSizeValue($sourceSizeStr);

        // Determine the associated media condition (if any)
        $mediaCondition = $this->parseMediaCondition($sourceSizeStr);

        // Return a source size instance
        return new SourceSize($sourceSizeValue, $mediaCondition);
    }

    /**
     * Parse the length component
     *
     * @param string $sourceSizeStr Source size string
     *
     * @return UnitLengthInterface AbstractLength component
     * @throws InvalidArgumentException If the source size string is ill-formatted
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
        return (new LengthFactory($this->calculatorServiceFactory, $this->emPixel))
            ->createLengthFromString($sourceSizeStrMatch[2]);
    }

    /**
     * Parse a calc() based length value
     *
     * @param string $sourceSizeStr Source size string
     *
     * @return UnitLengthInterface AbstractLength component
     * @throws InvalidArgumentException If the source size string is ill-formatted
     */
    protected function parseSourceSizeCalculatedValue(string &$sourceSizeStr): UnitLengthInterface
    {
        // Reverse-consume the source size string
        $sourceSizeRev = strrev($sourceSizeStr);
        $balance       = null;
        for ($pos = 0; $pos < strlen($sourceSizeStr); ++$pos) {
            $balance += $this->getCharacterBalance($sourceSizeRev[$pos]);
            if ($balance === 0) {
                $length        = (new CalcLengthFactory($this->calculatorServiceFactory, $this->emPixel))
                    ->createLengthFromString(substr($sourceSizeStr, -($pos + 5)));
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
        return ($char === '(') ? 1 : (($char === ')') ? -1 : 0);
    }

    /**
     * Parse and instantiate a source size media condition
     *
     * @param string $mediaConditionString
     *
     * @return SourceSizeMediaCondition|null Source size media condition
     * @see https://drafts.csswg.org/mediaqueries-4/#typedef-media-condition
     * @see https://developer.mozilla.org/de/docs/Web/CSS/Media_Queries/Using_media_queries#Pseudo-BNF_(for_those_of_you_that_like_that_kind_of_thing)
     */
    protected function parseMediaCondition(string $mediaConditionString): ?SourceSizeMediaCondition
    {
        return new SourceSizeMediaCondition(
            $mediaConditionString,
            array_merge(
                $this->parseWidthMediaConditions($mediaConditionString),
                $this->parseResolutionMediaConditions($mediaConditionString)
            )
        );
    }

    /**
     * Extract width media conditions
     *
     * @param string $mediaConditionString Media condition string
     *
     * @return WidthMediaCondition[] Width media conditions
     */
    protected function parseWidthMediaConditions(string $mediaConditionString): array
    {
        $widthMediaConditions  = [];
        $widthConditionMatches = $this->matchMediaConditions(
            $mediaConditionString,
            // width | min-width | max-width
            // device-width | min-device-width | max-device-width
            '/((?:min|max)\-)?(?:device\-)?width\s*\:\s*/'
        );

        foreach ($widthConditionMatches as $match) {
            try {
                $widthMediaConditions[] = new WidthMediaCondition(
                    $this->parseWidthMediaConditionValue($match[0]),
                    $match[1]
                );
            } catch (\Exception $e) {
                continue;
            }
        }

        return $widthMediaConditions;
    }

    /**
     * Match a media condition string
     *
     * @param string $mediaConditionString Media condition string
     * @param string $pattern              PCRE pattern
     *
     * @return array Matches
     */
    protected function matchMediaConditions(string $mediaConditionString, string $pattern): array
    {
        preg_match_all($pattern, $mediaConditionString, $matches, PREG_OFFSET_CAPTURE);
        $refinedMatches = [];
        foreach ($matches[0] as $index => $match) {
            $matchLength      = strlen($match[0]) + $match[1];
            $matchModifier    = is_array($matches[1][$index]) ? $matches[1][$index][0] : '';
            $refinedMatches[] = [substr($mediaConditionString, $matchLength), $matchModifier];
        }

        return $refinedMatches;
    }

    /**
     * Parse a width media condition value
     *
     * @param string $widthMediaConditionStr Width media condition string
     *
     * @return AbsoluteLength Width media condition value
     */
    protected function parseWidthMediaConditionValue(string $widthMediaConditionStr): AbsoluteLength
    {
        $widthMediaConditionValueStr = $this->shiftMediaConditionValue($widthMediaConditionStr);

        // Try to parse as simple unit length
        try {
            return (new LengthFactory($this->calculatorServiceFactory, $this->emPixel))
                ->createAbsoluteLengthFromString($widthMediaConditionValueStr);
        } catch (InvalidArgumentException $e) {
            // Skip
        }

        // Try to parse as calc() length
        return (new CalcLengthFactory($this->calculatorServiceFactory, $this->emPixel))
            ->createLengthFromString($widthMediaConditionValueStr);
    }

    /**
     * Shift a media condition value off the beginning of a media condition string
     *
     * @param string $mediaConditionValueStr Media condition string
     *
     * @return string Media condition value string
     */
    protected function shiftMediaConditionValue(string $mediaConditionValueStr): string
    {
        $stringLength = strlen($mediaConditionValueStr);
        $balance      = 1;
        for ($char = 0; $char < $stringLength; ++$char) {
            $balance += $this->getCharacterBalanceModifier($mediaConditionValueStr[$char]);
            if ($balance == 0) {
                return substr($mediaConditionValueStr, 0, $char);
            }
        }

        return $mediaConditionValueStr;
    }

    /**
     * Return the balance modifier for a particular character
     *
     * @param string $char Character
     *
     * @return int Balance modifier
     */
    protected function getCharacterBalanceModifier(string $char): int
    {
        return ($char == ')') ? -1 : (($char == '(') ? 1 : 0);
    }

    /**
     * Extract resolution media conditions
     *
     * @param string $mediaConditionString Media condition string
     *
     * @return ResolutionMediaCondition[] Resolution media conditions
     */
    protected function parseResolutionMediaConditions(string $mediaConditionString): array
    {
        $resolutionMediaConditions  = [];
        $resolutionConditionMatches = $this->matchMediaConditions(
            $mediaConditionString,
            // resolution | min-resolution | max-resolution
            '/((?:min|max)\-)?resolution\s*\:\s*/'
        );

        foreach ($resolutionConditionMatches as $match) {
            try {
                $resolutionMediaConditions[] = new ResolutionMediaCondition(
                    $this->parseResolutionMediaConditionValue($match[0]),
                    $match[1]
                );
            } catch (\Exception $e) {
                continue;
            }
        }

        return $resolutionMediaConditions;
    }

    /**
     * Parse a resolution media condition value
     *
     * @param string $resolutionMediaConditionStr Resolution media condition string
     *
     * @return LengthInterface Resolution media condition value
     * @throws InvalidArgumentException If the value is not numeric
     */
    protected function parseResolutionMediaConditionValue(string $resolutionMediaConditionStr): AbsoluteLength
    {
        $resolutionMediaConditionValueStr = $this->shiftMediaConditionValue($resolutionMediaConditionStr);

        // If the value is not numeric
        if (!is_numeric($resolutionMediaConditionValueStr)) {
            throw new InvalidArgumentException(
                sprintf(InvalidArgumentException::NON_WELL_FORMED_NUMERIC_VALUE_STR, $resolutionMediaConditionValueStr),
                InvalidArgumentException::NON_WELL_FORMED_NUMERIC_VALUE
            );
        }

        return $this->createAbsoluteLength(floatval($resolutionMediaConditionValueStr));
    }
}
