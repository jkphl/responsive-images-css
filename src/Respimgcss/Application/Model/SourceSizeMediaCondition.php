<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application
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
use Jkphl\Respimgcss\Domain\Model\Css\ResolutionMediaCondition;
use Jkphl\Respimgcss\Domain\Model\Css\WidthMediaCondition;

/**
 * Source size media condition
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application
 */
class SourceSizeMediaCondition
{
    /**
     * Condition properties
     *
     * @var array
     */
    const CONDITION_PROPERTIES = [
        WidthMediaCondition::class => ['minimumWidth', 'maximumWidth'],
        ResolutionMediaCondition::class => ['minimumResolution', 'maximumResolution'],
    ];
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
     * Minimum width
     *
     * @var int|null
     */
    protected $minimumWidth = null;
    /**
     * Maximum width
     *
     * @var int|null
     */
    protected $maximumWidth = null;
    /**
     * Minimum resolution
     *
     * @var float|null
     */
    protected $minimumResolution = null;
    /**
     * Maximum resolution
     *
     * @var float|null
     */
    protected $maximumResolution = null;

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
        $this->initialize();
    }

    /**
     * Initialize the minimum / maximum values
     */
    protected function initialize(): void
    {
        /** @var CssMinMaxMediaConditionInterface $condition */
        foreach ($this->conditions as $condition) {
            if (($condition instanceof CssMinMaxMediaConditionInterface)
                && array_key_exists(get_class($condition), self::CONDITION_PROPERTIES)
            ) {
                $conditionProperties = self::CONDITION_PROPERTIES[get_class($condition)];
                call_user_func([$this, 'initializeCondition'], $condition, ...$conditionProperties);
            }
        }
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
     * @param int|null $lastMinimumWidth     Minimum width of the next higher breakpoint
     *
     * @return bool This source size condition matches
     */
    public function matches(AbsoluteLengthInterface $width, float $density, int $lastMinimumWidth = null): bool
    {
        $match = true;

        // Run through all conditions
        /** @var CssMinMaxMediaConditionInterface $condition */
        foreach ($this->conditions as $condition) {
            if (!($match = $this->testCondition($condition, $width, $density, $lastMinimumWidth))) {
                break;
            }
        }

        return $match;
    }

    /**
     * Test whether a media condition matches a breakpoint range
     *
     * @param CssMinMaxMediaConditionInterface $condition Media condition
     * @param AbsoluteLengthInterface $width              Width
     * @param float $density                              Density
     * @param int|null $lastMinimumWidth                  Minimum width of the next higher breakpoint
     *
     * @return bool Media condition matches
     */
    protected function testCondition(
        CssMinMaxMediaConditionInterface $condition,
        AbsoluteLengthInterface $width,
        float $density,
        int $lastMinimumWidth = null
    ): bool {
        $rangeLower = ($condition instanceof WidthMediaCondition) ? ($width->getValue() * $density) : $density;
        $rangeUpper = ($lastMinimumWidth === null) ? $rangeLower : ($lastMinimumWidth * $density - 1);
        return $condition->matches($rangeLower) && $condition->matches($rangeUpper);
    }

    /**
     * Return the minimum width
     *
     * @return int|null Minimum width
     */
    public function getMinimumWidth(): ?int
    {
        return $this->minimumWidth;
    }

    /**
     * Return the maximum width
     *
     * @return int|null Maximum width
     */
    public function getMaximumWidth(): ?int
    {
        return $this->maximumWidth;
    }

    /**
     * Return the minimum resolution
     *
     * @return float Minimum resolution
     */
    public function getMinimumResolution(): ?float
    {
        return $this->minimumResolution;
    }

    /**
     * Return the maximum resolution
     *
     * @return float Maximum resolution
     */
    public function getMaximumResolution(): ?float
    {
        return $this->maximumResolution;
    }

    /**
     * Initialize a media condition
     *
     * @param CssMinMaxMediaConditionInterface $condition Media condition
     * @param string $minProperty                         Minimum property name
     * @param string $maxProperty                         Maximum property name
     */
    protected function initializeCondition(
        CssMinMaxMediaConditionInterface $condition,
        string $minProperty,
        string $maxProperty
    ): void {
        $modifier = $condition->getModifier();
        $value    = $condition->getValue()->getValue();

        // Minimum value
        if ($modifier == CssMinMaxMediaConditionInterface::MIN) {
            $this->initializeMinProperty($minProperty, $value);

            return;
        }

        // Maximum value
        if ($modifier == CssMinMaxMediaConditionInterface::MAX) {
            $this->initializeMaxProperty($maxProperty, $value);

            return;
        }

        $this->$minProperty = $this->$maxProperty = $value;
    }

    /**
     * Initialize a minimum property
     *
     * @param string $minProperty Property name
     * @param float $value        Property Value
     */
    protected function initializeMinProperty(string $minProperty, float $value): void
    {
        $this->$minProperty = ($this->$minProperty === null) ? $value : (min($value, $this->$minProperty));
    }

    /**
     * Initialize a maximum property
     *
     * @param string $maxProperty Property name
     * @param float $value        Property Value
     */
    protected function initializeMaxProperty(string $maxProperty, float $value): void
    {
        $this->$maxProperty = ($this->$maxProperty === null) ? $value : (max($value, $this->$maxProperty));
    }
}
