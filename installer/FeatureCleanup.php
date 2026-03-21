<?php
/**
 * Feature Cleanup - Removes files for features not selected
 */

namespace FluxorInstaller;

use Composer\IO\IOInterface;

class FeatureCleanup
{
    private IOInterface $io;
    private string $projectDir;

    public function __construct(IOInterface $io, string $projectDir)
    {
        $this->io = $io;
        $this->projectDir = $projectDir;
    }

    public function removeUnwantedFeatures(array $config): void
    {
        $this->io->write("\n<info>🧹 Configuring features...</info>");

        if (!$config['auth']) {
            $this->removeAuth();
            $this->io->write("  ✓ Authentication files removed");
        }

        if (!$config['mailer']) {
            $this->removeMailer();
            $this->io->write("  ✓ Mailer files removed");
        }

        if (!$config['uploader']) {
            $this->removeUploader();
            $this->io->write("  ✓ Uploader files removed");
        }

        $this->removeInstallerDirectory();
    }

    private function removeAuth(): void
    {
        $files = [
            'app/core/Auth.php',
            'src/Controllers/AuthController.php',
            'src/Models/User.php',
            'src/Views/auth/login.php',
            'src/Views/auth/register.php',
            'app/router/auth/login.php',
            'app/router/auth/register.php',
            'app/router/auth/logout.php'
        ];

        foreach ($files as $file) {
            $this->removeFile($file);
        }

        $dirs = [
            'app/router/auth',
            'src/Views/auth'
        ];

        foreach ($dirs as $dir) {
            $this->removeDirectory($dir);
        }
    }

    private function removeMailer(): void
    {
        $this->removeFile('app/core/Mailer.php');
    }

    private function removeUploader(): void
    {
        $this->removeFile('app/core/Uploader.php');
        $this->removeDirectory('public/uploads');
    }

    private function removeInstallerDirectory(): void
    {
        $this->removeDirectory('installer');
        $this->io->write("  ✓ Installer directory removed");
    }

    private function removeFile(string $path): void
    {
        $fullPath = $this->projectDir . '/' . $path;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
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