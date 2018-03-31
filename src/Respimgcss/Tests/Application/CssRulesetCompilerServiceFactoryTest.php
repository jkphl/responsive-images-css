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

use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;
use Jkphl\Respimgcss\Application\Factory\CssRulesetCompilerServiceFactory;
use Jkphl\Respimgcss\Application\Factory\LengthFactory;
use Jkphl\Respimgcss\Application\Model\ImageCandidateSet;
use Jkphl\Respimgcss\Domain\Model\DensityImageCandidate;
use Jkphl\Respimgcss\Domain\Model\Css\Ruleset;
use Jkphl\Respimgcss\Domain\Model\WidthImageCandidate;
use Jkphl\Respimgcss\Domain\Service\DensityCssRulesetCompilerService;
use Jkphl\Respimgcss\Domain\Service\WidthCssRulesetCompilerService;
use Jkphl\Respimgcss\Tests\AbstractTestBase;
use Jkphl\Respimgcss\Tests\Application\Mocks\ImageCandidateMock;

/**
 * CSS Ruleset compiler service factory tests
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests
 */
class CssRulesetCompilerServiceFactoryTest extends AbstractTestBase
{
    /**
     * Breakpoints
     *
     * @var UnitLengthInterface[]
     */
    protected $breakpoints = [];

    /**
     * Test the CSS Ruleset compiler service factory with an invalid image candidate set
     *
     * @expectedException \Jkphl\Respimgcss\Application\Exceptions\RuntimeException
     * @expectedExceptionCode 1522514954
     */
    public function testFactoryWithInvalidImageCandidateSet()
    {
        CssRulesetCompilerServiceFactory::createForImageCandidates(
            new Ruleset(),
            $this->breakpoints,
            new ImageCandidateSet(new ImageCandidateMock('image.jpg', 1))
        );
    }

    /**
     * Test the CSS Ruleset compiler service factory with a density based image candidate set
     */
    public function testFactoryWithDensityImageCandidateSet()
    {
        $compilerService = CssRulesetCompilerServiceFactory::createForImageCandidates(
            new Ruleset(),
            $this->breakpoints,
            new ImageCandidateSet(new DensityImageCandidate('image.jpg', 1))
        );
        $this->assertInstanceOf(DensityCssRulesetCompilerService::class, $compilerService);
    }

    /**
     * Test the CSS Ruleset compiler service factory with a width based image candidate set
     */
    public function testFactoryWithWidthImageCandidateSet()
    {
        $compilerService = CssRulesetCompilerServiceFactory::createForImageCandidates(
            new Ruleset(),
            $this->breakpoints,
            new ImageCandidateSet(new WidthImageCandidate('image.jpg', 1000))
        );
        $this->assertInstanceOf(WidthCssRulesetCompilerService::class, $compilerService);
    }

    /**
     * Test setup
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();
        $this->breakpoints[] = LengthFactory::createLengthFromString('24em');
        $this->breakpoints[] = LengthFactory::createLengthFromString('800px');
        $this->breakpoints[] = LengthFactory::createLengthFromString('72em');
    }
}