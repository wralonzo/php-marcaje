<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class EmailController extends Controller {

    public function send_email() {
        $email = \Config\Services::email();

        // Configurar los detalles del correo
        $email->setFrom('invoicerenergy@gmail.com', '');
        $email->setTo('test@gmail.com');
        $email->setSubject('Automatizacion recetas');
        $email->setMessage('<p>Este es un mensaje de prueba enviado desde CodeIgniter 4 usando Gmail SMTP.</p>');

        // Enviar el correo
        if ($email->send()) {
            echo 'Correo enviado correctamente.';
        } else {
            // Mostrar el error si ocurre algún problema
            $data = $email->printDebugger(['headers']);
            print_r($data);
        }
    }
}
