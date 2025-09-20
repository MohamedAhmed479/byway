<?php

namespace App\Models;

use CodeIgniter\Model;

class EnrollmentModel extends Model
{
    protected $table            = 'enrollments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'learner_id', 'course_id', 'enrolled_at', 'progress', 'completed_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'enrolled_at' => 'datetime',
        'progress' => 'decimal',
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
        'learner_id' => 'required|numeric|is_not_unique[users.id]',
        'course_id' => 'required|numeric|is_not_unique[courses.id]',
        'enrolled_at' => 'permit_empty|valid_date',
        'progress' => 'permit_empty|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
        'completed_at' => 'permit_empty|valid_date'
    ];
    protected $validationMessages   = [
        'learner_id' => [
            'required' => 'Learner is required',
            'numeric' => 'Learner ID must be a number',
            'is_not_unique' => 'Selected learner does not exist'
        ],
        'course_id' => [
            'required' => 'Course is required',
            'numeric' => 'Course ID must be a number',
            'is_not_unique' => 'Selected course does not exist'
        ],
        'progress' => [
            'decimal' => 'Progress must be a valid decimal number',
            'greater_than_equal_to' => 'Progress cannot be negative',
            'less_than_equal_to' => 'Progress cannot exceed 100%'
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
