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

use Jkphl\Respimgcss\Application\Contract\CalculatorServiceFactoryInterface;
use Jkphl\Respimgcss\Application\Factory\LengthFactory;
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
        $sourceSize1       = $sourceSizeFactory->createFromSourceSizeStr(
            '((min-width: 200px) and (min-resolution: 2)) 80vw'
        );
        $sourceSize2       = $sourceSizeFactory->createFromSourceSizeStr('(min-width: 800px) 50vw');
        $sourceSize3       = $sourceSizeFactory->createFromSourceSizeStr('100vw');
        $sourceSize4       = $sourceSizeFactory->createFromSourceSizeStr('(min-width: 400px) 80vw');
        $sourceSize5       = $sourceSizeFactory->createFromSourceSizeStr(
            '((min-width: 800px) and (max-width: 1200px)) 50vw'
        );
        $sourceSizeList    = new SourceSizeList(
            [$sourceSize1, $sourceSize2, $sourceSize3, $sourceSize4, $sourceSize5],
            $sourceSizeFactory
        );
        $this->assertInstanceOf(SourceSizeList::class, $sourceSizeList);

        $this->assertEquals(5, count($sourceSizeList));
        $this->assertEquals($sourceSize2, $sourceSizeList[0]);
        $this->assertEquals($sourceSize5, $sourceSizeList[1]);
        $this->assertEquals($sourceSize4, $sourceSizeList[2]);
        $this->assertEquals($sourceSize1, $sourceSizeList[3]);
        $this->assertEquals($sourceSize3, $sourceSizeList[4]);

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

        $imageCandidateResultFiles = ['small.jpg', 'medium.jpg', 'extralarge.jpg'];

        foreach ([1] as $density) {
            /** @var AbsoluteLengthInterface $breakpoint */
            foreach ($breakpoints as $breakpoint) {
                $imageCandidateMatch = $sourceSizeList->findImageCandidate($imageCandidateSet, $breakpoint, $density);
                $this->assertInstanceOf(ImageCandidateMatch::class, $imageCandidateMatch);
                $this->assertEquals(
                    $imageCandidateMatch->getImageCandidate()->getFile(),
                    array_shift($imageCandidateResultFiles)
                );
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
        $lengthFactory = new LengthFactory($this->createMock(CalculatorServiceFactoryInterface::class), 16);
        new SourceSizeList(['test'], $lengthFactory);
    }

    /**
     * Test empty source size list
     */
    public function testEmptySourceSizeList()
    {
        $imageCandidateSet = new ImageCandidateSet(new WidthImageCandidate('small.jpg', 400));
        $this->assertInstanceOf(ImageCandidateSet::class, $imageCandidateSet);

        $imageCandidateSet[] = new WidthImageCandidate('medium.jpg', 800);
        $imageCandidateSet[] = new WidthImageCandidate('large.jpg', 1200);
        $imageCandidateSet[] = new WidthImageCandidate('extralarge.jpg', 1600);

        $sourceSizeFactory = new SourceSizeFactory(new ViewportCalculatorServiceFactory(), 16);
        $sourceSizeList    = new SourceSizeList([], $sourceSizeFactory);
        $this->assertInstanceOf(SourceSizeList::class, $sourceSizeList);
        $this->assertEquals(0, count($sourceSizeList));
        $this->assertNull(
            $sourceSizeList->findImageCandidate(
                $imageCandidateSet,
                $sourceSizeFactory->createAbsoluteLength(0),
                1
            )
        );
    }

    /**
     * Test a non matching set of image candidates
     */
    public function testNonMatchingImageCandidates()
    {
        $imageCandidateSet = new ImageCandidateSet(new WidthImageCandidate('small.jpg', 400));
        $sourceSizeFactory = new SourceSizeFactory(new ViewportCalculatorServiceFactory(), 16);
        $sourceSize        = $sourceSizeFactory->createFromSourceSizeStr(
            '((min-width: 1000px) and (max-width: 2000px)) 100vw'
        );
        $sourceSizeList    = new SourceSizeList([$sourceSize], $sourceSizeFactory);
        $this->assertInstanceOf(SourceSizeList::class, $sourceSizeList);
        $this->assertEquals(1, count($sourceSizeList));
        $this->assertNull(
            $sourceSizeList->findImageCandidate(
                $imageCandidateSet,
                $sourceSizeFactory->createAbsoluteLength(1500),
                1
            )
        );
    }

    /**
     * Test empty image candidate set
     */
    public function testEmptyImageCandidateSet()
    {
        $imageCandidateSet = $this->createMock(ImageCandidateSet::class);
        $sourceSizeFactory = new SourceSizeFactory(new ViewportCalculatorServiceFactory(), 16);
        $sourceSize        = $sourceSizeFactory->createFromSourceSizeStr('(min-width: 1px) 100vw');
        $sourceSizeList    = new SourceSizeList([$sourceSize], $sourceSizeFactory);
        $this->assertInstanceOf(SourceSizeList::class, $sourceSizeList);
        $this->assertEquals(1, count($sourceSizeList));
        $this->assertNull(
            $sourceSizeList->findImageCandidate(
                $imageCandidateSet,
                $sourceSizeFactory->createAbsoluteLength(100),
                1
            )
        );
    }

    /**
     * Test the sorting of the source sizes list
     */
    public function testSourceSizeListSorting()
    {
        $sourceSizeFactory = new SourceSizeFactory(new ViewportCalculatorServiceFactory(), 16);
        $sourceSize1       = $sourceSizeFactory->createFromSourceSizeStr(
            '((min-resolution: 1) and (max-resolution: 3)) 100vw'
        );
        $sourceSize2       = $sourceSizeFactory->createFromSourceSizeStr('(resolution: 1) 100vw');
        $sourceSize3       = $sourceSizeFactory->createFromSourceSizeStr(
            '((min-resolution: 1) and (max-resolution: 2)) 100vw'
        );
        $sourceSize4       = $sourceSizeFactory->createFromSourceSizeStr('(min-resolution: 1) 100vw');
        $sourceSize5       = $sourceSizeFactory->createFromSourceSizeStr(
            '((min-resolution: 2) and (max-resolution: 3)) 100vw'
        );
        $sourceSize6       = $sourceSizeFactory->createFromSourceSizeStr('(resolution: 1) 100vw');
        $sourceSizeList    = new SourceSizeList(
            [$sourceSize1, $sourceSize2, $sourceSize3, $sourceSize4, $sourceSize5, $sourceSize6],
            $sourceSizeFactory
        );
        $this->assertInstanceOf(SourceSizeList::class, $sourceSizeList);
        $this->assertEquals(6, count($sourceSizeList));
        $this->assertEquals($sourceSize5, $sourceSizeList[0]);
        $this->assertEquals($sourceSize4, $sourceSizeList[1]);
        $this->assertEquals($sourceSize1, $sourceSizeList[2]);
        $this->assertEquals($sourceSize3, $sourceSizeList[3]);
        $this->assertEquals($sourceSize2, $sourceSizeList[4]);
        $this->assertEquals($sourceSize6, $sourceSizeList[5]);
    }
}