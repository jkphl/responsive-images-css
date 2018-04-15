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

use Jkphl\Respimgcss\Domain\Contract\ImageCandidateInterface;
use Jkphl\Respimgcss\Domain\Model\Css\MediaCondition;
use Jkphl\Respimgcss\Domain\Model\ImageCandidateMatch;
use Jkphl\Respimgcss\Domain\Model\WidthImageCandidate;
use Jkphl\Respimgcss\Tests\AbstractTestBase;

/**
 * Image candidate match tests
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests
 */
class ImageCandidateMatchTest extends AbstractTestBase
{
    /**
     * Test the image candidate match
     */
    public function testImageCandidateMatch()
    {
        $mediaCondition      = new MediaCondition('property', 'value');
        $imageCandidate      = new WidthImageCandidate('small.jpg', 400);
        $imageCandidateMatch = new ImageCandidateMatch($mediaCondition, $imageCandidate);
        $this->assertInstanceOf(ImageCandidateMatch::class, $imageCandidateMatch);
        $this->assertInstanceOf(MediaCondition::class, $imageCandidateMatch->getMediaCondition());
        $this->assertInstanceOf(ImageCandidateInterface::class, $imageCandidateMatch->getImageCandidate());
    }
}
