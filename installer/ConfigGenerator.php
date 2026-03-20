<?php
/**
 * Config Generator - Handles .env and composer.json generation
 */

namespace FluxorInstaller;

use Composer\IO\IOInterface;

class ConfigGenerator
{
    private IOInterface $io;

    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    public function collect(string $template): array
    {
        $config = [];

        $this->io->write("\n<info>🔧 Environment Configuration</info>");
        $this->io->write("----------------------------------------");

        $config['app_name'] = $this->askAppName();
        $config['app_env'] = $this->askEnvironment();
        $config['app_debug'] = $this->askDebugMode();
        $config['app_port'] = $this->askPort();
        $config['timezone'] = $this->askTimezone();
        $config['app_key'] = $this->generateAppKey();

        $templateInfo = TemplateManager::getTemplateInfo($template);

        if ($templateInfo['requires_db']) {
            $config = array_merge($config, $this->configureDatabase());
        }

        return $config;
    }

    private function askAppName(): string
    {
        return $this->io->ask(
            "Application name [<comment>Fluxor App</comment>]: ",
            'Fluxor App'
        );
    }

    private function askEnvironment(): string
    {
        return $this->io->askAndValidate(
            "Environment [<comment>development</comment>]: ",
            function ($value) {
                $value = $value ?: 'development';
                if (!in_array($value, ['development', 'production', 'testing', 'staging'])) {
                    throw new \RuntimeException('Environment must be development, production, testing, or staging');
                }
                return $value;
            },
            null,
            'development'
        );
    }

    private function askDebugMode(): string
    {
        $debug = $this->io->askConfirmation(
            "Enable debug mode? [<comment>Y/n</comment>] ",
            true
        );
        return $debug ? 'true' : 'false';
    }

