<?php

namespace App\Models;

use CodeIgniter\Model;

class Lesson extends Model
{
    protected $table            = 'lessons';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'course_id', 'title', 'video_url', 'duration', 'order'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'duration' => 'decimal',
        'order' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'course_id' => 'required|numeric|is_not_unique[courses.id]',
        'title' => 'required|min_length[3]|max_length[255]',
        'video_url' => 'permit_empty|max_length[255]',
        'duration' => 'permit_empty|decimal|greater_than_equal_to[0]',
        'order' => 'required|numeric|greater_than[0]'
    ];
    protected $validationMessages   = [
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
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
