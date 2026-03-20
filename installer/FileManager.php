<?php
/**
 * File Manager - Handles file operations during installation
 */

namespace FluxorInstaller;

use Composer\IO\IOInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;

class FileManager
{
    private IOInterface $io;
    private string $projectDir;
    private Filesystem $fs;
    
    public function __construct(IOInterface $io, string $projectDir)
    {
        $this->io = $io;
        $this->projectDir = $projectDir;
        $this->fs = new Filesystem();
    }
    
    public function copyTemplate(string $template): void
    {
        $source = TemplateManager::getTemplatePath($template);
        
        $this->io->write("\n<info>📂 Installing '{$template}' template...</info>");
        
        $finder = new Finder();
        $finder->files()->in($source);
        
        $count = 0;
        $errors = [];
        
        foreach ($finder as $file) {
            $relativePath = $file->getRelativePathname();
            $targetPath = $this->projectDir . '/' . $relativePath;
            
            if (strpos($relativePath, '.gitkeep') !== false) {
                continue;
            }
            
            try {
                $this->ensureDirectoryExists(dirname($targetPath));
                $this->fs->copy($file->getPathname(), $targetPath, true);
                $this->io->write(sprintf("  <info>Create:</info> %s", $relativePath));
                $count++;
            } catch (\Exception $e) {
                $errors[] = "Failed to copy {$relativePath}: " . $e->getMessage();
            }
        }
        
        if (!empty($errors)) {
            $this->io->writeError("\n<error>⚠️  Some files could not be copied:</error>");
            foreach ($errors as $error) {
                $this->io->writeError("  - " . $error);
            }
        }
        
        $this->io->write(sprintf("<info>✅ Template files copied (%d files)</info>", $count));
        
        if (count($errors) > 0) {
            throw new \RuntimeException("Template installation completed with errors");
        }
    }
    
    public function ensureDirectoryExists(string $directory): void
    {
        if (!$this->fs->exists($directory)) {
            $this->fs->mkdir($directory);
        }
    }
    
    public function removeFiles(array $paths): array
    {
        $removed = [];
        
        foreach ($paths as $path) {
            $fullPath = $this->projectDir . '/' . $path;
            if ($this->fs->exists($fullPath)) {
                $this->fs->remove($fullPath);
                $removed[] = $path;
            }
        }
        
        return $removed;
    }
    
    public function removeDirectories(array $directories): array
    {
        $removed = [];
        
        foreach ($directories as $dir) {
            $fullPath = $this->projectDir . '/' . $dir;
            if ($this->fs->exists($fullPath)) {
                $this->fs->remove($fullPath);
                $removed[] = $dir . '/';
            }
        }
        
        return $removed;
    }
    
    public function fileExists(string $path): bool
    {
        return $this->fs->exists($this->projectDir . '/' . $path);
    }
    
    public function createSymlink(string $target, string $link): void
    {
        $targetPath = $this->projectDir . '/' . $target;
        $linkPath = $this->projectDir . '/' . $link;
        
        if ($this->fs->exists($linkPath)) {
            $this->fs->remove($linkPath);
        }
        
        $this->fs->symlink($targetPath, $linkPath);
        $this->io->write(sprintf("  <info>Symlink:</info> %s -> %s", $link, $target));
    }
}