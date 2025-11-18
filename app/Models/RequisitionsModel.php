<?php
namespace App\Models;

use CodeIgniter\Model;

class RequisitionsModel extends Model
{
    protected $table      = 'tbl_job_application';
    protected $primaryKey = 'id_folio';

    protected $returnType = 'array';
   

    protected $allowedFields = [ 
        'id_folio',
        'id_user',
        'fecha_creacion',
        'empresa_solicitante',
        'centro_costos',
        'area_operativa',
        'tipo_de_personal',
        'puesto_solicitado',
        'personas_requeridas',
        'grado_estudios',
        'tipo_estudios',
        'motivo_requisicion',
        'jefe_inmediato',
        'colaborador_reemplazo',
        'cotizacion',
        'periodo',
        'salario_inicial',
        'salario_final',
        'genero_requerido',
        'estado_civil',
        'edad_minima',
        'edad_maxima',
        'licencia_conducir',
        'anios_experiencia',
        'rolar_turno',
        'trato_cli_prov',
        'manejo_personal',
        'jornada',
        'horario_inicial',
        'horario_final',
        'conocimiento_1',
        'conocimiento_2',
        'conocimiento_3',
        'conocimiento_4',
        'conocimiento_5',
        'competencia_1',
        'competencia_2',
        'competencia_3',
        'competencia_4',
        'competencia_5',
        'actividad_1',
        'actividad_2',
        'actividad_3',
        'actividad_4',
        'actividad_5',
        'estatus',
        'estatus_activo',
        'area_operativas',
        'date_answer',
    ];

    protected $useTimestamps = false;
    protected $createField = 'created_at';
    protected $updateField = 'update_at';

    /* protected $validationRules =[
    'nameUser' =>'required|alpha_space|min_length[3]|max_length[75]',
    'lastName' =>'required|alpha_space|min_length[3]|max_length[75]',
    'motherLastName'  =>'required|alpha_space|min_length[3]|max_length[75]',
    'dirUser' => 'required|min_length[8]|max_length[250]',
    'telUser'  => 'required|alpha_numeric_space|min_length[10]|max_length[10]',
    'emailUser'  => 'permit_empty|valid_email|max_length[85]',
    'passUser'  => 'required|alpha_space|min_length[3]|max_length[50]'
    ]; */

    /* protected $validationMessages = [
        'correo' =>[
            'valid_email' => 'Estimado usuario, debe ingresar un email valido'
        ]
    ]; */
    
    
   //protected $skipValidation = false;

   function request_per_user($id_user)
   {
    /* return $this->db->table('tbl_job_application')
                    ->where(['id_user' => $id_user])
                    ->get()
                    ->getResult();
 */
        /*  $db = \Config\Database::connect();
        $builder = $db->table('tbl_job_application a');
        $builder->select('*');
        $builder->join('tbl_user b', 'a. = sie_item_request.numRequest', 'left');
        $builder->where('sie_request.idRequest', $idRequest);
        $query = $builder->get()->getResult();
        $data = array('infoRequest' => $query); */

        $builder = $this->db->table('tbl_job_application a');
        $builder->select('a.*,b.name,b.surname,c.departament');
        $builder->join('tbl_users b', 'a.id_user = b.id_user', 'left');
        $builder->join('cat_departament c', 'c.id_depto = b.id_departament', 'left');
        $builder->where('a.id_user', $id_user);
        $builder->where('a.estatus_activo', 1);
        $builder->limit(1500);
       return $query = $builder->get()->getResult();
   }

}