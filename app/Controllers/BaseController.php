<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    // app/Controllers/BaseController.php

    // app/Controllers/BaseController.php

    // app/Controllers/BaseController.php

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $session = \Config\Services::session();

        // Jika user sudah login
        if (session_status() === PHP_SESSION_ACTIVE && $session->get('login')) {
            $lastActivity = $session->get('last_activity');
            $currentTime  = time();

            // Jika waktu diam melebihi 15 menit (900 detik)
            if ($lastActivity && ($currentTime - $lastActivity > 900)) {
                $session->destroy();
                // Redirect menggunakan script agar tidak bentrok dengan header yang sudah dikirim
                echo "<script>window.location.href='" . base_url('login') . "';</script>";
                exit();
            } else {
                // Update waktu aktivitas setiap kali halaman di-refresh/diakses
                $session->set('last_activity', $currentTime);
            }
        }
    }
}
