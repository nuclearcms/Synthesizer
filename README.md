# Synthesizer
The markdown and text processor that Nuclear CMS needs.
 
---
[![Build Status](https://travis-ci.org/NuclearCMS/Synthesizer.svg?branch=master)](https://travis-ci.org/NuclearCMS/Synthesizer)
[![Total Downloads](https://poser.pugx.org/Nuclear/Synthesizer/downloads)](https://packagist.org/packages/Nuclear/Synthesizer)
[![Latest Stable Version](https://poser.pugx.org/Nuclear/Synthesizer/version)](https://packagist.org/packages/Nuclear/Synthesizer)
[![License](https://poser.pugx.org/Nuclear/Synthesizer/license)](https://packagist.org/packages/Nuclear/Synthesizer)
 
This package is intended for [Nuclear CMS](https://github.com/NuclearCMS/Nuclear) and it constitutes its main markdown and text processing functionality. It is developed separately to enable individual development, testing and possible reuse.
 
## Installation
Installing Synthesizer is simple.
 
1. Pull this package in through [Composer](https://getcomposer.org).
    ```js
    {
        "require": {
            "nuclear/synthesizer": "~0.9"
        }
    }
    ```

2. In order to register Synthesizer Service Provider add `'Nuclear\Synthesizer\SynthesizerServiceProvider'` to the end of `providers` array in your `config/app.php` file.
    ```php
    'providers' => array(
    
        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        ...
        'Nuclear\Synthesizer\SynthesizerServiceProvider',
    
    ),
    ```
    
3. Publish the migrations and configuration files.
    ```bash
    php artisan vendor:publish --provider:"Nuclear\Synthesizer\SynthesizerServiceProvider"
    ```
    Do not forget to migrate the database.

4. Please check the tests and source code for further documentation.
 
## License
Synthesizer is released under [MIT License](https://github.com/NuclearCMS/Synthesizer/blob/master/LICENSE).
