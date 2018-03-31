<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Domain\Service
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

use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;
use Jkphl\Respimgcss\Domain\Contract\CssRulesetCompilerServiceInterface;
use Jkphl\Respimgcss\Domain\Contract\CssRulesetInterface;
use Jkphl\Respimgcss\Domain\Contract\ImageCandidateSetInterface;
use Jkphl\Respimgcss\Domain\Contract\LengthInterface;

/**
 * Abstract CSS Ruleset compiler service
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Domain
 */
abstract class AbstractCssRulesetCompilerService implements CssRulesetCompilerServiceInterface
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
     * @param CssRulesetInterface $cssRuleset             CSS Ruleset
     * @param UnitLengthInterface[] $breakPoints          Breakpoints
     * @param ImageCandidateSetInterface $imageCandidates Image candidates
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
     * Compile a CSS ruleset based on the registered breakpoints, image candidates and a given density
     *
     * @param int $density Device display density
     *
     * @return CssRulesetInterface CSS ruleset
     */
    public function compile(int $density): CssRulesetInterface
    {
        // Compile the minimum size
        $this->compileBreakpoint($density, null);

        // Run through and compile for all breakpoints
        foreach ($this->breakPoints as $breakPoint) {
            $this->compileBreakpoint($density, $breakPoint);
        }

        return $this->cssRuleset;
    }

    /**
     * Compile the CSS rules for particular breakpoint, a given density and the registered image candidates
     *
     * @param int $density                Device display density
     * @param LengthInterface $breakpoint Breakpoint length (NULL = minimum size / no breakpoint)
     */
    protected function compileBreakpoint(int $density, LengthInterface $breakpoint = null): void
    {

    }
}