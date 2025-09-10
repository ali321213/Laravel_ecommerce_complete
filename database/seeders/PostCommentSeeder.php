<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\PostComment;
use Illuminate\Support\Str;

class PostCommentSeeder extends Seeder
{
    public function run(): void
    {
        $postIds = \App\Models\Post::pluck('id')->toArray();
        if (empty($postIds)) {
            $this->command->warn('No posts found! Skipping PostCommentSeeder.');
            return;
        }
        for ($i = 0; $i < 20; $i++) {
            $topComment = PostComment::create([
                'post_id'   => $postIds[array_rand($postIds)],
                'user_id'   => rand(1, 5),
                'parent_id' => null,
                'comment'   => 'Top-level comment ' . Str::random(20),
                'status'    => 'active',
                'replied_comment' => null,
            ]);
            $replyCount = rand(1, 3);
            for ($j = 0; $j < $replyCount; $j++) {
                PostComment::create([
                    'post_id'         => $topComment->post_id,
                    'user_id'         => rand(1, 10),
                    'parent_id'       => $topComment->id,
                    'comment'         => 'Reply ' . Str::random(20),
                    'status'          => 'active',
                    'replied_comment' => null,
                ]);
            }
        }
    }
}
