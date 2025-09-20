<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonalAccessToken extends Model
{
    protected $table            = 'personal_access_tokens';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tokenable_type', 'tokenable_id', 'name', 'token',
        'abilities', 'last_used_at', 'expires_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'abilities' => 'json',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'tokenable_type' => 'required|max_length[255]',
        'tokenable_id'   => 'required|integer',
        'name'           => 'required|max_length[255]',
        'token'          => 'required|max_length[64]|is_unique[personal_access_tokens.token]',
        'abilities'      => 'permit_empty',
    ];

    protected $validationMessages = [
        'tokenable_type' => [
            'required' => 'Object type required',
        ],
        'tokenable_id' => [
            'required' => 'Object ID required',
            'integer' => 'Object ID must be a number',
        ],
        'name' => [
            'required' => 'Token name required',
        ],
        'token' => [
            'required' => 'Token required',
            'is_unique' => 'Token already exists',
        ],
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

    /**
     * Find token by hash
     */
    public function findByToken(string $token): ?array
    {
        return $this->where('token', hash('sha256', $token))
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->orWhere('expires_at', null)
            ->first();
    }

    /**
     * Revoke token
     */
    public function revokeToken(string $token): bool
    {
        return $this->where('token', hash('sha256', $token))->delete();
    }

    /**
     * Check if token has ability
     */
    public function hasAbility(array $tokenData, string $ability): bool
    {
        if (empty($tokenData['abilities'])) {
            return true; // No restrictions
        }

        $abilities = is_string($tokenData['abilities'])
            ? json_decode($tokenData['abilities'], true)
            : $tokenData['abilities'];

        return in_array('*', $abilities) || in_array($ability, $abilities);
    }


}
