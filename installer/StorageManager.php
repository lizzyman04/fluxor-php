<?php
/**
 * Storage Manager - Creates storage directories
 */

namespace FluxorInstaller;

use Composer\IO\IOInterface;

class StorageManager
{
    private IOInterface $io;
    private string $projectDir;

    public function __construct(IOInterface $io, string $projectDir)
    {
        $this->io = $io;
        $this->projectDir = $projectDir;
    }

    public function createDirectories(): void
    {
        $directories = [
            'storage/logs',
            'storage/cache',
            'storage/sessions'
        ];

        foreach ($directories as $dir) {
            $path = $this->projectDir . '/' . $dir;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }

            $gitkeep = $path . '/.gitkeep';
            if (!file_exists($gitkeep)) {
                file_put_contents($gitkeep, '');
            }
        }

        $this->io->write("  ✓ Storage directories created");
    }
}