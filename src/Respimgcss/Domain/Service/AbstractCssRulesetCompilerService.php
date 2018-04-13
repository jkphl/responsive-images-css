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

use Jkphl\Respimgcss\Domain\Contract\AbsoluteLengthInterface;
use Jkphl\Respimgcss\Domain\Contract\CssRulesetCompilerServiceInterface;
use Jkphl\Respimgcss\Domain\Contract\CssRulesetInterface;
use Jkphl\Respimgcss\Domain\Contract\ImageCandidateSetInterface;
use Jkphl\Respimgcss\Domain\Contract\LengthFactoryInterface;

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
     * @var AbsoluteLengthInterface[]
     */
    protected $breakpoints;
    /**
     * Image candidates
     *
     * @var ImageCandidateSetInterface
     */
    protected $imageCandidates = null;
    /**
     * Length factory
     *
     * @var LengthFactoryInterface
     */
    protected $lengthFactory;

    /**
     * CSS Ruleset Compiler Service constructor
     *
     * @param CssRulesetInterface $cssRuleset             CSS Ruleset
     * @param AbsoluteLengthInterface[] $breakpoints      Breakpoints
     * @param ImageCandidateSetInterface $imageCandidates Image candidates
     * @param LengthFactoryInterface $lengthFactory       Length factory
     */
    public function __construct(
        CssRulesetInterface $cssRuleset,
        array $breakpoints,
        ImageCandidateSetInterface $imageCandidates,
        LengthFactoryInterface $lengthFactory
    ) {
        $this->cssRuleset      = $cssRuleset;
        $this->breakpoints     = $breakpoints;
        $this->imageCandidates = $imageCandidates;
        $this->lengthFactory   = $lengthFactory;
        array_unshift($this->breakpoints, $this->lengthFactory->createAbsoluteLength(0));
    }
}
