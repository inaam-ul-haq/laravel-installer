# Laravel Web Installer

[![Packagist License](https://poser.pugx.org/froiden/laravel-installer/license)]()
[![Total Downloads](https://poser.pugx.org/froiden/laravel-installer/d/total)](https://packagist.org/packages/froiden/laravel-installer)

Laravel Web installer checks for the following things and installs the application in one go.

1. Check For Server Requirements.
2. Check For Folders Permissions.
3. Ability to set database information.
4. Migrate The Database.
5. Seed The Tables.

## Note:
You need to have `.env` to the root



# Installation

1)  If you are running **Laravel 5 or above** :

```
composer require froiden/laravel-installer:1.9.0
```
OR add this line to `composer.json`

```
"require": {
    "froiden/laravel-installer": "1.9.0"
}
```
2)  If you are running **Laravel 11 or above** :
```
composer require froiden/laravel-installer:11.0.0
```
OR add this line to `composer.json`
```
"require": {
    "froiden/laravel-installer": "11.0.0"
}
```

After updating the composer, add the ServiceProvider to the providers array in `config/app.php`.

```
'providers' => [
    Inaam\Installer\Providers\LaravelInstallerServiceProvider::class,
];
```


For laravel version 11.x and greater, add the serviceprovider to the providers array in `bootstrap/providers.php`.

```
[
    Inaam\Installer\Providers\LaravelInstallerServiceProvider::class,
];
```

## Usage

Before using this package you need to run :
```bash
php artisan vendor:publish --provider="Inaam\Installer\Providers\LaravelInstallerServiceProvider"
```

You will notice additional files and folders appear in your project :
 
 - `config/installer.php` : Set the requirements along with the folders permissions for your application to run, by default the array contains the default requirements for a basic Laravel app.
 - `public/installer/assets` : This folder contains a css folder and inside it you will find a `main.css` file, this file is responsible for the styling of your installer, you can overide the default styling and add your own.
 - `resources/views/vendor/installer` : Contains the HTML code for your installer.
 - `resources/lang/en/installer_messages.php` : This file holds all the messages/text.

## Installing your application
- **Install:** In order to install your application, go to the `/install` url and follow the instructions.
## Screenshots
 
![Laravel web installer](http://public.froid.works/knap1.png)

