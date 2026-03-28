<?php

namespace App\Traits;

use App\Helpers\ImageStorage;

trait HasImage
{
    /**
     * Get the full URL for the given image attribute.
     *
     * @param  string  $attributeName
     * @param  string|null  $default
     * @return string|null
     */
    public function getImageUrl(string $attributeName = 'image', ?string $default = null): ?string
    {
        $path = $this->getAttribute($attributeName);
        
        if (!$path) {
            return $default ?: asset('assets/images/no-image.png');
        }

        return ImageStorage::url($path);
    }
}
