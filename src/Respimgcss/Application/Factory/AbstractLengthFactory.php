<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Factory
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

namespace Jkphl\Respimgcss\Application\Factory;

use Jkphl\Respimgcss\Application\Contract\CalculatorServiceFactoryInterface;
use Jkphl\Respimgcss\Application\Contract\LengthFactoryInterface;
use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;
use Jkphl\Respimgcss\Application\Model\AbsoluteLength;
use Jkphl\Respimgcss\Application\Service\LengthNormalizerService;
use Jkphl\Respimgcss\Domain\Contract\AbsoluteLengthInterface;

/**
 * Abstract length factory
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Factory
 */
abstract class AbstractLengthFactory implements LengthFactoryInterface
{
    /**
     * Calculator service factory
     *
     * @var CalculatorServiceFactoryInterface
     */
    protected $calculatorServiceFactory;
    /**
     * EM to pixel ratio
     *
     * @var int
     */
    protected $emPixel;
    /**
     * Length normalizer service
     *
     * @var LengthNormalizerService
     */
    protected $lengthNormalizerService;

    /**
     * Length factory constructor
     *
     * @param CalculatorServiceFactoryInterface $calculatorServiceFactory Calculator service interface
     * @param int $emPixel                                                EM to pixel ratio
     */
    public function __construct(CalculatorServiceFactoryInterface $calculatorServiceFactory, int $emPixel)
    {
        $this->calculatorServiceFactory = $calculatorServiceFactory;
        $this->emPixel                  = $emPixel;
        $this->lengthNormalizerService  = new LengthNormalizerService($this->emPixel);
    }

    /**
     * Return the associated calculator service factory
     *
     * @return CalculatorServiceFactoryInterface Calculator service factory
     */
    public function getCalculatorServiceFactory(): CalculatorServiceFactoryInterface
    {
        return $this->calculatorServiceFactory;
    }

    /**
     * Create an absolute length
     *
     * @param float $value Value
     *
     * @return AbsoluteLengthInterface Absolute length
     */
    public function createAbsoluteLength(float $value): AbsoluteLengthInterface
    {
        return new AbsoluteLength($value, UnitLengthInterface::UNIT_PIXEL, $this->lengthNormalizerService);
    }
}
