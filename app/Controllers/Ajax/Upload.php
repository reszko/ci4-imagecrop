<?php

namespace App\Controllers\Ajax;

use App\Controllers\BaseController;
use App\Models\FotoModel;
use CodeIgniter;

class Upload extends BaseController
{

    public function doUpload()
    {
        if ($this->request->isAJAX()) {
            $file = $this->request->getFile('userfile');
            $file->move('../public/imagens', $file->getRandomName());
            $result = [];
            if ($file->hasMoved()) {
                $result = [
                    'path' => base_url("imagens/{$file->getName()}"),
                    'fileName' => $file->getName()
                ];
            }

            echo json_encode($result);
        }
    }

    public function cropImage()
    {
        helper('text');
        if ($this->request->isAJAX()) {
            $coordenadas = $this->request->getPost();

            $fileName = $coordenadas['fileName'];

            $newName = "new-{$fileName}";
            $result = [];

            try {
                $image = \Config\Services::image()
                    ->withFile("imagens/{$fileName}")
                    ->crop($coordenadas['w'], $coordenadas['h'], $coordenadas['x'], $coordenadas['y'])
                    ->save("../public/imagens/{$newName}");
            } catch (CodeIgniter\Images\Exceptions\ImageException $e) {
                $erro =  $e->getMessage();
            }

            if ($image) {
                $fotoModel = new FotoModel();
                $fotoModel->save([
                    'nome' => $newName
                ]);

                $result = [
                    'error' => false,
                    'pathImagem' => base_url("imagens/{$newName}")
                ];
            } else {
                $result = [
                    'error' => true,
                    'mensagem' => $erro
                ];
            }

            echo json_encode($result);
        }
    }
}
