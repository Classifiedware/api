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

    #[ORM\Column(length: 255)]
    private ?string $fileHash = null;

    #[ORM\Column]
    private ?int $width = null;

    #[ORM\Column]
    private ?int $height = null;

    #[ORM\Column(length: 255)]
    private ?string $fileName = null;

    #[ORM\Column]
    private ?int $fileSize = null;

    #[ORM\Column(length: 10)]
    private ?string $fileExtension = null;

    #[ORM\Column(length: 20)]
    private ?string $mimeType = null;

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

    public function getFileHash(): ?string
    {
        return $this->fileHash;
    }

    public function setFileHash(?string $fileHash): Media
    {
        $this->fileHash = $fileHash;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): Media
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): Media
    {
        $this->height = $height;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): Media
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    public function setFileSize(?int $fileSize): Media
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    public function getFileExtension(): ?string
    {
        return $this->fileExtension;
    }

    public function setFileExtension(?string $fileExtension): Media
    {
        $this->fileExtension = $fileExtension;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): Media
    {
        $this->mimeType = $mimeType;

        return $this;
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
