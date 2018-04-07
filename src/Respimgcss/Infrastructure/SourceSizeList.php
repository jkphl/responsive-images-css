<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Infrastructure
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

namespace Jkphl\Respimgcss\Infrastructure;

use Jkphl\Respimgcss\Application\Model\SourceSize;
use Jkphl\Respimgcss\Ports\InvalidArgumentException;

/**
 * Sizes list
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Infrastructure
 */
class SourceSizeList extends \ArrayObject
{
    /**
     * Source size list constructor
     *
     * @param SourceSize[] $sourceSizes Source sizes
     *
     * @throws InvalidArgumentException If the source size is invalid
     */
    public function __construct(array $sourceSizes)
    {
        // Run through all source sizes
        foreach ($sourceSizes as $sourceSize) {
            // If the source size is invalid
            if (!($sourceSize instanceof SourceSize)) {
                throw new InvalidArgumentException(
                    InvalidArgumentException::INVALID_SOURCE_SIZE_STR,
                    InvalidArgumentException::INVALID_SOURCE_SIZE
                );
            }
        }
        parent::__construct($sourceSizes);
    }
}