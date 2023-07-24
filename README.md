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
- Chat customization: chat color and font size.
  with simple and wonderful UI design.

...and much more you have to discover it yourself.

## Demo

- Demo app - [Click Here](https://github.com/OmniaAhmed208/live_chat_demo).

## Documentation

### 1- Installation 

Run the following command to install the package:<br/>

composer require omnia/oalivechat

### 2- App Config

- In the config/app.php file, add the following line to the 'providers' array: <br/>

"providers": { <br/>
  ... <br/>
  Omnia\Oalivechat\LiveChatServiceProvider::class, <br/>
}

### 3- publish Assets, css and js

- To publish the package's assets, CSS, and JS, run the following command:
- php artisan vendor:publish --tag=public --force <br/>
 This will create a 'liveChat/tools' directory in the public directory. <br/>
- If you want to change the user chat color and position, you can do so in public/liveChat/tools/chat/css/final.css.

### 4- Migration to database

- make migration for your database => php artisan migrate
- make migration for package database => php artisan migrate --path=vendor/omnia/oalivechat/src/database/migrations

### 5- Middleware for authentication admin chat

- First, make sure to check the admin in the database and set its role to 'admin', not 'user'.
- Create a middleware for checking the admin role: php artisan make:middleware CheckAdminRole 
- Add the following code to the handle method inside the middleware: <br/>
use Illuminate\Http\Request; <br/>
use Illuminate\Support\Facades\Auth; <br/>

public function handle(Request $request, Closure $next): Response <br/>
{ <br/>
  if (Auth::check()) { <br/>
    view()->share('loggedInUser', Auth::user()); <br/>
    view()->share('adminRole', Auth::user()->role === 'admin'); <br/>
  } <br/>

  return $next($request); <br/>
}

- Then, add the middleware in app/Http/Kernel.php:  <br/>
protected $middlewareGroups = [ <br/>
  'web' => [ <br/>
    // ... <br/>
    \App\Http\Middleware\CheckAdminRole::class, <br/>
  ], <br/>
];

- You should also have an Admin middleware. Create it using: <br/>
 php artisan make:middleware Admin
- Add the following code to the handle method inside the middleware:<br/>
use Illuminate\Http\Request; <br/>
use Illuminate\Support\Facades\Auth; <br/>

public function handle(Request $request, Closure $next): Response <br/>
{ <br/>
  if (Auth::check() && Auth::user()->role === 'admin') { <br/>
    return $next($request); <br/>
  } <br/>

  return redirect('/'); <br/>
}

- add the middleware aliases in app/Http/Kernel.php: <br/>
protected $middlewareAliases = [ <br/>
  // ... <br/>
  'admin' => \App\Http\Middleware\Admin::class, <br/>
  'adminRole' => \App\Http\Middleware\CheckAdminRole::class, <br/>
]; 

- Optionally, to show the admin's status in the user chat, make the following updates to the LoginController:
if you want this step: you should have laravel Authentication to get loginController file.

- code:<br/>
use Illuminate\Http\Request; <br/>
use Illuminate\Support\Facades\Auth; <br/>
use App\Models\User; <br/>

public function authenticated(Request $request, $user) <br/>
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

- You must have a directory for the admin dashboard (any route) with the name 'admin.index'.

## Usage

- To import admin chat, create a link anywhere in your view, for example: <br/>
`<a href=" {{ route('admin.chat') }} ">Messages</a>` <br/>

- For user chat, add the following code to a view that appears on all pages (e.g., footer):<br/>
<pre>
  @auth <br/>
    @if (Auth::user()->role != 'admin') <br/>
        @include('liveChat::pages.main.chat') <br/>
    @endif <br/>
  @else <br/>
    @include('liveChat::pages.main.chat')<br/>
  @endauth
<pre>
## Author

- [Omnia Ahmed](https://omnia-ahmed.onrender.com/index)

## License

Live chat is licensed under the [MIT license]
