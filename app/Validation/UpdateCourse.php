<?php

namespace App\Validation;

class UpdateCourse
{
    public function getRules(): array
    {
        return [
            'title'        => 'required|min_length[3]|max_length[255]',
            'description'  => 'required|min_length[10]',
            'price'        => 'permit_empty|numeric|greater_than_equal_to[0]',
            'category_id'  => 'required|numeric|is_not_unique[categories.id]',
            'duration'     => 'permit_empty|numeric|greater_than_equal_to[0]',
            'image_url' => [
                'rules' => 'permit_empty|is_image[image_url]|max_size[image_url,2048]|mime_in[image_url,image/jpg,image/jpeg,image/png]',
                'label' => 'Course Image'
            ],
            'video_url' => [
                'rules' => 'permit_empty|max_size[video_url,20480]|mime_in[video_url,video/mp4,video/mpeg,video/avi,video/quicktime]',
                'label' => 'Course Video'
            ],
        ];
    }

    public function getMessages(): array
    {
        return [
            'title' => [
                'required'   => 'Course title is required',
                'min_length' => 'Course title must be at least 3 characters long',
                'max_length' => 'Course title cannot exceed 255 characters'
            ],
            'description' => [
                'required'   => 'Course description is required',
                'min_length' => 'Course description must be at least 10 characters long'
            ],
            'price' => [
                'numeric'                => 'Price must be a valid number',
                'greater_than_equal_to'  => 'Price cannot be negative'
            ],
            'category_id' => [
                'required'      => 'Category is required',
                'numeric'       => 'Category ID must be a number',
                'is_not_unique' => 'Selected category does not exist'
            ],
            'duration' => [
                'numeric'               => 'Duration must be a valid number',
                'greater_than_equal_to' => 'Duration cannot be negative'
            ],
            'image_url' => [
                'is_image' => 'The file must be a valid image',
                'max_size' => 'Course image size cannot exceed 2MB',
                'mime_in'  => 'Course image must be JPG, JPEG, or PNG'
            ],
            'video_url' => [
                'max_size' => 'Course video size cannot exceed 20MB',
                'mime_in'  => 'Course video must be MP4, MPEG, AVI, or QuickTime format'
            ],
        ];
    }
}
