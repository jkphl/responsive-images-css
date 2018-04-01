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

use Jkphl\Respimgcss\Domain\Contract\CssMediaConditionInterface;
use Jkphl\Respimgcss\Domain\Model\Css\ResolutionMediaCondition;
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
     * @param CssMediaConditionInterface $mediaCondition Domain media condition
     *
     * @return RenderableMediaConditionInterface[] Renderable media conditions
     */
    public static function createFromMediaCondition(CssMediaConditionInterface $mediaCondition): array
    {
        switch (true) {
            case $mediaCondition instanceof ResolutionMediaCondition:
                $renderableMediaConditions = self::createFromResolutionMediaCondition($mediaCondition);
                break;
            default:
                $renderableMediaConditions = [];
        }

        return $renderableMediaConditions;
    }

    /**
     * Create renderable media conditions from a domain media condition
     *
     * @param ResolutionMediaCondition $resolutionMediaCondition Resolution media condition
     *
     * @return RenderableMediaConditionInterface[] Renderable media conditions
     */
    protected static function createFromResolutionMediaCondition(
        ResolutionMediaCondition $resolutionMediaCondition
    ): array {
        $renderableMediaConditions = [];
        $resolutionValue           = $resolutionMediaCondition->getValue();
        $resolutionModifier        = $resolutionMediaCondition->getModifier();

        // -webkit-device-pixel-ratio media condition
        $webkitDevicePixelRatioRule = new CssMediaConditionRule(
            sprintf('-webkit-%sdevice-pixel-ratio', $resolutionModifier)
        );
        $webkitDevicePixelRatioRule->setValue($resolutionValue);
        $renderableMediaConditions[] = new CssMediaCondition($webkitDevicePixelRatioRule);

        // resolution media condition (dpi)
        $resolutionRule = new CssMediaConditionRule(sprintf('%sresolution', $resolutionModifier));
        $resolutionRule->setValue(round($resolutionValue * 96).'dpi');
        $renderableMediaConditions[] = new CssMediaCondition($resolutionRule);

        // resolution media condition (dppx)
        $resolutionRule = new CssMediaConditionRule(sprintf('%sresolution', $resolutionModifier));
        $resolutionRule->setValue($resolutionValue.'ddpx');
        $renderableMediaConditions[] = new CssMediaCondition($resolutionRule);

        return $renderableMediaConditions;
    }
}