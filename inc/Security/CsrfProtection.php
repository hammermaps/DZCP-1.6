<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.2
 * CSRF Protection helper
 *
 * Wraps the existing csrf_token() / csrf_check() functions in a typed class.
 */

declare(strict_types=1);

namespace DZCP\Security;

final class CsrfProtection
{
    private string $sessionKey;

    public function __construct(string $sessionKey = 'dzcp_csrf_token')
    {
        $this->sessionKey = $sessionKey;
    }

    public function getToken(): string
    {
        if (empty($_SESSION[$this->sessionKey])) {
            $_SESSION[$this->sessionKey] = bin2hex(random_bytes(32));
        }
        return $_SESSION[$this->sessionKey];
    }

    public function regenerate(): string
    {
        $_SESSION[$this->sessionKey] = bin2hex(random_bytes(32));
        return $_SESSION[$this->sessionKey];
    }

    public function validate(?string $token): bool
    {
        return !empty($token) && hash_equals($this->getToken(), $token);
    }

    public function field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($this->getToken(), ENT_QUOTES, 'UTF-8') . '">';
    }
}
