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

namespace Jkphl\Respimgcss\Tests\Infrastructure;

use Jkphl\Respimgcss\Infrastructure\CssMediaCondition;
use Jkphl\Respimgcss\Infrastructure\CssMediaConditionAlternatives;
use Jkphl\Respimgcss\Infrastructure\LogicalAndCssMediaCondition;
use Jkphl\Respimgcss\Infrastructure\LogicalOrCssMediaCondition;
use Jkphl\Respimgcss\Tests\AbstractTestBase;
use Sabberworm\CSS\Rule\Rule;

/**
 * CSS media condition alternatives tests
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests
 */
class CssMediaConditionAlternativesTest extends AbstractTestBase
{
    /**
     * Test the CSS media condition alternatives
     */
    public function testCssMediaConditionAlternatives()
    {
        $alternatives = new CssMediaConditionAlternatives();
        $this->assertInstanceOf(CssMediaConditionAlternatives::class, $alternatives);

        $rule1 = new Rule('property1');
        $rule1->setValue('value1');
        $cssMediaCondition1 = new CssMediaCondition($rule1);

        $rule2 = new Rule('property2');
        $rule2->setValue('value2');
        $cssMediaCondition2 = new CssMediaCondition($rule2);

        $alternatives->appendCondition(new LogicalAndCssMediaCondition([$cssMediaCondition1, $cssMediaCondition2]));
        $alternatives->appendCondition(new LogicalOrCssMediaCondition([$cssMediaCondition1, $cssMediaCondition2]));
        $this->assertEquals(2, count($alternatives));
        $this->assertEquals(
            '(property1: value1;) and (property2: value2;),(property1: value1;) or (property2: value2;)',
            strval($alternatives)
        );
    }
}
