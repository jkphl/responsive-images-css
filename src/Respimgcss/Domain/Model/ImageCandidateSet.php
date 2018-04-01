<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Domain\Model
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
use Jkphl\Respimgcss\Domain\Contract\ImageCandidateSetInterface;
use Jkphl\Respimgcss\Domain\Exceptions\InvalidArgumentException;

/**
 * Image candidate set
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Domain
 */
class ImageCandidateSet implements ImageCandidateSetInterface
{
    /**
     * Image candidates
     *
     * @var ImageCandidateInterface[]
     */
    protected $imageCandidates = [];
    /**
     * Internal pointer
     *
     * @var int
     */
    protected $pointer = 0;

    /**
     * Return the current image candidate
     *
     * @return ImageCandidateInterface Image candidate
     */
    public function current(): ImageCandidateInterface
    {
        return $this->imageCandidates[$this->pointer];
    }

    /**
     * Move forward to next image candidate
     *
     * @return void
     */
    public function next(): void
    {
        ++$this->pointer;
    }

    /**
     * Return the key of the current image candidate
     *
     * @return int Current key
     */
    public function key(): int
    {
        return $this->pointer;
    }

    /**
     * Checks if current position is valid
     *
     * @return boolean The return value will be casted to boolean and then evaluated.
     */
    public function valid(): bool
    {
        return isset($this->imageCandidates[$this->pointer]);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->pointer = 0;
    }

    /**
     * Whether a offset exists
     *
     * @param int $offset Offset
     *
     * @return boolean Offset exists
     */
    public function offsetExists($offset): bool
    {
        return isset($this->imageCandidates[intval($offset)]);
    }

    /**
     * Offset to retrieve
     *
     * @param int $offset Offset
     *
     * @return ImageCandidateInterface Image candidate
     */
    public function offsetGet($offset): ImageCandidateInterface
    {
        return $this->imageCandidates[intval($offset)];
    }

    /**
     * Offset to set
     *
     * @param int|null $offset Offset
     * @param mixed $value
     *
     * @return void
     * @throws InvalidArgumentException If the value given is not a valid image candidate
     */
    public function offsetSet($offset, $value): void
    {
        if (!($value instanceof ImageCandidateInterface)) {
            throw new InvalidArgumentException(
                InvalidArgumentException::INVALID_IMAGE_CANDIDATE_STR,
                InvalidArgumentException::INVALID_IMAGE_CANDIDATE
            );
        }

        $this->imageCandidates[is_int($offset) ? $offset : count($this->imageCandidates)] = $value;
    }

    /**
     * Offset to unset
     *
     * @param int $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->imageCandidates[intval($offset)]);
        $this->imageCandidates = array_values($this->imageCandidates);
    }

    /**
     * Count elements of an object
     *
     * @return int Number of registered image candidates
     */
    public function count()
    {
        return count($this->imageCandidates);
    }
}