<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Model
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

namespace Jkphl\Respimgcss\Application\Model;

use ChrisKonnertz\StringCalc\Parser\Nodes\ContainerNode;
use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;
use Jkphl\Respimgcss\Application\Service\LengthNormalizerService;

/**
 * Viewport length
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Model
 */
class ViewportLength implements UnitLengthInterface
{
    /**
     * Calculation nodes
     *
     * @var ContainerNode
     */
    protected $calculation;
    /**
     * Length normalizer service
     *
     * @var LengthNormalizerService
     */
    protected $lengthNormalizerService;

    /**
     * Absolute length constructor
     *
     * @param ContainerNode $calculation                       Calculation nodes
     * @param LengthNormalizerService $lengthNormalizerService Length normalizer service
     */
    public function __construct(ContainerNode $calculation, LengthNormalizerService $lengthNormalizerService)
    {
        $this->lengthNormalizerService = $lengthNormalizerService;
        $this->calculation             = $calculation;
    }

    /**
     * Return the length value
     *
     * @return float Length value
     */
    public function getValue(): float
    {
        return 999;
    }

    /**
     * Return the lengths unit
     *
     * @return string Unit
     */
    public function getUnit(): string
    {
        return UnitLengthInterface::UNIT_PIXEL;
    }

    /**
     * Return the original value (in source units)
     *
     * @return float Original value (in source units)
     */
    public function getOriginalValue(): float
    {
        return 999;
    }

    /**
     * Return whether this is an absolute length
     *
     * @return boolean Absolute length
     */
    public function isAbsolute(): bool
    {
        return false;
    }

    /**
     * Return the serialized length
     *
     * @return string Serialized length
     */
    public function __toString(): string
    {
        return strval($this->getValue());
    }
}