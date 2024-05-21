<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AdminAccessTokenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminAccessTokenRepository::class)]
class AdminAccessToken
{
    use EntityIdTrait;
    use EntityCreatedAndUpdatedAtTrait;

    #[ORM\Column(type: 'string', length: 32, unique: true)]
    private string $token;

    #[ORM\ManyToOne(targetEntity: Admin::class, inversedBy: 'accessTokens')]
    private ?Admin $admin = null;

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): void
    {
        $this->admin = $admin;
    }
}