<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Application
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

namespace Jkphl\Respimgcss\Tests\Application;

use Jkphl\Respimgcss\Application\Factory\LengthFactory;
use Jkphl\Respimgcss\Application\Model\ImageCandidateSet;
use Jkphl\Respimgcss\Application\Service\CssRulesetCompilerService;
use Jkphl\Respimgcss\Domain\Contract\CssRulesetInterface;
use Jkphl\Respimgcss\Domain\Model\Css\Ruleset;
use Jkphl\Respimgcss\Domain\Model\DensityImageCandidate;
use Jkphl\Respimgcss\Infrastructure\ViewportCalculatorServiceFactory;
use Jkphl\Respimgcss\Tests\AbstractTestBase;

/**
 * CSS ruleset compiler service test
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Application
 */
class CssRulesetCompilerServiceTest extends AbstractTestBase
{
    /**
     * Test the  CSS ruleset compiler service
     */
    public function testCssRulesetCompilerService()
    {
        $lengthFactory     = new LengthFactory(new ViewportCalculatorServiceFactory(), 16);
        $ruleset           = new Ruleset();
        $breakpoints       = array_map([$lengthFactory, 'createLengthFromString'], ['24em', '800px', '72em']);
        $imageCandidateSet = new ImageCandidateSet(new DensityImageCandidate('image.jpg', 1));
        $compiler          = new CssRulesetCompilerService(
            $ruleset,
            $breakpoints,
            $imageCandidateSet,
            new ViewportCalculatorServiceFactory(),
            16
        );
        $this->assertInstanceOf(CssRulesetCompilerService::class, $compiler);

        $cssRulset = $compiler->compile([1, 2]);
        $this->assertInstanceOf(CssRulesetInterface::class, $cssRulset);
    }
}