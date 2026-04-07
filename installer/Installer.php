<?php

namespace FluxorInstaller;

use Composer\Script\Event;

class Installer
{
    private Event $event;
    private array $userConfig = [];
    private array $appConfig = [];
    private array $features = [];

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

        $setup = new Setup($this->event->getIO(), getcwd());
        $setup->run($this->userConfig, $this->appConfig, $this->features);

        $cleanup = new Cleanup($this->event->getIO(), getcwd());
        $cleanup->run($this->features);

        $this->success();
    }

    private function welcome(): void
    {
        $io = $this->event->getIO();
        $io->write([
            "\n<info>╔══════════════════════════════════════════════════════════════╗</info>",
            "<info>║                   Fluxor PHP Framework                        ║</info>",
            "<info>║                  Interactive Installation                     ║</info>",
            "<info>╚══════════════════════════════════════════════════════════════╝</info>\n"
        ]);
    }

    private function success(): void
    {
        $io = $this->event->getIO();
        $io->write([
            "\n<info>╔══════════════════════════════════════════════════════════════╗</info>",
            "<info>║                    Installation Complete!                     ║</info>",
            "<info>╚══════════════════════════════════════════════════════════════╝</info>",
            "\n<info>✅ Fluxor project created successfully! Happy coding! 🚀</info>\n"
        ]);
    }
}