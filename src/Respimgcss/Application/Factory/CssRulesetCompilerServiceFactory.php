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

use Jkphl\Respimgcss\Application\Contract\ImageCandidateSetInterface;
use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;
use Jkphl\Respimgcss\Application\Exceptions\RuntimeException;
use Jkphl\Respimgcss\Domain\Contract\CssRulesetCompilerServiceInterface;
use Jkphl\Respimgcss\Domain\Contract\CssRulesetInterface;
use Jkphl\Respimgcss\Domain\Contract\ImageCandidateInterface;
use Jkphl\Respimgcss\Domain\Service\DensityCssRulesetCompilerService;
use Jkphl\Respimgcss\Domain\Service\WidthCssRulesetCompilerService;

/**
 * CSS Ruleset compiler factory
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Factory
 */
class CssRulesetCompilerServiceFactory
{
    /**
     * CSS Ruleset Compiler Service constructor
     *
     * @param CssRulesetInterface $cssRuleset             CSS Ruleset
     * @param UnitLengthInterface[] $breakpoints          Breakpoints
     * @param ImageCandidateSetInterface $imageCandidates Image candidates
     *
     * @return CssRulesetCompilerServiceInterface CSS Ruleset compiler service
     * @throws RuntimeException If the image candidate set is invalid or empty
     */
    public static function createForImageCandidates(
        CssRulesetInterface $cssRuleset,
        array $breakpoints,
        ImageCandidateSetInterface $imageCandidates
    ) {
        $cssRulesetCompiler = null;
        switch ($imageCandidates->getType()) {
            case ImageCandidateInterface::TYPE_DENSITY:
                $cssRulesetCompiler = new DensityCssRulesetCompilerService($cssRuleset, $breakpoints, $imageCandidates);
                break;
            case ImageCandidateInterface::TYPE_WIDTH:
                $cssRulesetCompiler = new WidthCssRulesetCompilerService($cssRuleset, $breakpoints, $imageCandidates);
                break;
            default:
                throw new RuntimeException(
                    RuntimeException::INVALID_OR_EMPTY_IMAGE_CANDIDATE_SET_STR,
                    RuntimeException::INVALID_OR_EMPTY_IMAGE_CANDIDATE_SET
                );
        }

        return $cssRulesetCompiler;
    }
}