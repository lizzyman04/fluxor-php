<?php

namespace FluxorInstaller;

use Composer\IO\IOInterface;

class Config
{
    private IOInterface $io;

    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    public function collectUserInfo(): array
    {
        $this->io->write("\nAuthor Information");
        $this->io->write("----------------------------------------");

        return [
            'name' => $this->ask("Your name", 'Fluxor User'),
            'email' => $this->askEmail(),
            'homepage' => $this->askUrl("Your website", 'https://example.com'),
            'vendor' => $this->askVendor(),
        ];
    }

    public function collectAppConfig(): array
    {
        $this->io->write("\nApplication Configuration");
        $this->io->write("----------------------------------------");

        return [
            'app_name' => $this->ask("Application name", 'Fluxor App'),
            'app_env' => $this->choice("Environment", ['development', 'production', 'testing'], 'development'),
            'app_debug' => $this->confirm("Enable debug mode?", true),
            'app_port' => $this->askPort(),
            'timezone' => $this->askTimezone(),
            'app_key' => $this->generateKey(),
        ];
    }

    public function collectFeatures(): array
    {
        $this->io->write("\nFeature Selection");
        $this->io->write("----------------------------------------");

        $includeDocs = $this->confirm("Include project documentation (docs/) ?", true);

        return [
            'docs' => $includeDocs,
        ];
    }

    private function ask(string $question, string $default): string
    {
        return $this->io->ask($question . " [<comment>{$default}</comment>]: ", $default);
    }

    private function askEmail(): string
    {
        return $this->io->askAndValidate(
            "Your email [<comment>user@example.com</comment>]: ",
            fn($v) => filter_var($v ?: 'user@example.com', FILTER_VALIDATE_EMAIL) ?: throw new \RuntimeException('Invalid email'),
            null,
            'user@example.com'
        );
    }

    private function askUrl(string $question, string $default): string
    {
        return $this->io->askAndValidate(
            "{$question} [<comment>{$default}</comment>]: ",
            fn($v) => filter_var($v ?: $default, FILTER_VALIDATE_URL) ?: throw new \RuntimeException('Invalid URL'),
            null,
            $default
        );
    }

    private function askVendor(): string
    {
        return $this->io->askAndValidate(
            "Vendor name [<comment>yourname</comment>]: ",
            fn($v) => preg_match('/^[a-z0-9][a-z0-9-]*[a-z0-9]$/i', $v ?: 'yourname') ? strtolower($v) : throw new \RuntimeException('Invalid vendor name'),
            null,
            'yourname'
        );
    }

    private function choice(string $question, array $options, string $default): string
    {
        $optionsStr = implode('/', $options);
        return $this->io->askAndValidate(
            $question . " [<comment>{$optionsStr}</comment>]: ",
            fn($v) => in_array($v ?: $default, $options) ? ($v ?: $default) : throw new \RuntimeException('Invalid choice'),
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
            fn($v) => (($v = $v ?: '8000') && is_numeric($v) && $v >= 1024 && $v <= 65535) ? $v : throw new \RuntimeException('Invalid port (1024-65535)'),
            null,
            '8000'
        );
    }

    private function askTimezone(): string
    {
        return $this->io->askAndValidate(
            "Timezone [<comment>UTC</comment>]: ",
            fn($v) => in_array($v ?: 'UTC', timezone_identifiers_list()) ? ($v ?: 'UTC') : throw new \RuntimeException('Invalid timezone'),
            null,
            'UTC'
        );
    }

    private function generateKey(): string
    {
        return 'base64:' . base64_encode(random_bytes(32));
    }
}