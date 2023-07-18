# chat-package

<p style="text-align:center;width:100%;"><img src="/art/preview.png" alt="Chatify Laravel Package"></p>

<p align="center">
<a href="https://github.com/laravel/telescope/actions"><img src="https://poser.pugx.org/munafio/chatify/v/stable?style=flat-square" alt="Build Status"></a>
<a href="https://packagist.org/packages/munafio/chatify"><img src="https://poser.pugx.org/munafio/chatify/downloads?style=flat-square" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/munafio/chatify"><img src="https://poser.pugx.org/munafio/chatify/license?style=flat-square" alt="License"></a>
</p>

## Live Chat Laravel Package

Laravel's #1 one-to-one chatting system package between admin and user, helps you add a complete real-time chatting system to your new/existing Laravel application with only one command.

## Features

- chat system between admin and user.
- Real-time contact list updates.
- Upload attachments (Photo/File).
- Responsive design with all devices.
- chat customization : chat color and fontsize.
  with simple and wonderful UI design.

...and much more you have to discover it yourself.

<!-- ## Demo -->

<!-- - Demo app - [Click Here](https://github.com/munafio/chatify-demo). -->
<!-- - Demo video on YouTube - [Click Here](https://youtu.be/gjo74FUJJPI) -->

## Documentation

# 1- Installation 

composer require omnia/oalivechat

# 2- Require chat in your application

in composer installer should be installed this line

"require": {
    ...
    "omnia/oalivechat": "^0.0.1"
}

# 3- App Config

'providers' => ServiceProvider::defaultProviders()->merge([
    ...
    Omnia\Oalivechat\LiveChatServiceProvider::class,
]);


# 4- Migration to database

<!-- php artisan migrate -->
php artisan migrate --path=vendor/omnia/oalivechat/src/database/migrations
php artisan make:migration update_users --table=users  

## Author

- [Omnia Ahmed](https://omnia-ahmed.onrender.com/index)

## License

Live chat is licensed under the [MIT license]