<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class CategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct() {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data = [
            'title'      => 'Kelola Kategori',
            'categories' => $this->categoryModel->findAll()
        ];
        return view('superadmin/category/categories', $data);
    }

    public function save()
    {
        $id = $this->request->getPost('id');
        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
        ];

        if ($id) {
            $this->categoryModel->update($id, $data);
            $msg = 'Kategori berhasil diperbarui';
        } else {
            $this->categoryModel->insert($data);
            $msg = 'Kategori baru berhasil ditambahkan';
        }

        return redirect()->to('superadmin/categories')->with('success', $msg);
    }

    public function delete($id)
    {
        $this->categoryModel->delete($id);
        return redirect()->to('superadmin/categories')->with('success', 'Kategori berhasil dihapus');
    }
}