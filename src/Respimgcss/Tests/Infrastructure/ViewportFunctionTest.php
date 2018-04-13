<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Application
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

use ChrisKonnertz\StringCalc\Support\StringHelper;
use Jkphl\Respimgcss\Application\Factory\LengthFactory;
use Jkphl\Respimgcss\Infrastructure\ViewportCalculatorServiceFactory;
use Jkphl\Respimgcss\Infrastructure\ViewportFunction;
use Jkphl\Respimgcss\Tests\AbstractTestBase;

/**
 * Viewport function test
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests
 */
class ViewportFunctionTest extends AbstractTestBase
{
    /**
     * Test the viewport function
     */
    public function testViewportFunction()
    {
        $viewportWidth    = rand(1, getrandmax());
        $viewportFunction = new ViewportFunction(
            new StringHelper(),
            (new LengthFactory(new ViewportCalculatorServiceFactory(), 16))->createAbsoluteLength($viewportWidth)
        );
        $this->assertInstanceOf(ViewportFunction::class, $viewportFunction);
        $this->assertEquals(['viewport'], $viewportFunction->getIdentifiers());
        $this->assertEquals($viewportWidth, $viewportFunction->execute([]));
    }
}
