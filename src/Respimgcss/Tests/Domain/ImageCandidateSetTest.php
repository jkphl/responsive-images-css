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

use Jkphl\Respimgcss\Domain\Contract\ImageCandidateInterface;
use Jkphl\Respimgcss\Domain\Model\DensityImageCandidate;
use Jkphl\Respimgcss\Domain\Model\ImageCandidateSet;
use Jkphl\Respimgcss\Tests\AbstractTestBase;

/**
 * Image candidate set tests
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Domain
 */
class ImageCandidateSetTest extends AbstractTestBase
{
    /**
     * Test the image candidate set
     */
    public function testImageCandidateSet()
    {
        $imageCandidateSet = new ImageCandidateSet();
        $this->assertInstanceOf(ImageCandidateSet::class, $imageCandidateSet);

        $imageCandidateSet[] = new DensityImageCandidate('image.jpg', 1);
        $imageCandidateSet[] = new DensityImageCandidate('image.jpg', 2);
        $imageCandidateSet[] = new DensityImageCandidate('image.jpg', 3);
        $this->assertEquals(3, count($imageCandidateSet));
        $this->assertTrue(isset($imageCandidateSet[1]));
        unset($imageCandidateSet[1]);
        $this->assertEquals(2, count($imageCandidateSet));
        $this->assertInstanceOf(ImageCandidateInterface::class, $imageCandidateSet[0]);

        foreach ($imageCandidateSet as $index => $imageCandidate) {
            $this->assertInstanceOf(ImageCandidateInterface::class, $imageCandidate);
        }
    }

    /**
     * Test the image candidate set with an invalid member
     *
     * @expectedException \Jkphl\Respimgcss\Domain\Exceptions\InvalidArgumentException
     * @expectedExceptionCode 1522507099
     */
    public function testImageCandidateSetInvalidMember()
    {
        $imageCandidateSet = new ImageCandidateSet();
        $this->assertInstanceOf(ImageCandidateSet::class, $imageCandidateSet);
        $imageCandidateSet[] = 'invalid';
    }
}