<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\ImageManager;

class LpjBosImageCompressor
{
    public function store(UploadedFile $file, string $directory): string
    {
        $manager = new ImageManager(new Driver);
        $image = $manager->decodePath($file->getRealPath());

        $image->scaleDown(width: 1600);

        $filename = Str::uuid()->toString().'.jpg';
        $path = trim($directory, '/').'/'.$filename;

        Storage::disk('public')->put($path, (string) $image->encode(new JpegEncoder(quality: 75)));

        return $path;
    }
}
