<?php

namespace App\Models;

use App\Libraries\EmailManager;
use CodeIgniter\Model;

class User extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name', 'email', 'password', 'role', 'email_verified_at', 
        'profile_picture', 'bio', 'social_links'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'email_verified_at' => '?datetime',
        'social_links' => 'json',
        'created_at' => 'datetime',
        'updated_at' => '?datetime',
        'deleted_at' => '?datetime'
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
        'name' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|max_length[100]|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[8]',
        'role' => 'required|in_list[admin,learner,instructor]',
        'profile_picture' => 'permit_empty|max_length[255]',
        'bio' => 'permit_empty',
        'social_links' => 'permit_empty'
    ];
    protected $validationMessages   = [
        'name' => [
            'required' => 'Name is required',
            'min_length' => 'Name must be at least 2 characters long',
            'max_length' => 'Name cannot exceed 100 characters'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please provide a valid email address',
            'is_unique' => 'This email is already registered'
        ],
        'password' => [
            'required' => 'Password is required',
            'min_length' => 'Password must be at least 8 characters long'
        ],
        'role' => [
            'required' => 'User role is required',
            'in_list' => 'Role must be admin, learner, or instructor'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Hash password before inserting or updating
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    public function sendEmailVerificationNotification(string $email)
    {
        $otp = random_int(100000, 999999);
        $userKey = "otp_" . $otp;

        cache()->save($userKey, password_hash($otp, PASSWORD_DEFAULT), 300);

        $body = view('emails/verification', [
            'otp' => $otp,
            'username' => $this->name
        ]);

        $mailer = new EmailManager();
        if($mailer->send($email, "Verify Email", $body, "system@byway.com", env("app.Name"))) {
            return true;
        }

        return false;
    }

}
