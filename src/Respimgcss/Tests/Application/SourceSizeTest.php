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
use Jkphl\Respimgcss\Application\Factory\LengthFactory;
use Jkphl\Respimgcss\Application\Model\SourceSize;
use Jkphl\Respimgcss\Application\Model\SourceSizeMediaCondition;
use Jkphl\Respimgcss\Domain\Contract\AbsoluteLengthInterface;
use Jkphl\Respimgcss\Infrastructure\ViewportCalculatorServiceFactory;
use Jkphl\Respimgcss\Tests\AbstractTestBase;

/**
 * Source size test
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Application
 */
class SourceSizeTest extends AbstractTestBase
{
    /**
     * Test the source size
     *
     * @expectedException \Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException
     * @expectedExceptionCode 1523114805
     */
    public function testSourceSize()
    {
        $lengthFactory            = new LengthFactory(new ViewportCalculatorServiceFactory(), 16);
        $unitLength               = $lengthFactory->createLengthFromString('100px');
        $sourceSizeMediaCondition = new SourceSizeMediaCondition('value');
        $sourceSize               = new SourceSize($unitLength, $sourceSizeMediaCondition);
        $this->assertInstanceOf(SourceSize::class, $sourceSize);
        $this->assertInstanceOf(AbsoluteLengthInterface::class, $sourceSize->getValue());
        $this->assertEquals($unitLength, $sourceSize->getValue());
        $this->assertInstanceOf(SourceSizeMediaCondition::class, $sourceSize->getMediaCondition());
        $this->assertEquals($sourceSizeMediaCondition, $sourceSize->getMediaCondition());
        $this->assertFalse($sourceSize->hasConditions());

        // Test with an invalid unit type
        new SourceSize($this->createMock(UnitLengthInterface::class), $sourceSizeMediaCondition);
    }
}