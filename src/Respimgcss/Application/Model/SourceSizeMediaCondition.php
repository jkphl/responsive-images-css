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

use Jkphl\Respimgcss\Domain\Contract\AbsoluteLengthInterface;
use Jkphl\Respimgcss\Domain\Contract\CssMinMaxMediaConditionInterface;
use Jkphl\Respimgcss\Domain\Model\Css\WidthMediaCondition;

/**
 * Source size media condition
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Model
 */
class SourceSizeMediaCondition
{
    /**
     * Media condition string
     *
     * @var string
     */
    protected $value;
    /**
     * Size and resolution conditions
     *
     * @var CssMinMaxMediaConditionInterface[]
     */
    protected $conditions;

    /**
     * Source size media condition constructor
     *
     * @param string $value                                  Media condition string
     * @param CssMinMaxMediaConditionInterface[] $conditions Size and resolution conditions
     */
    public function __construct(string $value, array $conditions = [])
    {
        $this->value      = $value;
        $this->conditions = $conditions;
    }

    /**
     * Return the media condition string
     *
     * @return string Media condition string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Return the size and resolution conditions
     *
     * @return CssMinMaxMediaConditionInterface[] Size and resolution conditions
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * Test if this source size condition matches a particular width and density
     *
     * @param AbsoluteLengthInterface $width Width
     * @param float $density                 Density
     *
     * @return bool This source size condition matches
     */
    public function matches(AbsoluteLengthInterface $width, float $density): bool
    {
        $match = true;

        // Run through all conditions
        /** @var CssMinMaxMediaConditionInterface $condition */
        foreach ($this->conditions as $condition) {
            $value = ($condition instanceof WidthMediaCondition) ? $width->getValue() : $density;
            if (!$condition->matches($value)) {
                $match = false;
                break;
            }
        }

        return $match;
    }
}