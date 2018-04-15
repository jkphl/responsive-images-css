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

namespace Jkphl\Respimgcss\Tests\Ports;

use Jkphl\Respimgcss\Ports\Generator;

/**
 * Generator test
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests
 */
class GeneratorTest extends \Jkphl\Respimgcss\Tests\Infrastructure\GeneratorTest
{
    /**
     * Test the internal generator with density based image candidates
     */
    public function testGeneratorDensityImageCandidates()
    {
        $generator = new Generator(['24em', '800px', '72em'], 16);
        $this->assertInstanceOf(Generator::class, $generator);
        $this->runGeneratorDensityImageCandidatesAssertions($generator);
    }

    /**
     * Test the internal generator with width based image candidates
     */
    public function testGeneratorWidthImageCandidates()
    {
        $generator = new Generator(['24em', '800px', '72em'], 16);
        $this->assertInstanceOf(Generator::class, $generator);
        $this->runGeneratorWidthImageCandidatesAssertions($generator);
    }

    /**
     * Test the internal generator with width based image candidates
     */
    public function testGeneratorWidthImageCandidatesSourceSizes()
    {
        $generator = new Generator(['400px', '1200px'], 16);
        $this->assertInstanceOf(Generator::class, $generator);
        $this->runGeneratorWidthImageCandidatesSourceSizesAssertions($generator);
    }
}
