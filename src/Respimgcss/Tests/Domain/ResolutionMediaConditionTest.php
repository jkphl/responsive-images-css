<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests
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

use Jkphl\Respimgcss\Domain\Contract\AbsoluteLengthInterface;
use Jkphl\Respimgcss\Domain\Contract\CssMinMaxMediaConditionInterface;
use Jkphl\Respimgcss\Domain\Model\Css\ResolutionMediaCondition;
use Jkphl\Respimgcss\Tests\AbstractTestBase;
use Jkphl\Respimgcss\Tests\Domain\Mock\AbsoluteLength;

/**
 * Resolution media condition tests
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests
 */
class ResolutionMediaConditionTest extends AbstractTestBase
{
    /**
     * Test the resolution media condition
     */
    public function testResolutionMediaCondition()
    {
        $resolutionMediaCondition = new ResolutionMediaCondition(
            new AbsoluteLength(2.0),
            CssMinMaxMediaConditionInterface::MAX
        );
        $this->assertInstanceOf(ResolutionMediaCondition::class, $resolutionMediaCondition);
        $resolutionConditionValue = $resolutionMediaCondition->getValue();
        $this->assertInstanceOf(AbsoluteLengthInterface::class, $resolutionConditionValue);
        $this->assertEquals(2.0, $resolutionConditionValue->getValue());
        $this->assertEquals(CssMinMaxMediaConditionInterface::MAX, $resolutionMediaCondition->getModifier());
    }

    /**
     * Test the resolution media condition with an invalid modifier
     *
     * @expectedException \Jkphl\Respimgcss\Domain\Exceptions\InvalidArgumentException
     * @expectedExceptionCode 1522531210
     */
    public function testResolutionMediaConditionInvalidModifier()
    {
        new ResolutionMediaCondition(new AbsoluteLength(1.0), 'invalid');
    }
}
