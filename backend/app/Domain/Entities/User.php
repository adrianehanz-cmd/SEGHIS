<?php

declare(strict_types=1);

namespace App\Domain\Entities;

final class User
{
    public function __construct(
        private readonly int $id,
        private readonly int $roleId,
        private readonly string $roleName,
        private readonly string $username,
        private readonly string $password,
        private readonly string $firstName,
        private readonly ?string $middleName,
        private readonly string $lastName,
        private readonly ?string $email,
        private readonly ?string $phone,
        private readonly bool $isActive,
        private readonly ?string $lastLogin
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRoleId(): int
    {
        return $this->roleId;
    }

    public function getRoleName(): string
    {
        return $this->roleName;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getLastLogin(): ?string
    {
        return $this->lastLogin;
    }

    public function getFullName(): string
    {
        return trim(
            $this->firstName . ' ' .
            ($this->middleName ? $this->middleName . ' ' : '') .
            $this->lastName
        );
    }
}