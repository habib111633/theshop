<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class MediaFactory extends Factory
{
    protected $model = \App\Models\Media::class;

    public function definition(): array
    {
        // Define an array of dummy images (pre-existing in `database/seeders/images`)
        $dummyImages = [
            'dummy1.jpeg',
            'dummy2.jpeg',
            'dummy3.jpeg',
            'dummy4.jpeg',
        ];

        // Pick a random image
        $selectedImage = $this->faker->randomElement($dummyImages);

        // Copy the selected image to the public storage folder
        $storagePath = 'products/' . $selectedImage;
        Storage::disk('public')->put($storagePath, file_get_contents(database_path("seeders/images/{$selectedImage}")));

        return [
            'path'           => $storagePath,
            'imageable_id'   => null,
            'imageable_type' => null,
        ];
    }
}