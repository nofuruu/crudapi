<?php

namespace App\Controllers;

class DeliveryController extends BaseController
{
    public function index(): string
    {
        return view('Delivery/index');
    }
    public function add(): string
    {
        return view('Delivery/add-delivery');
    }
    public function create() {}

    public function edit($id) {}

    public function delete($id) {}
}
