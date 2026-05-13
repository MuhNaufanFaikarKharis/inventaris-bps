<?php

namespace App\Controllers;

class Notification extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        $data = [
            'title' => 'Inbox Notifikasi',
            'notifications' => $db->table('notifications')
                ->where('user_id', $userId)
                ->orderBy('created_at', 'DESC')
                ->get()->getResult(),
            'role' => session()->get('role')
        ];

        return view('notification/index', $data);
    }

    // Fungsi untuk menandai terbaca satu per satu (dipicu klik JS)
    public function markAsRead($id)
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        $db->table('notifications')
            ->where(['id' => $id, 'user_id' => $userId])
            ->update(['is_read' => 1]);

        return $this->response->setJSON(['status' => 'success']);
    }
}
