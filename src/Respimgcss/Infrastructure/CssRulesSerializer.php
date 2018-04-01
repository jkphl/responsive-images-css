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

use Jkphl\Respimgcss\Domain\Contract\CssMediaConditionInterface as DomainMediaConditionInterface;
use Jkphl\Respimgcss\Domain\Contract\CssRuleInterface;
use Jkphl\Respimgcss\Infrastructure\CssMediaConditionInterface as RenderableMediaConditionInterface;
use Jkphl\Respimgcss\Ports\InvalidArgumentException;
use Sabberworm\CSS\CSSList\AtRuleBlockList;
use Sabberworm\CSS\CSSList\Document;
use Sabberworm\CSS\Renderable;
use Sabberworm\CSS\Rule\Rule;
use Sabberworm\CSS\RuleSet\DeclarationBlock;
use Sabberworm\CSS\Value\CSSString;
use Sabberworm\CSS\Value\URL;

/**
 * CSS rules serializer
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Infrastructure
 */
class CssRulesSerializer
{
    /**
     * CSS rules
     *
     * @var CssRuleInterface[]
     */
    protected $rules;

    /**
     * CSS rules serializer constructor
     *
     * @param CssRuleInterface[] $rules CSS Rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * Return the registered rules as CSS
     *
     * @param string $selector CSS selector
     *
     * @return string Serialized CSS rules
     * @throws InvalidArgumentException If the CSS selector is invalid
     */
    public function toCss(string $selector): string
    {
        // If the CSS selector is invalid
        $selector = trim($selector);
        if (!strlen($selector)) {
            throw new InvalidArgumentException(
                sprintf(InvalidArgumentException::INVALID_CSS_SELECTOR_STR, $selector),
                InvalidArgumentException::INVALID_CSS_SELECTOR
            );
        }

        $cssDocument = new Document();

        /** @var CssRuleInterface $rule */
        foreach ($this->rules as $rule) {
            $cssDocument->append($this->exportCssRule($rule, $selector));
        }

        return $cssDocument->render();
    }

    /**
     * Export a single CSS rule
     *
     * @param CssRuleInterface $rule CSS rule
     * @param string $selector       CSS selector
     *
     * @return Renderable
     */
    protected function exportCssRule(CssRuleInterface $rule, string $selector): Renderable
    {
        // If the rule has conditions: Render as an @-rule block
        if (count($rule)) {
            return $this->exportCssRuleAtBlock($rule, $selector);
        }

        return $this->exportCssRuleDeclarationBlock($rule, $selector);
    }

    /**
     * Export a CSS rule as @-media block
     *
     * @param CssRuleInterface $rule CSS rule
     * @param string $selector       CSS selector
     *
     * @return AtRuleBlockList @-media block
     */
    protected function exportCssRuleAtBlock(CssRuleInterface $rule, string $selector): AtRuleBlockList
    {
        $atRuleMediaConditions = $this->exportCssRuleMediaConditions($rule);
        $atRuleBlockList       = new AtRuleBlockList('media', $atRuleMediaConditions);
        $atRuleBlockList->append($this->exportCssRuleDeclarationBlock($rule, $selector));

        return $atRuleBlockList;
    }

    /**
     * Export the media conditions associated with a CSS rule
     *
     * @param CssRuleInterface $rule CSS rule
     *
     * @return string Media conditions
     */
    protected function exportCssRuleMediaConditions(CssRuleInterface $rule): string
    {
        $alternativeMediaConditions = new CssMediaConditionAlternatives();

        // Run through all conditions of this rule
        /** @var DomainMediaConditionInterface $condition */
        foreach ($rule as $condition) {
            $addMediaConditionAlternatives = CssMediaConditionFactory::createFromMediaCondition($condition);

            // If there are already registered media conditions: Add and multiply
            if (count($alternativeMediaConditions)) {
                $alternativeMediaConditions = $this->addAndMultiplyCssMediaConditions(
                    $alternativeMediaConditions,
                    $addMediaConditionAlternatives
                );
                continue;
            }

            // Else: Add and wrap with logical and media conditions
            $alternativeMediaConditions = $this->initializeCssMediaConditions(
                $alternativeMediaConditions,
                $addMediaConditionAlternatives
            );
        }

        return strval($alternativeMediaConditions);
    }

    /**
     * Add and multiply media condition alternatives
     *
     * @param CssMediaConditionAlternatives $alternativeMediaConditions       Base set of alternative media conditions
     * @param RenderableMediaConditionInterface[] $mediaConditionAlternatives Media condition alternatives to add
     *
     * @return CssMediaConditionAlternatives Multiplied set of media condition alternatives
     */
    protected function addAndMultiplyCssMediaConditions(
        CssMediaConditionAlternatives $alternativeMediaConditions,
        array $mediaConditionAlternatives
    ): CssMediaConditionAlternatives {
        $multipliedAlternativeMediaConditions = new CssMediaConditionAlternatives();

        // Run through all generated media condition alternatives
        /** @var RenderableMediaConditionInterface $mediaConditionAlternative */
        foreach ($mediaConditionAlternatives as $mediaConditionAlternative) {
            // Run through all registered media condition alternatives
            /** @var LogicalCssMediaConditionInterface $registeredMediaConditionAlternative */
            foreach ($alternativeMediaConditions as $registeredMediaConditionAlternative) {
                $clonedMediaConditionAlternative = clone $registeredMediaConditionAlternative;
                $clonedMediaConditionAlternative->appendCondition($mediaConditionAlternative);
                $multipliedAlternativeMediaConditions->appendCondition($clonedMediaConditionAlternative);
            }
        }

        return $multipliedAlternativeMediaConditions;
    }

    /**
     * Initialize media condition alternatives
     *
     * @param CssMediaConditionAlternatives $alternativeMediaConditions       Base set of alternative media conditions
     * @param RenderableMediaConditionInterface[] $mediaConditionAlternatives Media condition alternatives to add
     *
     * @return CssMediaConditionAlternatives Multiplied set of media condition alternatives
     */
    protected function initializeCssMediaConditions(
        CssMediaConditionAlternatives $alternativeMediaConditions,
        array $mediaConditionAlternatives
    ): CssMediaConditionAlternatives {
        // Run through all generated media condition alternatives
        /** @var RenderableMediaConditionInterface $mediaConditionAlternative */
        foreach ($mediaConditionAlternatives as $mediaConditionAlternative) {
            $alternativeMediaConditions->appendCondition(new LogicalAndCssMediaCondition([$mediaConditionAlternative]));
        }

        return $alternativeMediaConditions;
    }

    /**
     * Export a CSS rule as declaration block (without media query)
     *
     * @param CssRuleInterface $rule CSS rule
     * @param string $selector       CSS selector
     *
     * @return DeclarationBlock Declaration block
     */
    protected function exportCssRuleDeclarationBlock(CssRuleInterface $rule, string $selector): DeclarationBlock
    {
        $declarationBlock = new DeclarationBlock();
        $declarationBlock->setSelectors([$selector]);
        $declarationBlock->addRule($this->exportCssRuleRule($rule));

        return $declarationBlock;
    }

    /**
     * Export the CSS rule property and value
     *
     * @param CssRuleInterface $rule CSS rule
     *
     * @return Rule Export CSS rule
     */
    protected function exportCssRuleRule(CssRuleInterface $rule): Rule
    {
        $imageCandidateFile = $rule->getImageCandidate()->getFile();
        $cssRule            = new Rule('background-image');
        $cssRule->setValue(new URL(new CSSString($imageCandidateFile)));

        return $cssRule;
    }
}