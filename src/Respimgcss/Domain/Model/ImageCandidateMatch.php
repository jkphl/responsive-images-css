<?php

/**
 * responsive-images-css
 *
 * @category   Jkphl
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Domain
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

namespace Jkphl\Respimgcss\Domain\Model;

use Jkphl\Respimgcss\Domain\Contract\CssMediaConditionInterface;
use Jkphl\Respimgcss\Domain\Contract\ImageCandidateInterface;
use Jkphl\Respimgcss\Domain\Contract\SourceSizeImageCandidateMatch;

/**
 * Source size image candidate match
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Domain
 */
class ImageCandidateMatch implements SourceSizeImageCandidateMatch
{
    /**
     * Source size media condition
     *
     * @var CssMediaConditionInterface
     */
    protected $mediaCondition;
    /**
     * Image candidate
     *
     * @var ImageCandidateInterface
     */
    protected $imageCandidate;

    /**
     * Source size image candidate match
     *
     * @param CssMediaConditionInterface $mediaCondition Source size media condition
     * @param ImageCandidateInterface $imageCandidate    Image candidate
     */
    public function __construct(CssMediaConditionInterface $mediaCondition, ImageCandidateInterface $imageCandidate)
    {
        $this->mediaCondition = $mediaCondition;
        $this->imageCandidate = $imageCandidate;
    }

    /**
     * Return the media condition
     *
     * @return CssMediaConditionInterface Media condition
     */
    public function getMediaCondition(): CssMediaConditionInterface
    {
        return $this->mediaCondition;
    }

    /**
     * Return the image candidate
     *
     * @return ImageCandidateInterface Image Candidate
     */
    public function getImageCandidate(): ImageCandidateInterface
    {
        return $this->imageCandidate;
    }
}
