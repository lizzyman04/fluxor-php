<?php
/**
 * Composer Updater - Updates composer.json with project information
 */

namespace FluxorInstaller;

use Composer\IO\IOInterface;

class ComposerUpdater
{
    private IOInterface $io;
    private string $projectDir;

    public function __construct(IOInterface $io, string $projectDir)
    {
        $this->io = $io;
        $this->projectDir = $projectDir;
    }

    public function update(array $config, array $userInfo): void
    {
        $composerFile = $this->projectDir . '/composer.json';
        if (!file_exists($composerFile)) {
            return;
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
        $composer['scripts']['prod'] = "php -S 0.0.0.0:{$config['app_port']} -t public";
        $composer['scripts']['test'] = "phpunit";

        if (isset($composer['autoload']['classmap'])) {
            $composer['autoload']['classmap'] = array_values(array_filter(
                $composer['autoload']['classmap'],
                fn($item) => $item !== 'installer/'
            ));

            if (empty($composer['autoload']['classmap'])) {
                unset($composer['autoload']['classmap']);
            }
        }

        file_put_contents(
            $composerFile,
            json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
        );

        $this->io->write("  ✓ composer.json updated");
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