<?php

namespace App\Traits;

trait HasUploads
{
    public static function bootHasUploads()
    {
        if (!property_exists(static::class, 'uploadMedia')) {
            $className = static::class;
            throw new \Exception("Propery '\$uploadMedia' has not defined in class {$className}");
        }
    }

    public function uploadMedia()
    {

        foreach ($this->uploadMedia as $mediaItem) {
            if (request()->hasFile($mediaItem)) {
                if ($mediaItem === 'sub_images') {
                    foreach (request()->file($mediaItem) as $file) {
                        $this->addMedia($file)->toMediaCollection($mediaItem);
                    }
                } else {
                    $this->addMediaFromRequest($mediaItem)->toMediaCollection($mediaItem);
                }
            }
        }
    }

    public function retrieveMedia()
    {
        $media = [];

        foreach ($this->uploadMedia as $collection) {
            $items = $this->getMedia($collection);
            $mediaItems = [];

            foreach ($items as $item) {
                $mediaItem = [
                    "name" => $item->file_name,
                    "url" => $item->getFullUrl(),
                ];

                if ($item->pivot) {
                    $mediaItem = array_merge($mediaItem, $item->pivot->toArray());
                }

                $mediaItems[] = $mediaItem;
            }

            $media[$collection] = $mediaItems;
        }

        return $media;
    }

    public function getProductMainImage()
    {
        $media = $this->getMedia('main_image');

        $mediaItems = $media->map(function ($item) {
            $mediaItem = [
                "name" => $item->file_name,
                "url" => $item->getFullUrl(),
            ];

            // If you have pivot data for the media, you can merge it here
            // $mediaItem = array_merge($mediaItem, $item->pivot->toArray());

            return $mediaItem;
        });

        return $mediaItems;
    }
}
