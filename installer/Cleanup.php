<?php

namespace FluxorInstaller;

use Composer\IO\IOInterface;

class Cleanup
{
    public function __construct(private IOInterface $io, private string $projectDir)
    {
    }

    public function run(array $features): void
    {
        $this->io->write("  - Cleaning up...");

        if (!$features['docs']) {
            $this->removeDir('docs');
        } else {
            $this->removeDir('docs/public');
            $this->removeDir('docs/.vitepress');
        }

        foreach (['.github', 'installer'] as $dir) {
            $this->removeDir($dir);
        }

        foreach (['docs/index.md', 'package.json', 'package-lock.json'] as $file) {
            $this->removeFile($file);
        }
    }

    private function removeDir(string $path): void
    {
        $full = "{$this->projectDir}/{$path}";
        if (!is_dir($full))
            return;

        foreach (scandir($full) as $item) {
            if ($item === '.' || $item === '..')
                continue;
            $itemPath = "{$full}/{$item}";
            is_dir($itemPath) ? $this->removeDir("{$path}/{$item}") : unlink($itemPath);
        }

        @rmdir($full) || (PHP_OS_FAMILY === 'Windows' ? system("rmdir /s /q " . escapeshellarg($full)) : system("rm -rf " . escapeshellarg($full)));
    }

    private function removeFile(string $path): void
    {
        $full = "{$this->projectDir}/{$path}";
        is_file($full) && unlink($full);
    }
}