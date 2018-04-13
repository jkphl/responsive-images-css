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
            }

            #container {
                position: relative;
                height: 0;
                padding-top: calc(200% / 3);
                background-repeat: no-repeat;
                background-position: top left;
                background-size: cover;
            }

            #size {
                position: absolute;
                top: 1em;
                left: 1em;
            }

            /* jkphl/reponsive-images-css */
            <?php

                $generator = new \Jkphl\Respimgcss\Ports\Generator(['400px', '800px', '1200px', '1600px'], 16);
                $generator->registerImageCandidate('../src/Respimgcss/Tests/Fixture/Images/400x267.png', '400w');
                $generator->registerImageCandidate('../src/Respimgcss/Tests/Fixture/Images/800x534.png', '800w');
                $generator->registerImageCandidate('../src/Respimgcss/Tests/Fixture/Images/1200x800.png', '1200w');
                $generator->registerImageCandidate('../src/Respimgcss/Tests/Fixture/Images/1600x1067.png', '1600w');

                $css = $generator->make([1, 2]);
                echo $css->toCss('#container');

            ?>
        </style>
    </head>
    <body>
        <div id="container">
            <div id="size"></div>
        </div>
        <script>
            var container = document.getElementById('container');
            var size = document.getElementById('size');
            (window.onresize = function () {
                size.innerHTML = 'Device pixel ratio: ' + window.devicePixelRatio + '<br>Container size: ' + container.clientWidth + '×' + container.clientHeight + ' px';
            })();
        </script>
    </body>
</html><?php

file_put_contents(
    dirname(__DIR__).DIRECTORY_SEPARATOR.'build'.DIRECTORY_SEPARATOR.'index.html',
    ob_get_clean()
);