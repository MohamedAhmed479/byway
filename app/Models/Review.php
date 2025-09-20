<?php

namespace App\Models;

use CodeIgniter\Model;

class Review extends Model
{
    protected $table            = 'reviews';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'course_id', 'learner_id', 'rating', 'comment'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'rating' => 'integer',
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
        'learner_id' => 'required|numeric|is_not_unique[users.id]',
        'rating' => 'required|integer|greater_than[0]|less_than_equal_to[5]',
        'comment' => 'permit_empty'
    ];
    protected $validationMessages   = [
        'course_id' => [
            'required' => 'Course is required',
            'numeric' => 'Course ID must be a number',
            'is_not_unique' => 'Selected course does not exist'
        ],
        'learner_id' => [
            'required' => 'Learner is required',
            'numeric' => 'Learner ID must be a number',
            'is_not_unique' => 'Selected learner does not exist'
        ],
        'rating' => [
            'required' => 'Rating is required',
            'integer' => 'Rating must be a whole number',
            'greater_than' => 'Rating must be at least 1',
            'less_than_equal_to' => 'Rating cannot exceed 5'
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
