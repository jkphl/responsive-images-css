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

use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;
use Jkphl\Respimgcss\Application\Factory\ImageCandidateFactory;
use Jkphl\Respimgcss\Application\Factory\LengthFactory;
use Jkphl\Respimgcss\Application\Factory\SourceSizeFactory;
use Jkphl\Respimgcss\Application\Model\ImageCandidateSet;
use Jkphl\Respimgcss\Application\Service\CssRulesetCompilerService;
use Jkphl\Respimgcss\Domain\Contract\ImageCandidateInterface;
use Jkphl\Respimgcss\Ports\CssRuleset;
use Jkphl\Respimgcss\Ports\CssRulesetInterface;
use Jkphl\Respimgcss\Ports\GeneratorInterface;
use Jkphl\Respimgcss\Ports\InvalidArgumentException;

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
        $lengthFactory     = new LengthFactory(new ViewportCalculatorServiceFactory(), $this->emPixel);
        $this->breakpoints = array_map(
            [$lengthFactory, 'createLengthFromString'],
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
        if ($this->imageCandidates === null) {
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
        return ($this->imageCandidates === null) ? [] : $this->imageCandidates->toArray();
    }

    /**
     * Create a CSS ruleset for the registered image candidates
     *
     * @param float[] $densities Device display densities
     * @param string $sizes      Source sizes
     *
     * @return CssRulesetInterface CSS Ruleset
     */
    public function make(array $densities = [1], string $sizes = ''): CssRulesetInterface
    {
        $cssRuleset = new CssRuleset();

        // If all necessary properties are given
        if (count($this->breakpoints) && count($this->getImageCandidates()) && count($densities)) {
            $cssRuleset = $this->compileCssRuleset($cssRuleset, $densities, $sizes);
        }

        return $cssRuleset;
    }

    /**
     * Compile a CSS ruleset
     *
     * @param CssRuleset $baseCssRuleset                 Base CSS ruleset
     * @param ImageCandidateInterface[] $imageCandidates Image candidates
     * @param int[] $densities                           Densities
     * @param string $sizes                              Source sizes
     *
     * @return CssRulesetInterface Compile CSS ruleset
     */
    protected function compileCssRuleset(
        CssRuleset $baseCssRuleset,
        array $densities,
        string $sizes
    ): CssRulesetInterface {
        $sourceSizeList = $this->validateSourceSizeList($this->makeSourceSizeList($sizes));

        // Instantiate a CSS ruleset compiler service and compile for all densities
        $cssRulesetCompilerService = new CssRulesetCompilerService(
            $baseCssRuleset,
            $this->breakpoints,
            $this->imageCandidates,
            new ViewportCalculatorServiceFactory(),
            $this->emPixel
        );

        return new CssRuleset($cssRulesetCompilerService->compile($densities));
    }

    /**
     * Check whether a source sizes list can be applied
     *
     * @param SourceSizeList|null $sourceSizeList Source size list
     *
     * @return SourceSizeList|null Source size list
     * @throws InvalidArgumentException If source sizes are used with resolution based image candidates
     */
    protected function validateSourceSizeList(SourceSizeList $sourceSizeList = null): ?SourceSizeList
    {
        // If source sizes are used with resolution based image candidates
        if ($sourceSizeList && ($this->imageCandidates->getType() == ImageCandidateInterface::TYPE_DENSITY)) {
            throw new InvalidArgumentException(
                InvalidArgumentException::SIZES_NOT_ALLOWED_STR,
                InvalidArgumentException::SIZES_NOT_ALLOWED
            );
        }

        return $sourceSizeList;
    }

    /**
     * Create a size list from a source size list
     *
     * @param string $sourceSizeListStr SourceSizeList size list
     *
     * @return SourceSizeList Size list
     */
    protected function makeSourceSizeList(string $sourceSizeListStr): ?SourceSizeList
    {
        $sourceSizeFactory   = new SourceSizeFactory(new ViewportCalculatorServiceFactory(), $this->emPixel);
        $unparsedSourceSizes = array_filter(array_map('trim', explode(',', $sourceSizeListStr)));
        $sourceSizes         = array_map(
            function ($unparsedSourceSize) use ($sourceSizeFactory) {
                return $sourceSizeFactory->createFromSourceSizeStr($unparsedSourceSize);
            },
            $unparsedSourceSizes
        );

        return count($sourceSizes) ? new SourceSizeList($sourceSizes) : null;
    }
}