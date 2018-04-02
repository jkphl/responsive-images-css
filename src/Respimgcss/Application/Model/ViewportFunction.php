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

namespace Jkphl\Respimgcss\Application\Model;

use ChrisKonnertz\StringCalc\Symbols\AbstractFunction;

/**
 * Viewport calculation function
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Model
 */
class ViewportFunction extends AbstractFunction
{
    /**
     * @inheritdoc
     */
    protected $identifiers = ['viewport'];

    /**
     * This method is called when the function is executed. A function can have 0-n parameters.
     * The implementation of this method is responsible to validate the number of arguments.
     * The $arguments array contains these arguments. If the number of arguments is improper,
     * the method has to throw a Exceptions\NumberOfArgumentsException exception.
     * The items of the $arguments array will always be of type int or float. They will never be null.
     * They keys will be integers starting at 0 and representing the positions of the arguments
     * in ascending order.
     * Overwrite this method in the concrete operator class.
     * If this class does NOT return a value of type int or float,
     * an exception will be thrown.
     *
     * @param  int|float[] $arguments
     *
     * @return int|float
     */
    public function execute(array $arguments)
    {
        return 1;
    }
}