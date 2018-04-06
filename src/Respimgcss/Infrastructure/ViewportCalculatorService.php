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

namespace Jkphl\Respimgcss\Infrastructure;

use ChrisKonnertz\StringCalc\Container\ContainerInterface;
use ChrisKonnertz\StringCalc\StringCalc;
use ChrisKonnertz\StringCalc\Tokenizer\Token;
use Jkphl\Respimgcss\Application\Contract\CalculatorServiceInterface;
use Jkphl\Respimgcss\Application\Factory\LengthFactory;
use Jkphl\Respimgcss\Domain\Contract\AbsoluteLengthInterface;

/**
 * Custom string calculator
 *
 * @package    Jkphl\Respimgcss
 * @subpackage Jkphl\Respimgcss\Application\Model
 */
class ViewportCalculatorService extends StringCalc implements CalculatorServiceInterface
{
    /**
     * Custom string calculator constructor
     *
     * @param AbsoluteLengthInterface $viewport Viewport width
     * @param ContainerInterface $container     Container
     *
     * @throws \ChrisKonnertz\StringCalc\Exceptions\ContainerException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\InvalidIdentifierException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\NotFoundException
     */
    public function __construct(AbsoluteLengthInterface $viewport = null)
    {
        parent::__construct();
        $stringHelper     = $this->getContainer()->get('stringcalc_stringhelper');
        $viewportFunction = new ViewportFunction(
            $stringHelper,
            $viewport ?: (new LengthFactory(new ViewportCalculatorServiceFactory(), 16))->createAbsoluteLength(0)
        );
        $this->symbolContainer->add($viewportFunction);
    }

    /**
     * Evaluate calculation tokens
     *
     * @param Token[] $tokens Calculation tokens
     *
     * @return float Result
     * @throws \ChrisKonnertz\StringCalc\Exceptions\ContainerException
     * @throws \ChrisKonnertz\StringCalc\Exceptions\NotFoundException
     */
    public function evaluate(array $tokens): float
    {
        $calculationRootNode = $this->parse($tokens);

        return $this->container->get('stringcalc_calculator')->calculate($calculationRootNode);
    }
}