    private function askPort(): int
    {
        return $this->io->askAndValidate(
            "Development server port [<comment>8000</comment>]: ",
            function ($value) {
                $value = $value ?: '8000';
                if (!is_numeric($value) || $value < 1024 || $value > 65535) {
                    throw new \RuntimeException('Please enter a valid port (1024-65535)');
                }
                return (int) $value;
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
                    throw new \RuntimeException('Please enter a valid PHP timezone (e.g., Africa/Maputo, America/Sao_Paulo)');
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

    private function configureDatabase(): array
    {
        $config = [];

        $this->io->write("\n<info>🗄️  Database Configuration</info>");

        $config['db_connection'] = $this->io->askAndValidate(
            "Database connection [<comment>mysql</comment>]: ",
            function ($value) {
                $value = $value ?: 'mysql';
                if (!in_array($value, ['mysql', 'pgsql', 'sqlite', 'mongodb', 'none'])) {
                    throw new \RuntimeException('Please choose mysql, pgsql, sqlite, mongodb, or none');
                }
                return $value;
            },
            null,
            'mysql'
        );

        if ($config['db_connection'] === 'none') {
            return $config;
        }

        if ($config['db_connection'] === 'sqlite') {
            $config['db_database'] = $this->io->ask(
                "Database path [<comment>database/fluxor.sqlite</comment>]: ",
                'database/fluxor.sqlite'
            );
            return $config;
        }

        $config['db_host'] = $this->io->ask("Database host [<comment>127.0.0.1</comment>]: ", '127.0.0.1');
        $config['db_port'] = $this->io->ask("Database port [<comment>3306</comment>]: ", '3306');
        $config['db_database'] = $this->io->ask("Database name: ");
        $config['db_username'] = $this->io->ask("Database username: ");
        $config['db_password'] = $this->io->ask("Database password: ");

        return $config;
    }

    public function generateEnvFile(string $projectDir, array $config): void
    {
        $envContent = $this->buildEnvContent($config);

        $envPath = $projectDir . '/.env';
        file_put_contents($envPath, $envContent);

        $envExamplePath = $projectDir . '/.env.example';
        $envExampleContent = $this->buildEnvExampleContent($config);
        file_put_contents($envExamplePath, $envExampleContent);

        $this->io->write("\n<info>✅ .env and .env.example files generated</info>");
    }

    private function buildEnvContent(array $config): string
    {
        $content = "# Application\n";
        $content .= "APP_NAME=\"{$config['app_name']}\"\n";
        $content .= "APP_ENV={$config['app_env']}\n";
        $content .= "APP_DEBUG={$config['app_debug']}\n";
        $content .= "APP_PORT={$config['app_port']}\n";
        $content .= "APP_TIMEZONE={$config['timezone']}\n";
        $content .= "APP_KEY={$config['app_key']}\n\n";

        if (isset($config['db_connection'])) {
            $content .= "# Database\n";
            $content .= "DB_CONNECTION={$config['db_connection']}\n";

            if ($config['db_connection'] === 'sqlite') {
                $content .= "DB_DATABASE={$config['db_database']}\n";
            } elseif ($config['db_connection'] !== 'none') {
                $content .= "DB_HOST={$config['db_host']}\n";
                $content .= "DB_PORT={$config['db_port']}\n";
                $content .= "DB_DATABASE={$config['db_database']}\n";
                $content .= "DB_USERNAME={$config['db_username']}\n";
                $content .= "DB_PASSWORD={$config['db_password']}\n";
            }
        }

        return $content;
    }

    private function buildEnvExampleContent(array $config): string
    {
        $content = "# Application\n";
        $content .= "APP_NAME=\"{$config['app_name']}\"\n";
        $content .= "APP_ENV=production\n";
        $content .= "APP_DEBUG=false\n";
        $content .= "APP_PORT=80\n";
        $content .= "APP_TIMEZONE=UTC\n";
        $content .= "# APP_KEY=base64:... (generate with: php artisan key:generate)\n\n";

        if (isset($config['db_connection'])) {
            $content .= "# Database\n";
            $content .= "DB_CONNECTION={$config['db_connection']}\n";

            if ($config['db_connection'] === 'sqlite') {
                $content .= "# DB_DATABASE=/path/to/database.sqlite\n";
            } elseif ($config['db_connection'] !== 'none') {
                $content .= "DB_HOST=127.0.0.1\n";
                $content .= "DB_PORT=3306\n";
                $content .= "# DB_DATABASE=your_database\n";
                $content .= "# DB_USERNAME=your_username\n";
                $content .= "# DB_PASSWORD=your_password\n";
            }
        }

        return $content;
    }

    public function updateComposerJson(string $projectDir, array $config, array $userInfo): void
    {
        $composerFile = $projectDir . '/composer.json';

        if (!file_exists($composerFile)) {
            throw new \RuntimeException("composer.json not found at: {$composerFile}");
        }

        $composer = json_decode(file_get_contents($composerFile), true);

        $packageName = $this->generatePackageName($config['app_name']);
        $composer['name'] = $userInfo['vendor'] . '/' . $packageName;

        $composer['authors'] = [
            [
                'name' => $userInfo['name'],
                'email' => $userInfo['email'],
                'homepage' => $userInfo['homepage'],
                'role' => 'Developer'
            ]
        ];

        $composer['description'] = $config['app_name'] . ' - Fluxor PHP Framework Application';

        if (isset($composer['scripts']['post-create-project-cmd'])) {
            unset($composer['scripts']['post-create-project-cmd']);
        }

        $composer['scripts']['dev'] = "php -S localhost:{$config['app_port']} -t public";
        $composer['scripts']['test'] = "phpunit";
        $composer['scripts']['serve'] = "composer dev";
        $composer['scripts']['prod'] = "php -S 0.0.0.0:{$config['app_port']} -t public";

        if (isset($composer['autoload']['classmap'])) {
            $composer['autoload']['classmap'] = array_values(array_filter(
                $composer['autoload']['classmap'],
                fn($item) => !in_array($item, ['Installer.php', 'installer/'])
            ));

            if (empty($composer['autoload']['classmap'])) {
                unset($composer['autoload']['classmap']);
            }
        }

        file_put_contents(
            $composerFile,
            json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
        );

        $this->io->write("<info>✅ composer.json updated</info>");
        $this->io->write(sprintf("  <comment>Package name:</comment> %s/%s", $userInfo['vendor'], $packageName));
    }

    private function generatePackageName(string $appName): string
    {
        $name = strtolower($appName);
        $name = preg_replace('/[^a-z0-9.-]/', '-', $name);
        $name = preg_replace('/-+/', '-', $name);
        $name = trim($name, '-');

        return $name ?: 'fluxor-app';
    }
}