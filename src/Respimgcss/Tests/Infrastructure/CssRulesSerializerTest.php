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

namespace Jkphl\Respimgcss\Tests\Infrastructure;

use Jkphl\Respimgcss\Domain\Contract\CssMinMaxMediaConditionInterface;
use Jkphl\Respimgcss\Domain\Model\Css\ResolutionMediaCondition;
use Jkphl\Respimgcss\Domain\Model\Css\Rule;
use Jkphl\Respimgcss\Domain\Model\DensityImageCandidate;
use Jkphl\Respimgcss\Domain\Model\AbstractLength;
use Jkphl\Respimgcss\Infrastructure\CssRulesSerializer;
use Jkphl\Respimgcss\Tests\AbstractTestBase;

/**
 * CSS rules serializer
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Infrastructure
 */
class CssRulesSerializerTest extends AbstractTestBase
{
    /**
     * Rule 1
     *
     * @var Rule
     */
    protected $rule1;
    /**
     * Rule 2
     *
     * @var Rule
     */
    protected $rule2;

    /**
     * Test the CSS rules serializer
     */
    public function testCssRulesSerializerTest()
    {
        $serializer = new CssRulesSerializer([$this->rule1, $this->rule2]);
        $this->assertInstanceOf(CssRulesSerializer::class, $serializer);

        $css = $serializer->toCss('.example');
        $this->assertTrue(is_string($css));
        $this->assertStringEqualsFile(dirname(__DIR__).'/Fixture/Css/DensityImageCandidates.css', $css);
    }

    /**
     * Test the CSS rules serializer
     *
     * @expectedException \Jkphl\Respimgcss\Ports\InvalidArgumentException
     * @expectedExceptionCode 1522574161
     */
    public function testCssRulesSerializerTestInvalidSelector()
    {
        $serializer = new CssRulesSerializer([$this->rule1, $this->rule2]);
        $this->assertInstanceOf(CssRulesSerializer::class, $serializer);
        $serializer->toCss('');
    }

    /**
     * Test setup
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();
        $imageCandidate1 = new DensityImageCandidate('small.jpg', 1);
        $this->rule1     = new Rule($imageCandidate1, []);
        $imageCandidate2 = new DensityImageCandidate('large.jpg', 2);
        $mediaCondition  = new ResolutionMediaCondition(new AbstractLength(2), CssMinMaxMediaConditionInterface::MIN);
        $this->rule2     = new Rule($imageCandidate2, [$mediaCondition]);
    }
}