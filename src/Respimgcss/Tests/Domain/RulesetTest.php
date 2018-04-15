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

namespace Jkphl\Respimgcss\Tests\Domain;

use Jkphl\Respimgcss\Domain\Model\Css\MediaCondition;
use Jkphl\Respimgcss\Domain\Model\Css\Rule;
use Jkphl\Respimgcss\Domain\Model\Css\Ruleset;
use Jkphl\Respimgcss\Domain\Model\DensityImageCandidate;
use Jkphl\Respimgcss\Tests\AbstractTestBase;

/**
 * Ruleset tests
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Tests
 */
class RulesetTest extends AbstractTestBase
{
    /**
     * Test the ruleset
     */
    public function testRuleset()
    {
        $ruleset = new Ruleset();
        $this->assertInstanceOf(Ruleset::class, $ruleset);

        $imageCandidate = new DensityImageCandidate('image.jpg', 1);
        $mediaCondition = new MediaCondition('property', 'value');
        $rule           = new Rule($imageCandidate, [$mediaCondition]);
        $this->assertInstanceOf(Ruleset::class, $ruleset->appendRule($rule));
    }
}
