<?php

use \RefinedDigital\Comments\Module\Http\Repositories\CommentRepository;

if (! function_exists('comments')) {
    function comments()
    {
        return app(CommentRepository::class);
    }
}
