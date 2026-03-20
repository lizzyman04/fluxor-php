<?php
/**
 * Template Manager - Handles template selection and metadata
 */

namespace FluxorInstaller;

use Composer\IO\IOInterface;

class TemplateManager
{
    private IOInterface $io;

    private const TEMPLATES = [
        'default' => [
            'name' => 'Default (Minimal)',
            'description' => 'Simple starter with basic routing and clean structure',
            'has_auth' => false,
            'has_views' => true,
            'has_controllers' => false,
            'api_endpoints' => 1,
            'requires_db' => false,
        ],
        'mvc' => [
            'name' => 'MVC Full Stack',
            'description' => 'Complete MVC with authentication, views, and controllers',
            'has_auth' => true,
            'has_views' => true,
            'has_controllers' => true,
            'api_endpoints' => 0,
            'requires_db' => true,
        ],
        'api' => [
            'name' => 'RESTful API',
            'description' => 'Lightweight API with CORS and JSON responses',
            'has_auth' => false,
            'has_views' => false,
            'has_controllers' => false,
            'api_endpoints' => 6,
            'requires_db' => false,
        ],
    ];

    private const NEXT_STEPS = [
        'Run development server: composer dev',
        'Visit: http://localhost:{port}',
    ];

    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    public function selectTemplate(): string
    {
        $this->displayTemplates();

        $choices = array_keys(self::TEMPLATES);
        $choice = $this->io->askAndValidate(
            "\nSelect a template by number [<comment>1</comment>]: ",
            function ($value) use ($choices) {
                $value = trim($value) ?: '1';
                $index = (int) $value - 1;

                if (!isset($choices[$index])) {
                    throw new \RuntimeException('Please enter a valid number from the list.');
                }

                return $choices[$index];
            },
            null,
            '1'
        );

        return $choice;
    }

    private function displayTemplates(): void
    {
        $this->io->write("\n<info>📦 Available Templates:</info>");

        $index = 1;
        foreach (self::TEMPLATES as $key => $template) {
            $endpoints = $template['api_endpoints'] > 0
                ? " ({$template['api_endpoints']} API endpoints)"
                : '';
            $db = $template['requires_db'] ? ' [requires DB]' : '';

            $this->io->write(sprintf(
                "  [%d] <comment>%s</comment> - %s%s%s",
                $index,
                $template['name'],
                $template['description'],
                $endpoints,
                $db
            ));
            $index++;
        }
    }

    public static function getTemplateInfo(string $template): array
    {
        return self::TEMPLATES[$template] ?? self::TEMPLATES['default'];
    }

    public static function getNextSteps(string $template): array
    {
        $steps = self::NEXT_STEPS;
        
        if (self::TEMPLATES[$template]['requires_db'] ?? false) {
            array_unshift($steps, 'Configure your database in the .env file');
        }
        
        return $steps;
    }

    public static function getTemplatePath(string $template): string
    {
        $path = __DIR__ . '/../templates/' . $template;

        if (!is_dir($path)) {
            throw new \RuntimeException("Template '{$template}' not found at: {$path}");
        }

        return $path;
    }
}