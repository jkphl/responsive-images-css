<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application
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

use Jkphl\Respimgcss\Application\Contract\CalculatorServiceFactoryInterface;
use Jkphl\Respimgcss\Application\Contract\ImageCandidateSetInterface;
use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;
use Jkphl\Respimgcss\Application\Factory\CssRulesetCompilerServiceFactory;
use Jkphl\Respimgcss\Domain\Contract\CssRulesetInterface;
use Jkphl\Respimgcss\Infrastructure\SourceSizeList;

/**
 * CSS Ruleset Compiler Service
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application
 */
class CssRulesetCompilerService
{
    /**
     * CSS Ruleset
     *
     * @var CssRulesetInterface
     */
    protected $cssRuleset;
    /**
     * Breakpoints
     *
     * @var UnitLengthInterface[]
     */
    protected $breakpoints;
    /**
     * Image candidates
     *
     * @var ImageCandidateSetInterface
     */
    protected $imageCandidates = null;
    /**
     * Calculator service factory
     *
     * @var CalculatorServiceFactoryInterface
     */
    protected $calculatorServiceFactory;
    /**
     * EM to pixel ratio
     *
     * @var int
     */
    protected $emPixel;
    /**
     * Source sizes list
     *
     * @var SourceSizeList|null
     */
    protected $sourceSizeList;

    /**
     * CSS Ruleset Compiler Service constructor
     *
     * @param CssRulesetInterface $cssRuleset                             CSS ruleset
     * @param UnitLengthInterface[] $breakpoints                          Breakpoints
     * @param ImageCandidateSetInterface $imageCandidates                 Image candidates
     * @param CalculatorServiceFactoryInterface $calculatorServiceFactory Calculator service factory
     * @param int $emPixel                                                EM to pixel ratio
     * @param SourceSizeList|null $sourceSizeList                         Source sizes list
     */
    public function __construct(
        CssRulesetInterface $cssRuleset,
        array $breakpoints,
        ImageCandidateSetInterface $imageCandidates,
        CalculatorServiceFactoryInterface $calculatorServiceFactory,
        int $emPixel,
        SourceSizeList $sourceSizeList = null
    ) {
        $this->cssRuleset               = $cssRuleset;
        $this->breakpoints              = $breakpoints;
        $this->imageCandidates          = $imageCandidates;
        $this->calculatorServiceFactory = $calculatorServiceFactory;
        $this->emPixel                  = $emPixel;
        $this->sourceSizeList           = $sourceSizeList;
    }

    /**
     * Compile the CSS rules for a set of densities
     *
     * @param float[] $densities Densities
     *
     * @return CssRulesetInterface CSS ruleset
     */
    public function compile(array $densities): CssRulesetInterface
    {
        // Run through all densities and iteratively compile a CSS ruleset
        foreach ($densities as $density) {
            $this->cssRuleset = CssRulesetCompilerServiceFactory::createForImageCandidates(
                $this->cssRuleset,
                $this->breakpoints,
                $this->imageCandidates,
                $this->calculatorServiceFactory,
                $this->emPixel,
                $this->sourceSizeList
            )->compile($density);
        }

        return $this->cssRuleset;
    }
}
