<?php

namespace Tests\Unit;

use App\Rules\LandscapeImage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class LandscapeImageTest extends TestCase
{
    public function test_accepts_landscape_images(): void
    {
        $rule = new LandscapeImage();
        $file = UploadedFile::fake()->image('banner.jpg', 1600, 900);

        $this->assertTrue($rule->passes('gambar', $file));
    }

    public function test_rejects_portrait_images(): void
    {
        $rule = new LandscapeImage();
        $file = UploadedFile::fake()->image('banner.jpg', 900, 1600);

        $this->assertFalse($rule->passes('gambar', $file));
    }
}
