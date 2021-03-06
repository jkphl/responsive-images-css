<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Domain
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

use Jkphl\Respimgcss\Domain\Contract\CssMediaConditionInterface;
use Jkphl\Respimgcss\Domain\Contract\CssRuleInterface;
use Jkphl\Respimgcss\Domain\Contract\ImageCandidateInterface;

/**
 * CSS rule
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Domain
 */
class Rule extends \ArrayObject implements CssRuleInterface
{
    /**
     * Image candidate
     *
     * @var ImageCandidateInterface
     */
    protected $imageCandidate;

    /**
     * CSS rule
     *
     * @param ImageCandidateInterface $imageCandidate  Image candidate
     * @param CssMediaConditionInterface[] $conditions CSS media conditions
     */
    public function __construct(ImageCandidateInterface $imageCandidate, array $conditions = [])
    {
        $this->imageCandidate = $imageCandidate;
        parent::__construct($conditions);
    }

    /**
     * Add a CSS media condition to this rule
     *
     * @param CssMediaConditionInterface $condition CSS media condition
     *
     * @return CssRuleInterface Self reference
     */
    public function appendCondition(CssMediaConditionInterface $condition): CssRuleInterface
    {
        $rule = clone $this;
        $rule->append($condition);

        return $rule;
    }

    /**
     * Return the image candidate associated with this rule
     *
     * @return ImageCandidateInterface Image candidate
     */
    public function getImageCandidate(): ImageCandidateInterface
    {
        return $this->imageCandidate;
    }
}
