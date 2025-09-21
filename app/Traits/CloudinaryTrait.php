<?php

namespace App\Traits;

use Cloudinary\Cloudinary;

trait CloudinaryTrait
{

    protected function uploadFile($file, string $folder = 'byway')
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

            // Get file info for debugging
            $fileSize = $file->getSize();
            $fileName = $file->getName();
            $mimeType = $file->getClientMimeType();
            

            // Set upload options based on file type
            $uploadOptions = ["folder" => $folder];
            
            // For videos, add specific options
            if (strpos($mimeType, 'video/') === 0) {
                $uploadOptions['resource_type'] = 'video';
                $uploadOptions['chunk_size'] = 6000000; // 6MB chunks for large videos
            }

            $result = $cloudinary->uploadApi()->upload(
                $file->getTempName(),
                $uploadOptions
            );

            log_message('info', "CloudinaryTrait: Upload successful - URL: {$result['secure_url']}");

            return [
                'url' => $result['secure_url'],
                'public_id' => $result['public_id']
            ];
        } catch (\Cloudinary\Api\Exception\ApiError $e) {
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }


    protected function deleteFile(string $publicId)
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
        } catch (\Cloudinary\Api\Exception\ApiError $e) {
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }


    protected function updateFile($newFile, ?string $oldPublicId = null, string $folder = 'byway')
    {
        if (!empty($oldPublicId)) {
            $deleteResult = $this->deleteFile($oldPublicId);
        } 

        return $this->uploadFile($newFile, $folder);
    }
}
