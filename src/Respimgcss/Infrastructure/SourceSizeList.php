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
use Jkphl\Respimgcss\Application\Model\SourceSizeMediaCondition;
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
 * @see        https://www.sitepoint.com/community/t/pixels-or-percentages-for-media-queries/37487
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
     * @param SourceSize[] $sourceSizes             Source sizes
     * @param LengthFactoryInterface $lengthFactory Length factory
     *
     * @throws InvalidArgumentException If the source size is invalid
     */
    public function __construct(array $sourceSizes, LengthFactoryInterface $lengthFactory)
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

        usort($sourceSizes, [$this, 'sortSourceSizes']);
        parent::__construct($sourceSizes);

        $this->lengthFactory = $lengthFactory;
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
        $lastMinimumWidth = null;
        foreach ($this->getArrayCopy() as $sourceSize) {
            $mediaCondition = $sourceSize->getMediaCondition();
            if ($mediaCondition->matches($breakpoint, $density)) {
                return $this->findImageCandidateForSourceSize(
                    $sourceSize,
                    $imageCandidates,
                    $breakpoint,
                    $this->getSourceSizeMaximumWidth($mediaCondition, $lastMinimumWidth)
                );
            }
            $lastMinimumWidth = $mediaCondition->getMinimumWidth();
        }

        return null;
    }

    /**
     * Find an image candidate for a particular source size
     *
     * @param SourceSize $sourceSize                      Matching source size
     * @param ImageCandidateSetInterface $imageCandidates Image candidates
     * @param AbsoluteLengthInterface $minWidth           Minimum viewport width
     * @param AbsoluteLengthInterface|null $maxWidth      Maximum viewport width
     *
     * @return SourceSizeImageCandidateMatch|null
     */
    protected function findImageCandidateForSourceSize(
        SourceSize $sourceSize,
        ImageCandidateSetInterface $imageCandidates,
        AbsoluteLengthInterface $minWidth,
        AbsoluteLengthInterface $maxWidth = null
    ): ?SourceSizeImageCandidateMatch {
        // If there's no upper limit: Use the largest image candidate in any case
        if ($maxWidth === null) {
            return $this->createLargestImageCandidateMatch($sourceSize, $imageCandidates);
        }

        // Run through all image candidates for the effective minimum image, current source size and current breakpoint
        return $this->findImageCandidateForMinImageWidth(
            $sourceSize,
            $imageCandidates,
            max($sourceSize->getValue()->getValue($minWidth), $sourceSize->getValue()->getValue($maxWidth))
        );
    }

    /**
     * Create and return a match for the largest image candidate
     *
     * @param SourceSize $sourceSize                      Source size
     * @param ImageCandidateSetInterface $imageCandidates Image candidates
     *
     * @return SourceSizeImageCandidateMatch|null Largest image candidate match
     */
    protected function createLargestImageCandidateMatch(
        SourceSize $sourceSize,
        ImageCandidateSetInterface $imageCandidates
    ): ?SourceSizeImageCandidateMatch {
        if (count($imageCandidates)) {
            $largestImageCandidate = $imageCandidates[count($imageCandidates) - 1];
            return $this->createImageCandidateMatch($sourceSize, $largestImageCandidate);
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
     * Find an image candidate for a particular source size
     *
     * @param SourceSize $sourceSize                      Matching source size
     * @param ImageCandidateSetInterface $imageCandidates Image candidates
     * @param int $minImageWidth                          Minimum image width
     *
     * @return SourceSizeImageCandidateMatch|null Image candidate
     */
    protected function findImageCandidateForMinImageWidth(
        SourceSize $sourceSize,
        ImageCandidateSetInterface $imageCandidates,
        int $minImageWidth
    ): ?SourceSizeImageCandidateMatch {
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
     * Get the maximum width for a source size
     *
     * @param SourceSizeMediaCondition $condition Source size media condition
     * @param float|null $lastMinimumWidth        Last source size minimum width
     *
     * @return AbsoluteLengthInterface Maximum width
     */
    protected function getSourceSizeMaximumWidth(
        SourceSizeMediaCondition $condition,
        float $lastMinimumWidth = null
    ): ?AbsoluteLengthInterface {
        $maximumWidth = $this->considerLastMinimumWidth($condition->getMaximumWidth(), $lastMinimumWidth);
        if ($maximumWidth === null) {
            return null;
        }
        if ($lastMinimumWidth !== null) {
            $maximumWidth = max(0, min($maximumWidth, $lastMinimumWidth - 1));
        }
        return $this->lengthFactory->createAbsoluteLength($maximumWidth);
    }

    /**
     * Consider the last minimum width in case the maximum width is undefined
     *
     * @param int|null $maximumWidth       Maximum width
     * @param float|null $lastMinimumWidth Last minimum width
     *
     * @return float|null Maximum width
     */
    protected function considerLastMinimumWidth(int $maximumWidth = null, float $lastMinimumWidth = null): ?float
    {
        if (($maximumWidth === null) && ($lastMinimumWidth !== null)) {
            $maximumWidth = max(0, $lastMinimumWidth - 1);
        }
        return $maximumWidth;
    }

    /**
     * Compare and sort two source sizes against each other
     *
     * @param SourceSize $sourceSize1 Source size 1
     * @param SourceSize $sourceSize2 Source size 2
     *
     * @return int Sort order
     */
    protected function sortSourceSizes(SourceSize $sourceSize1, SourceSize $sourceSize2): int
    {
        $hasConditions1 = $sourceSize1->hasConditions();
        $hasConditions2 = $sourceSize2->hasConditions();

        // If one of the sources sizes doesn't have conditions: Default source size (move to the end)
        if ($hasConditions1 !== $hasConditions2) {
            return $hasConditions1 ? -1 : 1;
        }

        return $hasConditions1 ? $this->sortSourceSizesByWidth($sourceSize1, $sourceSize2) : 0;
    }

    /**
     * Compare and sort two source sizes by width
     *
     * @param SourceSize $sourceSize1 Source size 1
     * @param SourceSize $sourceSize2 Source size 2
     *
     * @return int Sort order
     */
    protected function sortSourceSizesByWidth(SourceSize $sourceSize1, SourceSize $sourceSize2): int
    {
        // Sort by minimum width
        $minWidth1 = $sourceSize1->getMediaCondition()->getMinimumWidth();
        $minWidth2 = $sourceSize2->getMediaCondition()->getMinimumWidth();
        if ($minWidth1 !== $minWidth2) {
            return $this->sortSourceSizesByDifferingValues($minWidth1, $minWidth2);
        }

        // Sort by maximum width
        $maxWidth1 = $sourceSize1->getMediaCondition()->getMaximumWidth();
        $maxWidth2 = $sourceSize2->getMediaCondition()->getMaximumWidth();
        if ($maxWidth1 !== $maxWidth2) {
            return $this->sortSourceSizesByDifferingValues($maxWidth1, $maxWidth2);
        }

        // Sort by resolution
        return $this->sortSourceSizesByResolution($sourceSize1, $sourceSize2);
    }

    /**
     * Sort by differing values
     *
     * @param float|null $value1 Value 1
     * @param float|null $value2 Value 2
     *
     * @return int Sort order
     */
    protected function sortSourceSizesByDifferingValues(float $value1 = null, float $value2 = null): int
    {
        if ($value1 === null) {
            return -1;
        }
        if ($value2 === null) {
            return 1;
        }
        return ($value1 > $value2) ? -1 : 1;
    }

    /**
     * Compare and sort two source sizes by resolution
     *
     * @param SourceSize $sourceSize1 Source size 1
     * @param SourceSize $sourceSize2 Source size 2
     *
     * @return int Sort order
     */
    protected function sortSourceSizesByResolution(SourceSize $sourceSize1, SourceSize $sourceSize2): int
    {
        // Sort by minimum resolution
        $minResolution1 = $sourceSize1->getMediaCondition()->getMinimumResolution();
        $minResolution2 = $sourceSize2->getMediaCondition()->getMinimumResolution();
        if ($minResolution1 !== $minResolution2) {
            return $this->sortSourceSizesByDifferingValues($minResolution1, $minResolution2);
        }

        // Sort by maximum resolution
        $maxResolution1 = $sourceSize1->getMediaCondition()->getMaximumResolution();
        $maxResolution2 = $sourceSize2->getMediaCondition()->getMaximumResolution();
        if ($maxResolution1 !== $maxResolution2) {
            return $this->sortSourceSizesByDifferingValues($maxResolution1, $maxResolution2);
        }

        return 0;
    }
}