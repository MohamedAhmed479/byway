<?php

namespace App\Models;

use CodeIgniter\Model;

class Withdrawals extends Model
{
    protected $table            = 'withdrawals';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id', 'instructor_id', 'amount', 'status', 'method', 'details', 'requested_at', 'processed_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'amount' => 'decimal',
        'details' => 'json',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime'
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
        'id' => 'required|max_length[255]|is_unique[withdrawals.id,id,{id}]',
        'instructor_id' => 'required|numeric|is_not_unique[users.id]',
        'amount' => 'required|decimal|greater_than[0]',
        'status' => 'permit_empty|in_list[pending,completed,failed,refunded]',
        'method' => 'required|max_length[50]',
        'details' => 'permit_empty',
        'requested_at' => 'permit_empty|valid_date',
        'processed_at' => 'permit_empty|valid_date'
    ];
    protected $validationMessages   = [
        'id' => [
            'required' => 'Withdrawal ID is required',
            'max_length' => 'Withdrawal ID cannot exceed 255 characters',
            'is_unique' => 'This withdrawal ID already exists'
        ],
        'instructor_id' => [
            'required' => 'Instructor is required',
            'numeric' => 'Instructor ID must be a number',
            'is_not_unique' => 'Selected instructor does not exist'
        ],
        'amount' => [
            'required' => 'Amount is required',
            'decimal' => 'Amount must be a valid decimal number',
            'greater_than' => 'Amount must be greater than 0'
        ],
        'status' => [
            'in_list' => 'Status must be one of: pending, completed, failed, refunded'
        ],
        'method' => [
            'required' => 'Withdrawal method is required',
            'max_length' => 'Method cannot exceed 50 characters'
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
