<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\AbstractUid;

trait EntityIdTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: UuidType::NAME)]
    private ?AbstractUid $uuid = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?AbstractUid
    {
        return $this->uuid;
    }

    public function setUuid(?AbstractUid $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }
}
