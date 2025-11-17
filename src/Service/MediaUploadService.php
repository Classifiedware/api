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
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Uid\Uuid;

class MediaUploadService
{
    public function __construct(
      private readonly Filesystem $filesystem,
      private readonly ImageManager $imageManager,
      private readonly LoggerInterface $logger,
      private readonly EntityManagerInterface $entityManager,
      private readonly SluggerInterface $slugger,
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

    private function getFileName(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        return $this->slugger->slug($originalFilename)->toString();
    }

    private function saveMedia(UploadedFile $imageFile): ?Media
    {
        $fileExtension = $this->getFileExtension($imageFile);
        $fileName = $this->getFileName($imageFile);
        $fileHash = $this->generateFileHash($fileName);
        $mediaPath = $this->generateHashedPath($this->mediaDir, $fileHash, $fileName. '.'. $fileExtension);
        $mediaPathRelative = str_replace($this->mediaDir, $this->mediaDirRelative, $mediaPath);
        $mediaPathRelative = str_replace('public/', '', $mediaPathRelative);
        $newFileName = pathinfo($mediaPath, PATHINFO_BASENAME);
        $mediaDir = dirname($mediaPath);

        if (!$this->filesystem->exists($mediaDir)) {
            try {
                $this->filesystem->mkdir($mediaDir);
            } catch (IOExceptionInterface $exception) {
                $this->logger->error('Unable to create hashed media folder', ['path' => $exception->getPath()]);
            }
        }

        try {
            $fileSize = $imageFile->getSize();

            $imageFile->move($mediaDir, $newFileName);

            $imageData = getimagesize($mediaPath);
            [$width, $height] = $imageData;

            $media = $this->createMediaEntity(
                $mediaPathRelative,
                $fileHash,
                $width,
                $height,
                $fileName,
                $fileSize,
                $fileExtension,
                $imageData['mime']
            );

            $this->createThumbnails($media, $mediaPath);

            return $media;
        } catch (FileException) {
            $this->logger->error('Unable to move media file to its final path', ['path' => $mediaPath]);
        }

        return null;
    }

    private function createThumbnails(Media $media, string $mediaPath): void
    {
        foreach ($this->thumbnailSizes as $thumbnailSize) {
            [$width, $height] = explode('x', $thumbnailSize);

            $this->createThumbnail($width, $height, $mediaPath, $media);
        }
    }

    private function createThumbnail(string $width, string $height, string $mediaPath, Media $media): void
    {
        $fileName = sprintf(
            '%s_%sx%s.%s',
            $media->getFileName(),
            $width,
            $height,
            $media->getFileExtension()
        );
        $thumbnailPath = $this->generateHashedPath($this->thumbnailDir, $media->getFileHash(), $fileName);
        $thumbnailPathRelative = str_replace($this->thumbnailDir, $this->thumbnailDirRelative, $thumbnailPath);
        $thumbnailPathRelative = str_replace('public/', '', $thumbnailPathRelative);
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

    private function generateHashedPath(string $uploadPath, string $fileHash, string $fileName): string
    {
        $firstHash = mb_substr($fileHash, 0, 2);
        $secondHash = mb_substr($fileHash, 2, 2);
        $thirdHash = mb_substr($fileHash, 4, 2);
        $lastHash = mb_substr($fileHash, 0, 10);

        return sprintf(
            '%s/%s/%s/%s/%s/%s',
            $uploadPath,
            $firstHash,
            $secondHash,
            $thirdHash,
            $lastHash,
            $fileName
        );
    }

    private function generateFileHash(string $fileName): string
    {
        return sha1(sprintf(
            '%s%s%s',
            bin2hex(random_bytes(16)),
            $fileName,
            time()
        ));
    }

    private function createMediaEntity(
        string $path,
        string $fileHash,
        int $width,
        int $height,
        string $fileName,
        int $fileSize,
        string $fileExtension,
        string $mimeType
    ): Media {
        $media = new Media();
        $media->setUuid(Uuid::v4());
        $media->setPath($path);
        $media->setFileHash($fileHash);
        $media->setWidth($width);
        $media->setHeight($height);
        $media->setFileName($fileName);
        $media->setFileSize($fileSize);
        $media->setFileExtension($fileExtension);
        $media->setMimeType($mimeType);
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