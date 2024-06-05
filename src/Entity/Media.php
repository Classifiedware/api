<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    use EntityIdTrait;
    use EntityCreatedAndUpdatedAtTrait;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\OneToMany(mappedBy: 'media', targetEntity: MediaThumbnail::class)]
    private Collection $mediaThumbnails;

    public function __construct()
    {
        $this->mediaThumbnails = new ArrayCollection();
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function getMediaThumbnails(): Collection
    {
        return $this->mediaThumbnails;
    }

    public function setMediaThumbnails(Collection $mediaThumbnails): void
    {
        $this->mediaThumbnails = $mediaThumbnails;
    }

    public function addMediaThumbnail(MediaThumbnail $mediaThumbnail): self
    {
        if (!$this->mediaThumbnails->contains($mediaThumbnail)) {
            $this->mediaThumbnails[] = $mediaThumbnail;
        }

        return $this;
    }
}
