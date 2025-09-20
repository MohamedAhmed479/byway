<?php

namespace App\Models;

use CodeIgniter\Model;

class Payment extends Model
{
    protected $table            = 'payments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id', 'learner_id', 'amount', 'payment_method', 'status', 'transaction_id', 'paid_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'amount' => 'decimal',
        'paid_at' => 'datetime',
        'created_at' => 'datetime'
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
        'id' => 'required|max_length[255]|is_unique[payments.id,id,{id}]',
        'learner_id' => 'required|numeric|is_not_unique[users.id]',
        'amount' => 'required|decimal|greater_than[0]',
        'payment_method' => 'required|max_length[50]',
        'status' => 'permit_empty|in_list[pending,completed,failed,refunded]',
        'transaction_id' => 'permit_empty|max_length[255]',
        'paid_at' => 'required|valid_date'
    ];
    protected $validationMessages   = [
        'id' => [
            'required' => 'Payment ID is required',
            'max_length' => 'Payment ID cannot exceed 255 characters',
            'is_unique' => 'This payment ID already exists'
        ],
        'learner_id' => [
            'required' => 'Learner is required',
            'numeric' => 'Learner ID must be a number',
            'is_not_unique' => 'Selected learner does not exist'
        ],
        'amount' => [
            'required' => 'Amount is required',
            'decimal' => 'Amount must be a valid decimal number',
            'greater_than' => 'Amount must be greater than 0'
        ],
        'payment_method' => [
            'required' => 'Payment method is required',
            'max_length' => 'Payment method cannot exceed 50 characters'
        ],
        'status' => [
            'in_list' => 'Status must be one of: pending, completed, failed, refunded'
        ],
        'paid_at' => [
            'required' => 'Payment date is required',
            'valid_date' => 'Payment date must be a valid date'
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
