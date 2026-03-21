<?php
/**
 * Config Generator - Handles .env generation
 */

namespace FluxorInstaller;

use Composer\IO\IOInterface;

class ConfigGenerator
{
    private IOInterface $io;
    private array $config;

    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    public function collect(): array
    {
        $this->io->write("\n<info>🔧 Application Configuration</info>");
        $this->io->write("----------------------------------------");

        $config = [];
        $config['app_name'] = $this->ask("Application name", 'Fluxor App');
        $config['app_env'] = $this->askChoice("Environment", ['development', 'production', 'testing'], 'development');
        $config['app_debug'] = $this->confirm("Enable debug mode?", true) ? 'true' : 'false';
        $config['app_port'] = $this->askPort();
        $config['timezone'] = $this->askTimezone();
        $config['app_key'] = $this->generateAppKey();

        return $config;
    }

    private function ask(string $question, string $default): string
    {
        return $this->io->ask($question . " [<comment>{$default}</comment>]: ", $default);
    }

    private function askChoice(string $question, array $choices, string $default): string
    {
        $choicesStr = implode('/', $choices);
        return $this->io->askAndValidate(
            $question . " [<comment>{$choicesStr}</comment>]: ",
            function ($value) use ($choices, $default) {
                $value = $value ?: $default;
                if (!in_array($value, $choices)) {
                    throw new \RuntimeException("Invalid choice. Choose from: " . implode(', ', $choices));
                }
                return $value;
            },
            null,
            $default
        );
    }

    private function confirm(string $question, bool $default): bool
    {
        $defaultText = $default ? "Y/n" : "y/N";
        return $this->io->askConfirmation($question . " [<comment>{$defaultText}</comment>]: ", $default);
    }

    private function askPort(): int
    {
        return (int) $this->io->askAndValidate(
            "Development server port [<comment>8000</comment>]: ",
            function ($value) {
                $value = $value ?: '8000';
                if (!is_numeric($value) || $value < 1024 || $value > 65535) {
                    throw new \RuntimeException('Please enter a valid port (1024-65535)');
                }
                return $value;
            },
            null,
            '8000'
        );
    }

    private function askTimezone(): string
    {
        return $this->io->askAndValidate(
            "Timezone [<comment>UTC</comment>]: ",
            function ($value) {
                $value = $value ?: 'UTC';
                if (!in_array($value, timezone_identifiers_list())) {
                    throw new \RuntimeException('Please enter a valid PHP timezone');
                }
                return $value;
            },
            null,
            'UTC'
        );
    }

    private function generateAppKey(): string
    {
        return 'base64:' . base64_encode(random_bytes(32));
    }
}