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
                'pos'           => $json->pos,
                'territorio'           => $json->pos,
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
        $user = $userModel->where('dpi', $json->email)->where('estado', 1)->first();
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
        try {
            $userModel = new UserModel();
            $user = $userModel->select(
                'users.id,
            users.email,
            users.password,
            users.name,
            users.role,
            users.dpi,
            users.group_id,
            territorio.id_territorio,
            territorio.nombre as territorio,
            users.pos,puntosventa.name as posName '
            )->join('puntosventa', 'users.pos = puntosventa.idPos')
            ->join('territorio', 'territorio.id_territorio = users.territorio')
            ->where('estado', 1)
            ->findAll();
            $response = [
                'message' => 'Login successful',
                'logged' => true,
                'users' => $user
            ];

            return $this->respond($response);
        } catch (Exception $e) {
            return $this->failServerError('An error occurred: ' . $e->getMessage());
        }
    }

    public function getUser($id)
    {
        try {
            $userModel = new UserModel();
            $user = $userModel->where('estado', 1)->find($id);
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

    public function generate($id)
    {
        try {
            // Validar que el ID del usuario sea válido
            $userModel = new UserModel();
            $user = $userModel->find($id);
            if (!$user) {
                return $this->failNotFound('Usuario no encontrado');
            }
            $passwordRamdom = $this->generateRandomPassword();
            $data =  [
                'password' => password_hash($passwordRamdom, PASSWORD_DEFAULT),
            ];

            $userModel->update($id, $data);
            return $this->respondUpdated(['message' => $passwordRamdom, 'statusCode' => 200]);
        } catch (Exception $e) {
            return $this->failServerError('Error al generar la contraseña: ' . $e->getMessage());
        }
    }

    public function password()
    {
        try {
            // Validar que el ID del usuario sea válido
            $json = $this->request->getJSON();
            $userModel = new UserModel();
            $user = $userModel->where('dpi', $json->dpi)->first();
            if (!$user) {
                return $this->failNotFound('DPI no encontrado');
            }
            $json->password = password_hash($json->password, PASSWORD_DEFAULT);
            $userModel->update($user['id'], $json);
            return $this->respondUpdated(['message' => 'Contraseña recuperada correctamente', 'statusCode' => 200,]);
        } catch (Exception $e) {
            return $this->failServerError('Error al recuperar la contraseña: ' . $e->getMessage());
        }
    }

    private function generateRandomPassword($length = 10)
    {
        // Definir el conjunto de caracteres para generar la contraseña
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        // Generar una cadena aleatoria de la longitud especificada
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function deleteOne($id)
    {
        try {

            // Buscar el registro en el modelo
            $model = new UserModel();
            $record = $model->find($id);

            // Verificar si el registro existe
            if (!$record) {
                return $this->failNotFound('Record not found');
            }
            $model->update($id, [
                "estado" => 0
            ]);
            // Respuesta exitosa con el registro encontrado
            $response = [
                'message' => 'Record found successfully',
                'logged' => true,
                'data' => $record
            ];

            return $this->respond($response, 200); // Código HTTP 200 OK
        } catch (Exception $e) {
            return $this->failServerError('An error occurred: ' . $e->getMessage());
        }
    }
}
