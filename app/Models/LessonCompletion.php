<?php

namespace App\Models;

use CodeIgniter\Model;

class LessonCompletion extends Model
{
    protected $table            = 'lesson_completions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'enrollment_id', 'lesson_id', 'completed_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'completed_at' => 'datetime'
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'enrollment_id' => 'required|numeric|is_not_unique[enrollments.id]',
        'lesson_id' => 'required|numeric|is_not_unique[lessons.id]',
        'completed_at' => 'permit_empty|valid_date'
    ];
    protected $validationMessages   = [
        'enrollment_id' => [
            'required' => 'Enrollment is required',
            'numeric' => 'Enrollment ID must be a number',
            'is_not_unique' => 'Selected enrollment does not exist'
        ],
        'lesson_id' => [
            'required' => 'Lesson is required',
            'numeric' => 'Lesson ID must be a number',
            'is_not_unique' => 'Selected lesson does not exist'
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
