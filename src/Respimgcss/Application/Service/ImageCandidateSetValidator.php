<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Service
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

namespace Jkphl\Respimgcss\Application\Service;

use Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException;
use Jkphl\Respimgcss\Domain\Contract\ImageCandidateInterface;

/**
 * Image candidate set validator
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application
 */
class ImageCandidateSetValidator
{
    /**
     * Image candidates
     *
     * @var ImageCandidateInterface[]
     */
    protected $imageCandidates = [];

    /**
     * ImageCandidateSetValidator constructor.
     *
     * @param ImageCandidateInterface[] ...$imageCandidates
     */
    public function __construct(ImageCandidateInterface ...$imageCandidates)
    {
        $this->imageCandidates = $imageCandidates;
    }

    /**
     * Validate the current set of image candidates
     *
     * @return bool Valid set
     * @throws InvalidArgumentException If the image candidate types are inconsistent
     * @throws InvalidArgumentException If the image candidate value isn't unique
     */
    public function validate(): bool
    {
        $type   = null;
        $values = [];
        foreach ($this->imageCandidates as $imageCandidate) {
            // If the image candidate types differ
            if (($type !== null) && ($imageCandidate->getType() !== $type)) {
                throw new InvalidArgumentException(
                    InvalidArgumentException::INCONSISTENT_IMAGE_CANDIDATE_TYPES_STR,
                    InvalidArgumentException::INCONSISTENT_IMAGE_CANDIDATE_TYPES
                );
            }
            $type  = $imageCandidate->getType();
            $value = $imageCandidate->getValue();
            if (array_key_exists($value, $values)) {
                throw new InvalidArgumentException(
                    sprintf(InvalidArgumentException::OVERLAPPING_IMAGE_CANDIDATE_VALUE_STR, $value),
                    InvalidArgumentException::OVERLAPPING_IMAGE_CANDIDATE_VALUE
                );
            }
            $values[$value] = $value;
        }

        return true;
    }
}