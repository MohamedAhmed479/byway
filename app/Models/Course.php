<?php

namespace App\Models;

use CodeIgniter\Model;

class Course extends Model
{
    protected $table            = 'courses';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title', 'description', 'image_url', 'video_url', 'price', 
        'instructor_id', 'category_id', 'status', 'thumbnail', 'duration'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'price' => 'decimal',
        'duration' => 'decimal',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
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
        'title' => 'required|min_length[3]|max_length[255]',
        'description' => 'required',
        'image_url' => 'permit_empty|max_length[255]',
        'video_url' => 'permit_empty|max_length[255]',
        'price' => 'permit_empty|decimal|greater_than_equal_to[0]',
        'instructor_id' => 'required|numeric|is_not_unique[users.id]',
        'category_id' => 'required|numeric|is_not_unique[categories.id]',
        'status' => 'permit_empty|in_list[draft,pending_approval,published,archived]',
        'thumbnail' => 'permit_empty|max_length[255]',
        'duration' => 'permit_empty|decimal|greater_than_equal_to[0]'
    ];
    protected $validationMessages   = [
        'title' => [
            'required' => 'Course title is required',
            'min_length' => 'Course title must be at least 3 characters long',
            'max_length' => 'Course title cannot exceed 255 characters'
        ],
        'description' => [
            'required' => 'Course description is required'
        ],
        'price' => [
            'decimal' => 'Price must be a valid decimal number',
            'greater_than_equal_to' => 'Price cannot be negative'
        ],
        'instructor_id' => [
            'required' => 'Instructor is required',
            'numeric' => 'Instructor ID must be a number',
            'is_not_unique' => 'Selected instructor does not exist'
        ],
        'category_id' => [
            'required' => 'Category is required',
            'numeric' => 'Category ID must be a number',
            'is_not_unique' => 'Selected category does not exist'
        ],
        'status' => [
            'in_list' => 'Status must be one of: draft, pending_approval, published, archived'
        ],
        'duration' => [
            'decimal' => 'Duration must be a valid decimal number',
            'greater_than_equal_to' => 'Duration cannot be negative'
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
