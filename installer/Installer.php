<?php

namespace FluxorInstaller;

use Composer\Script\Event;

class Installer
{
    private Event $event;
    private array $userConfig = [];
    private array $appConfig = [];
    private array $features = [];
    private string $docsPath = 'docs';

    public static function postCreateProject(Event $event): void
    {
        $installer = new self($event);
        $installer->run();
    }

    private function __construct(Event $event)
    {
        $this->event = $event;
    }

    private function run(): void
    {
        $this->welcome();

        $config = new Config($this->event->getIO());
        $this->userConfig = $config->collectUserInfo();
        $this->appConfig = $config->collectAppConfig();
        $this->features = $config->collectFeatures();

        if ($this->features['docs']) {
            $this->docsPath = $config->collectDocsPath();
        }

        $setup = new Setup($this->event->getIO(), getcwd());
        $setup->run($this->userConfig, $this->appConfig, $this->features, $this->docsPath);

        $cleanup = new Cleanup($this->event->getIO(), getcwd());
        $cleanup->run($this->features);

        if ($this->features['docs'] && $this->docsPath !== 'docs') {
            $old = getcwd() . '/docs';
            $new = getcwd() . '/' . $this->docsPath;
            if (is_dir($old)) {
                rename($old, $new);
                $this->event->getIO()->write("  - Renamed docs/ to {$this->docsPath}/");
            }
        }

        $this->success();
    }

    private function welcome(): void
    {
        $io = $this->event->getIO();
        $io->write([
            "\n<info>╔══════════════════════════════════════════════════════════════╗</info>",
            "<info>║                   Fluxor PHP Framework                       ║</info>",
            "<info>║                  Interactive Installation                    ║</info>",
            "<info>╚══════════════════════════════════════════════════════════════╝</info>\n"
        ]);
    }

    private function success(): void
    {
        $io = $this->event->getIO();
        $port = $this->appConfig['app_port'] ?? 8000;
        $io->write([
            "\n<info>╔══════════════════════════════════════════════════════════════╗</info>",
            "<info>║                    Installation Complete!                    ║</info>",
            "<info>╚══════════════════════════════════════════════════════════════╝</info>",
            "\n<info>✅ Fluxor project created successfully! Happy coding! 🚀</info>\n",
            "",
            "<comment>Next Steps:</comment>",
            "  1. cd " . basename(getcwd()),
            "  2. Start the development server: composer dev",
            "  3. Visit: http://localhost:{$port}",
            "",
            "<info>Happy coding!</info>\n"
        ]);
    }
}