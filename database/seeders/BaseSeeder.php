<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

abstract class BaseSeeder extends Seeder
{
    public function createFakeImage($imageTitle)
    {
        $directory = storage_path('app/temp');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $imageName = Str::random(10) . '.png';
        $imagePath = $directory . '/' . $imageName;

        $image = imagecreatetruecolor(640, 480);
        $bgColor = imagecolorallocate($image, rand(100, 255), rand(100, 255), rand(100, 255));
        imagefill($image, 0, 0, $bgColor);
        imagestring($image, 5, 200, 220, $imageTitle, imagecolorallocate($image, 0, 0, 0));
        imagepng($image, $imagePath);
        imagedestroy($image);

        return $imagePath;
    }
}
