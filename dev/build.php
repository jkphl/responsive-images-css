<?php

/**
 * responsive-images-css
 *
 * @category  Jkphl
 * @package   Jkphl\Respimgcss
 * @author    Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @copyright Copyright © 2018 Joschi Kuphal <joschi@kuphal.net> / @jkphl
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
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

require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

ob_start();

?><!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>jkphl/responsive-images-css | Test document</title>
        <style>
            body {
                margin: 0;
                font-family: Arial, Helvetica, sans-serif;
                padding-bottom: 4em;
                line-height: 1.4;
            }

            header {
                padding: 1em;
            }

            table {
                width: 100%;
            }

            td, th {
                vertical-align: top;
            }

            th {
                text-align: left;
                width: 11em;
            }

            ul {
                list-style-type: none;
                padding: 0;
                margin: 0;
                display: flex;
            }

            li:not(:first-child)::before {
                content: ', ';
            }

            .container {
                position: relative;
                height: 0;
                padding-top: calc(200% / 3);
                background-repeat: no-repeat;
                background-position: top left;
                background-size: cover;
            }

            @media (min-width: 32em) {
                #container-3 {
                    width: 50%;
                    padding-top: calc(100% / 3);
                }
            }

            @media (min-width: 64em) {
                #container-3 {
                    width: 33.3333%;
                    padding-top: calc(200% / 9);
                }
            }

            .size {
                position: absolute;
                top: 1em;
                left: 1em;
            }

            pre {
                width: calc(100vw - 13em);
                margin: 0;
                overflow-x: scroll;
            }

            /* Full-width image, width based srcset */
            <?php

                $generator1 = new \Jkphl\Respimgcss\Ports\Generator(['400px', '800px', '1200px', '1600px'], 16);
                $generator1->registerImageCandidate('../src/Respimgcss/Tests/Fixture/Images/400x267.png', '400w');
                $generator1->registerImageCandidate('../src/Respimgcss/Tests/Fixture/Images/800x534.png', '800w');
                $generator1->registerImageCandidate('../src/Respimgcss/Tests/Fixture/Images/1200x800.png', '1200w');
                $generator1->registerImageCandidate('../src/Respimgcss/Tests/Fixture/Images/1600x1067.png', '1600w');

                $css1 = $generator1->make([1, 2]);
                echo $css1 = $css1->toCss('#container-1');

                ?>

            /* Full-width image, density based srcset */
            <?php

                $generator2 = new \Jkphl\Respimgcss\Ports\Generator([], 16);
                $generator2->registerImageCandidate('../src/Respimgcss/Tests/Fixture/Images/800x534.png', '1x');
                $generator2->registerImageCandidate('../src/Respimgcss/Tests/Fixture/Images/1600x1067.png', '2x');

                $css2 = $generator2->make([1, 2]);
                echo $css2 = $css2->toCss('#container-2');

            ?>

            /* 1-2-3 column layout, width based srcset with sizes */
            <?php

                $generator3 = new \Jkphl\Respimgcss\Ports\Generator(['32em', '64em'], 16);
                $generator3->registerImageCandidate('../src/Respimgcss/Tests/Fixture/Images/400x267.png', '400w');
                $generator3->registerImageCandidate('../src/Respimgcss/Tests/Fixture/Images/800x534.png', '800w');
                $generator3->registerImageCandidate('../src/Respimgcss/Tests/Fixture/Images/1200x800.png', '1200w');
                $generator3->registerImageCandidate('../src/Respimgcss/Tests/Fixture/Images/1600x1067.png', '1600w');

                $css3= $generator3->make([1, 2], '(min-width: 32em) 50vw, (min-width: 64em) 33.3333vw, 100vw');
                echo $css3= $css3->toCss('#container-3');

            ?>
        </style>
    </head>
    <body>
        <header>
            <h1>Responsive background images</h1>
            <p>Device pixel ratio:
                <script>document.write(window.devicePixelRatio)</script>
            </p>
        </header>

        <section>
            <header>
                <h2>A) Full-width image, width based srcset</h2>
                <table>
                    <tr>
                        <th>Breakpoints</th>
                        <td>
                            <ul>
                                <li>400px</li>
                                <li>800px</li>
                                <li>1200px</li>
                                <li>1600px</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <th>Densities</th>
                        <td>
                            <ul>
                                <li>1</li>
                                <li>2</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <th>srcset</th>
                        <td><code>400x267.png 400w, 800x534.png 800w, 1200x800.png 1200w, 1600x1067.png 600w</code></td>
                    </tr>
                    <tr>
                        <th>sizes</th>
                        <td>—</td>
                    </tr>
                    <tr>
                        <th>CSS</th>
                        <td>
                            <pre><code><?= $css1; ?></code></pre>
                        </td>
                    </tr>
                </table>
            </header>
            <div class="container" id="container-1">
                <div class="size"></div>
            </div>
        </section>

        <section>
            <header>
                <h2>B) Full-width image, density based srcset</h2>
                <table>
                    <tr>
                        <th>Breakpoints</th>
                        <td>(ignored)</td>
                    </tr>
                    <tr>
                        <th>Densities</th>
                        <td>
                            <ul>
                                <li>1</li>
                                <li>2</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <th>srcset</th>
                        <td><code>800x534.png 1x, 1600x1067.png 2x</code></td>
                    </tr>
                    <tr>
                        <th>sizes</th>
                        <td>—</td>
                    </tr>
                    <tr>
                        <th>CSS</th>
                        <td>
                            <pre><code><?= $css2; ?></code></pre>
                        </td>
                    </tr>
                </table>
            </header>
            <div class="container" id="container-2">
                <div class="size"></div>
            </div>
        </section>

        <section>
            <header>
                <h2>C) 1-2-3 column layout, width based srcset with sizes</h2>
                <table>
                    <tr>
                        <th>Breakpoints</th>
                        <td>
                            <ul>
                                <li>32em</li>
                                <li>64em</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <th>Densities</th>
                        <td>
                            <ul>
                                <li>1</li>
                                <li>2</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <th>srcset</th>
                        <td><code>800x534.png 1x, 1600x1067.png 2x</code></td>
                    </tr>
                    <tr>
                        <th>sizes</th>
                        <td><code>(min-width: 32em) 50vw, (min-width: 64em) 33.3333vw, 100vw</code></td>
                    </tr>
                    <tr>
                        <th>CSS</th>
                        <td>
                            <pre><code><?= $css3; ?></code></pre>
                        </td>
                    </tr>
                </table>
            </header>
            <div class="container" id="container-3">
                <div class="size"></div>
            </div>
        </section>

        <script>
            var sizes = document.querySelectorAll('.size');
            (window.onresize = function () {
                sizes.forEach(function (size) {
                    size.innerHTML = 'Container size: ' + size.parentNode.clientWidth + '×' + size.parentNode.clientHeight + ' px';
                })
            })();
        </script>
    </body>
</html><?php

file_put_contents(
    dirname(__DIR__).DIRECTORY_SEPARATOR.'build'.DIRECTORY_SEPARATOR.'index.html',
    ob_get_clean()
);