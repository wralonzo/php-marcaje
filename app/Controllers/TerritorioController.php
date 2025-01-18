<?php

namespace App\Controllers;

use App\Models\TerritorioModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class TerritorioController extends ResourceController
{

    public function index()
    {
        $userModel = new TerritorioModel();
        $data = $userModel->findAll();
        // Aquí puedes generar un token JWT u otra lógica
        $response = [
            'message' => 'Successful',
            'logged' => true,
            'data' => $data
        ];

        return $this->respond($response);
    }


    public function create()
    {
        try {
            $model = new TerritorioModel();
            $json = $this->request->getJSON();
            $response = $model->save($json);
            return $this->respondCreated(['response' => $response, 'message' => 'Datos registrados', 'statusCode' => 201]);
        } catch (Exception $e) {
            return $this->respondCreated(['message' => $e]);
        }
    }

    public function upgrade($id)
    {
        try {
            $model = new TerritorioModel();
            $record = $model->find($id);
            if (!$record) {
                return $this->failNotFound('record not found');
            }
            $json = $this->request->getJSON();
            $model->update($id, $json);
            return $this->respondUpdated(['message' => 'record updated successfully', 'statusCode' => 200]);
        } catch (Exception $e) {
            return $this->failServerError('An error occurred: ' . $e->getMessage());
        }
    }

    public function find($id)
    {
        try {

            // Buscar el registro en el modelo
            $model = new TerritorioModel();
            $record = $model->find($id);

            // Verificar si el registro existe
            if (!$record) {
                return $this->failNotFound('Record not found');
            }

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

    public function deleteOne($id)
    {
        try {
            // Buscar el registro en el modelo
            $model = new TerritorioModel();
            $record = $model->find($id);

            // Verificar si el registro existe
            if (!$record) {
                return $this->failNotFound('Record not found');
            }
            $model->delete($id);
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
