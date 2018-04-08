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

use Jkphl\Respimgcss\Application\Factory\LengthFactory;
use Jkphl\Respimgcss\Domain\Contract\CssRulesetInterface;
use Jkphl\Respimgcss\Domain\Contract\SourceSizeImageCandidateMatch;
use Jkphl\Respimgcss\Domain\Contract\SourceSizeListInterface;
use Jkphl\Respimgcss\Domain\Model\Css\MediaCondition;
use Jkphl\Respimgcss\Domain\Model\Css\Ruleset;
use Jkphl\Respimgcss\Domain\Model\ImageCandidateSet;
use Jkphl\Respimgcss\Domain\Model\WidthImageCandidate;
use Jkphl\Respimgcss\Domain\Service\WidthCssRulesetCompilerService;
use Jkphl\Respimgcss\Infrastructure\ViewportCalculatorServiceFactory;
use Jkphl\Respimgcss\Tests\AbstractTestBase;
use Jkphl\Respimgcss\Tests\Domain\Mock\AbsoluteLength;
use PHPUnit\Framework\MockObject\Stub\ConsecutiveCalls;

/**
 * Width CSS ruleset compiler service tests
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Domain
 */
class WidthCssRulesetCompilerServiceTest extends AbstractTestBase
{
    /**
     * Test the width CSS ruleset compiler service using the image candidates
     */
    public function testWidthCssRulesetCompilerServiceImageCandidates()
    {
        $ruleset             = new Ruleset();
        $length              = new AbsoluteLength(500);
        $imageCandidateSet   = new ImageCandidateSet();
        $imageCandidateSet[] = new WidthImageCandidate('small.jpg', 400);
        $imageCandidateSet[] = new WidthImageCandidate('medium.jpg', 800);
        $lengthFactory       = new LengthFactory(new ViewportCalculatorServiceFactory(), 16);
        $compiler            = new WidthCssRulesetCompilerService(
            $ruleset,
            [$length],
            $imageCandidateSet,
            $lengthFactory
        );
        $this->assertInstanceOf(WidthCssRulesetCompilerService::class, $compiler);

        $cssRuleset = $compiler->compile(2);
        $this->assertInstanceOf(CssRulesetInterface::class, $cssRuleset);
    }

    /**
     * Test the width CSS ruleset compiler service using a source sizes list
     */
    public function testWidthCssRulesetCompilerServiceSourceSizes()
    {
        $ruleset             = new Ruleset();
        $imageCandidateSet   = new ImageCandidateSet();
        $imageCandidateSet[] = new WidthImageCandidate('small.jpg', 400);
        $imageCandidateSet[] = new WidthImageCandidate('medium.jpg', 800);
        $imageCandidateSet[] = new WidthImageCandidate('large.jpg', 1200);
        $imageCandidateSet[] = new WidthImageCandidate('extralarge.jpg', 1600);
        $lengthFactory       = new LengthFactory(new ViewportCalculatorServiceFactory(), 16);
        $sourceSizeList      = $this->createMock(SourceSizeListInterface::class);
        $sourceSizeList->/** @scrutinizer ignore-call */
        method('findImageCandidate')
            ->will($this->getImageCandidateMatches($imageCandidateSet));

        $compiler = new WidthCssRulesetCompilerService(
            $ruleset,
            [new AbsoluteLength(400), new AbsoluteLength(800), new AbsoluteLength(1200)],
            $imageCandidateSet,
            $lengthFactory,
            $sourceSizeList
        );
        $this->assertInstanceOf(WidthCssRulesetCompilerService::class, $compiler);

        $cssRuleset = $compiler->compile(1);
        $this->assertInstanceOf(CssRulesetInterface::class, $cssRuleset);
    }

    /**
     * Create a list of consecutive image candidate matches
     *
     * @param ImageCandidateSet $imageCandidateSet Image candidate set
     *
     * @return ConsecutiveCalls Image candidate matches
     * @throws \ReflectionException
     */
    protected function getImageCandidateMatches(ImageCandidateSet $imageCandidateSet): ConsecutiveCalls
    {
        $imageCandidates = [];
        foreach ($imageCandidateSet as $imageCandidate) {
            $imageCandidateReturn = $this->createMock(SourceSizeImageCandidateMatch::class);
            $imageCandidateReturn->/** @scrutinizer ignore-call */
            method('getMediaCondition')->willReturn(
                new MediaCondition('', '(min-width: '.$imageCandidate->getValue().'px)')
            );
            $imageCandidateReturn->/** @scrutinizer ignore-call */
            method('getImageCandidate')->willReturn($imageCandidate);
            $imageCandidates[] = $imageCandidateReturn;
        }

        return call_user_func_array([$this, 'onConsecutiveCalls'], $imageCandidates);
    }
}