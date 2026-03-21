<?php

namespace App\Controllers;

use Fluxor\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $page = (int) $this->request->input('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $posts = Post::paginate($limit, $offset);
        $total = Post::count();
        
        return $this->view('posts/index', [
            'title' => 'Blog Posts',
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => ceil($total / $limit)
        ]);
    }
    
    public function show()
    {
        $id = (int) $this->request->param('id');
        $post = Post::find($id);
        
        if (!$post) {
            return $this->view('errors/404', ['title' => 'Post Not Found']);
        }
        
        return $this->view('posts/show', [
            'title' => $post['title'],
            'post' => $post
        ]);
    }
    
    public function create()
    {
        return $this->view('posts/create', [
            'title' => 'Create Post'
        ]);
    }
    
    public function store()
    {
        $title = $this->request->input('title');
        $content = $this->request->input('content');
        
        $errors = [];
        
        if (empty($title)) {
            $errors['title'] = 'Title is required';
        }
        
        if (empty($content)) {
            $errors['content'] = 'Content is required';
        }
        
        if (!empty($errors)) {
            return $this->view('posts/create', [
                'title' => 'Create Post',
                'errors' => $errors,
                'old' => ['title' => $title, 'content' => $content]
            ]);
        }
        
        $postId = Post::create([
            'title' => $title,
            'content' => $content,
            'user_id' => $this->request->user()['id'],
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return $this->redirect("/posts/{$postId}");
    }
}