<?php
/**
 * Env Generator - Creates .env and .env.example files
 */

namespace FluxorInstaller;

use Composer\IO\IOInterface;

class EnvGenerator
{
    private IOInterface $io;
    private string $projectDir;

    public function __construct(IOInterface $io, string $projectDir)
    {
        $this->io = $io;
        $this->projectDir = $projectDir;
    }

    public function generate(array $config): void
    {
        $this->generateEnvFile($config);
        $this->generateEnvExample($config);
        $this->io->write("  ✓ .env and .env.example files generated");
    }

    private function generateEnvFile(array $config): void
    {
        $content = $this->buildEnvContent($config);
        file_put_contents($this->projectDir . '/.env', $content);
    }

    private function generateEnvExample(array $config): void
    {
        $content = $this->buildEnvExampleContent($config);
        file_put_contents($this->projectDir . '/.env.example', $content);
    }

    private function buildEnvContent(array $config): string
    {
        $content = "# Application Configuration\n";
        $content .= "APP_NAME=\"{$config['app_name']}\"\n";
        $content .= "APP_ENV={$config['app_env']}\n";
        $content .= "APP_DEBUG={$config['app_debug']}\n";
        $content .= "APP_PORT={$config['app_port']}\n";
        $content .= "APP_TIMEZONE={$config['timezone']}\n";
        $content .= "APP_KEY={$config['app_key']}\n\n";

        if ($config['auth'] ?? false) {
            $content .= "# Authentication\n";
            $content .= "AUTH_SECRET_KEY=\"{$this->generateAppKey()}\"\n";
            $content .= "AUTH_SESSION_EXPIRY=1800\n";
            $content .= "AUTH_REMEMBER_EXPIRY=2592000\n\n";
        }

        if ($config['mailer'] ?? false) {
            $content .= "# Mail Configuration\n";
            $content .= "MAIL_HOST={$config['smtp_host']}\n";
            $content .= "MAIL_PORT={$config['smtp_port']}\n";
            $content .= "MAIL_USERNAME={$config['smtp_username']}\n";
            $content .= "MAIL_PASSWORD={$config['smtp_password']}\n";
            $content .= "MAIL_FROM_ADDRESS=noreply@localhost\n";
            $content .= "MAIL_FROM_NAME=\"{$config['app_name']}\"\n\n";
        }

        if ($config['uploader'] ?? false) {
            $content .= "# Upload Configuration\n";
            $content .= "UPLOAD_MAX_SIZE=5242880\n";
            $content .= "UPLOAD_ALLOWED_TYPES=jpg,jpeg,png,gif,webp,pdf,doc,docx\n\n";
        }

        return $content;
    }

    private function buildEnvExampleContent(array $config): string
    {
        $content = "# Application Configuration\n";
        $content .= "APP_NAME=\"{$config['app_name']}\"\n";
        $content .= "APP_ENV=production\n";
        $content .= "APP_DEBUG=false\n";
        $content .= "APP_PORT=80\n";
        $content .= "APP_TIMEZONE=UTC\n";
        $content .= "# APP_KEY=base64:... (generate with: php artisan key:generate)\n\n";

        if ($config['auth'] ?? false) {
            $content .= "# Authentication\n";
            $content .= "# AUTH_SECRET_KEY=\n";
            $content .= "# AUTH_SESSION_EXPIRY=1800\n";
            $content .= "# AUTH_REMEMBER_EXPIRY=2592000\n\n";
        }

        if ($config['mailer'] ?? false) {
            $content .= "# Mail Configuration\n";
            $content .= "# MAIL_HOST=smtp.gmail.com\n";
            $content .= "# MAIL_PORT=587\n";
            $content .= "# MAIL_USERNAME=\n";
            $content .= "# MAIL_PASSWORD=\n";
            $content .= "# MAIL_FROM_ADDRESS=noreply@example.com\n";
            $content .= "# MAIL_FROM_NAME=\"{$config['app_name']}\"\n\n";
        }

        if ($config['uploader'] ?? false) {
            $content .= "# Upload Configuration\n";
            $content .= "# UPLOAD_MAX_SIZE=5242880\n";
            $content .= "# UPLOAD_ALLOWED_TYPES=jpg,jpeg,png,gif,webp,pdf,doc,docx\n\n";
        }

        return $content;
    }

    private function generateAppKey(): string
    {
        return 'base64:' . base64_encode(random_bytes(32));
    }
}