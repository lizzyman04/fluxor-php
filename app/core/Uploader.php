<?php

namespace Core;

class Uploader
{
    private const UPLOAD_DIR = 'public/uploads';

    public static function upload(array $file, array $allowedTypes = null, int $maxSize = null): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $maxSize = $maxSize ?? (int) env('UPLOAD_MAX_SIZE', 5242880);
        if ($file['size'] > $maxSize) {
            throw new \RuntimeException(sprintf(
                "File too large. Max size: %.2f MB",
                $maxSize / 1024 / 1024
            ));
        }

        $allowedTypes = $allowedTypes ?? explode(',', env('UPLOAD_ALLOWED_TYPES', 'jpg,jpeg,png,gif,webp,pdf,doc,docx'));
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedTypes)) {
            throw new \RuntimeException(sprintf(
                "File type '%s' not allowed. Allowed: %s",
                $extension,
                implode(', ', $allowedTypes)
            ));
        }

        $uploadPath = base_path(self::UPLOAD_DIR);
        if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true)) {
            throw new \RuntimeException("Cannot create upload directory: {$uploadPath}");
        }

        $hash = hash_file('sha256', $file['tmp_name']);
        $fileName = $hash . '.' . $extension;
        $targetPath = $uploadPath . '/' . $fileName;

        if (!file_exists($targetPath)) {
            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                throw new \RuntimeException("Failed to move uploaded file");
            }
        }

        return self::getUrl($fileName);
    }

    public static function delete(string $filePath): bool
    {
        $fullPath = base_path(self::UPLOAD_DIR . '/' . ltrim($filePath, '/'));
        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }

    public static function deleteByHash(string $hash, string $extension): bool
    {
        return self::delete($hash . '.' . $extension);
    }

    public static function getUrl(string $filePath): string
    {
        $filePath = ltrim($filePath, '/');
        return base_url(self::UPLOAD_DIR . '/' . $filePath);
    }

    public static function getPath(string $filePath): string
    {
        return base_path(self::UPLOAD_DIR . '/' . ltrim($filePath, '/'));
    }

    public static function exists(string $filePath): bool
    {
        return file_exists(self::getPath($filePath));
    }

    public static function getHashFromFile(string $filePath): string
    {
        $fullPath = self::getPath($filePath);
        if (!file_exists($fullPath)) {
            throw new \RuntimeException("File not found: {$filePath}");
        }
        return hash_file('sha256', $fullPath);
    }

    public static function getAll(array $options = []): array
    {
        $uploadPath = base_path(self::UPLOAD_DIR);
        if (!is_dir($uploadPath)) {
            return [];
        }

        $pattern = $options['pattern'] ?? '*';
        $files = glob($uploadPath . '/' . $pattern);

        $result = [];
        foreach ($files as $file) {
            if (is_file($file)) {
                $filename = basename($file);
                $result[] = [
                    'name' => $filename,
                    'hash' => pathinfo($filename, PATHINFO_FILENAME),
                    'extension' => pathinfo($filename, PATHINFO_EXTENSION),
                    'size' => filesize($file),
                    'url' => self::getUrl($filename),
                    'path' => $file,
                    'created' => filectime($file),
                    'modified' => filemtime($file)
                ];
            }
        }

        if (isset($options['sort'])) {
            usort($result, function ($a, $b) use ($options) {
                $direction = $options['direction'] ?? 'asc';
                $result = $a[$options['sort']] <=> $b[$options['sort']];
                return $direction === 'desc' ? -$result : $result;
            });
        }

        return $result;
    }

    public static function cleanOldFiles(int $olderThan = 86400): int
    {
        $uploadPath = base_path(self::UPLOAD_DIR);
        if (!is_dir($uploadPath)) {
            return 0;
        }

        $now = time();
        $deleted = 0;

        foreach (glob($uploadPath . '/*') as $file) {
            if (is_file($file) && ($now - filectime($file)) > $olderThan) {
                if (unlink($file)) {
                    $deleted++;
                }
            }
        }

        return $deleted;
    }

    public static function validate(array $file, array $allowedTypes = null, int $maxSize = null): array
    {
        $errors = [];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'No file uploaded or upload error occurred';
            return $errors;
        }

        $maxSize = $maxSize ?? (int) env('UPLOAD_MAX_SIZE', 5242880);
        if ($file['size'] > $maxSize) {
            $errors[] = sprintf('File too large. Max size: %.2f MB', $maxSize / 1024 / 1024);
        }

        $allowedTypes = $allowedTypes ?? explode(',', env('UPLOAD_ALLOWED_TYPES', 'jpg,jpeg,png,gif,webp,pdf,doc,docx'));
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedTypes)) {
            $errors[] = sprintf("File type '%s' not allowed. Allowed: %s", $extension, implode(', ', $allowedTypes));
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedMimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        if (isset($allowedMimeTypes[$extension]) && $mimeType !== $allowedMimeTypes[$extension]) {
            $errors[] = sprintf('File MIME type mismatch: expected %s, got %s', $allowedMimeTypes[$extension], $mimeType);
        }

        return $errors;
    }

    public static function getFileInfo(string $filePath): ?array
    {
        $fullPath = self::getPath($filePath);
        if (!file_exists($fullPath)) {
            return null;
        }

        $filename = basename($fullPath);
        return [
            'name' => $filename,
            'hash' => pathinfo($filename, PATHINFO_FILENAME),
            'extension' => pathinfo($filename, PATHINFO_EXTENSION),
            'size' => filesize($fullPath),
            'url' => self::getUrl($filename),
            'path' => $fullPath,
            'created' => filectime($fullPath),
            'modified' => filemtime($fullPath),
            'mime_type' => mime_content_type($fullPath)
        ];
    }
}