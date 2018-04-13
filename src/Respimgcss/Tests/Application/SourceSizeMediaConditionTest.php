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

use Jkphl\Respimgcss\Application\Model\SourceSizeMediaCondition;
use Jkphl\Respimgcss\Domain\Contract\CssMinMaxMediaConditionInterface;
use Jkphl\Respimgcss\Domain\Model\Css\ResolutionMediaCondition;
use Jkphl\Respimgcss\Domain\Model\Css\WidthMediaCondition;
use Jkphl\Respimgcss\Tests\AbstractTestBase;
use Jkphl\Respimgcss\Tests\Domain\Mock\AbsoluteLength;

/**
 * Source size media condition tests
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Application
 */
class SourceSizeMediaConditionTest extends AbstractTestBase
{
    /**
     * Test the source media condition tests
     */
    public function testSourceSizeMediaCondition()
    {
        $sourceSizeMediaCondition = new SourceSizeMediaCondition('value', ['condition']);
        $this->assertInstanceOf(SourceSizeMediaCondition::class, $sourceSizeMediaCondition);
        $this->assertEquals('value', $sourceSizeMediaCondition->getValue());
        $this->assertEquals(['condition'], $sourceSizeMediaCondition->getConditions());
    }

    /**
     * Test the minimum & maximum values of a source size media condition
     */
    public function testSourceSizesMinMaxMediaConditions()
    {
        $sourceSizeMediaCondition = $this->makeSourceSizeMediaCondition();
        $this->assertInstanceOf(SourceSizeMediaCondition::class, $sourceSizeMediaCondition);
        $this->assertEquals(100, $sourceSizeMediaCondition->getMinimumWidth());
        $this->assertEquals(200, $sourceSizeMediaCondition->getMaximumWidth());
        $this->assertEquals(2, $sourceSizeMediaCondition->getMinimumResolution());
        $this->assertEquals(3, $sourceSizeMediaCondition->getMaximumResolution());

        $this->assertTrue($sourceSizeMediaCondition->matches(new AbsoluteLength(150), 2.5));
        $this->assertFalse($sourceSizeMediaCondition->matches(new AbsoluteLength(50), 2.5));
        $this->assertFalse($sourceSizeMediaCondition->matches(new AbsoluteLength(250), 2.5));
        $this->assertFalse($sourceSizeMediaCondition->matches(new AbsoluteLength(150), 1));
        $this->assertFalse($sourceSizeMediaCondition->matches(new AbsoluteLength(150), 4));
        $this->assertFalse($sourceSizeMediaCondition->matches(new AbsoluteLength(50), 1));
        $this->assertFalse($sourceSizeMediaCondition->matches(new AbsoluteLength(250), 4));
    }

    /**
     * Create a source size media condition for testing purposes
     *
     * @return SourceSizeMediaCondition Source size media condition
     */
    protected function makeSourceSizeMediaCondition(): SourceSizeMediaCondition
    {
        $minWidthCondition      = new WidthMediaCondition(
            new AbsoluteLength(100),
            CssMinMaxMediaConditionInterface::MIN
        );
        $maxWidthCondition      = new WidthMediaCondition(
            new AbsoluteLength(200),
            CssMinMaxMediaConditionInterface::MAX
        );
        $minResolutionCondition = new ResolutionMediaCondition(
            new AbsoluteLength(2),
            CssMinMaxMediaConditionInterface::MIN
        );
        $maxResolutionCondition = new ResolutionMediaCondition(
            new AbsoluteLength(3),
            CssMinMaxMediaConditionInterface::MAX
        );
        return new SourceSizeMediaCondition(
            'value',
            [$minWidthCondition, $maxWidthCondition, $minResolutionCondition, $maxResolutionCondition]
        );
    }

    /**
     * Test the equaling values of a source size media condition
     */
    public function testSourceSizesEqualsMediaConditions()
    {
        $eqWidthCondition         = new WidthMediaCondition(
            new AbsoluteLength(100),
            CssMinMaxMediaConditionInterface::EQ
        );
        $eqResolutionCondition    = new ResolutionMediaCondition(
            new AbsoluteLength(2),
            CssMinMaxMediaConditionInterface::EQ
        );
        $sourceSizeMediaCondition = new SourceSizeMediaCondition(
            'value',
            [$eqWidthCondition, $eqResolutionCondition]
        );
        $this->assertInstanceOf(SourceSizeMediaCondition::class, $sourceSizeMediaCondition);
        $this->assertEquals(100, $sourceSizeMediaCondition->getMinimumWidth());
        $this->assertEquals(100, $sourceSizeMediaCondition->getMaximumWidth());
        $this->assertEquals(2, $sourceSizeMediaCondition->getMinimumResolution());
        $this->assertEquals(2, $sourceSizeMediaCondition->getMaximumResolution());
        $this->assertTrue($sourceSizeMediaCondition->matches(new AbsoluteLength(100), 2));
        $this->assertFalse($sourceSizeMediaCondition->matches(new AbsoluteLength(50), 2));
        $this->assertFalse($sourceSizeMediaCondition->matches(new AbsoluteLength(100), 3));
    }
}
