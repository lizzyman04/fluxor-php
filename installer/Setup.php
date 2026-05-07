<?php

namespace FluxorInstaller;

use Composer\IO\IOInterface;

class Setup
{
    private IOInterface $io;
    private string $projectDir;

    public function __construct(IOInterface $io, string $projectDir)
    {
        $this->io = $io;
        $this->projectDir = $projectDir;
    }

    public function run(array $userConfig, array $appConfig, array $features): void
    {
        $this->io->write("\nSetting up your Fluxor project...");

        $this->updateComposer($userConfig, $appConfig);
        $this->createEnvFile($appConfig);
        $this->createStorageDirectories();
        $this->updateReadme($appConfig, $features);

        $this->io->write("  - Project configured successfully");
    }

    private function updateComposer(array $userConfig, array $appConfig): void
    {
        $composerFile = $this->projectDir . '/composer.json';
        $composer = json_decode(file_get_contents($composerFile), true);

        $packageName = $this->slugify($appConfig['app_name']);
        $composer['name'] = $userConfig['vendor'] . '/' . $packageName;
        $composer['authors'] = [
            [
                'name' => $userConfig['name'],
                'email' => $userConfig['email'],
                'homepage' => $userConfig['homepage'],
                'role' => 'Developer'
            ]
        ];
        $composer['description'] = $appConfig['app_name'] . ' - Fluxor PHP Framework Application';

        unset($composer['scripts']['post-create-project-cmd']);
        $composer['scripts']['dev'] = "php -S localhost:{$appConfig['app_port']} -t public";
        $composer['scripts']['clear-router-cache'] = "@php vendor/bin/fluxor cache:clear-router";

        if (isset($composer['autoload']['classmap'])) {
            unset($composer['autoload']['classmap']);
        }

        file_put_contents($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
        $this->io->write("  - composer.json updated");
    }

    private function createEnvFile(array $appConfig): void
    {
        $content = "# Application Configuration\n";
        $content .= "APP_NAME=\"{$appConfig['app_name']}\"\n";
        $content .= "APP_ENV={$appConfig['app_env']}\n";
        $content .= "APP_DEBUG=" . ($appConfig['app_debug'] ? 'true' : 'false') . "\n";
        $content .= "APP_PORT={$appConfig['app_port']}\n";
        $content .= "APP_TIMEZONE={$appConfig['timezone']}\n";
        $content .= "APP_KEY={$appConfig['app_key']}\n\n";
        $content .= "# Database, Mail, Upload features available in fluxor-mvc-template\n";

        file_put_contents($this->projectDir . '/.env', $content);
        $this->io->write("  - .env file created");
    }

    private function createStorageDirectories(): void
    {
        $dirs = ['storage/cache', 'storage/logs', 'storage/sessions'];
        foreach ($dirs as $dir) {
            $path = $this->projectDir . '/' . $dir;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
                file_put_contents($path . '/.gitkeep', '');
            }
        }
        $this->io->write("  - Storage directories created");
    }

    private function updateReadme(array $appConfig, array $features): void
    {
        $docsNote = $features['docs']
            ? "Full documentation available in the `docs/` directory.\n."
            : "Full documentation: https://lizzyman04.github.io/fluxor-php";

        $readme = "# {$appConfig['app_name']}\n\n";
        $readme .= "## Quick Start\n\n";
        $readme .= "```bash\ncomposer dev\n```\n\n";
        $readme .= "Visit `http://localhost:{$appConfig['app_port']}`\n\n";
        $readme .= "## Project Structure\n\n";
        $readme .= "```\napp/router/     # File-based routes\nsrc/Views/      # View templates\npublic/assets/  # Static assets\nstorage/        # Logs, cache, sessions\n```\n\n";
        $readme .= $docsNote . "\n\n";
        $readme .= "## Need More Features?\n\n";
        $readme .= "For authentication, mailer, and uploader:\n\n";
        $readme .= "Visit https://github.com/lizzyman04/fluxor-mvc-template\n\n";
        $readme .= "## License\n\nMIT\n";

        file_put_contents($this->projectDir . '/README.md', $readme);
        $this->io->write("  - README.md updated");
    }

    private function slugify(string $text): string
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9-]/', '-', $text);
        return trim(preg_replace('/-+/', '-', $text), '-');
    }
}