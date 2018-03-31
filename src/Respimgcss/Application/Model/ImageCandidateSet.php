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

namespace Jkphl\Respimgcss\Application\Model;

use Jkphl\Respimgcss\Application\Contract\ImageCandidateSetInterface;
use Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException;
use Jkphl\Respimgcss\Domain\Contract\ImageCandidateInterface;

/**
 * Typed and validating image candidate set
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application
 */
class ImageCandidateSet extends \Jkphl\Respimgcss\Domain\Model\ImageCandidateSet implements ImageCandidateSetInterface
{
    /**
     * Image candidate set type
     *
     * @var string
     */
    protected $type = null;
    /**
     * Image candidate values
     *
     * @var array
     */
    protected $values = [];

    /**
     * Image candidate set constructor
     *
     * @param ImageCandidateInterface $imageCandidate Image candidate
     */
    public function __construct(ImageCandidateInterface $imageCandidate)
    {
        $this->type                                = $imageCandidate->getType();
        $this->imageCandidates[]                   = $imageCandidate;
        $this->values[$imageCandidate->getValue()] = true;
    }

    /**
     * Offset to set
     *
     * @param int $offset Offset
     * @param ImageCandidateInterface $value
     *
     * @return void
     * @throws InvalidArgumentException If the value given is not a valid image candidate
     * @throws InvalidArgumentException If the image candidate types are inconsistent
     * @throws InvalidArgumentException If the image candidate value isn't unique
     */
    public function offsetSet($offset, $value): void
    {
        if (!($value instanceof ImageCandidateInterface)) {
            throw new InvalidArgumentException(
                InvalidArgumentException::INVALID_IMAGE_CANDIDATE_STR,
                InvalidArgumentException::INVALID_IMAGE_CANDIDATE
            );
        }

        // Test if the image candidate type matches the current set type
        if ($value->getType() !== $this->type) {
            throw new InvalidArgumentException(
                InvalidArgumentException::INCONSISTENT_IMAGE_CANDIDATE_TYPES_STR,
                InvalidArgumentException::INCONSISTENT_IMAGE_CANDIDATE_TYPES
            );
        }

        if (array_key_exists($value->getValue(), $this->values)) {
            throw new InvalidArgumentException(
                sprintf(InvalidArgumentException::OVERLAPPING_IMAGE_CANDIDATE_VALUE_STR, $value),
                InvalidArgumentException::OVERLAPPING_IMAGE_CANDIDATE_VALUE
            );
        }

        parent::offsetSet($offset, $value);
        $this->values[$value->getValue()] = true;
    }

    /**
     * Return all image candidates as an array
     *
     * @return array Image candidates
     */
    public function toArray(): array
    {
        return $this->imageCandidates;
    }

    /**
     * Return the image candidate set type
     *
     * @return string|null Image candidate set type
     */
    public function getType(): string
    {
        return $this->type;
    }
}