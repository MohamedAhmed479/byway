<?php

namespace App\Models;

use CodeIgniter\Model;

class CartItem extends Model
{
    protected $table            = 'carts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'course_id', 'added_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'added_at' => 'datetime'
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
        'user_id' => 'required|numeric|is_not_unique[users.id]',
        'course_id' => 'required|numeric|is_not_unique[courses.id]',
        'added_at' => 'permit_empty|valid_date'
    ];
    protected $validationMessages   = [
        'user_id' => [
            'required' => 'User is required',
            'numeric' => 'User ID must be a number',
            'is_not_unique' => 'Selected user does not exist'
        ],
        'course_id' => [
            'required' => 'Course is required',
            'numeric' => 'Course ID must be a number',
            'is_not_unique' => 'Selected course does not exist'
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
