<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Session\Handlers\FileHandler;

class Session extends BaseConfig
{
    public string $driver = FileHandler::class;
    public string $cookieName = 'ci_session';

    // Pengaturan Utama: 900 detik = 15 Menit
    public int $expiration = 900;

    public string $savePath = WRITEPATH . 'session';
    public bool $expireOnClose = false;
    public bool $matchIP = false;

    // Regenerasi ID setiap 5 menit (300 detik) untuk keamanan
    public int $timeToUpdate = 300;
    public bool $regenerateDestroy = false;
}
