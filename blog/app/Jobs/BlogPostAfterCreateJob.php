<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\BlogPost;

class BlogPostAfterCreateJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    /**
     * @var BlogPost
     */
    private $blogPost;
    /**
     * Create a new job instance.
     */
    public function __construct(BlogPost $blogPost)
    {
        $this->blogPost = $blogPost;
    }
 
    public function handle()
    {
        logs()->info("Створено новий запис в блозі [{$this->blogPost->id}]");
    }
}
