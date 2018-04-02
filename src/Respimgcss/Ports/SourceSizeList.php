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

use Jkphl\Respimgcss\Application\Factory\SourceSizeFactory;

/**
 * Size list
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Ports
 * @see        http://w3c.github.io/html/semantics-embedded-content.html#ref-for-viewport-based-selection%E2%91%A0
 * @see        http://w3c.github.io/html/semantics-embedded-content.html#valid-source-size-list
 * @see        http://w3c.github.io/html/semantics-embedded-content.html#parse-a-sizes-attribute
 */
class SourceSizeList extends \Jkphl\Respimgcss\Infrastructure\SourceSizeList
{
    /**
     * Create a size list from a source size list
     *
     * @param $sourceSizeListStr SourceSizeList size list
     *
     * @return SourceSizeList Size list
     * @api
     */
    public static function fromString($sourceSizeListStr)
    {
        $unparsedSourceSizes = array_filter(array_map('trim', explode(',', $sourceSizeListStr)));
        $sourceSizes         = array_map([SourceSizeFactory::class, 'createFromSourceSizeStr'], $unparsedSourceSizes);
        print_r($sourceSizes);

        return new static($sourceSizes);
    }
}