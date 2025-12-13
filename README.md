PHP_Laravel12_Like_And_DesLike_System
---
A beginner‑friendly Laravel 12 Like & Dislike System with AJAX, Authentication, and MySQL.

This project demonstrates how users can like or dislike posts, toggle their reaction, and see live counts using a clean Laravel structure.


Project Overview
---
In this project, you will learn how to:

Create a Laravel 12 project from scratch

Design database tables for posts and likes/dislikes

Implement Like / Dislike toggle logic

Use Laravel Authentication

Handle AJAX requests with jQuery

Display like & dislike counts dynamically

Follow proper MVC structure

This project is perfect for freshers and interview preparation

Requirements
---
PHP >= 8.2

Composer

Node.js & NPM

MySQL

XAMPP / WAMP / Laragon

Step‑by‑Step Installation
---


Step 1 — Create New Laravel Project
```
composer create-project laravel/laravel PHP_Laravel12_Like_And_DesLike_System
cd PHP_Laravel12_Like_And_DesLike_System
```

This creates a fresh Laravel 12 project with all default folders.



Step 2 — Database Configuration

Create a MySQL database named:
```

php_laravel12_like_dislike_system
```

Update .env file:
```

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=php_laravel12_like_dislike_system
DB_USERNAME=root
DB_PASSWORD=
```

Laravel uses this file to connect to MySQL.



Step 3 — Create Posts & Likes Migrations
 Create Posts Table
```

php artisan make:migration create_posts_table
```

 Create Likes Table
```

php artisan make:migration create_likes_table
```

Add image in Post  Table
```

php artisan make:migration add_image_to_posts_table
```



