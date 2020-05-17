<?php namespace App\Models;

use CodeIgniter\Model;

class FotoModel extends Model {

    protected $table = 'fotos';

    protected $allowedFields = [
        'nome'
    ];

}