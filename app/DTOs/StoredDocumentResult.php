<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class StoredDocumentResult
{
    public function __construct(
        public string $filePath,
        public string $fileMime,
        public int $fileSize,
        public string $fileHash,
        public string $originalName,
    ) {}
}
