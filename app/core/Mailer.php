<?php

namespace Core;

class Mailer
{
    private $host;
    private $port;
    private $username;
    private $password;
    private $fromAddress;
    private $fromName;

    public function __construct()
    {
        $this->host = env('MAIL_HOST', '');
        $this->port = env('MAIL_PORT', 587);
        $this->username = env('MAIL_USERNAME', '');
        $this->password = env('MAIL_PASSWORD', '');
        $this->fromAddress = env('MAIL_FROM_ADDRESS', 'noreply@example.com');
        $this->fromName = env('MAIL_FROM_NAME', 'Fluxor App');
    }

    public function send(string $to, string $subject, string $htmlBody, ?string $textBody = null): bool
    {
        if (empty($this->host) || empty($this->username)) {
            error_log("Mail not configured. Skipping email to {$to}");
            return false;
        }

        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            "From: {$this->fromName} <{$this->fromAddress}>",
            "Reply-To: {$this->fromAddress}",
            "X-Mailer: PHP/" . PHP_VERSION
        ];

        $textBody = $textBody ?? strip_tags($htmlBody);

        $boundary = md5(time());
        $headers[] = "Content-Type: multipart/alternative; boundary=\"{$boundary}\"";

        $body = "--{$boundary}\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $body .= $textBody . "\r\n\r\n";
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $body .= $htmlBody . "\r\n\r\n";
        $body .= "--{$boundary}--";

        return mail($to, $subject, $body, implode("\r\n", $headers));
    }

    public function sendTemplate(string $to, string $template, array $data = []): bool
    {
        $subject = $data['subject'] ?? 'Message from Fluxor App';
        $htmlBody = $this->renderTemplate($template, $data);
        return $this->send($to, $subject, $htmlBody);
    }

    private function renderTemplate(string $template, array $data): string
    {
        extract($data);
        ob_start();
        include base_path("src/Views/emails/{$template}.php");
        return ob_get_clean();
    }
}