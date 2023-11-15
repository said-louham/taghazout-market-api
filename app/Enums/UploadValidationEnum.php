<?php

namespace App\Enums;

enum UploadValidationEnum: string
{
    private const ONE_MB = 1024;

    public const MAX_FILE_SIZE = 2 * self::ONE_MB;

    public const MAX_IMAGE_SIZE = self::ONE_MB;

    public const IMAGE = ['png', 'jpg', 'jpeg', 'webp'];
}
