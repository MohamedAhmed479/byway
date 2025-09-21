<?php

namespace App\Traits;

use Cloudinary\Cloudinary;

trait CloudinaryTrait
{

    protected function uploadImage($file, string $folder = 'byway')
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return false;
        }

        try {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => config('Cloudinary')->cloudName,
                    'api_key'    => config('Cloudinary')->apiKey,
                    'api_secret' => config('Cloudinary')->apiSecret,
                ]
            ]);

            $result = $cloudinary->uploadApi()->upload(
                $file->getTempName(),
                ["folder" => $folder]
            );

            return [
                'url' => $result['secure_url'],
                'public_id' => $result['public_id']
            ];
        } catch (\Exception $e) {
            return false;
        }
    }


    protected function deleteImage(string $publicId)
    {
        if (empty($publicId)) {
            return true;
        }

        try {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => config('Cloudinary')->cloudName,
                    'api_key'    => config('Cloudinary')->apiKey,
                    'api_secret' => config('Cloudinary')->apiSecret,
                ]
            ]);

            $result = $cloudinary->uploadApi()->destroy($publicId);
            return $result['result'] === 'ok';
        } catch (\Exception $e) {
            return false;
        }
    }


    protected function updateImage($newFile, ?string $oldPublicId = null, string $folder = 'byway')
    {
        if (!empty($oldPublicId)) {
            $this->deleteImage($oldPublicId);
        }

        return $this->uploadImage($newFile, $folder);
    }
}