Update the two files:

 database/migrations/*_create_posts_table.php
```

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');   // post title
            $table->text('body');      // post body
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('posts');
    }
};
```

database/migrations/*_create_likes_table.php
```

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('like');   // true = like, false = dislike
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('likes');
    }
};


```

database/migrations/*add_image_to_posts_table.php
```

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('image')->nullable()->after('body');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};

```

Run Migrations
```

php artisan migrate
```


Step 4 — Create Models

Create models for Post and Like:
```

php artisan make:model Post
php artisan make:model Like
```


app/Models/Post.php
```

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Like;   //  IMPORTANT

class Post extends Model
{
    use HasFactory;

    // Add image here
    protected $fillable = ['title', 'body', 'image'];

    // only likes
    public function likes()
    {
        return $this->hasMany(Like::class)->where('like', true);
    }

    // only dislikes
    public function dislikes()
    {
        return $this->hasMany(Like::class)->where('like', false);
    }
}

```

app/Models/Like.php
```

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model {
    use HasFactory;

    protected $fillable = ['post_id', 'user_id', 'like'];

    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}

```


Update User Model

Open app/Models/User.php and add:
```

public function likes() {
    return $this->hasMany(Like::class);
}

// check if user has liked
public function hasLiked($postId) {
    return $this->likes()->where('post_id', $postId)->where('like', true)->exists();
}

// check if user has disliked
public function hasDisliked($postId) {
    return $this->likes()->where('post_id', $postId)->where('like', false)->exists();
}

// toggle logic
public function toggleLikeDislike($postId, $like) {
    $existing = $this->likes()->where('post_id', $postId)->first();
    if ($existing) {
        if ($existing->like == $like) {
            $existing->delete();
            return ['hasLiked' => false, 'hasDisliked' => false];
        }
        $existing->update(['like' => $like]);
    } else {
        $this->likes()->create(['post_id' => $postId, 'like' => $like]);
    }

    return [
        'hasLiked' => $this->hasLiked($postId),
        'hasDisliked' => $this->hasDisliked($postId)
    ];
}
```



Step 5 — Create Dummy Post Seeder
```

php artisan make:seeder CreateDummyPost
```


Update database/seeders/CreateDummyPost.php:
```

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;

class CreateDummyPost extends Seeder
{
    public function run(): void
    {
        $data = [
    [
        'title' => 'Laravel CRUD Tutorial',
        'body' => 'Some quick example text to build on the card title.',
        'image' => 'https://itsolutionstuff.com/uploads/posts/featured_image/laravel-crud.png'
    ],
    [
        'title' => 'Laravel Image Upload',
        'body' => 'Some quick example text to build on the card title.',
        'image' => 'https://itsolutionstuff.com/uploads/posts/featured_image/laravel-image-upload.png'
    ],
    [
        'title' => 'Laravel AJAX Example',
        'body' => 'Some quick example text to build on the card title.',
        'image' => 'https://itsolutionstuff.com/uploads/posts/featured_image/laravel-ajax.png'
    ],
];

        foreach ($data as $post) {
            Post::create($post);
        }
    }
}

```



Seed it:
```

php artisan db:seed --class=CreateDummyPost
```

But in DatabaseSeeder.php you already call:
```

$this->call(CreateDummyPost::class);
```


 Best practice (simpler):
```

php artisan db:seed
```

Step 6 — Install Authentication

Install Laravel UI:
```

composer require laravel/ui


Generate Auth:

php artisan ui bootstrap --auth
npm install
npm run build

```


Step 7 — Routes

Add these to routes/web.php:
```

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/', fn() => view('welcome'));

Route::middleware('auth')->group(function () {
    Route::get('posts', [PostController::class,'index'])->name('posts.index');
    Route::post('posts/ajax-like-dislike', [PostController::class,'ajaxLike'])->name('posts.ajax.like.dislike');
});
```


Step 8 — Controller
```

php artisan make:controller PostController
```


Update app/Http/Controllers/PostController.php:
```

<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller {
    public function index(){
        $posts = Post::all();
        return view('posts', compact('posts'));
    }

    public function ajaxLike(Request $request){
        $response = auth()->user()->toggleLikeDislike($request->id, $request->like);
        return response()->json(['success' => $response]);
    }
}
```



Step 9 — Blade Templates
➤ resources/views/layouts/app.blade.php
```

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel Like Dislike') }}</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    @yield('style')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@yield('script')

</html>
```



resources/views/posts.blade.php
```

@extends('layouts.app')

@section('style')
<style>
    .post-img {
        height: 180px;
        object-fit: cover;
    }
    i {
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="container">

    <div class="card shadow-sm">
        <div class="card-header fw-bold">
            Posts List
        </div>

        <div class="card-body">
            <div class="row">

                @foreach($posts as $post)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">

                        <!-- Image -->
                        <img src="https://picsum.photos/300/200?random={{ $post->id }}"
                             class="card-img-top post-img">

                        <div class="card-body">
                            <h6 class="fw-bold">{{ $post->title }}</h6>
                            <p class="text-muted small">{{ $post->body }}</p>
                        </div>

                        <!-- Like Dislike -->
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex align-items-center gap-3">

                                <span>
                                    <i data-id="{{ $post->id }}"
                                       class="like fa-thumbs-up
                                       {{ auth()->user()->hasLiked($post->id) ? 'fa-solid text-success' : 'fa-regular' }}">
                                    </i>
                                    <span class="like-count">{{ $post->likes->count() }}</span>
                                </span>

                                <span>
                                    <i data-id="{{ $post->id }}"
                                       class="dislike fa-thumbs-down
                                       {{ auth()->user()->hasDisliked($post->id) ? 'fa-solid text-danger' : 'fa-regular' }}">
                                    </i>
                                    <span class="dislike-count">{{ $post->dislikes->count() }}</span>
                                </span>

                            </div>
                        </div>

                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
$.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});

$('.like, .dislike').click(function () {
    let id = $(this).data('id');
    let like = $(this).hasClass('like') ? 1 : 0;

    $.post("{{ route('posts.ajax.like.dislike') }}", {
        id: id,
        like: like
    }, function () {
        location.reload(); // simple refresh
    });
});
</script>
@endsection

```


Step 10 — Run App
```

php artisan serve

```

Open in browser:
```

http://localhost:8000

```

You can see this type Output:

Main page:

<img width="1916" height="967" alt="Screenshot 2025-12-13 114859" src="https://github.com/user-attachments/assets/e6e3c8f7-43c4-4810-987b-08a7c53eb2ba" />

Register page:

<img width="1919" height="962" alt="Screenshot 2025-12-13 114947" src="https://github.com/user-attachments/assets/d62fcf1a-2be7-4055-b170-ea281d375112" />

login page:

<img width="1918" height="959" alt="Screenshot 2025-12-13 115647" src="https://github.com/user-attachments/assets/8075ad84-5015-43c9-bed4-81f1f6e7dbbb" />

Posts Page:

<img width="1919" height="958" alt="Screenshot 2025-12-13 115819" src="https://github.com/user-attachments/assets/13e91889-c9bd-4b8c-9dff-84dc16928355" />

like/dislike show page :

<img width="1919" height="956" alt="Screenshot 2025-12-13 115903" src="https://github.com/user-attachments/assets/afad785b-b953-4101-8127-ef0cc795882d" />


 Project Folder Structure :
```
 PHP_Laravel12_Like_And_DesLike_System
│
├── app
│   ├── Http
│   │   ├── Controllers
│   │   │   ├── Auth
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── RegisterController.php
│   │   │   │   └── ...
│   │   │   ├── HomeController.php
│   │   │   └── PostController.php    (Like / Dislike logic)
│   │   │
│   │   └── Middleware
│   │
│   ├── Models
│   │   ├── User.php       (toggleLikeDislike logic)
│   │   ├── Post.php       (posts + likes relationship)
│   │   └── Like.php      (like/dislike model)
│   │
│   └── Providers
│
├── bootstrap
│   └── app.php
│
├── config
│   ├── app.php
│   ├── auth.php
│   └── database.php
│
├── database
│   ├── factories
│   │
│   ├── migrations
│   │   ├── xxxx_xx_xx_create_posts_table.php
│   │   ├── xxxx_xx_xx_create_likes_table.php
│   │   └── xxxx_xx_xx_add_image_to_posts_table.php
│   │
│   ├── seeders
│   │   ├── DatabaseSeeder.php
│   │   └── CreateDummyPost.php   
│
├── public
│   ├── index.php
│   └── assets
│
├── resources
│   ├── css
│   ├── js
│   │
│   └── views
│       ├── auth
│       │   ├── login.blade.php
│       │   ├── register.blade.php
│       │   └── passwords
│       │
│       ├── layouts
│       │   └── app.blade.php   
│       │
│       ├── posts.blade.php      (Like / Dislike UI)
│       ├── home.blade.php
│       └── welcome.blade.php
│
├── routes
│   ├── web.php        
│   ├── auth.php
│   └── console.php
│
├── storage
│   ├── app
│   ├── framework
│   └── logs
│
├── tests
│
├── .env                Database config
├── artisan
├── composer.json
├── package.json
├── vite.config.js
└── README.md           (your project documentation)


