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

namespace Jkphl\Respimgcss\Tests\Infrastructure;

use Jkphl\Respimgcss\Domain\Contract\CssMinMaxMediaConditionInterface;
use Jkphl\Respimgcss\Domain\Model\Css\MediaCondition;
use Jkphl\Respimgcss\Domain\Model\Css\ResolutionMediaCondition;
use Jkphl\Respimgcss\Domain\Model\AbstractLength;
use Jkphl\Respimgcss\Infrastructure\CssMediaCondition;
use Jkphl\Respimgcss\Infrastructure\CssMediaConditionFactory;
use Jkphl\Respimgcss\Tests\AbstractTestBase;

/**
 * CSS media condition factory tests
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Infrastructure
 */
class CssMediaConditionFactoryTest extends AbstractTestBase
{
    /**
     * Test the CSS media condition factory
     */
    public function testCssMediaConditionFactory()
    {
        $mediaConditions = CssMediaConditionFactory::createFromMediaCondition(
            new MediaCondition('property', 'value')
        );
        $this->assertEquals([], $mediaConditions);
    }

    /**
     * Test the CSS media condition factory
     */
    public function testCssMediaConditionFactoryResolution()
    {
        $mediaConditions = CssMediaConditionFactory::createFromMediaCondition(
            new ResolutionMediaCondition(new AbstractLength(2), CssMinMaxMediaConditionInterface::MAX)
        );
        $this->assertTrue(is_array($mediaConditions));
        $this->assertEquals(3, count($mediaConditions));
        $mediaConditionsSpecs = [
            '(-webkit-max-device-pixel-ratio: 2)',
            '(max-resolution: 192dpi)',
            '(max-resolution: 2ddpx)'
        ];
        /** @var CssMediaCondition $mediaCondition */
        foreach ($mediaConditions as $mediaCondition) {
            $this->assertInstanceOf(CssMediaCondition::class, $mediaCondition);
            $this->assertEquals(array_shift($mediaConditionsSpecs), strval($mediaCondition));
        }
    }
}