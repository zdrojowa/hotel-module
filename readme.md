# HotelModule

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

Hotels list.

## Installation

Via Composer

``` bash
$ composer require zdrojowa/hotel-module
```

## NPM required:

``` bash
"@ckeditor/ckeditor5-vue": "1.0.1",
"@ckeditor/ckeditor5-build-classic": "18.0.0",
"axios": "^0.19",
"bootstrap-vue": "2.16.0"
"vue": "^2.6.10",
"vue-multiselect": "2.1.6",
"vuedraggable": "2.24.3",
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

[link-packagist]: https://packagist.org/packages/zdrojowa/hotel-module
[link-downloads]: https://packagist.org/packages/zdrojowa/hotel-module
[link-author]: https://github.com/zdrojowa
[link-contributors]: ../../contributors
