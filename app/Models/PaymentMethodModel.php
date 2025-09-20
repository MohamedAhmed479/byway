<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentMethodModel extends Model
{
    protected $table            = 'payment_methods';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id', 'user_id', 'type', 'details', 'is_default'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'details' => 'json',
        'is_default' => 'boolean',
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
        'id' => 'required|max_length[255]|is_unique[payment_methods.id,id,{id}]',
        'user_id' => 'required|numeric|is_not_unique[users.id]',
        'type' => 'required|max_length[50]',
        'details' => 'permit_empty',
        'is_default' => 'permit_empty|in_list[0,1]'
    ];
    protected $validationMessages   = [
        'id' => [
            'required' => 'Payment method ID is required',
            'max_length' => 'Payment method ID cannot exceed 255 characters',
            'is_unique' => 'This payment method ID already exists'
        ],
        'user_id' => [
            'required' => 'User is required',
            'numeric' => 'User ID must be a number',
            'is_not_unique' => 'Selected user does not exist'
        ],
        'type' => [
            'required' => 'Payment method type is required',
            'max_length' => 'Type cannot exceed 50 characters'
        ],
        'is_default' => [
            'in_list' => 'Is default must be 0 or 1'
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
