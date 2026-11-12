<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ImageUploader
{
    public const MAX_KB = 5120;

    /**
     * Re-encode an image through GD to remove metadata and payloads.
     *
     * @return array{path: string, url: string}
     */
    public function store(UploadedFile $file, int $userId, int $maxWidth = 1600): array
    {
        if (! $file->isValid()) {
            throw new RuntimeException('The image did not upload. Try again.');
        }

        $bytes = (int) $file->getSize();

        if ($bytes > self::MAX_KB * 1024) {
            throw new RuntimeException('Keep images under 5 MB.');
        }

        $info = @getimagesize($file->getRealPath());

        if (! $info) {
            throw new RuntimeException('That file is not a readable image.');
        }

        $mime = $info['mime'] ?? '';
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];

        if (! isset($allowed[$mime])) {
            throw new RuntimeException('Use a JPG, PNG or WebP image.');
        }

        $source = @imagecreatefromstring((string) file_get_contents($file->getRealPath()));

        if (! $source) {
            throw new RuntimeException('We could not process that image. Try exporting it again.');
        }

        $width = imagesx($source);
        $height = imagesy($source);

        if ($width > $maxWidth) {
            $newHeight = max(1, (int) round($height * ($maxWidth / $width)));
            $resized = imagecreatetruecolor($maxWidth, $newHeight);
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $source, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
            imagedestroy($source);
            $source = $resized;
        }

        $extension = $allowed[$mime];
        $name = Str::random(24).'.'.$extension;
        $relative = $userId.'/'.$name;
        $target = Storage::disk('uploads')->path($relative);

        if (! is_dir(dirname($target))) {
            mkdir(dirname($target), 0775, true);
        }

        $saved = match ($extension) {
            'png' => imagepng($source, $target, 6),
            'webp' => imagewebp($source, $target, 84),
            default => imagejpeg($source, $target, 86),
        };

        imagedestroy($source);

        if (! $saved) {
            throw new RuntimeException('The image could not be saved. Try a smaller file.');
        }

        return [
            'path' => 'uploads/'.$relative,
            'url' => asset('uploads/'.$relative),
        ];
    }
}
