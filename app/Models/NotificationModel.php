<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table            = 'notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'type', 'message', 'read_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'read_at' => 'datetime',
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
        'user_id' => 'required|numeric|is_not_unique[users.id]',
        'type' => 'required|in_list[enrollment,payment_success,payment_failure,course_update,review_added,withdrawal_request]',
        'message' => 'required',
        'read_at' => 'permit_empty|valid_date'
    ];
    protected $validationMessages   = [
        'user_id' => [
            'required' => 'User is required',
            'numeric' => 'User ID must be a number',
            'is_not_unique' => 'Selected user does not exist'
        ],
        'type' => [
            'required' => 'Notification type is required',
            'in_list' => 'Invalid notification type'
        ],
        'message' => [
            'required' => 'Notification message is required'
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
