<?php
/**
 * Fluxor Installer - Main Entry Point
 * 
 * This script runs during composer create-project to set up the project.
 * It orchestrates the entire installation process.
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
    private string $template;

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
        $this->displayWelcome();
        
        try {
            $collector = new UserInputCollector($this->io);
            $this->userInfo = $collector->collect();
            
            $templateManager = new TemplateManager($this->io);
            $this->template = $templateManager->selectTemplate();
            
            $configGenerator = new ConfigGenerator($this->io);
            $this->config = $configGenerator->collect($this->template);
            
            $fileManager = new FileManager($this->io, $this->projectDir);
            $fileManager->copyTemplate($this->template);
            
            $configGenerator->generateEnvFile($this->projectDir, $this->config);
            $configGenerator->updateComposerJson($this->projectDir, $this->config, $this->userInfo);
            
            $cleanup = new CleanupManager($this->io, $this->projectDir);
            $cleanup->clean();
            
            $this->showNextSteps();
            $this->displaySuccess();
            
        } catch (\Exception $e) {
            $this->handleError($e);
        }
    }

    private function displayWelcome(): void
    {
        $this->io->write([
            "\n<info>✨ Welcome to Fluxor PHP Framework ✨</info>",
            "<comment>Let's configure your new project in just a few steps.</comment>\n"
        ]);
    }

    private function showNextSteps(): void
    {
        $steps = TemplateManager::getNextSteps($this->template);
        
        $this->io->write("\n<info>🎯 Next Steps:</info>");
        $this->io->write("----------------------------------------");
        
        foreach ($steps as $index => $step) {
            $step = str_replace('{port}', $this->config['app_port'], $step);
            $this->io->write(sprintf("  %d. %s", $index + 1, $step));
        }
        
        $this->io->write("\n<comment>📚 Documentation: https://lizzyman04.github.io/fluxor-php</comment>");
    }

    private function displaySuccess(): void
    {
        $this->io->write("\n<info>✅ Fluxor project created successfully! Happy coding! 🚀</info>\n");
    }

    private function handleError(\Exception $e): void
    {
        $this->io->writeError("\n<error>❌ Installation failed: " . $e->getMessage() . "</error>\n");
        
        try {
            $cleanup = new CleanupManager($this->io, $this->projectDir);
            $cleanup->emergencyCleanup();
            $this->io->writeError("<comment>Emergency cleanup performed.</comment>");
        } catch (\Exception $cleanupError) {
        }
        
        exit(1);
    }
}