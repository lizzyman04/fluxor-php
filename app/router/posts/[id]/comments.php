<?php

use Fluxor\Flow;
use Fluxor\Response;

Flow::GET()->do(function ($req) {
    $postId = $req->param('id');
    
    return Response::success([
        'post_id' => $postId,
        'comments' => [
            ['id' => 1, 'text' => 'Great post!'],
            ['id' => 2, 'text' => 'Thanks for sharing']
        ]
    ]);
});