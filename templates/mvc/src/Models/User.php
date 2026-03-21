<?php

namespace App\Models;

class User
{
    private static $users = [];
    private static $nextId = 1;
    
    public static function init()
    {
        if (empty(self::$users)) {
            // Create default admin user
            self::$users[1] = [
                'id' => 1,
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'created_at' => date('Y-m-d H:i:s')
            ];
            self::$nextId = 2;
            
            // Load from database or file in production
            $usersFile = base_path('storage/users.json');
            if (file_exists($usersFile)) {
                $data = json_decode(file_get_contents($usersFile), true);
                if ($data) {
                    self::$users = $data['users'] ?? [];
                    self::$nextId = $data['nextId'] ?? 2;
                }
            }
        }
    }
    
    public static function all()
    {
        self::init();
        return array_values(self::$users);
    }
    
    public static function find($id)
    {
        self::init();
        return self::$users[$id] ?? null;
    }
    
    public static function findByEmail($email)
    {
        self::init();
        foreach (self::$users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }
        return null;
    }
    
    public static function create($data)
    {
        self::init();
        
        $id = self::$nextId++;
        $data['id'] = $id;
        $data['created_at'] = date('Y-m-d H:i:s');
        
        self::$users[$id] = $data;
        
        // Save to file in production
        $usersFile = base_path('storage/users.json');
        file_put_contents($usersFile, json_encode([
            'users' => self::$users,
            'nextId' => self::$nextId
        ]));
        
        return $id;
    }
    
    public static function update($id, $data)
    {
        self::init();
        
        if (!isset(self::$users[$id])) {
            return false;
        }
        
        self::$users[$id] = array_merge(self::$users[$id], $data);
        
        // Save to file
        $usersFile = base_path('storage/users.json');
        file_put_contents($usersFile, json_encode([
            'users' => self::$users,
            'nextId' => self::$nextId
        ]));
        
        return true;
    }
    
    public static function delete($id)
    {
        self::init();
        
        if (!isset(self::$users[$id])) {
            return false;
        }
        
        unset(self::$users[$id]);
        
        // Save to file
        $usersFile = base_path('storage/users.json');
        file_put_contents($usersFile, json_encode([
            'users' => self::$users,
            'nextId' => self::$nextId
        ]));
        
        return true;
    }
}