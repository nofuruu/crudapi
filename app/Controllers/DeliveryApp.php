<?php

namespace App\Controllers;

class DeliveryApp extends BaseController 
{
    public function index(): string
    {
        return view('Deliveryapp/index');
    }
}
