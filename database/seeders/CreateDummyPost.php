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
