# chat-package

<p style="text-align:center;width:100%;"><img src="/art/preview.png" alt="Live chat Laravel Package"></p>

<p align="center">
<a href="https://packagist.org/packages/omnia/oalivechat"><img src="https://poser.pugx.org/munafio/chatify/downloads?style=flat-square" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/omnia/oalivechat"><img src="https://poser.pugx.org/munafio/chatify/license?style=flat-square" alt="License"></a>
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

## Demo

- Demo app - [Click Here](https://github.com/OmniaAhmed208/live_chat_demo).

## Documentation

### 1- Installation 

composer require omnia/oalivechat

### 2- Require chat in your application

in composer installer should be installed this line

"require": { <br/>
  ... <br/>
  "omnia/oalivechat": "^0.0.1" <br/>
}

### 3- App Config

- at config in app.php in 'providers' add this line <br/>
"providers": { <br/>
  ... <br/>
  Omnia\Oalivechat\LiveChatServiceProvider::class, <br/>
}

### 4- publish Assets, css and js

- php artisan vendor:publish --tag=public --force <br/>
 it's should create 'liveChat/tools' in public dirrectry

### 5- Migration to database

- create migration to update user table <br/>
Ex: php artisan make:migration update_users --table=users

- make migration for your database => php artisan migrate
- make migration for package database => php artisan migrate --path=vendor/omnia/oalivechat/src/database/migrations

### 6- Middleware for authentication admin chat

- check the admin in database and make it's role = 'admin' not 'user'
- make middleware => Ex: php artisan make:middleware CheckAdminRole
- put this code inside it <br/>
if (Auth::check()) { <br/>
  view()->share('loggedInUser', Auth::user()); <br/>
  view()->share('adminRole', Auth::user()->role === 'admin');<br/>
}<br/>
return $next($request);

- add it to kernel.php in $middlewareGroups in 'web' <br/>
 \App\Http\Middleware\CheckAdminRole::class,

- and you should also have Admin Middleware <br/>
if (Auth::check() && Auth::user()->role === 'admin') { <br/>
  return $next($request); <br/>
}<br/>
return redirect('/');

- add it to kernel.php in $middlewareAliases <br/>
'admin' => \App\Http\Middleware\AdminMiddleware::class, <br/>
'adminRole' => \App\Http\Middleware\CheckAdminRole::class,

- update loginController to this: <br/>
-public function authenticated(Request $request, $user)
{<br/>
    $user->status = 'online';<br/>
    $user->save();<br/>
    return redirect()->intended($this->redirectPath());<br/>
}<br/>
public function logout(Request $request) <br/>
{<br/>
  $user = Auth::user();<br/>
  if ($user) { <br/>
      $userModel = User::find($user->id);<br/>
      $userModel->status = 'offline';<br/>
      $userModel->save();<br/>
  }<br/>
  Auth::logout();<br/>
  // Additional logout logic... <br/>
  return redirect('/'); <br/>
}

## Note

- you must have directory for admin dashboard (any route) with name 'admin.index'

## Usage

- you can import admin chat by => @include('liveChat::pages.admin.chat') or route {{ route('admin.chat') }} in your view
- User chat => @include('liveChat::pages.main.chat')

## Author

- [Omnia Ahmed](https://omnia-ahmed.onrender.com/index)

## License

Live chat is licensed under the [MIT license]
