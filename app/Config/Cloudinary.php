<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cloudinary extends BaseConfig
{
    public $cloudName;
    public $apiKey;
    public $apiSecret;

    public function __construct()
    {
        parent::__construct();

        $this->cloudName = env("CLOUDINARY_NAME");
        $this->apiKey    = env("CLOUDINARY_KEY");
        $this->apiSecret = env("CLOUDINARY_SECRET");
    }
}
