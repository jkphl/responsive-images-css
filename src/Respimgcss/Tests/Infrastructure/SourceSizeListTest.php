<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Infrastructure
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

namespace Jkphl\Respimgcss\Tests\Infrastructure;

use Jkphl\Respimgcss\Application\Factory\SourceSizeFactory;
use Jkphl\Respimgcss\Application\Model\ImageCandidateSet;
use Jkphl\Respimgcss\Domain\Contract\AbsoluteLengthInterface;
use Jkphl\Respimgcss\Domain\Model\ImageCandidateMatch;
use Jkphl\Respimgcss\Domain\Model\WidthImageCandidate;
use Jkphl\Respimgcss\Infrastructure\SourceSizeList;
use Jkphl\Respimgcss\Infrastructure\ViewportCalculatorServiceFactory;
use Jkphl\Respimgcss\Tests\AbstractTestBase;

/**
 * Source size list test
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Infrastructure
 */
class SourceSizeListTest extends AbstractTestBase
{
    /**
     * Test the source size list
     */
    public function testSourceSizeList()
    {
        $sourceSizeFactory = new SourceSizeFactory(new ViewportCalculatorServiceFactory(), 16);
        $sourceSize1       = $sourceSizeFactory->createFromSourceSizeStr('((min-width: 200px) and (min-resolution: 2)) 80vw');
        $sourceSize2       = $sourceSizeFactory->createFromSourceSizeStr('(min-width: 400px) 80vw');
        $sourceSize3       = $sourceSizeFactory->createFromSourceSizeStr('(min-width: 800px) 50vw');
        $sourceSize4       = $sourceSizeFactory->createFromSourceSizeStr('100vw');
        $sourceSizeList    = new SourceSizeList([$sourceSize1, $sourceSize2, $sourceSize3, $sourceSize4]);
        $this->assertInstanceOf(SourceSizeList::class, $sourceSizeList);
        $this->assertEquals(4, count($sourceSizeList));
        $this->assertEquals($sourceSize1, $sourceSizeList[2]);
        $this->assertEquals($sourceSize2, $sourceSizeList[1]);
        $this->assertEquals($sourceSize3, $sourceSizeList[0]);
        $this->assertEquals($sourceSize4, $sourceSizeList[3]);

        $this->matchImageCandidates(
            $sourceSizeList,
            [
                $sourceSizeFactory->createAbsoluteLength(0),
                $sourceSizeFactory->createAbsoluteLength(400),
                $sourceSizeFactory->createAbsoluteLength(800),
            ]
        );
    }

    /**
     * Find image candidates for breakpoints
     *
     * @param SourceSizeList $sourceSizeList         Source sizes list
     * @param AbsoluteLengthInterface[] $breakpoints Breakpoints
     */
    protected function matchImageCandidates(SourceSizeList $sourceSizeList, array $breakpoints)
    {
        $imageCandidateSet = new ImageCandidateSet(new WidthImageCandidate('small.jpg', 400));
        $this->assertInstanceOf(ImageCandidateSet::class, $imageCandidateSet);

        $imageCandidateSet[] = new WidthImageCandidate('medium.jpg', 800);
        $imageCandidateSet[] = new WidthImageCandidate('large.jpg', 1200);
        $imageCandidateSet[] = new WidthImageCandidate('extralarge.jpg', 1600);

        foreach ([1] as $density) {
            /** @var AbsoluteLengthInterface $breakpoint */
            foreach ($breakpoints as $breakpoint) {
                $imageCandidateMatch = $sourceSizeList->findImageCandidate($imageCandidateSet, $breakpoint, $density);
//                echo $breakpoint->getValue().': ';
//                print_r($imageCandidateMatch->getImageCandidate());
//                echo PHP_EOL;
                $this->assertInstanceOf(ImageCandidateMatch::class, $imageCandidateMatch);
            }
        }
    }

    /**
     * Test the source size list with invalid source size
     *
     * @expectedException \Jkphl\Respimgcss\Ports\InvalidArgumentException
     * @expectedExceptionCode 1523047851
     */
    public function testSourceSizeListInvalid()
    {
        new SourceSizeList(['test']);
    }
}