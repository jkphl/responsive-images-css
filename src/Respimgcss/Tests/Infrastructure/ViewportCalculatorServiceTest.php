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

use Jkphl\Respimgcss\Application\Contract\CalculatorServiceInterface;
use Jkphl\Respimgcss\Application\Factory\LengthFactory;
use Jkphl\Respimgcss\Domain\Contract\AbsoluteLengthInterface;
use Jkphl\Respimgcss\Infrastructure\ViewportCalculatorService;
use Jkphl\Respimgcss\Infrastructure\ViewportCalculatorServiceFactory;
use Jkphl\Respimgcss\Ports\InvalidArgumentException;
use Jkphl\Respimgcss\Tests\AbstractTestBase;

/**
 * Viewport calculator service tests
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Infrastructure
 */
class ViewportCalculatorServiceTest extends AbstractTestBase
{
    /**
     * Absolute length
     *
     * @var AbsoluteLengthInterface
     */
    protected $absoluteLength;
    /**
     * Calculator service
     *
     * @var CalculatorServiceInterface
     */
    protected $calculatorService;

    /**
     * Test setup
     */
    protected function setUp()
    {
        parent::setUp();
        $this->absoluteLength    = (new LengthFactory(new ViewportCalculatorServiceFactory(), 16))
            ->createAbsoluteLength(1000);
        $this->calculatorService = new ViewportCalculatorService($this->absoluteLength);
    }

    /**
     * Test the viewport calculator service
     */
    public function testViewportCalculatorService()
    {
        $this->assertInstanceOf(CalculatorServiceInterface::class, $this->calculatorService);

        // Tokenize
        $viewportCalculationTokens = $this->calculatorService->tokenize('calc(100vw - 100px)');
        $this->assertTrue(is_array($viewportCalculationTokens));
        $this->assertEquals(8, count($viewportCalculationTokens));

        // Refine
        $refinedCalculationTokens = $this->calculatorService->refineCalculationTokens($viewportCalculationTokens, 16);
        $this->assertTrue(is_array($refinedCalculationTokens));
        $this->assertEquals(11, count($refinedCalculationTokens));

        // Test for viewport tokens
        $this->assertEquals(
            1,
            array_reduce(
                $refinedCalculationTokens,
                function($sum, $token) {
                    return $sum + $this->calculatorService->isViewportToken($token) * 1;
                },
                0
            )
        );

        // Evaluate
        $this->assertEquals(900, $this->calculatorService->evaluate($refinedCalculationTokens));
    }

    /**
     * Run other atomic calculator service tests
     *
     * @param string $calculationString      Calculation string
     * @param int $numTokens                 Number of parsed tokens
     * @param int $numRefinedTokens          Number of refined tokens
     * @param string|null $expectedException Optional: Expected exception
     * @param int $expectedExceptionCode     Optional: Expected exception code
     *
     * @dataProvider          provideCalculatorData
     */
    public function testViewportCalculatorServiceAtoms(
        string $calculationString,
        int $numTokens,
        int $numRefinedTokens,
        string $expectedException = null,
        int $expectedExceptionCode = 0
    ) {
        $this->assertInstanceOf(CalculatorServiceInterface::class, $this->calculatorService);

        if ($expectedException) {
            $this->expectException($expectedException);
        }

        if ($expectedExceptionCode) {
            $this->expectExceptionCode($expectedExceptionCode);
        }

        // Tokenize
        $calculationTokens = $this->calculatorService->tokenize($calculationString);
        $this->assertTrue(is_array($calculationTokens));
        $this->assertEquals($numTokens, count($calculationTokens));

        // Refine
        $refinedCalculationTokens = $this->calculatorService->refineCalculationTokens($calculationTokens, 16);
        $this->assertTrue(is_array($refinedCalculationTokens));
        $this->assertEquals($numRefinedTokens, count($refinedCalculationTokens));
    }

    /**
     * Data provider for atomic calculation tests
     *
     * @return array Calculation data
     */
    public function provideCalculatorData()
    {
        return [
            ['1 + 2', 3, 3],
            ['1 calc(1)', 5, 4],
            ['1abc', 2, 0, InvalidArgumentException::class, 1522701212],
        ];
    }
}