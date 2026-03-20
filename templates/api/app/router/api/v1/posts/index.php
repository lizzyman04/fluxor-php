<?php
use Fluxor\Flow;
use Fluxor\Response;

$posts = [
    ['id' => 1, 'title' => 'First Post', 'content' => 'Hello World!'],
    ['id' => 2, 'title' => 'Second Post', 'content' => 'Another great post'],
];

Flow::GET()->do(function($req) use ($posts) {
    return Response::success($posts);
});

Flow::POST()->do(function($req) use (&$posts) {
    $data = $req->only(['title', 'content']);
    
    if (empty($data['title'])) {
        return Response::error('Title is required', 422);
    }
    
    $newId = count($posts) + 1;
    $newPost = ['id' => $newId, ...$data];
    $posts[] = $newPost;
    
    return Response::success($newPost, 'Post created', 201);
});