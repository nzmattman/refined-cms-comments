<?php

Route::namespace('Comments\Module\Http\Controllers')
    ->group(function() {
        Route::resource('comments', 'CommentController');
    })
;