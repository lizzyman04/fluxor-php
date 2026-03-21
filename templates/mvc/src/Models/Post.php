<?php

namespace App\Models;

class Post
{
    private static $posts = [];
    private static $nextId = 1;
    
    public static function init()
    {
        if (empty(self::$posts)) {
            // Create some demo posts
            self::$posts = [
                1 => [
                    'id' => 1,
                    'title' => 'Welcome to Fluxor',
                    'content' => 'Fluxor is a modern PHP framework with file-based routing.',
                    'user_id' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ],
                2 => [
                    'id' => 2,
                    'title' => 'Getting Started',
                    'content' => 'Learn how to build amazing applications with Fluxor.',
                    'user_id' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ]
            ];
            self::$nextId = 3;
            
            // Load from file in production
            $postsFile = base_path('storage/posts.json');
            if (file_exists($postsFile)) {
                $data = json_decode(file_get_contents($postsFile), true);
                if ($data) {
                    self::$posts = $data['posts'] ?? [];
                    self::$nextId = $data['nextId'] ?? 3;
                }
            }
        }
    }
    
    public static function all()
    {
        self::init();
        return array_values(self::$posts);
    }
    
    public static function paginate($limit, $offset)
    {
        self::init();
        return array_slice(array_values(self::$posts), $offset, $limit);
    }
    
    public static function count()
    {
        self::init();
        return count(self::$posts);
    }
    
    public static function find($id)
    {
        self::init();
        return self::$posts[$id] ?? null;
    }
    
    public static function create($data)
    {
        self::init();
        
        $id = self::$nextId++;
        $data['id'] = $id;
        
        self::$posts[$id] = $data;
        
        // Save to file
        $postsFile = base_path('storage/posts.json');
        file_put_contents($postsFile, json_encode([
            'posts' => self::$posts,
            'nextId' => self::$nextId
        ]));
        
        return $id;
    }
    
    public static function update($id, $data)
    {
        self::init();
        
        if (!isset(self::$posts[$id])) {
            return false;
        }
        
        self::$posts[$id] = array_merge(self::$posts[$id], $data);
        
        // Save to file
        $postsFile = base_path('storage/posts.json');
        file_put_contents($postsFile, json_encode([
            'posts' => self::$posts,
            'nextId' => self::$nextId
        ]));
        
        return true;
    }
    
    public static function delete($id)
    {
        self::init();
        
        if (!isset(self::$posts[$id])) {
            return false;
        }
        
        unset(self::$posts[$id]);
        
        // Save to file
        $postsFile = base_path('storage/posts.json');
        file_put_contents($postsFile, json_encode([
            'posts' => self::$posts,
            'nextId' => self::$nextId
        ]));
        
        return true;
    }
}