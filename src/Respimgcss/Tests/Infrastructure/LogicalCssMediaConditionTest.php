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

use Jkphl\Respimgcss\Infrastructure\CssMediaCondition;
use Jkphl\Respimgcss\Infrastructure\CssMediaConditionInterface;
use Jkphl\Respimgcss\Infrastructure\LogicalAndCssMediaCondition;
use Jkphl\Respimgcss\Infrastructure\LogicalOrCssMediaCondition;
use Jkphl\Respimgcss\Tests\AbstractTestBase;
use Sabberworm\CSS\Rule\Rule;

/**
 * Logical CSS media condition tests
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Infrastructure
 */
class LogicalCssMediaConditionTest extends AbstractTestBase
{
    /**
     * CSS media condition 1
     *
     * @var CssMediaConditionInterface
     */
    protected $cssMediaCondition1;
    /**
     * CSS media condition 2
     *
     * @var CssMediaConditionInterface
     */
    protected $cssMediaCondition2;

    /**
     * Test setup
     */
    protected function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();

        $rule1 = new Rule('property1');
        $rule1->setValue('value1');
        $this->cssMediaCondition1 = new CssMediaCondition($rule1);

        $rule2 = new Rule('property2');
        $rule2->setValue('value2');
        $this->cssMediaCondition2 = new CssMediaCondition($rule2);
    }

    /**
     * Test the logical "and" CSS media condition
     */
    public function testLogicalAndCssMediaCondition()
    {
        $logicalAnd = new LogicalAndCssMediaCondition([$this->cssMediaCondition1]);
        $this->assertInstanceOf(LogicalAndCssMediaCondition::class, $logicalAnd);
        $logicalAnd->appendCondition($this->cssMediaCondition2);
        $this->assertEquals('(property1: value1;) and (property2: value2;)', strval($logicalAnd));
    }

    /**
     * Test the logical "or" CSS media condition
     */
    public function testLogicalOrCssMediaCondition()
    {
        $logicalAnd = new LogicalOrCssMediaCondition([$this->cssMediaCondition1]);
        $this->assertInstanceOf(LogicalOrCssMediaCondition::class, $logicalAnd);
        $logicalAnd->appendCondition($this->cssMediaCondition2);
        $this->assertEquals('(property1: value1;) or (property2: value2;)', strval($logicalAnd));
    }

    /**
     * Test the logical media conditions with an invalid operand
     *
     * @expectedException \Jkphl\Respimgcss\Ports\InvalidArgumentException
     * @expectedExceptionCode 1523084780
     */
    public function testCssLogicalMediaConditionInvalid()
    {
        new LogicalAndCssMediaCondition(['invalid']);
    }
}