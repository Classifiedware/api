<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Media;
use App\Entity\MediaThumbnail;
use Doctrine\ORM\EntityManagerInterface;
use Intervention\Image\ImageManager;
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
      private readonly ImageManager $imageManager,
      private readonly LoggerInterface $logger,
      private readonly EntityManagerInterface $entityManager,
      private readonly string $mediaDirRelative,
      private readonly string $thumbnailDirRelative,
      private readonly string $mediaDir,
      private readonly string $thumbnailDir,
      private readonly array $uploadAllowedExtensions,
      private readonly array $thumbnailSizes
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
        $fileHash = $this->generateFileHash($imageFile->getFilename());
        $mediaPath = $this->generateHashedPath($this->mediaDir, $fileHash, $this->getFileExtension($imageFile));
        $mediaPathRelative = str_replace($this->mediaDir, $this->mediaDirRelative, $mediaPath);
        $fileName = pathinfo($mediaPath, PATHINFO_BASENAME);
        $mediaDir = dirname($mediaPath);

        if (!$this->filesystem->exists($mediaDir)) {
            try {
                $this->filesystem->mkdir($mediaDir);
            } catch (IOExceptionInterface $exception) {
                $this->logger->error('Unable to create hashed media folder', ['path' => $exception->getPath()]);
            }
        }

        try {
            $imageFile->move($mediaDir, $fileName);

            $media = $this->createMediaEntity($mediaPathRelative);

            $this->createThumbnails($media, $mediaPath, $imageFile);

            return $media;
        } catch (FileException) {
            $this->logger->error('Unable to move media file to its final path', ['path' => $mediaPath]);
        }

        return null;
    }

    private function createThumbnails(Media $media, string $mediaPath, UploadedFile $imageFile): void
    {
        foreach ($this->thumbnailSizes as $thumbnailSize) {
            [$width, $height] = explode('x', $thumbnailSize);

            $this->createThumbnail($width, $height, $mediaPath, $media, $imageFile);
        }
    }

    private function createThumbnail(string $width, string $height, string $mediaPath, Media $media, UploadedFile $imageFile): void
    {
        $thumbnailExtension = $this->getFileExtension($imageFile);
        $fileHash = $this->generateFileHash(
            sprintf(
                '%s_%sx%s.%s',
                $imageFile->getFilename(),
                $width,
                $height,
                $thumbnailExtension
            )
        );
        $thumbnailPath = $this->generateHashedPath($this->thumbnailDir, $fileHash, $thumbnailExtension);
        $thumbnailPathRelative = str_replace($this->thumbnailDir, $this->thumbnailDirRelative, $thumbnailPath);
        $thumbnailDir = dirname($thumbnailPath);

        if (!$this->filesystem->exists($thumbnailDir)) {
            try {
                $this->filesystem->mkdir($thumbnailDir);
            } catch (IOExceptionInterface $exception) {
                $this->logger->error('Unable to create hashed thumbnail folder', ['path' => $exception->getPath()]);
            }
        }

        $image = $this->imageManager->read($mediaPath);
        $image->resize((int)$width, (int)$height);
        $image->save($thumbnailPath);

        $mediaThumbnail = new MediaThumbnail();
        $mediaThumbnail->setUuid(Uuid::v4());
        $mediaThumbnail->setPath($thumbnailPathRelative);
        $mediaThumbnail->setMedia($media);
        $mediaThumbnail->setWidth($width);
        $mediaThumbnail->setHeight($height);
        $mediaThumbnail->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($mediaThumbnail);
        $this->entityManager->flush();
    }

    private function generateHashedPath(string $uploadPath, string $fileHash, string $extension): string
    {
        $firstHash = mb_substr($fileHash, 0, 2);
        $secondHash = mb_substr($fileHash, 2, 2);
        $thirdHash = mb_substr($fileHash, 4, 2);

        return sprintf(
            '%s/%s/%s/%s/%s.%s',
            $uploadPath,
            $firstHash,
            $secondHash,
            $thirdHash,
            $fileHash,
            $extension
        );
    }

    private function generateFileHash(string $fileName): string
    {
        return sha1(sprintf(
            '%s%s%s',
            bin2hex(random_bytes(6)),
            $fileName,
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