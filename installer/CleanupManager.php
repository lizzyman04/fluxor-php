<?php
/**
 * Cleanup Manager - Handles post-installation cleanup
 */

namespace FluxorInstaller;

use Composer\IO\IOInterface;
use Symfony\Component\Filesystem\Filesystem;

class CleanupManager
{
    private IOInterface $io;
    private string $projectDir;
    private Filesystem $fs;

    private const FILES_TO_REMOVE = [
        'docs',
        'templates',
        'package.json',
        'package-lock.json',
        '.github/workflows/deploy-docs.yml',
        'installer',
    ];

    private const DIRS_TO_REMOVE = [
        '.github',
    ];

    private const OPTIONAL_FILES = [
        'storage/framework/.gitkeep',
        'storage/logs/.gitkeep',
        'storage/cache/.gitkeep',
    ];

    public function __construct(IOInterface $io, string $projectDir)
    {
        $this->io = $io;
        $this->projectDir = $projectDir;
        $this->fs = new Filesystem();
    }

    public function clean(): void
    {
        $removed = [];

        $removed = array_merge($removed, $this->removeFiles(self::FILES_TO_REMOVE));
        $removed = array_merge($removed, $this->removeDirectories(self::DIRS_TO_REMOVE));

        $this->removeOptionalFiles();
        $this->cleanComposerJson();

        if (!empty($removed)) {
            $this->io->write("\n<info>🧹 Cleaned up installation files</info>");
            foreach ($removed as $item) {
                $this->io->write(sprintf("  <comment>Removed:</comment> %s", $item));
            }
        }
    }

    private function removeFiles(array $files): array
    {
        $removed = [];

        foreach ($files as $file) {
            $fullPath = $this->projectDir . '/' . $file;
            if ($this->fs->exists($fullPath)) {
                try {
                    $this->fs->remove($fullPath);
                    $removed[] = $file;
                } catch (\Exception $e) {
                    $this->io->writeError(sprintf("  <error>Failed to remove %s: %s</error>", $file, $e->getMessage()));
                }
            }
        }

        return $removed;
    }

    private function removeDirectories(array $directories): array
    {
        $removed = [];

        foreach ($directories as $dir) {
            $fullPath = $this->projectDir . '/' . $dir;
            if ($this->fs->exists($fullPath)) {
                try {
                    $this->fs->remove($fullPath);
                    $removed[] = $dir . '/';
                } catch (\Exception $e) {
                    $this->io->writeError(sprintf("  <error>Failed to remove directory %s: %s</error>", $dir, $e->getMessage()));
                }
            }
        }

        return $removed;
    }

    private function removeOptionalFiles(): void
    {
        foreach (self::OPTIONAL_FILES as $file) {
            $fullPath = $this->projectDir . '/' . $file;
            if ($this->fs->exists($fullPath)) {
                try {
                    $this->fs->remove($fullPath);
                } catch (\Exception $e) {
                }
            }
        }
    }

    private function cleanComposerJson(): void
    {
        $composerFile = $this->projectDir . '/composer.json';
        if (!$this->fs->exists($composerFile)) {
            return;
        }

        try {
            $composer = json_decode(file_get_contents($composerFile), true);
            if (!is_array($composer)) {
                return;
            }

            $modified = false;

            if (isset($composer['scripts']['post-create-project-cmd'])) {
                unset($composer['scripts']['post-create-project-cmd']);
                $modified = true;
            }

            if (isset($composer['autoload']['classmap'])) {
                $filteredClassmap = array_filter($composer['autoload']['classmap'], function ($item) {
                    return $item !== 'installer/';
                });

                if (count($filteredClassmap) !== count($composer['autoload']['classmap'])) {
                    $composer['autoload']['classmap'] = array_values($filteredClassmap);
                    $modified = true;

                    if (empty($composer['autoload']['classmap'])) {
                        unset($composer['autoload']['classmap']);
                    }
                }
            }

            if ($modified) {
                file_put_contents(
                    $composerFile,
                    json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
                );
            }
        } catch (\Exception $e) {
        }
    }

    public function emergencyCleanup(): void
    {
        $this->io->write("\n<comment>Performing emergency cleanup...</comment>");

        $criticalFiles = ['installer'];
        $this->removeFiles($criticalFiles);
        $this->cleanComposerJson();
    }

    public function verifyCleanup(): bool
    {
        $hasErrors = false;

        foreach (self::FILES_TO_REMOVE as $file) {
            $fullPath = $this->projectDir . '/' . $file;
            if ($this->fs->exists($fullPath)) {
                $this->io->writeError(sprintf("  <error>Warning: %s still exists</error>", $file));
                $hasErrors = true;
            }
        }

        $composerFile = $this->projectDir . '/composer.json';
        if ($this->fs->exists($composerFile)) {
            try {
                $composer = json_decode(file_get_contents($composerFile), true);
                if (isset($composer['autoload']['classmap']) && in_array('installer/', $composer['autoload']['classmap'])) {
                    $this->io->writeError("  <error>Warning: installer still in classmap</error>");
                    $hasErrors = true;
                }
            } catch (\Exception $e) {
            }
        }

        return !$hasErrors;
    }
}