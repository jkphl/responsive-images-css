<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application
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

use Jkphl\Respimgcss\Application\Service\LengthNormalizerService;
use Jkphl\Respimgcss\Domain\Contract\AbsoluteLengthInterface;

/**
 * Absolute length
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application
 */
class AbsoluteLength extends AbstractLength implements AbsoluteLengthInterface
{
    /**
     * AbstractLength normalizer service
     *
     * @var LengthNormalizerService
     */
    protected $lengthNormalizerService;

    /**
     * Absolute length constructor
     *
     * @param float $value                                     Value
     * @param string $unit                                     Unit
     * @param LengthNormalizerService $lengthNormalizerService AbstractLength normalizer service
     */
    public function __construct(float $value, string $unit, LengthNormalizerService $lengthNormalizerService)
    {
        $this->lengthNormalizerService = $lengthNormalizerService;
        parent::__construct($this->lengthNormalizerService->normalize($value, $unit), $unit, $value);
    }

    /**
     * Return the length value
     *
     * @param AbsoluteLengthInterface $viewport Viewport width
     *
     * @return float AbstractLength value
     */
    public function getValue(AbsoluteLengthInterface $viewport = null): float
    {
        return $this->value;
    }
}
