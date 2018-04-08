<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Domain
 * @author     Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @copyright  Copyright © 2018 Joschi Kuphal <joschi@kuphal.net> / @jkphl
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

use Jkphl\Respimgcss\Domain\Contract\AbsoluteLengthInterface;
use Jkphl\Respimgcss\Domain\Contract\CssMinMaxMediaConditionInterface;
use Jkphl\Respimgcss\Domain\Model\Css\WidthMediaCondition;
use Jkphl\Respimgcss\Tests\AbstractTestBase;
use Jkphl\Respimgcss\Tests\Domain\Mock\AbsoluteLength;

/**
 * Width media condition test
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Domain
 */
class WidthMediaConditionTest extends AbstractTestBase
{
    /**
     * Test the width media condition
     */
    public function testWidthMediaCondition()
    {
        $width               = new AbsoluteLength(100);
        $widthMediaCondition = new WidthMediaCondition($width, CssMinMaxMediaConditionInterface::MIN);
        $this->assertInstanceOf(WidthMediaCondition::class, $widthMediaCondition);
        $this->assertEquals('width', $widthMediaCondition->getProperty());
        $this->assertEquals($width, $widthMediaCondition->getValue());
        $this->assertEquals(CssMinMaxMediaConditionInterface::MIN, $widthMediaCondition->getModifier());
        $widthMediaConditionValue = $widthMediaCondition->getValue();
        $this->assertInstanceOf(AbsoluteLengthInterface::class, $widthMediaConditionValue);
        $this->assertEquals(100, $widthMediaConditionValue->getValue());
        $this->assertTrue($widthMediaCondition->matches(200));
        $this->assertFalse($widthMediaCondition->matches(0));

        $widthMediaCondition = new WidthMediaCondition($width, CssMinMaxMediaConditionInterface::MAX);
        $this->assertTrue($widthMediaCondition->matches(0));
        $this->assertFalse($widthMediaCondition->matches(200));

        $widthMediaCondition = new WidthMediaCondition($width, CssMinMaxMediaConditionInterface::EQ);
        $this->assertTrue($widthMediaCondition->matches(100));
        $this->assertFalse($widthMediaCondition->matches(200));
    }

    /**
     * Test the width media condition with an invalid modifier
     *
     * @expectedException \Jkphl\Respimgcss\Domain\Exceptions\InvalidArgumentException
     * @expectedExceptionCode 1522531210
     */
    public function testWidthMediaConditionInvalidModifier()
    {
        new WidthMediaCondition(new AbsoluteLength(100), 'invalid');
    }
}
