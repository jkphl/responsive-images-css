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

use Jkphl\Respimgcss\Application\Contract\ImageCandidateSetInterface;
use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;
use Jkphl\Respimgcss\Application\Factory\CssRulesetCompilerServiceFactory;
use Jkphl\Respimgcss\Domain\Contract\CssRulesetInterface;

/**
 * CSS Ruleset Compiler Service
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Service
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
    protected $breakPoints;
    /**
     * Image candidates
     *
     * @var ImageCandidateSetInterface
     */
    protected $imageCandidates = null;

    /**
     * CSS Ruleset Compiler Service constructor
     *
     * @param CssRulesetInterface $cssRuleset
     * @param UnitLengthInterface[] $breakPoints Breakpoints
     * @param ImageCandidateSetInterface $imageCandidates
     */
    public function __construct(
        CssRulesetInterface $cssRuleset,
        array $breakPoints,
        ImageCandidateSetInterface $imageCandidates
    ) {
        $this->cssRuleset      = $cssRuleset;
        $this->breakPoints     = $breakPoints;
        $this->imageCandidates = $imageCandidates;
    }

    /**
     * Compile the CSS rules for a set of densities
     *
     * @param array $densities Densities
     *
     * @return CssRulesetInterface CSS ruleset
     */
    public function compile(array $densities): CssRulesetInterface
    {
        // Run through all densities and iteratively compile a CSS ruleset
        foreach ($densities as $density) {
            $this->cssRuleset = CssRulesetCompilerServiceFactory::createForImageCandidates(
                $this->cssRuleset,
                $this->breakPoints,
                $this->imageCandidates
            )->compile($density);
        }

        return $this->cssRuleset;
    }
}