<?php
use Fluxor\Flow;
use Fluxor\Response;

$posts = [
    1 => ['id' => 1, 'title' => 'First Post', 'content' => 'Hello World!'],
    2 => ['id' => 2, 'title' => 'Second Post', 'content' => 'Another great post'],
];

Flow::GET()->do(function ($req) use ($posts) {
    $id = (int) $req->param('id');

    if (!isset($posts[$id])) {
        return Response::error('Post not found', 404);
    }

    return Response::success($posts[$id]);
});

Flow::PUT()->do(function ($req) use (&$posts) {
    $id = (int) $req->param('id');
    $data = $req->only(['title', 'content']);

    if (!isset($posts[$id])) {
        return Response::error('Post not found', 404);
    }

    $posts[$id] = array_merge($posts[$id], $data);
    return Response::success($posts[$id], 'Post updated');
});

Flow::DELETE()->do(function ($req) use (&$posts) {
    $id = (int) $req->param('id');

    if (!isset($posts[$id])) {
        return Response::error('Post not found', 404);
    }

    unset($posts[$id]);
    return Response::success(null, 'Post deleted');
});