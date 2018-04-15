<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Domain
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

namespace Jkphl\Respimgcss\Domain\Service;

use Jkphl\Respimgcss\Domain\Contract\AbsoluteLengthInterface;
use Jkphl\Respimgcss\Domain\Contract\CssMinMaxMediaConditionInterface;
use Jkphl\Respimgcss\Domain\Contract\CssRulesetInterface;
use Jkphl\Respimgcss\Domain\Contract\ImageCandidateInterface;
use Jkphl\Respimgcss\Domain\Contract\ImageCandidateSetInterface;
use Jkphl\Respimgcss\Domain\Contract\LengthFactoryInterface;
use Jkphl\Respimgcss\Domain\Contract\SourceSizeImageCandidateMatch;
use Jkphl\Respimgcss\Domain\Contract\SourceSizeListInterface;
use Jkphl\Respimgcss\Domain\Model\Css\MediaCondition;
use Jkphl\Respimgcss\Domain\Model\Css\ResolutionMediaCondition;
use Jkphl\Respimgcss\Domain\Model\Css\Rule;
use Jkphl\Respimgcss\Domain\Model\Css\WidthMediaCondition;

/**
 * Pixel density CSS ruleset compiler service
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Domain
 */
class WidthCssRulesetCompilerService extends AbstractCssRulesetCompilerService
{
    /**
     * Source sizes list
     *
     * @var SourceSizeListInterface|null
     */
    protected $sourceSizeList;

    /**
     * Width CSS Ruleset Compiler Service constructor
     *
     * @param CssRulesetInterface $cssRuleset              CSS Ruleset
     * @param AbsoluteLengthInterface[] $breakpoints       Breakpoints
     * @param ImageCandidateSetInterface $imageCandidates  Image candidates
     * @param LengthFactoryInterface $lengthFactory        Length factory
     * @param SourceSizeListInterface|null $sourceSizeList Source sizes list
     */
    public function __construct(
        CssRulesetInterface $cssRuleset,
        array $breakpoints,
        ImageCandidateSetInterface $imageCandidates,
        LengthFactoryInterface $lengthFactory,
        SourceSizeListInterface $sourceSizeList = null
    ) {
        parent::__construct($cssRuleset, $breakpoints, $imageCandidates, $lengthFactory);
        $this->sourceSizeList = $sourceSizeList;
    }

    /**
     * Compile a CSS ruleset for a given density
     *
     * @param float $density Density
     *
     * @return CssRulesetInterface CSS ruleset
     */
    public function compile(float $density): CssRulesetInterface
    {
        ($this->sourceSizeList instanceof SourceSizeListInterface) ?
            $this->compileForSourceSizes($density) :
            $this->compileForImageCandidates($density);

        return $this->cssRuleset;
    }

    /**
     * Compile a CSS ruleset based on a list of source sizes
     *
     * @param float $density Density
     */
    protected function compileForSourceSizes(float $density): void
    {
        // Run through all breakpoints (from lowest to highest)
        /** @var AbsoluteLengthInterface $breakpoint */
        foreach ($this->breakpoints as $breakpoint) {
            // Try to find a matching image candidate
            /** @scrutinizer ignore-call */
            $imageCandidateMatch = $this->sourceSizeList->findImageCandidate(
                $this->imageCandidates,
                $breakpoint,
                $density
            );
            if ($imageCandidateMatch instanceof SourceSizeImageCandidateMatch) {
                $this->cssRuleset->appendRule(
                    $this->createSourceSizeMatchRule($imageCandidateMatch, $density)
                );
            }
        }
    }

    /**
     * Create a source size based CSS rule
     *
     * @param SourceSizeImageCandidateMatch $imageCandidateMatch Source size match
     * @param float $density                                     Density
     *
     * @return Rule Source size based CSS rule
     */
    protected function createSourceSizeMatchRule(SourceSizeImageCandidateMatch $imageCandidateMatch, float $density)
    {
        $rule = new Rule(
            $imageCandidateMatch->getImageCandidate(),
            array_filter([$imageCandidateMatch->getMediaCondition()], function (MediaCondition $mediaCondition) {
                return !!$mediaCondition->getValue();
            })
        );

        return $this->addDensityCondition($rule, $density);
    }

    /**
     * Add a density condition to a CSS rule
     *
     * @param Rule $rule     CSS rule
     * @param float $density Density
     *
     * @return Rule CSS rule
     */
    protected function addDensityCondition(Rule $rule, float $density): Rule
    {
        // If this is not the default density: Add a resolution condition
        if ($density > 1) {
            $resolutionMediaCondition = new ResolutionMediaCondition(
                $this->lengthFactory->createAbsoluteLength($density),
                CssMinMaxMediaConditionInterface::MIN
            );
            $rule                     = $rule->appendCondition($resolutionMediaCondition);
        }

        return $rule;
    }

    /**
     * Compile a CSS ruleset based on the registered breakpoints, image candidates and a given density
     *
     * @param float $density Density
     */
    protected function compileForImageCandidates(float $density): void
    {
        // Initialize a virtual breakpoint
        $lastImageCandidateWidth = 0;

        // Run through and test all image candidates
        /** @var ImageCandidateInterface $imageCandidate */
        foreach ($this->imageCandidates as $imageCandidate) {
            if ($lastImageCandidateWidth || ($density == 1)) {
                $this->cssRuleset->appendRule(
                    $this->createImageCandidateRule($imageCandidate, $density, $lastImageCandidateWidth)
                );
            }
            $lastImageCandidateWidth = $imageCandidate->getValue();
        }
    }

    /**
     * Create a width CSS rule for a particular image candidate
     *
     * @param ImageCandidateInterface $imageCandidate Image candidate
     * @param float $density                          Density
     * @param int $imageCandidateWidth                Image candidate width
     *
     * @return Rule Density CSS rule
     */
    protected function createImageCandidateRule(
        ImageCandidateInterface $imageCandidate,
        float $density,
        int $imageCandidateWidth
    ): Rule {
        $rule = new Rule($imageCandidate);
        $rule = $this->addWidthCondition($rule, $imageCandidateWidth, $density);

        return $this->addDensityCondition($rule, $density);
    }

    /**
     * Add a width condition to a CSS rule
     *
     * @param Rule $rule               CSS rule
     * @param int $imageCandidateWidth Image candidate width in pixels
     * @param float $density           Density
     *
     * @return Rule CSS rule
     */
    protected function addWidthCondition(Rule $rule, int $imageCandidateWidth, float $density): Rule
    {
        // If this is not the minimum width: Add a width condition
        if ($imageCandidateWidth) {
            $breakpoint          = $this->lengthFactory->createAbsoluteLength(round($imageCandidateWidth) / $density);
            $widthMediaCondition = new WidthMediaCondition($breakpoint, CssMinMaxMediaConditionInterface::MIN);
            $rule                = $rule->appendCondition($widthMediaCondition);
        }

        return $rule;
    }
}
