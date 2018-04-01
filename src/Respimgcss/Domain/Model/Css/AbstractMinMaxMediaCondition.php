<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Domain\Model\Css
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

namespace Jkphl\Respimgcss\Domain\Model\Css;

use Jkphl\Respimgcss\Domain\Contract\CssMinMaxMediaConditionInterface;
use Jkphl\Respimgcss\Domain\Contract\LengthInterface;
use Jkphl\Respimgcss\Domain\Exceptions\InvalidArgumentException;

/**
 * Abstract min/max media condition
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Domain\Model\Css
 */
abstract class AbstractMinMaxMediaCondition extends MediaCondition implements CssMinMaxMediaConditionInterface
{
    /**
     * Property name
     *
     * @var string
     */
    const PROPERTY = 'none';
    /**
     * Property modifier
     *
     * @var string
     */
    protected $modifier = self::EQ;
    /**
     * Property value
     *
     * @var LengthInterface
     */
    protected $value;

    /**
     * Min/Max CSS media condition constructor
     *
     * @param LengthInterface $value Property value
     * @param string $modifier       Condition modifier
     *
     */
    public function __construct(LengthInterface $value, string $modifier = self::EQ)
    {
        parent::__construct(static::PROPERTY, $value);

        if (
            ($modifier !== self::EQ)
            && ($modifier !== self::MIN)
            && ($modifier !== self::MAX)
        ) {
            throw new InvalidArgumentException(
                sprintf(InvalidArgumentException::INVALID_CSS_CONDITION_MODIFIER_STR, $modifier),
                InvalidArgumentException::INVALID_CSS_CONDITION_MODIFIER
            );
        }

        $this->modifier = $modifier;
    }

    /**
     * Return the property modifier
     *
     * @return string Property modifier
     */
    public function getModifier(): string
    {
        return $this->modifier;
    }

    /**
     * Return the property value
     *
     * @return LengthInterface Property value
     */
    public function getValue()
    {
        return $this->value;
    }
}