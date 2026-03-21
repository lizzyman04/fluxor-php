<?php
/**
 * Feature Selector - Handles feature selection questions
 */

namespace FluxorInstaller;

use Composer\IO\IOInterface;

class FeatureSelector
{
    private IOInterface $io;

    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    public function select(): array
    {
        $features = [];

        $this->io->write("\n<info>📦 Feature Selection</info>");
        $this->io->write("----------------------------------------");

        $features['auth'] = $this->confirm("Include authentication system?", true);
        $features['mailer'] = $this->confirm("Include mailer support?", false);
        $features['uploader'] = $this->confirm("Include file upload support?", false);

        if ($features['auth']) {
            $this->io->write("\n<info>🔐 Authentication Configuration</info>");
            $features['admin_email'] = $this->ask("Admin email", 'admin@example.com');
            $features['admin_password'] = $this->askSecret("Admin password", 'admin123');
        }

        if ($features['mailer']) {
            $this->io->write("\n<info>📧 Mailer Configuration</info>");
            $features['smtp_host'] = $this->ask("SMTP host", 'smtp.gmail.com');
            $features['smtp_port'] = $this->ask("SMTP port", '587');
            $features['smtp_username'] = $this->ask("SMTP username", '');
            $features['smtp_password'] = $this->askSecret("SMTP password", '');
        }

        return $features;
    }

    private function confirm(string $question, bool $default): bool
    {
        $defaultText = $default ? "Y/n" : "y/N";
        return $this->io->askConfirmation($question . " [<comment>{$defaultText}</comment>]: ", $default);
    }

    private function ask(string $question, string $default): string
    {
        return $this->io->ask($question . " [<comment>{$default}</comment>]: ", $default);
    }

    private function askSecret(string $question, string $default): string
    {
        $value = $this->io->askAndHideAnswer($question . " [<comment>{$default}</comment>]: ");
        return $value ?: $default;
    }
}