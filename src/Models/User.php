<?php

namespace App\Models;

class User
{
    private static array $users = [];
    private static bool $initialized = false;

    private static function init(): void
    {
        if (self::$initialized) {
            return;
        }

        $storageFile = base_path('storage/users.json');
        if (file_exists($storageFile)) {
            $data = json_decode(file_get_contents($storageFile), true);
            self::$users = $data['users'] ?? [];
        } else {
            self::$users = [
                1 => [
                    'id' => 1,
                    'name' => 'Admin User',
                    'email' => 'admin@example.com',
                    'password' => password_hash('admin123', PASSWORD_DEFAULT),
                    'role' => 'admin',
                    'created_at' => date('Y-m-d H:i:s')
                ]
            ];
            self::save();
        }

        self::$initialized = true;
    }

    private static function save(): void
    {
        $storageFile = base_path('storage/users.json');
        $dir = dirname($storageFile);

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($storageFile, json_encode(['users' => self::$users], JSON_PRETTY_PRINT));
    }

    public static function findByEmail(string $email): ?array
    {
        self::init();

        foreach (self::$users as $user) {
            if ($user['email'] === $email) {
                return $user;
            }
        }

        return null;
    }

    public static function find(int $id): ?array
    {
        self::init();
        return self::$users[$id] ?? null;
    }

    public static function create(array $data): int
    {
        self::init();

        $id = max(array_keys(self::$users)) + 1;

        $user = [
            'id' => $id,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'] ?? 'user',
            'created_at' => date('Y-m-d H:i:s')
        ];

        self::$users[$id] = $user;
        self::save();

        return $id;
    }

    public static function update(int $id, array $data): bool
    {
        self::init();

        if (!isset(self::$users[$id])) {
            return false;
        }

        self::$users[$id] = array_merge(self::$users[$id], $data);
        self::save();

        return true;
    }

    public static function delete(int $id): bool
    {
        self::init();

        if (!isset(self::$users[$id])) {
            return false;
        }

        unset(self::$users[$id]);
        self::save();

        return true;
    }

    public static function all(): array
    {
        self::init();
        return array_values(self::$users);
    }

    public static function count(): int
    {
        self::init();
        return count(self::$users);
    }
}