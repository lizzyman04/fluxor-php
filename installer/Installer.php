<?php
/**
 * Fluxor Installer - Main Entry Point
 * 
 * This script runs during composer create-project to set up the project.
 */

namespace FluxorInstaller;

use Composer\Script\Event;
use Composer\IO\IOInterface;

class Installer
{
    private IOInterface $io;
    private string $projectDir;
    private array $userInfo;
    private array $config;

    public static function postCreateProject(Event $event): void
    {
        $installer = new self($event);
        $installer->run();
    }

    private function __construct(Event $event)
    {
        $this->io = $event->getIO();
        $this->projectDir = getcwd();
    }

    private function run(): void
    {
        $this->welcome();

        try {
            $this->gatherUserInfo();
            $this->gatherConfiguration();
            $this->gatherFeatures();

            $this->setupProject();

            $this->success();

        } catch (\Exception $e) {
            $this->handleError($e);
        }
    }

    private function gatherUserInfo(): void
    {
        $collector = new UserInputCollector($this->io);
        $this->userInfo = $collector->collect();
    }

    private function gatherConfiguration(): void
    {
        $generator = new ConfigGenerator($this->io);
        $this->config = $generator->collect();
    }

    private function gatherFeatures(): void
    {
        $features = new FeatureSelector($this->io);
        $this->config = array_merge($this->config, $features->select());
    }

    private function setupProject(): void
    {
        $this->io->write("\n<info>📦 Setting up your Fluxor project...</info>");

        $env = new EnvGenerator($this->io, $this->projectDir);
        $env->generate($this->config);

        $composer = new ComposerUpdater($this->io, $this->projectDir);
        $composer->update($this->config, $this->userInfo);

        $cleanup = new FeatureCleanup($this->io, $this->projectDir);
        $cleanup->removeUnwantedFeatures($this->config);

        $storage = new StorageManager($this->io, $this->projectDir);
        $storage->createDirectories();

        $this->showNextSteps();
    }

    private function showNextSteps(): void
    {
        $steps = new NextSteps($this->io);
        $steps->show($this->config);
    }

    private function welcome(): void
    {
        $this->io->write([
            "\n<info>╔══════════════════════════════════════════════════════════════╗</info>",
            "<info>║                   Fluxor PHP Framework                        ║</info>",
            "<info>║                  Interactive Installation                     ║</info>",
            "<info>╚══════════════════════════════════════════════════════════════╝</info>",
            "\n<comment>We'll configure your project based on your needs.</comment>",
            "<comment>You can always add features later by creating the necessary files.</comment>\n"
        ]);
    }

    private function success(): void
    {
        $this->io->write([
            "\n<info>╔══════════════════════════════════════════════════════════════╗</info>",
            "<info>║                    Installation Complete!                     ║</info>",
            "<info>╚══════════════════════════════════════════════════════════════╝</info>",
            "\n<info>✅ Fluxor project created successfully! Happy coding! 🚀</info>\n"
        ]);
    }

    private function handleError(\Exception $e): void
    {
        $this->io->writeError("\n<error>❌ Installation failed: " . $e->getMessage() . "</error>\n");

        $this->io->writeError("<comment>Cleaning up...</comment>");
        $this->removeDirectory($this->projectDir . '/installer');

        exit(1);
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }

        rmdir($dir);
    }
}