<?php

namespace FluxorInstaller;

use Composer\IO\IOInterface;

class Cleanup
{
    private IOInterface $io;
    private string $projectDir;

    public function __construct(IOInterface $io, string $projectDir)
    {
        $this->io = $io;
        $this->projectDir = $projectDir;
    }

    public function run(array $features): void
    {
        $this->io->write("\nCleaning up...");

        if (!$features['docs']) {
            $this->removeDirectory('docs');
        } else {
            $this->removeDirectory('docs/public');
            $this->removeDirectory('docs/.vitepress');
        }

        $this->removeDirectory('installer');
    }

    private function removeDirectory(string $path): void
    {
        $fullPath = $this->projectDir . '/' . $path;
        if (!is_dir($fullPath)) {
            return;
        }

        $items = scandir($fullPath);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $itemPath = $fullPath . '/' . $item;
            if (is_dir($itemPath)) {
                $this->removeDirectory($itemPath);
            } else {
                unlink($itemPath);
            }
        }

        rmdir($fullPath);
    }
}