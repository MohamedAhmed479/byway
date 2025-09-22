<?php

namespace App\Validation;

class UpdateLesson
{
    public function getRules(): array
    {
        return [
            'course_id' => 'required|numeric|is_not_unique[courses.id]',
            'title' => 'required|min_length[3]|max_length[255]',
            'video_url' => [
                'rules' => 'permit_empty|max_size[video_url,20480]|mime_in[video_url,video/mp4,video/mpeg,video/avi,video/quicktime]',
                'label' => 'Lesson Video'
            ],
            'duration' => 'permit_empty|decimal|greater_than_equal_to[0]',
            'order' => 'required|numeric|greater_than[0]'
        ];
    }

    public function getMessages(): array
    {
        return [
            'course_id' => [
                'required' => 'Course is required',
                'numeric' => 'Course ID must be a number',
                'is_not_unique' => 'Selected course does not exist'
            ],
            'title' => [
                'required' => 'Lesson title is required',
                'min_length' => 'Lesson title must be at least 3 characters long',
                'max_length' => 'Lesson title cannot exceed 255 characters'
            ],
            'video_url' => [
                'max_size' => 'Lesson video size cannot exceed 20MB',
                'mime_in' => 'Lesson video must be MP4, MPEG, AVI, or QuickTime format'
            ],
            'duration' => [
                'decimal' => 'Duration must be a valid decimal number',
                'greater_than_equal_to' => 'Duration cannot be negative'
            ],
            'order' => [
                'required' => 'Lesson order is required',
                'numeric' => 'Order must be a number',
                'greater_than' => 'Order must be greater than 0'
            ]
        ];
    }
}
