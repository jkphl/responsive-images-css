<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Model
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

namespace Jkphl\Respimgcss\Domain\Model;

use Jkphl\Respimgcss\Domain\Contract\ImageCandidateInterface;

/**
 * Abstract image candidate
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application
 */
abstract class AbstractImageCandidate implements ImageCandidateInterface
{
    /**
     * Image candidate file
     *
     * @var string
     */
    protected $file;
    /**
     * Image candidate value
     *
     * @var int
     */
    protected $value;
    /**
     * Image candidate type
     *
     * @var string
     */
    protected $type;

    /**
     * Image candidate constructor
     *
     * @param string $file Image candidate file path and name
     * @param int $value   Image candidate value
     */
    public function __construct(string $file, int $value)
    {
        $this->file  = $file;
        $this->value = $value;
    }

    /**
     * Return the image candidate file path and name
     *
     * @return string Image candidate file path and name
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * Return the image candidate value
     *
     * @return int Image candidate value
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * Return the image candidate type
     *
     * @return string Image candidate type
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Return the image candidate string
     *
     * @return string Image candidate string
     */
    public function __toString(): string
    {
        return $this->file.' '.$this->value.$this->type;
    }
}