# chat-package

<p style="text-align:center;width:100%;"><img src="/art/preview.png" alt="Live chat Laravel Package"></p>

<p align="center">
<a href="https://packagist.org/packages/omnia/oalivechat"><img src="https://poser.pugx.org/munafio/chatify/downloads?style=flat-square" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/omnia/oalivechat"><img src="https://poser.pugx.org/munafio/chatify/license?style=flat-square" alt="License"></a>
</p>

## Live Chat Laravel Package

Laravel's one-to-one chatting system package between admin and user, helps you add a complete real-time chatting system to your new/existing Laravel application with some commands.

<!-- ## Technologies

- Laravel Framework [^9.0, ^10.0]
- PHP 8.1 or higher
- Database (eg: MySQL)
- Web Server (eg: Apache) -->

## Features

- chat system between admin and user.
- Real-time contact list updates.
- Upload attachments (Photo/File).
- Responsive design with all devices.
- Chat customization: chat color and font size.
  with a simple design.

...and much more you have to discover it yourself.

## Demo

- Demo app - [Click Here](https://github.com/OmniaAhmed208/live_chat_demo).

## Documentation

### 1- Installation 

Run the following command to install the package:<br/>
```php
composer require omnia/oalivechat
```
### 2- App Config

- In the `config/app.php` file, add the following line to the `providers` array: <br/>
```php
"providers": { 
  ... 
  Omnia\Oalivechat\LiveChatServiceProvider::class, 
}
```
### 3- publish Assets, css and js

- To publish the package's assets, CSS, and JS, run the following command:

```php
php artisan vendor:publish --tag=public --force 
```
This will create a `liveChat/tools` directory in the public directory. <br/>
- If you want to change the user chat color and position, you can do so in `public/liveChat/tools/chat/css/final.css`

### 4- Migration to database

- make migration for your database
```php
php artisan migrate
```
- make migration for package database
```php
php artisan migrate --path=vendor/omnia/oalivechat/src/database/migrations
```

### 5- Middleware for authentication admin chat

- **First**, make sure to check the admin in the database and set its **role_for_messages** to `admin`, not 'user'.
- Create a middleware for checking the admin role: 
```php
php artisan make:middleware CheckAdminRole 
```

- Add the following code to the handle method inside the middleware: 
```php
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
```
```php
public function handle(Request $request, Closure $next) 
{ 
  if (Auth::check()) { 
    view()->share('loggedInUser', Auth::user()); 
    view()->share('adminRole', Auth::user()->role_for_messages === 'admin'); 
  } 

  return $next($request);
}
```

- Then, add the middleware in `app/Http/Kernel.php`:  
```php
protected $middlewareGroups = [ 
  'web' => [ 
    // ... 
    \App\Http\Middleware\CheckAdminRole::class, 
  ], 
];
```

- Create an AdminMessages middleware by this code: 
```php
php artisan make:middleware AdminMessages
```

- Add the following code to the handle method inside the middleware:
```php
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
```
```php
public function handle(Request $request, Closure $next) 
{ 
  if (Auth::check() && Auth::user()->role_for_messages === 'admin') { 
    return $next($request); 
  } 

  return redirect('/'); 
}
```

- add the middleware aliases in `app/Http/Kernel.php`: 
```php
protected $middlewareAliases = [ 
  // ... 
  'adminMessages' => \App\Http\Middleware\AdminMessages::class, 
  'adminRole' => \App\Http\Middleware\CheckAdminRole::class, 
]; 
```

- Optionally, to show the `admin's status` in the user chat, make the following updates to the `LoginController`: <br>
if you want this step: you should have **laravel Authentication** to get loginController file.
```php
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use App\Models\User; 
```
```php
public function authenticated(Request $request, $user) 
{
    $user->status_for_messages = 'online';
    $user->save();
    return redirect()->intended($this->redirectPath());
}
public function logout(Request $request) 
{
  $user = Auth::user();
  if ($user) { 
      $userModel = User::find($user->id);
      $userModel->status_for_messages = 'offline';
      $userModel->save();
  }
  Auth::logout();
  // Additional logout logic... 
  return redirect('/'); 
}
```

## Note

- You must have a directory for the admin dashboard (any route) with the name `admin.index`.

## Usage

- To import admin chat, create a link anywhere in your view, for example:
```php
<a href=" {{ route('admin.chat') }} ">Messages</a>
```

- if you want the counter of messages => put this code on your view
and only enter your id name When calling the function which you want the count appeares inside it.
```php
<script src="{{ asset('/liveChat/tools/chat/js/msg_counter.js') }}"></script> 
<script>
    window.onload = function() { 
      var routeUrl = "{{ route('fetchNewMessages') }}"; 
      fetchNewMessages(routeUrl,'id_name'); 
    }; 
</script> 
```

- For user chat, add the following code to a view that appears on all pages (e.g., footer):<br/>
```php
@php
    $websiteName = "your website name";
    $websiteColor = "your color";
@endphp

@auth 
  @if (Auth::user()->role_for_messages != 'admin') 
      @include('liveChat::pages.main.chat', ['websiteName' => $websiteName], ['chatColor' => $websiteColor]) 
  @endif 
@else 
  @include('liveChat::pages.main.chat', ['websiteName' => $websiteName], ['chatColor' => $websiteColor])
@endauth
```

- if you have static design you may put `$websiteName` and `$websiteColor` any value or empty (e.g., "") but not remove them from the previous code.

## Author

- [Omnia Ahmed](https://omnia-ahmed.onrender.com/index)

## License

Live chat is licensed under the [MIT license]
