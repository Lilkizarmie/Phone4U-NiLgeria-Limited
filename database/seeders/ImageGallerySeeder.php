<?php

namespace Database\Seeders;

use App\Models\Admin\ImageGallery;
use Illuminate\Database\Seeder;

class ImageGallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ImageGallery::create([
            'Image' => 'gallery-1.jpg',
        ]);
        ImageGallery::create([
            'Image' => 'gallery-3.jpg',
        ]);
        ImageGallery::create([
            'Image' => 'gallery-6.jpg',
        ]);
        ImageGallery::create([
            'Image' => 'gallery-2.jpg',
        ]);
        ImageGallery::create([
            'Image' => 'gallery-4.jpg',
        ]);
        ImageGallery::create([
            'Image' => 'gallery-7.jpg',
        ]);
        ImageGallery::create([
            'Image' => null,
        ]);
        ImageGallery::create([
            'Image' => 'gallery-5.jpg',
        ]);

    }
}
