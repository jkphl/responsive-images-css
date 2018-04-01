<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests\Ports
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

use Jkphl\Respimgcss\Domain\Contract\ImageCandidateInterface;
use Jkphl\Respimgcss\Ports\Generator;
use Jkphl\Respimgcss\Tests\AbstractTestBase;
use Sabberworm\CSS\Parser;

/**
 * Generator test
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests
 */
class GeneratorTest extends AbstractTestBase
{
    /**
     * Test the generator
     */
    public function testGenerator()
    {
        $generator = new Generator(['24em', '800px', '72em'], 16);
        $this->assertInstanceOf(Generator::class, $generator);

        $generator->registerImageCandidate('small.jpg');
        $generator->registerImageCandidate('large.jpg', '2x');
        $imageCandidates = $generator->getImageCandidates();
        $this->assertTrue(is_array($imageCandidates));
        $this->assertEquals(2, count($imageCandidates));
        $this->assertInstanceOf(ImageCandidateInterface::class, current($imageCandidates));

        $cssRuleset = $generator->make([1, 2]);
        echo $cssRuleset->toCss('.example');
    }

    public function testCssParser()
    {
        $oCssParser   = new Parser(file_get_contents(dirname(__DIR__).'/Fixture/Css/example.css'));
        $oCssDocument = $oCssParser->parse();
        print_r($oCssDocument);
    }
}