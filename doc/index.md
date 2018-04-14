# jkphl/responsive-images-css

[![Build Status][travis-image]][travis-url] [![Coverage Status][coveralls-image]][coveralls-url] [![Scrutinizer Code Quality][scrutinizer-image]][scrutinizer-url] [![Code Climate][codeclimate-image]][codeclimate-url]  [![Clear architecture][clear-architecture-image]][clear-architecture-url]

> HTML5-like responsive background images in CSS (sort of …)

## About

The purpose of *responsive-images-css* is to ease the creation process of responsive background images in CSS. It provides similar semantics as responsive images via `<img srcset="…" sizes="…">` in HTML5.

## Usage

The main entry point for the creation of a responsive background image is the `Generator`. You will want to use it like this:

1. Create a **Generator** instance and let it know the CSS breakpoints of your project (ignored for resolution based rulesets).
2. Register a set of **Image Candidates**, each one coming with a **width or resolution descriptor** (corresponds to `srcset` in HTML5).
3. Trigger the creation of a CSS ruleset by passing in a **list of resolutions** to render the image for and an optional **`sizes` specification** (width based image candidates only).

The rendering sequence of a standard HTML5 responsive (foreground) image is a highly complex process. It's impossible to fully predict which exact image candidate a browser will pick as some decisions may depend on environment settings that are only available at runtime (such as the network performance).

In contrast, *responsive-images-css* generates CSS code on the server-side — that is, long before the browser gets to interpret the generated output. To make this work, some asumptions have to be made:

* The generator needs to be given a fixed **em to pixel ratio** in order to predictably deal with `em` / `rem` values.
* The generator utilizes the given breakpoints only, even if the image candidates suggest additional steps.
* The device densities (resolutions) for which the CSS should be rendered must be explictly provided.

### Resolution based responsive image

### Width based responsive image

### Width based responsive image with `sizes` specification




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
