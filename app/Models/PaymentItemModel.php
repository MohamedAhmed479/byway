<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentItemModel extends Model
{
    protected $table            = 'payment_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'payment_id', 'course_id', 'price_at_purchase'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'price_at_purchase' => 'decimal'
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
        'payment_id' => 'required|max_length[255]|is_not_unique[payments.id]',
        'course_id' => 'required|numeric|is_not_unique[courses.id]',
        'price_at_purchase' => 'permit_empty|decimal|greater_than_equal_to[0]'
    ];
    protected $validationMessages   = [
        'payment_id' => [
            'required' => 'Payment is required',
            'max_length' => 'Payment ID cannot exceed 255 characters',
            'is_not_unique' => 'Selected payment does not exist'
        ],
        'course_id' => [
            'required' => 'Course is required',
            'numeric' => 'Course ID must be a number',
            'is_not_unique' => 'Selected course does not exist'
        ],
        'price_at_purchase' => [
            'decimal' => 'Price must be a valid decimal number',
            'greater_than_equal_to' => 'Price cannot be negative'
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
