<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Media;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

class MediaUploadService
{
    public function __construct(
      private readonly Filesystem $filesystem,
      private readonly LoggerInterface $logger,
      private readonly EntityManagerInterface $entityManager,
      private readonly string $mediaDirRelative,
      private readonly string $mediaDir,
      private readonly string $thumbnailDir,
      private readonly array $uploadAllowedExtensions
    ) {
        $this->createMediaDir();
        $this->createThumbnailDir();
    }

    /**
     * @param array<UploadedFile> $imageFiles
     * @return array<Media>
     */
    public function uploadMedia(array $imageFiles): array
    {
        $createdMediaEntities = [];

        foreach ($imageFiles as $imageFile) {
            if (!$this->isUploadedFileValid($imageFile)) {
                continue;
            }

            $savedMedia = $this->saveMedia($imageFile);

            if ($savedMedia instanceof Media) {
                $createdMediaEntities[] = $savedMedia;
            }
        }

        return $createdMediaEntities;
    }

    private function isUploadedFileValid(UploadedFile $file): bool
    {
        $fileExtension = $this->getFileExtension($file);

        return in_array($fileExtension, $this->uploadAllowedExtensions, true);
    }

    private function getFileExtension(UploadedFile $file): string
    {
        return mb_strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
    }

    private function saveMedia(UploadedFile $imageFile): ?Media
    {
        $fileHash = $this->generateFileHash($imageFile);
        $hashedPath = $this->generateHashedPath($fileHash);
        $uploadPath = $this->mediaDir. '/'. $hashedPath;
        $fileName = $this->generateHashedFileName($fileHash, $imageFile);
        $mediaPath = sprintf('%s/%s/%s', $this->mediaDirRelative, $hashedPath, $fileName);

        if (!$this->filesystem->exists($uploadPath)) {
            try {
                $this->filesystem->mkdir($uploadPath);
            } catch (IOExceptionInterface $exception) {
                $this->logger->error('Unable to create hashed media folder', ['path' => $exception->getPath()]);
            }
        }

        try {
            $imageFile->move($uploadPath, $fileName);

            return $this->createMediaEntity($mediaPath);
        } catch (FileException) {
            $this->logger->error('Unable to move media file to its final path', ['path' => $mediaPath]);
        }

        return null;
    }

    private function generateHashedPath(string $fileHash): string
    {
        $firstHash = mb_substr($fileHash, 0, 2);
        $secondHash = mb_substr($fileHash, 2, 2);
        $thirdHash = mb_substr($fileHash, 4, 2);

        return sprintf(
            '%s/%s/%s',
            $firstHash,
            $secondHash,
            $thirdHash
        );
    }

    private function generateHashedFileName(string $fileHash, UploadedFile $file): string
    {
        return sprintf('%s.%s', $fileHash, $this->getFileExtension($file));
    }

    private function generateFileHash(UploadedFile $file): string
    {
        return sha1(sprintf(
            '%s%s%s',
            bin2hex(random_bytes(6)),
            $file->getFilename(),
            time()
        ));
    }

    private function createMediaEntity(string $path): Media
    {
        $media = new Media();
        $media->setUuid(Uuid::v4());
        $media->setPath($path);
        $media->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($media);
        $this->entityManager->flush();

        return $media;
    }

    private function createMediaDir(): void
    {
        if ($this->filesystem->exists($this->mediaDir)) {
            $this->logger->info('Media folder already exists so not created.', ['path' => $this->mediaDir]);

            return;
        }

        try {
            $this->filesystem->mkdir($this->mediaDir);

            $this->logger->info('Media folder created.', ['path' => $this->mediaDir]);
        } catch (IOExceptionInterface $exception) {
            $this->logger->error('Unable to create media folder', ['path' => $exception->getPath()]);
        }
    }

    private function createThumbnailDir(): void
    {
        if ($this->filesystem->exists($this->thumbnailDir)) {
            $this->logger->info('Thumbnail folder already exists so not created.', ['path' => $this->thumbnailDir]);

            return;
        }

        try {
            $this->filesystem->mkdir($this->thumbnailDir);

            $this->logger->info('Thumbnail folder created.', ['path' => $this->thumbnailDir]);
        } catch (IOExceptionInterface $exception) {
            $this->logger->error('Unable to create thumbnail folder', ['path' => $exception->getPath()]);
        }
    }
}