<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MediaThumbnailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaThumbnailRepository::class)]
class MediaThumbnail
{
    use EntityIdTrait;
    use EntityCreatedAndUpdatedAtTrait;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    private ?string $width = null;

    #[ORM\Column(length: 255)]
    private ?string $height = null;

    #[ORM\ManyToOne(targetEntity: Media::class, inversedBy: 'mediaThumbnails')]
    private ?Media $media = null;

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function getWidth(): ?string
    {
        return $this->width;
    }

    public function setWidth(?string $width): void
    {
        $this->width = $width;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function setHeight(?string $height): void
    {
        $this->height = $height;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): void
    {
        $this->media = $media;
    }
}
