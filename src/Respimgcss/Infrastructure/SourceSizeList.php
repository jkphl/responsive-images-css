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

use Jkphl\Respimgcss\Application\Contract\LengthFactoryInterface;
use Jkphl\Respimgcss\Application\Contract\SourceSizeListInterface;
use Jkphl\Respimgcss\Application\Model\SourceSize;
use Jkphl\Respimgcss\Domain\Contract\AbsoluteLengthInterface;
use Jkphl\Respimgcss\Domain\Contract\ImageCandidateInterface;
use Jkphl\Respimgcss\Domain\Contract\ImageCandidateSetInterface;
use Jkphl\Respimgcss\Domain\Contract\SourceSizeImageCandidateMatch;
use Jkphl\Respimgcss\Domain\Model\Css\MediaCondition;
use Jkphl\Respimgcss\Domain\Model\ImageCandidateMatch;
use Jkphl\Respimgcss\Ports\InvalidArgumentException;

/**
 * Sizes list
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Infrastructure
 * @see        http://w3c.github.io/html/semantics-embedded-content.html#ref-for-viewport-based-selection%E2%91%A0
 * @see        http://w3c.github.io/html/semantics-embedded-content.html#valid-source-size-list
 * @see        http://w3c.github.io/html/semantics-embedded-content.html#parse-a-sizes-attribute
 */
class SourceSizeList extends \ArrayObject implements SourceSizeListInterface
{
    /**
     * Length factory
     *
     * @var LengthFactoryInterface
     */
    protected $lengthFactory;

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
        parent::__construct($this->sortSourceSizes($sourceSizes));
    }

    /**
     * Sort the list of source sizes
     *
     * @param SourceSize[] $sourceSizes Source sizes
     *
     * @return SourceSize[] Sorted source sizes
     */
    protected function sortSourceSizes(array $sourceSizes): array
    {
        $sourceSizesCount = count($sourceSizes);
        if ($sourceSizesCount) {
            $defaultSize = strlen($sourceSizes[$sourceSizesCount - 1]->getMediaCondition()->getValue()) ?
                null : array_pop($sourceSizes);
            $sourceSizes = array_reverse($sourceSizes);
            if ($defaultSize) {
                array_push($sourceSizes, $defaultSize);
            }
        }

        return $sourceSizes;
    }

    /**
     * Find the optimum image candidate for a particular breakpoint
     *
     * @param ImageCandidateSetInterface $imageCandidates Image candidates
     * @param AbsoluteLengthInterface $breakpoint         Breakpoint
     * @param float $density                              Density
     *
     * @return SourceSizeImageCandidateMatch|null Image candidate match
     */
    public function findImageCandidate(
        ImageCandidateSetInterface $imageCandidates,
        AbsoluteLengthInterface $breakpoint,
        float $density
    ): ?SourceSizeImageCandidateMatch {

        // Run through the source sizes
        /** @var SourceSize $sourceSize */
        foreach ($this->getArrayCopy() as $sourceSize) {
            if ($sourceSize->getMediaCondition()->matches($breakpoint, $density)) {
                return $this->findImageCandidateForSourceSize($sourceSize, $breakpoint, $imageCandidates);
            }
        }

        return null;
    }

    /**
     * Find an image candidate for a particular source size
     *
     * @param SourceSize $sourceSize                      Matching source size
     * @param AbsoluteLengthInterface $breakpoint         Breakpoint
     * @param ImageCandidateSetInterface $imageCandidates Image candidates
     *
     * @return SourceSizeImageCandidateMatch|null
     */
    protected function findImageCandidateForSourceSize(
        SourceSize $sourceSize,
        AbsoluteLengthInterface $breakpoint,
        ImageCandidateSetInterface $imageCandidates
    ): ?SourceSizeImageCandidateMatch {
        // Calculate the effective image width for the current source size and breakpoint
        $minImageWidth = $sourceSize->getValue()->getValue($breakpoint);

        // Run through all image candidates
        /** @var ImageCandidateInterface $imageCandidate */
        foreach ($imageCandidates as $imageCandidate) {
            if ($imageCandidate->getValue() >= $minImageWidth) {
                return $this->createImageCandidateMatch($sourceSize, $imageCandidate);
            }
        }

        return null;
    }

    /**
     * Create a source size image candidate match
     *
     * @param SourceSize $sourceSize                  Matching source size
     * @param ImageCandidateInterface $imageCandidate Image candidata
     *
     * @return SourceSizeImageCandidateMatch Source size image candidate match
     */
    protected function createImageCandidateMatch(
        SourceSize $sourceSize,
        ImageCandidateInterface $imageCandidate
    ): SourceSizeImageCandidateMatch {
        $mediaCondition = new MediaCondition('', $sourceSize->getMediaCondition()->getValue());

        return new ImageCandidateMatch($mediaCondition, $imageCandidate);
    }

    /**
     * Inject a length factory
     *
     * @param LengthFactoryInterface $lengthFactory Length factory
     */
    public function setLengthFactory(LengthFactoryInterface $lengthFactory): void
    {
        $this->lengthFactory = $lengthFactory;
    }
}