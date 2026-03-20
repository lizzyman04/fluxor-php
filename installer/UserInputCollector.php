<?php
/**
 * User Input Collector - Handles user information collection
 */

namespace FluxorInstaller;

use Composer\IO\IOInterface;

class UserInputCollector
{
    private IOInterface $io;
    
    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }
    
    public function collect(): array
    {
        $this->displayHeader();
        
        $name = $this->askName();
        $email = $this->askEmail();
        $homepage = $this->askHomepage();
        $vendor = $this->askVendor();
        
        return [
            'name' => $name,
            'email' => $email,
            'homepage' => $homepage,
            'vendor' => $vendor,
        ];
    }
    
    private function displayHeader(): void
    {
        $this->io->write("\n<info>👤 Author Information</info>");
        $this->io->write("----------------------------------------");
    }
    
    private function askName(): string
    {
        return $this->io->ask(
            "Your name [<comment>Fluxor User</comment>]: ",
            'Fluxor User'
        );
    }
    
    private function askEmail(): string
    {
        return $this->io->askAndValidate(
            "Your email [<comment>user@example.com</comment>]: ",
            function ($value) {
                $value = $value ?: 'user@example.com';
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    throw new \RuntimeException('Please enter a valid email address');
                }
                return $value;
            },
            null,
            'user@example.com'
        );
    }
    
    private function askHomepage(): string
    {
        return $this->io->askAndValidate(
            "Your website (optional) [<comment>https://example.com</comment>]: ",
            function ($value) {
                $value = $value ?: 'https://example.com';
                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    throw new \RuntimeException('Please enter a valid URL (e.g., https://example.com)');
                }
                return $value;
            },
            null,
            'https://example.com'
        );
    }
    
    private function askVendor(): string
    {
        return $this->io->askAndValidate(
            "Vendor name (for composer package) [<comment>yourname</comment>]: ",
            function ($value) {
                $value = $value ?: 'yourname';
                if (!preg_match('/^[a-z0-9][a-z0-9-]*[a-z0-9]$/i', $value)) {
                    throw new \RuntimeException('Vendor name must contain only letters, numbers, and hyphens, and cannot start/end with hyphen');
                }
                return strtolower($value);
            },
            null,
            'yourname'
        );
    }
}