<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Response;
use Exception;

class AuthController extends ResourceController
{
    public function register()
    {
        try {
            $rules = [
                'name'     => 'required',
                'role'     => 'required',
                'email'    => 'required',
                'password' => 'required|min_length[3]'
            ];

            if (!$this->validate($rules)) {
                return $this->fail($this->validator->getErrors());
            }

            $userModel = new UserModel();

            $json = $this->request->getJSON();

            $data = [
                'name'          => $json->name ?? null,
                'email'         => $json->email ?? null,
                'role'          => $json->role ?? null,
                'dpi'           => $json->dpi ?? null,
                'group_id'      => $json->group_id ?? null,
                'password'      => password_hash($json->password ?? '', PASSWORD_DEFAULT),
            ];
            $userModel->save($data);
            return $this->respondCreated(['message' => 'User registered successfully', 'statusCode' => 200]);
        } catch (Exception $e) {
            return $this->respondCreated(['message' => $e]);
        }
    }

    public function login()
    {
        $rules = [
            'email'    => 'required',
            'password' => 'required|min_length[3]',
        ];

        if (!$this->validate($rules)) {
            return $this->fail($this->validator->getErrors());
        }
        $json = $this->request->getJSON();
        $userModel = new UserModel();
        $user = $userModel->where('dpi', $json->email)->first();
        if (!$user || !password_verify($json->password, $user['password'])) {
            $response = [
                'message' => 'Credenciales no válidas',
                'logged' => false,
            ];
            return $this->respondCreated($response);
        }

        // Aquí puedes generar un token JWT u otra lógica
        $response = [
            'message' => 'Login successful',
            'logged' => true,
            'user' => $user
        ];

        return $this->respondCreated($response);
    }

    public function users()
    {
        $userModel = new UserModel();
        $user = $userModel->findAll();
        // Aquí puedes generar un token JWT u otra lógica
        $response = [
            'message' => 'Login successful',
            'logged' => true,
            'users' => $user
        ];

        return $this->respond($response);
    }

    public function getUser($id)
    {
        try {
            $userModel = new UserModel();
            $user = $userModel->find($id);
            $response = [
                'message' => 'Login successful',
                'logged' => true,
                'user' => $user
            ];

            return $this->respond($response);
        } catch (Exception $e) {
            return $this->failServerError('An error occurred: ' . $e->getMessage());
        }
    }

    public function updateUser($id)
    {
        try {
            // Validar que el ID del usuario sea válido
            $userModel = new UserModel();
            $user = $userModel->find($id);
            if (!$user) {
                return $this->failNotFound('User not found');
            }
            $json = $this->request->getJSON();
            if ($json->password != null) {
                $json->password = password_hash($json->password, PASSWORD_DEFAULT);
            } else {
                unset($json->password);
            }

            $userModel->update($id, $json);
            return $this->respondUpdated(['message' => 'User updated successfully', 'statusCode' => 200]);
        } catch (Exception $e) {
            return $this->failServerError('An error occurred: ' . $e->getMessage());
        }
    }
}
