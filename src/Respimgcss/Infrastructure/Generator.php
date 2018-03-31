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

use Jkphl\Respimgcss\Application\Contract\ImageCandidateSetInterface;
use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;
use Jkphl\Respimgcss\Application\Factory\ImageCandidateFactory;
use Jkphl\Respimgcss\Application\Factory\LengthFactory;
use Jkphl\Respimgcss\Application\Model\ImageCandidateSet;
use Jkphl\Respimgcss\Application\Service\CssRulesetCompilerService;
use Jkphl\Respimgcss\Domain\Contract\ImageCandidateInterface;
use Jkphl\Respimgcss\Ports\CssRuleset;
use Jkphl\Respimgcss\Ports\CssRulesetInterface;
use Jkphl\Respimgcss\Ports\GeneratorInterface;

/**
 * Responsive image CSS generator (internal)
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Ports
 */
abstract class Generator implements GeneratorInterface
{
    /**
     * Breakpoints
     *
     * @var UnitLengthInterface[]
     */
    protected $breakpoints;
    /**
     * EM to pixel ratio
     *
     * @var int
     */
    protected $emPixel;
    /**
     * Image candidates
     *
     * @var ImageCandidateSet
     */
    protected $imageCandidates = null;

    /**
     * Generator constructor
     *
     * @param string[] $breakpoints List of breakpoint length strings
     * @param int $emPixel          EM to pixel ratio
     */
    public function __construct(array $breakpoints, int $emPixel)
    {
        $this->emPixel     = $emPixel;
        $this->breakpoints = array_map(
            [LengthFactory::class, 'createLengthFromString'],
            $breakpoints,
            array_fill(0, count($breakpoints), $this->emPixel)
        );
    }

    /**
     * Register an image candidate
     *
     * @param string $file            Image candidate file path and name
     * @param string|null $descriptor Image candidate descriptor
     *
     * @return GeneratorInterface Self reference
     * @api
     */
    public function registerImageCandidate(string $file, string $descriptor = null): GeneratorInterface
    {
        $imageCandidate = ($descriptor === null) ?
            ImageCandidateFactory::createImageCandidateFromString($file) :
            ImageCandidateFactory::createImageCandidateFromFileAndDescriptor($file, $descriptor);

        // If the image candidate set doesn't exist yet
        if (!($this->imageCandidates instanceof ImageCandidateSetInterface)) {
            $this->imageCandidates = new ImageCandidateSet($imageCandidate);

            return $this;
        }

        // Register the image candidate
        $this->imageCandidates[] = $imageCandidate;

        return $this;
    }

    /**
     * Return the registered image candidates
     *
     * @return ImageCandidateInterface[] Image candidates
     */
    public function getImageCandidates(): array
    {
        return ($this->imageCandidates instanceof ImageCandidateSetInterface) ?
            $this->imageCandidates->toArray() : [];
    }

    /**
     * Create a CSS rulset for the registered image candidates
     *
     * @param float[] $densities Device display densities
     *
     * @return CssRulesetInterface CSS Ruleset
     */
    public function make(array $densities = [1]): CssRulesetInterface
    {
        $cssRuleset = new CssRuleset();

        // If all necessary properties are given
        if (count($this->breakpoints) && count($this->imageCandidates) && count($densities)) {
            // Instantiate a CSS ruleset compiler service and compile for all densities
            $cssRulesetCompilerService = new CssRulesetCompilerService(
                $cssRuleset,
                $this->breakpoints,
                $this->imageCandidates
            );
            $cssRuleset                = $cssRulesetCompilerService->compile($densities);
        }

        return $cssRuleset;
    }
}