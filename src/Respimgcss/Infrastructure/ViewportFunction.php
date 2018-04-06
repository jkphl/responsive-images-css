<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Model
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

namespace Jkphl\Respimgcss\Infrastructure;

use ChrisKonnertz\StringCalc\Support\StringHelperInterface;
use ChrisKonnertz\StringCalc\Symbols\AbstractFunction;
use Jkphl\Respimgcss\Domain\Contract\AbsoluteLengthInterface;

/**
 * Viewport calculation function
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Model
 */
class ViewportFunction extends AbstractFunction
{
    /**
     * Viewport width
     *
     * @var AbsoluteLengthInterface
     */
    protected $viewport;
    /**
     * @inheritdoc
     */
    protected $identifiers = ['viewport'];

    /**
     * AbstractSymbol constructor.
     *
     * @param StringHelperInterface $stringHelper String helper
     * @param AbsoluteLengthInterface $viewport   Viewport width
     *
     * @throws \ChrisKonnertz\StringCalc\Exceptions\InvalidIdentifierException
     */
    public function __construct(StringHelperInterface $stringHelper, AbsoluteLengthInterface $viewport)
    {
        parent::__construct($stringHelper);
        $this->viewport = $viewport;
//        $this->name = rand();
//        echo 'init '.$this->name;
//        try {
//        	throw new \Exception;
//        } catch (\Exception $e) {
//        	echo $e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL;
//        }
//        echo PHP_EOL.PHP_EOL;
//        print_r($this->viewport);
    }

    /**
     * Execute the function
     *
     * @param  int|float[] $arguments
     *
     * @return int|float Viewport width
     */
    public function execute(array $arguments)
    {
//        echo 'exec '.$this->name;
//        try {
//            throw new \Exception;
//        } catch (\Exception $e) {
//            echo $e->getMessage().PHP_EOL.$e->getTraceAsString().PHP_EOL;
//        }
//        print_r($this->viewport);
//        echo PHP_EOL.PHP_EOL;
        return $this->viewport->getValue();
    }
}