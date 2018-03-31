<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Ports
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

namespace Jkphl\Respimgcss\Ports;

use Jkphl\Respimgcss\Domain\Contract\ImageCandidateInterface;

/**
 * GeneratorInterface
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Ports
 */
interface GeneratorInterface
{
    /**
     * Register an image candidate
     *
     * @param string $file            Image candidate file path and name
     * @param string|null $descriptor Image candidate descriptor
     *
     * @return GeneratorInterface Self reference
     * @api
     */
    public function registerImageCandidate(string $file, string $descriptor = null): GeneratorInterface;

    /**
     * Return the registered image candidates
     *
     * @return ImageCandidateInterface[] Image candidates
     */
    public function getImageCandidates(): array;

    /**
     * Create a CSS rulset for the registered image candidates
     *
     * @param float[] $densities Device display densities
     *
     * @return CssRulesetInterface CSS Ruleset
     */
    public function make(array $densities = [1]): CssRulesetInterface;
}