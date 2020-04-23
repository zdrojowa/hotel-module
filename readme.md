# HotelModule

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

Hotels list.

## Installation

Via Composer

``` bash
$ composer require zdrojowa/hotel-module
```

## Usage

- Add in webpack.mix.js

``` bash
mix.module('HotelModule', 'vendor/zdrojowa/hotel-module');
```

- Add module HotelModule in config/selene.php

``` bash
'modules' => [
    HotelModule::class,
],

'menu-order' => [
    'HotelModule',
],
```

- run npm

``` bash
npm install
npm run prod
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [author name][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/zdrojowa/hotel-module.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/zdrojowa/hotel-module.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/zdrojowa/hotel-module/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/zdrojowa/hotel-module
[link-downloads]: https://packagist.org/packages/zdrojowa/hotel-module
[link-travis]: https://travis-ci.org/zdrojowa/hotel-module
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/zdrojowa
[link-contributors]: ../../contributors
