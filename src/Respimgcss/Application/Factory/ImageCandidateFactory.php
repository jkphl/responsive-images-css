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

use Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException;
use Jkphl\Respimgcss\Domain\Contract\ImageCandidateInterface;
use Jkphl\Respimgcss\Domain\Model\DensityImageCandidate;
use Jkphl\Respimgcss\Domain\Model\WidthImageCandidate;

/**
 * Image candidate factory
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application
 */
class ImageCandidateFactory
{
    /**
     * Create an image candidate from an image candidate string
     *
     * @param string $imageCandidateString Image Candidate string
     *
     * @return ImageCandidateInterface Image candidate
     * @throws InvalidArgumentException If the image candidate string is invalid
     */
    public static function createImageCandidateFromString(string $imageCandidateString): ImageCandidateInterface
    {
        $imageCandidateStringParts = (array)(preg_split('/\s+/', trim($imageCandidateString)) ?: null);
        $imageCandidateStringParts = array_replace([null, '1x'], $imageCandidateStringParts);

        // If the image candidate string is invalid
        if (count($imageCandidateStringParts) !== 2) {
            throw new InvalidArgumentException(
                sprintf(InvalidArgumentException::INVALID_IMAGE_CANDIDATE_STRING_STR, $imageCandidateString),
                InvalidArgumentException::INVALID_IMAGE_CANDIDATE_STRING
            );
        }

        return self::createImageCandidateFromFileAndDescriptor(
            $imageCandidateStringParts[0],
            $imageCandidateStringParts[1]
        );
    }

    /**
     * Create an image candidate from a file path and descriptor
     *
     * @param string $imageCandidateFile       Image candidate file path and name
     * @param string $imageCandidateDescriptor Image candidate descriptor
     *
     * @return ImageCandidateInterface Image candidate
     * @throws InvalidArgumentException If the Image candidate file is invalid
     * @throws InvalidArgumentException If the Image candidate descriptor is invalid
     */
    public static function createImageCandidateFromFileAndDescriptor(
        string $imageCandidateFile,
        string $imageCandidateDescriptor = '1x'
    ): ImageCandidateInterface {
        $imageCandidateFile = trim($imageCandidateFile);

        // If the Image candidate file is invalid
        if (!strlen($imageCandidateFile)) {
            throw new InvalidArgumentException(
                sprintf(InvalidArgumentException::INVALID_IMAGE_CANDIDATE_FILE_STR, $imageCandidateFile),
                InvalidArgumentException::INVALID_IMAGE_CANDIDATE_FILE
            );
        }

        // If the image candidate string is invalid
        if (!preg_match('/^(\d+)(w|x)$/', trim($imageCandidateDescriptor), $imageCandidateDescriptorMatch)) {
            throw new InvalidArgumentException(
                sprintf(InvalidArgumentException::INVALID_IMAGE_CANDIDATE_DESCRIPTOR_STR, $imageCandidateDescriptor),
                InvalidArgumentException::INVALID_IMAGE_CANDIDATE_DESCRIPTOR
            );
        }

        $value = intval($imageCandidateDescriptorMatch[1]);

        return ($imageCandidateDescriptorMatch[2] == ImageCandidateInterface::TYPE_DENSITY) ?
            new DensityImageCandidate($imageCandidateFile, $value) :
            new WidthImageCandidate($imageCandidateFile, $value);
    }
}