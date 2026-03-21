<?php
/**
 * Next Steps - Displays post-installation instructions
 */

namespace FluxorInstaller;

use Composer\IO\IOInterface;

class NextSteps
{
    private IOInterface $io;

    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    public function show(array $config): void
    {
        $this->io->write("\n<info>🎯 Next Steps:</info>");
        $this->io->write("----------------------------------------");
        $this->io->write("  1. Run: composer install");
        $this->io->write("  2. Start the development server: composer dev");
        $this->io->write("  3. Visit: http://localhost:{$config['app_port']}");
        $this->io->write("\n  4. Explore the code:");
        $this->io->write("     - Routes: app/router/");
        $this->io->write("     - Views: src/Views/");
        $this->io->write("     - Configuration: .env");

        if ($config['auth'] ?? false) {
            $this->io->write("\n  🔐 Default admin credentials:");
            $this->io->write("     Email: {$config['admin_email']}");
            $this->io->write("     Password: {$config['admin_password']}");
        }

        if ($config['mailer'] ?? false) {
            $this->io->write("\n  📧 Mailer configured. Update .env with your SMTP credentials.");
        }

        if ($config['uploader'] ?? false) {
            $this->io->write("\n  📁 Uploads directory: public/uploads/");
        }

        $this->io->write("\n  📚 Documentation: https://lizzyman04.github.io/fluxor-php");
    }
}