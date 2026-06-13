<?php

namespace Tests\Unit;

use App\Services\LpjBosImageCompressor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LpjBosImageCompressorTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_a_compressed_jpeg_image_on_public_disk(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('large.jpg', 2400, 1600)->size(9000);
        $service = new LpjBosImageCompressor;

        $path = $service->store($file, 'lpj-bos/1/foto');

        Storage::disk('public')->assertExists($path);
        $this->assertStringStartsWith('lpj-bos/1/foto/', $path);
        $this->assertStringEndsWith('.jpg', $path);
    }
}
