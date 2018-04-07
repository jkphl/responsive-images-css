<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Infrastructure
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

namespace Jkphl\Respimgcss\Infrastructure;

use Jkphl\Respimgcss\Application\Contract\UnitLengthInterface;
use Jkphl\Respimgcss\Domain\Contract\CssMediaConditionInterface as DomainMediaConditionInterface;
use Jkphl\Respimgcss\Domain\Model\Css\ResolutionMediaCondition;
use Jkphl\Respimgcss\Domain\Model\Css\WidthMediaCondition;
use Jkphl\Respimgcss\Infrastructure\CssMediaConditionInterface as RenderableMediaConditionInterface;

/**
 * CSS media condition factory
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Infrastructure
 */
class CssMediaConditionFactory
{
    /**
     * Create renderable media conditions from a domain media condition
     *
     * @param DomainMediaConditionInterface $mediaCondition Domain media condition
     *
     * @return RenderableMediaConditionInterface[] Renderable media conditions
     */
    public static function createFromMediaCondition(DomainMediaConditionInterface $mediaCondition): array
    {
        switch (true) {
            case $mediaCondition instanceof ResolutionMediaCondition:
                $renderableMediaConditions = self::createFromResolutionMediaCondition($mediaCondition);
                break;
            case $mediaCondition instanceof WidthMediaCondition:
                $renderableMediaConditions = self::createFromWidthMediaCondition($mediaCondition);
                break;
            default:
                $renderableMediaConditions = [];
        }

        return $renderableMediaConditions;
    }

    /**
     * Create renderable media conditions from a resolution media condition
     *
     * @param ResolutionMediaCondition $resolutionMediaCondition Resolution media condition
     *
     * @return RenderableMediaConditionInterface[] Renderable media conditions
     */
    protected static function createFromResolutionMediaCondition(
        ResolutionMediaCondition $resolutionMediaCondition
    ): array {
        $resolutionValue      = $resolutionMediaCondition->getValue()->getValue();
        $resolutionModifier   = $resolutionMediaCondition->getModifier();
        $resolutionProperties = ['-webkit-%sdevice-pixel-ratio', '%sresolution', '%sresolution'];
        $resolutionValues     = [
            strval($resolutionValue),
            round($resolutionValue * 96).'dpi',
            $resolutionValue.'ddpx'
        ];

        return array_map(
            function($resolutionProperty, $resolutionValue) use ($resolutionModifier) {
                return self::createMediaCondition($resolutionProperty, $resolutionModifier, $resolutionValue);
            },
            $resolutionProperties,
            $resolutionValues
        );
    }

    /**
     * Create a renderable media condition
     *
     * @param string $property Condition property
     * @param string $modifier Condition modifier
     * @param string $value    Condition value
     *
     * @return CssMediaConditionInterface Renderable media condition
     */
    protected static function createMediaCondition(
        string $property,
        string $modifier,
        string $value
    ): CssMediaConditionInterface {
        $rule = new CssMediaConditionRule(sprintf($property, $modifier));
        $rule->setValue($value);

        return new CssMediaCondition($rule);
    }

    /**
     * Create renderable media conditions from a width media condition
     *
     * @param WidthMediaCondition $widthMediaCondition Resolution media condition
     *
     * @return RenderableMediaConditionInterface[] Renderable media conditions
     */
    protected static function createFromWidthMediaCondition(WidthMediaCondition $widthMediaCondition): array
    {
        $widthValue    = $widthMediaCondition->getValue();
        $widthUnit     = ($widthValue instanceof UnitLengthInterface) ? $widthValue->getUnit() : '';
        $widthModifier = $widthMediaCondition->getModifier();
        $widthRule     = new CssMediaConditionRule(sprintf('%swidth', $widthModifier));
        $widthRule->setValue($widthValue->getValue().$widthUnit);

        return [new CssMediaCondition($widthRule)];
    }
}