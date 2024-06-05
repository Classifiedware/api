<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ClassifiedMediaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassifiedMediaRepository::class)]
class ClassifiedMedia
{
    use EntityIdTrait;
    use EntityCreatedAndUpdatedAtTrait;

    #[ORM\ManyToOne(targetEntity: Classified::class, inversedBy: 'media')]
    private ?Classified $classified = null;

    #[ORM\ManyToOne(targetEntity: Media::class)]
    private ?Media $media = null;

    public function getClassified(): ?Classified
    {
        return $this->classified;
    }

    public function setClassified(?Classified $classified): void
    {
        $this->classified = $classified;
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
