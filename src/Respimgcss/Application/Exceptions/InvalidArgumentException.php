<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Exceptions
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

namespace Jkphl\Respimgcss\Application\Exceptions;

/**
 * Invalid argument exception
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application
 */
class InvalidArgumentException extends \Jkphl\Respimgcss\Domain\Exceptions\InvalidArgumentException
{
    /**
     * Invalid length
     *
     * @var string
     */
    const INVALID_LENGTH_STR = 'Invalid length "%s"';
    /**
     * Invalid length
     *
     * @var int
     */
    const INVALID_LENGTH = 1522492102;
    /**
     * Invalid unit
     *
     * @var string
     */
    const INVALID_UNIT_STR = 'Invalid unit "%s"';
    /**
     * Invalid unit
     *
     * @var int
     */
    const INVALID_UNIT = 1522493474;
    /**
     * Invalid image candidate string
     *
     * @var string
     */
    const INVALID_IMAGE_CANDIDATE_STRING_STR = 'Invalid image candidate string "%s"';
    /**
     * Invalid image candidate string
     *
     * @var int
     */
    const INVALID_IMAGE_CANDIDATE_STRING = 1522500150;
    /**
     * Invalid image candidate file
     *
     * @var string
     */
    const INVALID_IMAGE_CANDIDATE_FILE_STR = 'Invalid image candidate file "%s"';
    /**
     * Invalid image candidate file
     *
     * @var int
     */
    const INVALID_IMAGE_CANDIDATE_FILE = 1522502569;
    /**
     * Invalid image candidate descriptor
     *
     * @var string
     */
    const INVALID_IMAGE_CANDIDATE_DESCRIPTOR_STR = 'Invalid image candidate descriptor "%s"';
    /**
     * Invalid image candidate descriptor
     *
     * @var int
     */
    const INVALID_IMAGE_CANDIDATE_DESCRIPTOR = 1522502721;
    /**
     * Inconsistent image candidate types
     *
     * @var string
     */
    const INCONSISTENT_IMAGE_CANDIDATE_TYPES_STR = 'Inconsistent image candidate types';
    /**
     * Inconsistent image candidate types
     *
     * @var int
     */
    const INCONSISTENT_IMAGE_CANDIDATE_TYPES = 1522504523;
    /**
     * Overlapping image candidate value
     *
     * @var string
     */
    const OVERLAPPING_IMAGE_CANDIDATE_VALUE_STR = 'Overlapping image candidate value "%s"';
    /**
     * Overlapping image candidate value
     *
     * @var int
     */
    const OVERLAPPING_IMAGE_CANDIDATE_VALUE = 1522504652;
    /**
     * Ill-formatted source size string
     *
     * @var string
     */
    const ILL_FORMATTED_SOURCE_SIZE_STRING_STR = 'Ill-formatted source size string "%s"';
    /**
     * Ill-formatted source size string
     *
     * @var int
     */
    const ILL_FORMATTED_SOURCE_SIZE_STRING = 1522685593;
    /**
     * Ill-formatted calc() length string
     *
     * @var string
     */
    const ILL_FORMATTED_CALC_LENGTH_STRING_STR = 'Ill-formatted calc() length string "%s"';
    /**
     * Ill-formatted calc() length string
     *
     * @var int
     */
    const ILL_FORMATTED_CALC_LENGTH_STRING = 1522687100;
    /**
     * Non well formed numeric value
     *
     * @var string
     */
    const NON_WELL_FORMED_NUMERIC_VALUE_STR = 'Non well formed numeric value "%s"';
    /**
     * Non well formed numeric value
     *
     * @var int
     */
    const NON_WELL_FORMED_NUMERIC_VALUE = 1523097732;
}