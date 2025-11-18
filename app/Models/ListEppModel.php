<?php

namespace App\Models;

use CodeIgniter\Model;

class ListEppModel extends Model
{
    protected $table      = 'tbl_hse_inventary_epp';
    protected $primaryKey = 'id_product';

    protected $returnType = 'array';


    protected $allowedFields = [
        'id_product',
        'created_user',
        'code_product',
        'description_product',
        'unit_of_measurement',
        'stock_product',
        'id_cat',
        'stock_min',
        'stock_max',
        'created_at',
        'active_status',
        'image_product',
        'id_user_delete'

    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';



    function isAlreadyRegister($email)
    {
        return ($this->db->table('tbl_users')->getWhere(['email' => $email])->getRowArray() > 0) ? true : false;
    }

    function ListEppAll()
    {
    
        $db = \Config\Database::connect(); // Obtén una instancia de la base de datos

        $builder = $db->table('tbl_hse_inventary_epp'); // Reemplaza 'usuarios' con el nombre de tu tabla

        $builder->select('description_product');
        $builder->where('active_status', 1); // Reemplaza 'estado' con el nombre de tu columna y 'activo' con el valor deseado

        $query = $builder->get();

        return $query->getResult(); // Retorna los resultados de la consulta
    }

    function ListEppName($item)
    {
    
        $db = \Config\Database::connect(); // Obtén una instancia de la base de datos

        $builder = $db->table('tbl_hse_inventary_epp'); // Reemplaza 'usuarios' con el nombre de tu tabla

        $builder->select('id_product,stock_product,unit_of_measurement');
        $builder->where('description_product', $item); // Reemplaza 'estado' con el nombre de tu columna y 'activo' con el valor deseado
        $builder->where('active_status', 1); // Reemplaza 'estado' con el nombre de tu columna y 'activo' con el valor deseado

        $query = $builder->get();

        return $query->getResult(); // Retorna los resultados de la consulta
    }

    function inventary()
    {
        $db = \Config\Database::connect(); // Obtén una instancia de la base de datos

        $builder = $db->table('tbl_hse_inventary_epp'); // Reemplaza 'usuarios' con el nombre de tu tabla

        $builder->select('id_product,
        created_user,
        code_product,
        description_product,
        unit_of_measurement,
        stock_product,
        id_cat,
        stock_min,
        stock_max,
        image_product');
    
        $builder->where('active_status', 1); // Reemplaza 'estado' con el nombre de tu columna y 'activo' con el valor deseado

        $query = $builder->get();

        return $query->getResult(); // Retorna los resultados de la consulta
    }
   
   
   
}
