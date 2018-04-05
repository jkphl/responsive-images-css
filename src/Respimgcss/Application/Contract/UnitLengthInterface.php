<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Contract
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

namespace Jkphl\Respimgcss\Application\Contract;

use Jkphl\Respimgcss\Domain\Contract\LengthInterface;

/**
 * AbstractLength with unit interface
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application
 */
interface UnitLengthInterface extends LengthInterface
{
    // Units
    const UNIT_PIXEL = 'px';
    const UNIT_EM = 'em';
    const UNIT_REM = 'rem';
    const UNIT_PERCENT = '%';
    const UNIT_CM = 'cm';
    const UNIT_MM = 'mm';
    const UNIT_IN = 'in';
    const UNIT_PC = 'pc';
    const UNIT_PT = 'pt';
    const UNIT_VW = 'vw';

    /**
     * Return the lengths unit
     *
     * @return string Unit
     */
    public function getUnit(): string;

    /**
     * Return the original value (in source units)
     *
     * @return mixed Original value (in source units)
     */
    public function getOriginalValue();

    /**
     * Return whether this is an absolute length
     *
     * @return boolean Absolute length
     */
    public function isAbsolute(): bool;
}