<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use CodeIgniter\RESTful\ResourceController;
use Exception;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class CompanyController extends ResourceController
{
    public function generateqr($idPos)
    {
        try {
            // Datos para generar el código QR
            $qrData = base_url() . 'company/generateqr/' . $idPos;

            // Crear una instancia de QrCode
            $qrCode = new QrCode($qrData);

            // Crear un escritor de PNG para guardar la imagen
            $writer = new PngWriter();

            // Especificar el archivo donde se guardará el código QR
            $filename = FCPATH . 'uploads/qr.png';

            // Guardar la imagen PNG del código QR
            $writer->write($qrCode)->saveToFile($filename);

            // Mostrar el código QR en la página
            echo "<img src='" . base_url('uploads/qr.png') . "' alt='Código QR' />";
        } catch (Exception $e) {
            var_dump($e);
            return 'Error generating QR code: ' . $e->getMessage();
        }
    }

    public function index()
    {
        $userModel = new CompanyModel();
        $data = $userModel->findAll();
        // Aquí puedes generar un token JWT u otra lógica
        $response = [
            'message' => 'Login successful',
            'logged' => true,
            'data' => $data
        ];

        return $this->respond($response);
    }


    public function create()
    {
        try {
            $model = new CompanyModel();
            $json = $this->request->getJSON();
            $response = $model->save($json);
            $lastId = $model->insertID();
            $qrData = $lastId;
            $qrCode = new QrCode($qrData);
            $writer = new PngWriter();
            $filename = FCPATH . 'uploads/' . $lastId . '.png';
            $writer->write($qrCode)->saveToFile($filename);
            $json = $this->request->getJSON();
            $model->update($lastId, array('qr' => base_url('uploads/' . $lastId . '.png')));
            return $this->respondCreated(['response' => $response, 'message' => 'Datos registrados', 'statusCode' => 201]);
        } catch (Exception $e) {
            return $this->respondCreated(['message' => $e]);
        }
    }

    public function upgrade($id)
    {
        try {
            // Validar que el ID del usuario sea válido
            if ($id == 7) {
                $response = [
                    'message' => 'Record found successfully',
                    'logged' => true,
                    'data' => ''
                ];
                return $this->respond($response, 200); //
            }
            $model = new CompanyModel();
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
            $model = new CompanyModel();
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
            if ($id == 7) {
                $response = [
                    'message' => 'Record found successfully',
                    'logged' => true,
                    'data' => ''
                ];
                return $this->respond($response, 200); //
            }
            // Buscar el registro en el modelo
            $model = new CompanyModel();
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
