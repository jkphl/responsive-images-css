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

namespace Jkphl\Respimgcss\Tests\Application;

use Jkphl\Respimgcss\Application\Factory\ImageCandidateFactory;
use Jkphl\Respimgcss\Domain\Model\DensityImageCandidate;
use Jkphl\Respimgcss\Domain\Model\WidthImageCandidate;
use Jkphl\Respimgcss\Tests\AbstractTestBase;

/**
 * Image candidate factory test
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests
 */
class ImageCandidateFactoryTest extends AbstractTestBase
{
    /**
     * Test the image candidate factory with an invalid image candidate string
     *
     * @expectedException \Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException
     * @expectedExceptionCode 1522500150
     */
    public function testImageCandidateFactoryWithInvalidString()
    {
        ImageCandidateFactory::createImageCandidateFromString('invalid image candidate string');
    }

    /**
     * Test the image candidate factory with a pixel density based candidate
     */
    public function testImageCandidateFactoryWithPixelDensityString()
    {
        $pixelDensityImageCandidate = ImageCandidateFactory::createImageCandidateFromString('image.jpg 1x');
        $this->assertInstanceOf(DensityImageCandidate::class, $pixelDensityImageCandidate);
    }

    /**
     * Test the image candidate factory with an undefined candidate notation
     */
    public function testImageCandidateFactoryWithImpliedPixelDensityString()
    {
        $pixelDensityImageCandidate = ImageCandidateFactory::createImageCandidateFromString('image.jpg');
        $this->assertInstanceOf(DensityImageCandidate::class, $pixelDensityImageCandidate);
    }

    /**
     * Test the image candidate factory with a pixel density based candidate
     */
    public function testImageCandidateFactoryWithWidthString()
    {
        $widthImageCandidate = ImageCandidateFactory::createImageCandidateFromString('image.jpg 1000w');
        $this->assertInstanceOf(WidthImageCandidate::class, $widthImageCandidate);
    }

    /**
     * Test the image candidate factory with an invalid image candidate file
     *
     * @expectedException \Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException
     * @expectedExceptionCode 1522502569
     */
    public function testImageCandidateFactoryWithInvalidFile()
    {
        ImageCandidateFactory::createImageCandidateFromFileAndDescriptor('');
    }

    /**
     * Test the image candidate factory with an invalid image candidate descriptor
     *
     * @expectedException \Jkphl\Respimgcss\Application\Exceptions\InvalidArgumentException
     * @expectedExceptionCode 1522502721
     */
    public function testImageCandidateFactoryWithInvalidDescriptor()
    {
        ImageCandidateFactory::createImageCandidateFromFileAndDescriptor('image.jpg', '124abc');
    }

    /**
     * Test the image candidate factory with a pixel density based file and descriptor
     */
    public function testImageCandidateFactoryWithPixelDensityDescriptor()
    {
        $pixelDensityImageCandidate = ImageCandidateFactory::createImageCandidateFromFileAndDescriptor(
            'image.jpg',
            '1x'
        );
        $this->assertInstanceOf(DensityImageCandidate::class, $pixelDensityImageCandidate);
    }

    /**
     * Test the image candidate factory with an undefined candidate descriptor
     */
    public function testImageCandidateFactoryWithImpliedPixelDensityDescriptor()
    {
        $pixelDensityImageCandidate = ImageCandidateFactory::createImageCandidateFromFileAndDescriptor('image.jpg');
        $this->assertInstanceOf(DensityImageCandidate::class, $pixelDensityImageCandidate);
    }

    /**
     * Test the image candidate factory with a pixel density based candidate
     */
    public function testImageCandidateFactoryWithWidthDescriptor()
    {
        $widthImageCandidate = ImageCandidateFactory::createImageCandidateFromFileAndDescriptor(
            'image.jpg',
            '1000w'
        );
        $this->assertInstanceOf(WidthImageCandidate::class, $widthImageCandidate);
    }
}
