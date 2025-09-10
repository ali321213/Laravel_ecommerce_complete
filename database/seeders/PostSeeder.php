<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $imageNames = [
            'product.jpg',
            'product1.jpg',
            'product2.jpg',
            'product3.png',
        ];
        $categories = PostCategory::pluck('id')->toArray();
        $tags = PostTag::pluck('id')->toArray();
        $users = User::pluck('id')->toArray();
        // Ensure you have categories, tags, and users
        if (empty($categories) || empty($tags) || empty($users)) {
            $this->command->warn('Skipping PostSeeder: Missing categories, tags, or users.');
            return;
        }
        for ($i = 1; $i <= 30; $i++) {
            $image = $imageNames[$i % count($imageNames)];
            Post::create([
                'title'        => 'Sample Blog Post ' . $i,
                'slug'         => 'sample-blog-post-' . $i,
                'summary'      => 'This is a short summary for blog post ' . $i,
                'description'  => 'This is a longer description with some detailed content for blog post ' . $i,
                'photo'        => "products/$image",
                'quote'        => '“This is a quote from blog post ' . $i . '.”',
                'tags'         => 'tag' . rand(1, 5),
                'post_cat_id'  => $categories[array_rand($categories)],
                'post_tag_id'  => $tags[array_rand($tags)],
                'added_by'     => $users[array_rand($users)],
                'status'       => rand(0, 1) ? 'active' : 'inactive',
            ]);
        }
    }
}
