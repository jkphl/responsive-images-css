# jkphl/responsive-images-css

[![Build Status][travis-image]][travis-url] [![Coverage Status][coveralls-image]][coveralls-url] [![Scrutinizer Code Quality][scrutinizer-image]][scrutinizer-url] [![Code Climate][codeclimate-image]][codeclimate-url]  [![Clear architecture][clear-architecture-image]][clear-architecture-url]

> HTML5-like responsive background images in CSS (sort of …)

## About

The purpose of *responsive-images-css* is to ease the creation process of responsive background images in CSS. It provides similar semantics as responsive images via `<img srcset="…" sizes="…">` in HTML5.

The rendering sequence of a standard HTML5 responsive (foreground) image is a highly complex process. It's impossible to fully predict which exact image candidate a browser will pick as some decisions may depend on environment settings that are only available at runtime (such as the network performance).

In contrast, *responsive-images-css* generates CSS code on the server-side — that is, long before the browser gets to interpret the generated output. To make this work, some asumptions have to be made:

* The generator needs a fixed **em to pixel ratio** in order to predictably deal with `em` / `rem` values.
* The generator utilizes the **specified breakpoints** only, even if the image candidates suggest additional steps.
* The **device densities** (resolutions) for which the CSS should be rendered must be explictly provided.

## Usage

### The generator

Creating a responsive background image always starts with a fresh `Generator` instance:

```php
use Jkphl\Respimgcss\Ports\Generator;

$breakpoints = ['24em', '36em', '48em']; // CSS Breakpoints 
$emToPixel = 16; // EM to PX ratio

$generator = new Generator($breakpoints, $emToPixel);
```

As you see in the example, the `Generator` accepts a list of **CSS breakpoints** and an **`em` to `px` ratio** as constructor arguments. The latter defaults to `16` if omitted. The breakpoints only get used in combination with a width based image candidates set and [a `sizes` specification](#using-sizes) (you can pass in an empty array in all other cases).

### Image candidates

Next, you have to register a couple of **image candidates** for the various states of the responsive image. The file names don't get validated in any way — they will be used as-is for the generated CSS.

```php
// Use a `srcset`-like combined file name + width descriptor string ...
$generator->registerImageCandidate('small-400.jpg 400w');

// ... or an explicit width / resolution descriptor as second argument
$generator->registerImageCandidate('medium-800.jpg', '800w');
$generator->registerImageCandidate('large-1200.jpg', '1200w');
```   

As with HTML5 responsive images, you can use **resolution** or **width based descriptors** for the image candidates, but be aware that you're not allowed to mix them within a single image candidate set.


```php
$generator->registerImageCandidate('small-400.jpg', '1x');
$generator->registerImageCandidate('medium-800.jpg', '2x');
```

### Compiling the CSS ruleset 

Finally, to create the responsive image CSS, call the generator's `make()` method and apply a **CSS selector** of your choice to the resulting CSS ruleset:

```php
$cssRuleset = $generator->make([1.0, 2.0]);
echo $cssRuleset->toCss('.respimg-container');
```

The list of floating point numbers passed to the `make()` method are the **device pixel densities / resolutions** you want the CSS to be rendered for. If you omit this argument, only the default density `1.0` will be considered. The output will look something like this (not pretty-printed):

```css
.respimg-container {
    background-image: url("small-400.jpg");
}
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi),(min-resolution: 2ddpx) {
    .respimg-container {
        background-image: url("medium-800.jpg");
    }
}
```

As you see in the example, **only the `background-image` property is specified** for the image candidates. For a fully functional responsive image you will need some more lines of CSS — in order to give you full control, however, it's up to you to add this to your overall CSS.

### Example

A minimal, all-things-inlined HTML / PHP example document with responsive background image could look like this:

```php
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Example document with responsive background image</title>
        <style>
            .respimg {
                padding-bottom: 75%; /* 4:3 aspect ratio */
                background-repeat: no-repeat;
                background-position: top left;
                background-size: cover;
            }
            <?php
            
            $generator = new Jkphl\Respimgcss\Ports\Generator();
            $generator->registerImageCandidate('small-400.jpg', '1x');
            $generator->registerImageCandidate('medium-800.jpg', '2x');
            echo $generator->make([1, 2])->toCss('.respimg');
            
            ?>
        </style>
    </head>
    <body>
        <div class="respimg"></div>
    </body>
</html>
```

### Using `sizes`

A very powerful feature of HTML5 responsive images is the [`sizes` attribute](http://w3c.github.io/html/semantics-embedded-content.html#ref-for-viewport-based-selection%E2%91%A0) which lets you further describe the way your image gets displayed. *responsive-images-css* aims to support the `sizes` specification to a reasonable extent so that you can use the same values as you would for `<img srcset="…" sizes="…">`:

```php
$cssRuleset = $generator->make(
    [1, 2], // Device resolutions
    '(min-width: 400px) 50vw, (min-width: 800px) 33.33vw, 100vw' // Image sizes
);
```

The `Generator` will try to calculate the anticipated image sizes for the registered breakpoints and select the appropriate image candidates accordingly. Please be aware that

* `sizes` may only be used in combination with **width based image candidates sets**,
* you **must provide breakpoints** to the `Generator` constructor when using `sizes` and that
* the breakpoints used for the `sizes` value **should match** the registered global breakpoints. 

## Installation

This library requires PHP 7.1 or later. I recommend using the latest available version of PHP as a matter of principle. It has no userland dependencies.

## Dependencies

![Composer dependency graph](https://rawgit.com/jkphl/responsive-images-css/master/doc/dependencies.svg)

## Quality

To run the unit tests at the command line, issue `composer install` and then `phpunit` at the package root. This requires [Composer](http://getcomposer.org/) to be available as `composer`, and [PHPUnit](http://phpunit.de/manual/) to be available as `phpunit`.

This library attempts to comply with [PSR-1][], [PSR-2][], and [PSR-4][]. If you notice compliance oversights, please send a patch via pull request.

## Contributing

Found a bug or have a feature request? [Please have a look at the known issues](https://github.com/jkphl/responsive-images-css/issues) first and open a new issue if necessary. Please see [contributing](CONTRIBUTING.md) and [conduct](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email joschi@kuphal.net instead of using the issue tracker.

## Credits

- [Joschi Kuphal][author-url]
- [All Contributors](../../contributors)

## License

Copyright © 2018 [Joschi Kuphal][author-url] / joschi@kuphal.net. Licensed under the terms of the [MIT license](LICENSE).


[travis-image]: https://secure.travis-ci.org/jkphl/responsive-images-css.svg
[travis-url]: https://travis-ci.org/jkphl/responsive-images-css
[coveralls-image]: https://coveralls.io/repos/jkphl/responsive-images-css/badge.svg?branch=master&service=github
[coveralls-url]: https://coveralls.io/github/jkphl/responsive-images-css?branch=master
[scrutinizer-image]: https://scrutinizer-ci.com/g/jkphl/responsive-images-css/badges/quality-score.png?b=master
[scrutinizer-url]: https://scrutinizer-ci.com/g/jkphl/responsive-images-css/?branch=master
[codeclimate-image]: https://lima.codeclimate.com/github/jkphl/responsive-images-css/badges/gpa.svg
[codeclimate-url]: https://lima.codeclimate.com/github/jkphl/responsive-images-css

[clear-architecture-image]: https://img.shields.io/badge/Clear%20Architecture-%E2%9C%94-brightgreen.svg
[clear-architecture-url]: https://github.com/jkphl/clear-architecture
[author-url]: https://jkphl.is
[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md
