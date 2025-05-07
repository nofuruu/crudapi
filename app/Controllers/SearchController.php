<?php

namespace App\Controllers;

class SearchController extends BaseController
{
    public function index(): string
    {
        return view('produk/v_search');
    }
    
}
