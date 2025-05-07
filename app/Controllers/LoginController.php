<?php

namespace App\Controllers;

class LoginController extends BaseController
{
    public function index(): string
    {
        return view('Auth/v_login');
    }

    public function setSession()
    {
        $request = service('request');
        $userId = $request->getPost('user_id');
        $userName = $request->getPost('user_name');
        $jwtToken = $request->getPost('jwt_token');

        session()->set([
            'isLoggedin' => true,
            'user_id' => $userId,
            'user_name' => $userName,
            'jwt_token' => $jwtToken
        ]);

        return $this->response->setJSON(['status' => true]);
    }


    public function logout()
    {
        $token = $this->request->getHeaderLine('Authorization');

        if ($token) {
            $token = str_replace('Bearer ', '', $token);
            try {
                $client = \Config\Services::curlrequest();
                $client->post('http://10.21.1.125:8000/api/logout', [
                    'headers' => ['Authorization' => 'Bearer ' . $token]
                ]);
            } catch (\Throwable $th) {
            }
        }

        session()->destroy();
        return redirect()->to('/login');
    }


    public function register()
    {
        return view('Auth/v_register');
    }
}
