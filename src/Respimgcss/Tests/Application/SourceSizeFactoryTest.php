<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Infrastructure
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

use Jkphl\Respimgcss\Application\Factory\SourceSizeFactory;
use Jkphl\Respimgcss\Application\Model\SourceSize;
use Jkphl\Respimgcss\Domain\Model\Css\WidthMediaCondition;
use Jkphl\Respimgcss\Infrastructure\ViewportCalculatorServiceFactory;
use Jkphl\Respimgcss\Tests\AbstractTestBase;

/**
 * Source size factory tests
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Infrastructure
 */
class SourceSizeFactoryTest extends AbstractTestBase
{
    /**
     * Source size factory
     *
     * @var SourceSizeFactory
     */
    protected $sourceSizeFactory;

    /**
     * Test setup
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->sourceSizeFactory = new SourceSizeFactory(new ViewportCalculatorServiceFactory(), 16);
    }

    /**
     * Test the source size factory with a viewport size value
     */
    public function testSourceSizeFactoryViewport()
    {
        $this->assertInstanceOf(SourceSizeFactory::class, $this->sourceSizeFactory);

        $sourceSize = $this->sourceSizeFactory->createFromSourceSizeStr('((max-width: 500px) and (resolution: 1)) 100vw');
        $this->assertInstanceOf(SourceSize::class, $sourceSize);
    }

    /**
     * Test the source size factory with a calc() size value
     */
    public function testSourceSizeFactoryCalc()
    {
        $this->assertInstanceOf(SourceSizeFactory::class, $this->sourceSizeFactory);

        $sourceSize = $this->sourceSizeFactory->createFromSourceSizeStr('(max-width: 500px) calc(40vw - 100px)');
        $this->assertInstanceOf(SourceSize::class, $sourceSize);
    }

    /**
     * Test the source size factory with an invalid string
     *
     * @expectedException \Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException
     * @expectedExceptionCode 1522685593
     */
    public function testSourceSizeFactoryInvalid()
    {
        $this->sourceSizeFactory->createFromSourceSizeStr('');
    }

    /**
     * Test the source size factory with an invalid string
     *
     * @expectedException \Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException
     * @expectedExceptionCode 1522685593
     */
    public function testSourceSizeFactoryInvalidCalc1()
    {
        $this->sourceSizeFactory->createFromSourceSizeStr(')');
    }

    /**
     * Test the source size factory with an invalid string
     *
     * @expectedException \Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException
     * @expectedExceptionCode 1522685593
     */
    public function testSourceSizeFactoryInvalidCalc2()
    {
        $this->sourceSizeFactory->createFromSourceSizeStr('calc())');
    }

    /**
     * Test the source size factory with an invalid string
     */
    public function testSourceSizeFactoryInvalidWidthResolution()
    {
        $sourceSize = $this->sourceSizeFactory->createFromSourceSizeStr('((min-width: 123abc) and (min-resolution: 456abc)) 100vw');
        $this->assertInstanceOf(SourceSize::class, $sourceSize);
        $this->assertTrue(is_array($sourceSize->getMediaCondition()->getConditions()));
        $this->assertEquals([], $sourceSize->getMediaCondition()->getConditions());
    }

    /**
     * Test the source size factory with a calc based media condition value
     */
    public function testSourceSizeFactoryWidthCalc()
    {
        $sourceSize = $this->sourceSizeFactory->createFromSourceSizeStr('(min-width: calc(33em - 100px)) 100vw');
        $this->assertInstanceOf(SourceSize::class, $sourceSize);

        $sourceSizeMediaCondition = $sourceSize->getMediaCondition();
        $this->assertTrue(is_array($sourceSizeMediaCondition->getConditions()));
        $this->assertEquals(1, count($sourceSizeMediaCondition->getConditions()));

        $mediaCondition = current($sourceSizeMediaCondition->getConditions());
        $this->assertInstanceOf(WidthMediaCondition::class, $mediaCondition);
        $this->assertEquals(428, $mediaCondition->getValue()->getValue());
    }

    /**
     * Test the source size factory with an invalid calc based media condition value
     */
    public function testSourceSizeFactoryWidthInvalidCalc()
    {
        $sourceSize = $this->sourceSizeFactory->createFromSourceSizeStr('(min-width: calc((33em - 100px) 100vw');
        $this->assertInstanceOf(SourceSize::class, $sourceSize);
        $this->assertTrue(is_array($sourceSize->getMediaCondition()->getConditions()));
        $this->assertEquals([], $sourceSize->getMediaCondition()->getConditions());
    }
}