<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Ports
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

namespace Jkphl\Respimgcss\Ports;

/**
 * Invalid argument exception
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Ports
 */
class InvalidArgumentException extends \Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException
{
    /**
     * Invalid CSS selector
     *
     * @var string
     */
    const INVALID_CSS_SELECTOR_STR = 'Invalid CSS selector "%s"';
    /**
     * Invalid CSS selector
     *
     * @var int
     */
    const INVALID_CSS_SELECTOR = 1522574161;
    /**
     * Invalid word token in source size value
     *
     * @var string
     */
    const INVALID_WORD_TOKEN_IN_SOURCE_SIZE_VALUE_STR = 'Invalid word token "%s" in source size value';
    /**
     * Invalid word token in source size value
     *
     * @var int
     */
    const INVALID_WORD_TOKEN_IN_SOURCE_SIZE_VALUE = 1522701212;
    /**
     * Invalid source size
     *
     * @var string
     */
    const INVALID_SOURCE_SIZE_STR = 'Invalid source size';
    /**
     * Invalid source size
     *
     * @var int
     */
    const INVALID_SOURCE_SIZE = 1523047851;
    /**
     * Invalid media condition
     *
     * @var string
     */
    const INVALID_MEDIA_CONDITION_STR = 'Invalid media condition';
    /**
     * Invalid media condition
     *
     * @var int
     */
    const INVALID_MEDIA_CONDITION = 1523084780;
    /**
     * Source sizes not allowed with resolution based image candidates
     *
     * @var string
     */
    const SIZES_NOT_ALLOWED_STR = 'Source sizes not allowed with resolution based image candidates';
    /**
     * Source sizes not allowed with resolution based image candidates
     *
     * @var int
     */
    const SIZES_NOT_ALLOWED = 1523091652;
}
