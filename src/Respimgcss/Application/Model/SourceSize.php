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

use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;

/**
 * Source size
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Model
 */
class SourceSize
{
    /**
     * Source size valie
     *
     * @var UnitLengthInterface
     */
    protected $value;
    /**
     * Media condition
     *
     * @var SourceSizeMediaCondition|null
     */
    protected $mediaCondition;

    /**
     * Source size constructor
     *
     * @param UnitLengthInterface $value               Source size value
     * @param SourceSizeMediaCondition $mediaCondition Media condition
     */
    public function __construct(UnitLengthInterface $value, SourceSizeMediaCondition $mediaCondition = null)
    {
        $this->value          = $value;
        $this->mediaCondition = $mediaCondition;
    }

    /**
     * Return the source size value
     *
     * @return UnitLengthInterface Source size value
     */
    public function getValue(): UnitLengthInterface
    {
        return $this->value;
    }

    /**
     * Return the source size media condition
     *
     * @return SourceSizeMediaCondition|null Media condition
     */
    public function getMediaCondition(): ?SourceSizeMediaCondition
    {
        return $this->mediaCondition;
    }
}