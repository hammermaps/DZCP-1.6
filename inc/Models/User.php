<?php
/**
 * DZCP - deV!L`z ClanPortal 1.6.2
 * User model (data object)
 */

declare(strict_types=1);

namespace DZCP\Models;

final class User
{
    public ?int $id = null;
    public string $nick = '';
    public string $email = '';
    public int $level = 0;
    public ?string $passwordHash = null;

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $user = new self();
        $user->id = isset($data['id']) ? (int) $data['id'] : null;
        $user->nick = (string) ($data['user'] ?? $data['nick'] ?? '');
        $user->email = (string) ($data['email'] ?? '');
        $user->level = (int) ($data['level'] ?? 0);
        $user->passwordHash = isset($data['pass']) ? (string) $data['pass'] : null;
        return $user;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user' => $this->nick,
            'email' => $this->email,
            'level' => $this->level,
            'pass' => $this->passwordHash,
        ];
    }
}
