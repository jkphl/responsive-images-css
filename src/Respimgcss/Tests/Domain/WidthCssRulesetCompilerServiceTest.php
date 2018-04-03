<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Domain
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

namespace Jkphl\Respimgcss\Tests\Domain;

use Jkphl\Respimgcss\Domain\Contract\CssRulesetInterface;
use Jkphl\Respimgcss\Domain\Model\Css\Ruleset;
use Jkphl\Respimgcss\Domain\Model\ImageCandidateSet;
use Jkphl\Respimgcss\Domain\Model\AbstractLength;
use Jkphl\Respimgcss\Domain\Model\WidthImageCandidate;
use Jkphl\Respimgcss\Domain\Service\WidthCssRulesetCompilerService;
use Jkphl\Respimgcss\Tests\AbstractTestBase;

/**
 * Width CSS ruleset compiler service tests
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Domain
 */
class WidthCssRulesetCompilerServiceTest extends AbstractTestBase
{
    /**
     * Test the width CSS ruleset compiler service
     */
    public function testWidthCssRulesetCompilerService()
    {
        $ruleset             = new Ruleset();
        $length              = new AbstractLength(500);
        $imageCandidateSet   = new ImageCandidateSet();
        $imageCandidateSet[] = new WidthImageCandidate('small.jpg', 400);
        $imageCandidateSet[] = new WidthImageCandidate('medium.jpg', 800);

        $compiler = new WidthCssRulesetCompilerService($ruleset, [$length], $imageCandidateSet);
        $this->assertInstanceOf(WidthCssRulesetCompilerService::class, $compiler);

        $cssRuleset = $compiler->compile(1);
        $this->assertInstanceOf(CssRulesetInterface::class, $cssRuleset);
    }
}