<?php

namespace App\Controllers;

class ProdukController extends BaseController
{
    public function index(): string
    {
        return view('produk/v_produk');
    }

    public function edit()
    {
        return view('produk/edit_produk.php');
    }
}
