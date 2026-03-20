<?php
/**
 * Fluxor Installer
 * 
 * This script runs during composer create-project to set up the project.
 * It asks for template selection and environment configuration.
 */

namespace FluxorInstaller;

use Composer\Script\Event;
use Composer\IO\IOInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Installer
{
    private const TEMPLATES = [
        'default' => [
            'name' => 'Default (Minimal)',
            'description' => 'Simple starter with basic routing and clean structure',
            'has_auth' => false,
            'has_views' => true,
            'has_controllers' => false,
        ],
        'mvc' => [
            'name' => 'MVC Full Stack',
            'description' => 'Complete MVC with authentication, views, and controllers',
            'has_auth' => true,
            'has_views' => true,
            'has_controllers' => true,
        ],
        'api' => [
            'name' => 'RESTful API',
            'description' => 'Lightweight API with CORS and JSON responses',
            'has_auth' => false,
            'has_views' => false,
            'has_controllers' => false,
        ],
    ];

    private const NEXT_STEPS = [
        'default' => [
            'Run development server: php -S localhost:{port} -t public',
            'Visit: http://localhost:{port}',
            'Try the API: http://localhost:{port}/api/hello?name=Fluxor',
        ],
        'mvc' => [
            'Configure your database in the .env file',
            'Run development server: php -S localhost:{port} -t public',
            'Register at: http://localhost:{port}/auth/register',
            'Login with: admin@example.com / password',
        ],
        'api' => [
            'Run development server: php -S localhost:{port} -t public',
            'Test API: curl http://localhost:{port}/api/v1/users',
            'Add header: X-API-Key: test-key for authenticated endpoints',
        ],
    ];

    public static function postCreateProject(Event $event): void
    {
        $io = $event->getIO();
        $projectDir = getcwd();

        $io->write([
            "\n<info>✨ Welcome to Fluxor PHP Framework ✨</info>",
            "<comment>Let's configure your new project in just a few steps.</comment>\n"
        ]);

        try {
            $template = self::selectTemplate($io);
            $config = self::configureEnvironment($io, $template);
            self::copyTemplate($template, $projectDir, $io);
            self::generateEnvFile($projectDir, $config, $io);
            self::updateComposerJson($projectDir, $config, $io);
            self::cleanup($projectDir, $io);
            self::showNextSteps($template, $config, $io);

            $io->write("\n<info>✅ Fluxor project created successfully! Happy coding! 🚀</info>\n");

        } catch (\Exception $e) {
            $io->writeError("\n<error>❌ Installation failed: " . $e->getMessage() . "</error>\n");
            exit(1);
        }
    }

    private static function selectTemplate(IOInterface $io): string
    {
        $io->write("<info>📦 Available Templates:</info>");

        $choices = [];
        $index = 1;
        foreach (self::TEMPLATES as $key => $template) {
            $io->write(sprintf(
                "  [%d] <comment>%s</comment> - %s",
                $index,
                $template['name'],
                $template['description']
            ));
            $choices[$index] = $key;
            $index++;
        }

        $choice = $io->askAndValidate(
            "\nSelect a template by number [<comment>1</comment>]: ",
            function ($value) use ($choices) {
                $value = trim($value) ?: '1';
                if (!isset($choices[(int) $value])) {
                    throw new \RuntimeException('Please enter a valid number from the list.');
                }
                return $choices[(int) $value];
            },
            null,
            '1'
        );

        return $choice;
    }

    private static function configureEnvironment(IOInterface $io, string $template): array
    {
        $config = [];

        $io->write("\n<info>🔧 Environment Configuration</info>");
        $io->write("----------------------------------------");

        $config['app_name'] = $io->ask(
            "Application name [<comment>Fluxor App</comment>]: ",
            'Fluxor App'
        );

        $config['app_env'] = $io->askAndValidate(
            "Environment [<comment>development</comment>]: ",
            function ($value) {
                $value = $value ?: 'development';
                if (!in_array($value, ['development', 'production', 'testing'])) {
                    throw new \RuntimeException('Environment must be development, production, or testing');
                }
                return $value;
            },
            null,
            'development'
        );

        $config['app_debug'] = $io->askConfirmation(
            "Enable debug mode? [<comment>Y/n</comment>] ",
            true
        ) ? 'true' : 'false';

        $config['app_port'] = $io->askAndValidate(
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

        $config['timezone'] = $io->ask(
            "Timezone [<comment>UTC</comment>]: ",
            'UTC'
        );

        $config['app_key'] = 'base64:' . base64_encode(random_bytes(32));

        if (self::TEMPLATES[$template]['has_auth'] || self::TEMPLATES[$template]['has_controllers']) {
            $io->write("\n<info>🗄️  Database Configuration</info>");

            $config['db_connection'] = $io->askAndValidate(
                "Database connection [<comment>mysql</comment>]: ",
                function ($value) {
                    $value = $value ?: 'mysql';
                    if (!in_array($value, ['mysql', 'pgsql', 'sqlite', 'none'])) {
                        throw new \RuntimeException('Please choose mysql, pgsql, sqlite, or none');
                    }
                    return $value;
                },
                null,
                'mysql'
            );

            if ($config['db_connection'] !== 'none' && $config['db_connection'] !== 'sqlite') {
                $config['db_host'] = $io->ask("Database host [<comment>127.0.0.1</comment>]: ", '127.0.0.1');
                $config['db_port'] = $io->ask("Database port [<comment>3306</comment>]: ", '3306');
                $config['db_database'] = $io->ask("Database name: ");
                $config['db_username'] = $io->ask("Database username: ");
                $config['db_password'] = $io->ask("Database password: ");
            } elseif ($config['db_connection'] === 'sqlite') {
                $config['db_database'] = $io->ask("Database path [<comment>database/fluxor.sqlite</comment>]: ", 'database/fluxor.sqlite');
            }
        }

        return $config;
    }

    private static function copyTemplate(string $template, string $projectDir, IOInterface $io): void
    {
        $source = __DIR__ . '/templates/' . $template;

        if (!is_dir($source)) {
            throw new \RuntimeException("Template source not found: {$source}");
        }

        $io->write("\n<info>📂 Installing '{$template}' template...</info>");

        $finder = new Finder();
        $finder->files()->in($source);

        $fs = new Filesystem();
        $count = 0;

        foreach ($finder as $file) {
            $relativePath = $file->getRelativePathname();
            $targetPath = $projectDir . '/' . $relativePath;

            if (strpos($relativePath, '.gitkeep') !== false) {
                continue;
            }

            $fs->mkdir(dirname($targetPath));
            $fs->copy($file->getPathname(), $targetPath);

            $io->write(sprintf("  <info>Create:</info> %s", $relativePath));
            $count++;
        }

        $io->write(sprintf("<info>✅ Template files copied (%d files)</info>", $count));
    }

    private static function generateEnvFile(string $projectDir, array $config, IOInterface $io): void
    {
        $envContent = "# Application\n";
        $envContent .= "APP_NAME=\"{$config['app_name']}\"\n";
        $envContent .= "APP_ENV={$config['app_env']}\n";
        $envContent .= "APP_DEBUG={$config['app_debug']}\n";
        $envContent .= "APP_PORT={$config['app_port']}\n";
        $envContent .= "APP_TIMEZONE={$config['timezone']}\n";
        $envContent .= "APP_KEY={$config['app_key']}\n\n";

        if (isset($config['db_connection'])) {
            $envContent .= "# Database\n";
            $envContent .= "DB_CONNECTION={$config['db_connection']}\n";

            if ($config['db_connection'] === 'sqlite') {
                $envContent .= "DB_DATABASE={$config['db_database']}\n";
            } elseif ($config['db_connection'] !== 'none') {
                $envContent .= "DB_HOST={$config['db_host']}\n";
                $envContent .= "DB_PORT={$config['db_port']}\n";
                $envContent .= "DB_DATABASE={$config['db_database']}\n";
                $envContent .= "DB_USERNAME={$config['db_username']}\n";
                $envContent .= "DB_PASSWORD={$config['db_password']}\n";
            }
        }

        file_put_contents($projectDir . '/.env', $envContent);
        $io->write("\n<info>✅ .env file generated</info>");
    }

    private static function updateComposerJson(string $projectDir, array $config, IOInterface $io): void
    {
        $composerFile = $projectDir . '/composer.json';

        if (!file_exists($composerFile)) {
            $io->writeError("<error>composer.json not found</error>");
            return;
        }

        $composer = json_decode(file_get_contents($composerFile), true);

        $name = strtolower(preg_replace('/[^a-z0-9-]/', '-', $config['app_name']));
        $composer['name'] = 'user/' . $name;

        if (isset($composer['scripts']['post-create-project-cmd'])) {
            $composer['scripts']['post-create-project-cmd'] = [];
        }

        $composer['scripts']['dev'] = "php -S localhost:{$config['app_port']} -t public";
        $composer['scripts']['test'] = "phpunit";

        file_put_contents($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");

        $io->write("<info>✅ composer.json updated</info>");
    }

    private static function cleanup(string $projectDir, IOInterface $io): void
    {
        $fs = new Filesystem();
        $removed = [];

        $toRemove = [
            'Installer.php',
            'templates',
            'mkdocs.yml',
            'README.docs.md',
            'package.json',
            'package-lock.json',
            '.github/workflows/deploy.yml',
            '.github/workflows/deploy-docs.yml',
        ];

        $dirsToRemove = [
            'docs',
            '.github',
        ];

        foreach ($toRemove as $item) {
            $path = $projectDir . '/' . $item;
            if ($fs->exists($path)) {
                $fs->remove($path);
                $removed[] = $item;
            }
        }

        foreach ($dirsToRemove as $dir) {
            $path = $projectDir . '/' . $dir;
            if ($fs->exists($path)) {
                $fs->remove($path);
                $removed[] = $dir . '/';
            }
        }

        if (!empty($removed)) {
            $io->write("\n<info>🧹 Cleaned up installation files</info>");
        }
    }

    private static function showNextSteps(string $template, array $config, IOInterface $io): void
    {
        $io->write("\n<info>🎯 Next Steps:</info>");
        $io->write("----------------------------------------");

        $steps = self::NEXT_STEPS[$template] ?? self::NEXT_STEPS['default'];

        foreach ($steps as $index => $step) {
            $step = str_replace('{port}', $config['app_port'], $step);
            $io->write(sprintf("  %d. %s", $index + 1, $step));
        }

        $io->write("\n<comment>📚 Documentation: https://lizzyman04.github.io/fluxor-php</comment>");
    }
}