<?php

namespace App\Controllers\Stationery;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\StationeryModel;
use App\Models\StationeryInventoryModel;
use App\Models\StationeryInventoryModelVH;
use App\Models\StationeryRequestsModel;
use App\Models\StationeryRequestsModelVH;
use App\Models\StationeryEntriesModel;
use App\Models\StationeryEntriesModelVH;
use App\Models\StationeryDeparturesModel;
use App\Models\StationeryItemsModel;
use App\Models\StationeryItemsModelVH;
use App\Models\ModificationStationeryModel;
use Spipu\Html2Pdf\Html2Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Stationery extends BaseController
{
    protected $helpers = ['form'];
    public function __construct()
    {
        require_once APPPATH . '/Libraries/vendor/autoload.php';
        $this->userModel = new UserModel();
        $this->stationeryModel = new StationeryModel();
        $this->stationeryEntriesModel = new StationeryEntriesModel();
        $this->stationeryDeparturesModel = new StationeryDeparturesModel();
        $this->inventoryModel = new StationeryInventoryModel();
        $this->stationeryRequestModel = new StationeryRequestsModel();
        $this->stationeryitemsModel = new StationeryItemsModel();
        $this->modificationStationeryModel = new ModificationStationeryModel();

        $this->inventoryModelVH = new StationeryInventoryModelVH();
        $this->stationeryRequestModelVH = new StationeryRequestsModelVH();
        $this->stationeryitemsModelVH = new StationeryItemsModelVH();
        $this->stationeryEntriesModelVH = new StationeryEntriesModelVH();

        $this->db = \Config\Database::connect();
        $this->is_logged = session()->is_logged ? true : false;
    }

    public function stationery()
    {
        $stations = $this->stationeryModel->where('active_status', 1)->find();
        $builder = $this->db->table('tbl_users a');
        $builder->select('a.name,a.surname,a.second_surname,a.email,a.payroll_number,b.departament,c.clave_cost_center');
        $builder->join('cat_departament b', 'b.id_depto = a.id_departament', 'left');
        $builder->join('cat_cost_center c', 'c.id_cost_center = a.id_cost_center', 'left');
        $builder->where('a.id_user', session()->id_user);
        $builder->limit(1);
        $query = $builder->get()->getResult();
        $data = ["datos" => $query, "catalogo" => $stations];
        //var_dump($data);
        return ($this->is_logged) ?  view('stationery/stationery', $data) : redirect()->to(site_url());
    }


    /*-------vvvvvvvvvvvvvvvvvvvvvvvvvvvvv---------*/
    public function stationeryVH()
    {
        $stations = $this->stationeryModel->where('active_status', 1)->find();
        $builder = $this->db->table('tbl_users a');
        $builder->select('a.name,a.surname,a.second_surname,a.email,a.payroll_number,b.departament,c.cost_center');
        $builder->join('cat_departament b', 'b.id_depto = a.id_departament', 'left');
        $builder->join('cat_cost_center c', 'c.id_cost_center = a.id_cost_center', 'left');
        $builder->where('a.id_user', session()->id_user);
        $builder->limit(1);
        $query = $builder->get()->getResult();
        $data = ["datos" => $query, "catalogo" => $stations];
        //var_dump($data);
        return ($this->is_logged) ?  view('stationery/vh_stationery', $data) : redirect()->to(site_url());
    }
    /*--------^^^^^^^^^^^^^^^^^^^^^^--------*/


    public function viewInventary()
    {
        $builder = $this->db->table('cat_stationery_category');
        $builder->select('id_cat,category');
        $builder->where('active_status', 1);
        $datos = $builder->get()->getResult();
        $data = ["data" => $datos];

        return ($this->is_logged) ?  view('stationery/stationery_inventory', $data) : redirect()->to(site_url());
    }

    /*----------vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv--------*/
    public function viewInventaryVH()
    {
        $builder = $this->db->table('cat_stationery_category');
        $builder->select('id_cat,category');
        $builder->where('active_status', 1);
        $datos = $builder->get()->getResult();
        $data = ["data" => $datos];

        return ($this->is_logged) ?  view('stationery/vh_stationery_inventory', $data) : redirect()->to(site_url());
    }
    /*--------^^^^^^^^^^^^^^^^^^^^^^--------*/

    public function viewEntries()
    {
        return ($this->is_logged) ?  view('stationery/stationery_entrances_and_exits') : redirect()->to(site_url());
    }

    public function viewReports()
    {
        return ($this->is_logged) ?  view('stationery/stationery_reports') : redirect()->to(site_url());
    }

    /*----------vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv--------*/
    public function viewReportsVH()
    {
        return ($this->is_logged) ?  view('stationery/vh_stationery_reports') : redirect()->to(site_url());
    }
    /*--------^^^^^^^^^^^^^^^^^^^^^^--------*/



    public function viewMyRequests()
    {
        return ($this->is_logged) ?  view('stationery/stationery_requests') : redirect()->to(site_url());
    }


    public function viewAuthorize()
    {
        return ($this->is_logged) ?  view('stationery/view_authorize') : redirect()->to(site_url());
    }


    /*----------vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv--------*/
    public function viewMyRequestsVH()
    {
        return ($this->is_logged) ?  view('stationery/vh_stationery_requests') : redirect()->to(site_url());
    }
    /*--------^^^^^^^^^^^^^^^^^^^^^^--------*/



    public function viewAllRequests()
    {
        try {
            return ($this->is_logged) ?  view('stationery/all_requests') : redirect()->to(site_url());
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }
    /*----------vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv--------*/
    public function viewAllRequestsVH()
    {
        try {
            return ($this->is_logged) ?  view('stationery/vh_all_requests') : redirect()->to(site_url());
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }
    /*-----^----^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^--------*/


    public function newArticle()
    {
        try {
            $category = trim($this->request->getPost('categoria'));
            $unit = trim($this->request->getPost('unidad'));
            $description = trim($this->request->getPost('descripcion'));
            $amount = trim($this->request->getPost('cantidad'));
            $min = trim($this->request->getPost('minimo'));
            $max = trim($this->request->getPost('maximo'));
            $date = date("Y-m-d H:i:s");

            switch ($category) {

                case 1:
                    $carpeta = '../public/images/papeleria/Block/';
                    break;
                case 2:
                    $carpeta = '../public/images/papeleria/Boligrafos/';
                    break;
                case 3:
                    $carpeta = '../public/images/papeleria/Papel/';
                    break;
                case 4:
                    $carpeta = '../public/images/papeleria/Carpetas/';
                    break;
                case 5:
                    $carpeta = '../public/images/papeleria/Folder/';
                    break;
                case 6:
                    $carpeta = '../public/images/papeleria/Marcadores/';
                    break;
                case 7:
                    $carpeta = '../public/images/papeleria/Sobres/';
                    break;
                case 8:
                    $carpeta = '../public/images/papeleria/Varios/';
                    break;
                case 9:
                    $carpeta = '../public/images/papeleria/Cordones/';
                    break;
                default:
                    $carpeta = '../public/uploads/';
                    break;
            }

            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            };

            if ($imageFile = $this->request->getFile('file')) {
                $originalName = $imageFile->getClientName();
                $ext = $imageFile->getClientExtension();
                $type = $imageFile->getClientMimeType();
                $newName = $imageFile->getRandomName();
                $imageFile = $imageFile->move($carpeta,  $originalName);
                $path = $carpeta . "/" . $originalName;
            } else {
                $path = "NA";
            }
            $dataInsert = [
                "created_user" => session()->id_user,
                "description_product" => $description,
                "unit_of_measurement" => $unit,
                "stock_product" => $amount,
                "id_cat" => $category,
                "stock_min" => $min,
                "stock_max" => $max,
                "image_product" => $path,
                "created_at" => $date
            ];

            return ($this->inventoryModel->insert($dataInsert)) ? true : false;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
    /*-----v-v-v-v-v-v-v-v-v-v-v-v----*/
    public function newArticleVH()
    {
        try {
            $category = trim($this->request->getPost('categoria'));
            $description = trim($this->request->getPost('descripcion'));
            $amount = trim($this->request->getPost('cantidad'));
            $min = trim($this->request->getPost('minimo'));
            $max = trim($this->request->getPost('maximo'));
            $date = date("Y-m-d H:i:s");

            switch ($category) {

                case 1:
                    $carpeta = '../public/images/papeleria/Block/';
                    break;
                case 2:
                    $carpeta = '../public/images/papeleria/Boligrafos/';
                    break;
                case 3:
                    $carpeta = '../public/images/papeleria/Papel/';
                    break;
                case 4:
                    $carpeta = '../public/images/papeleria/Carpetas/';
                    break;
                case 5:
                    $carpeta = '../public/images/papeleria/Folder/';
                    break;
                case 6:
                    $carpeta = '../public/images/papeleria/Marcadores/';
                    break;
                case 7:
                    $carpeta = '../public/images/papeleria/Sobres/';
                    break;
                case 8:
                    $carpeta = '../public/images/papeleria/Varios/';
                    break;
                case 9:
                    $carpeta = '../public/images/papeleria/Cordones/';
                    break;
                default:
                    $carpeta = '../public/uploads/';
                    break;
            }

            if (!file_exists($carpeta)) {
                mkdir($carpeta, 0777, true);
            };

            if ($imageFile = $this->request->getFile('file')) {
                $originalName = $imageFile->getClientName();
                $ext = $imageFile->getClientExtension();
                $type = $imageFile->getClientMimeType();
                $newName = $imageFile->getRandomName();
                $imageFile = $imageFile->move($carpeta,  $originalName);
                $path = $carpeta . "/" . $originalName;
            } else {
                $path = "NA";
            }
            $dataInsert = [
                "created_user" => session()->id_user,
                "description_product" => $description,
                "stock_product" => $amount,
                "id_cat" => $category,
                "stock_min" => $min,
                "stock_max" => $max,
                "image_product" => $path,
                "created_at" => $date
            ];

            return ($this->inventoryModelVH->insert($dataInsert)) ? true : false;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function reportEntries()
    {
        $data = json_decode(stripslashes($this->request->getPost('data')));

        if ($data->categoria == 1) {
            $NombreArchivo = "entradas_papeleria.xlsx";
            $table = 'tbl_stationery_entries';
            $date_colum = 'FECHA ENTRADA';
            $title = 'Entradas';
        } else {
            $NombreArchivo = "salidas_papeleria.xlsx";
            $table = 'tbl_stationery_departures';
            $date_colum = 'FECHA SALIDA';
            $title = 'Salidas';
        }



        $query = $this->db->query("SELECT id_user, id_product,code_epicor, product, amount, observations, created_at                         
                                    FROM
                                       $table
                                    WHERE
                                    created_at BETWEEN '" . $data->inicio_entradas . "' AND '" . $data->final_entradas . "'");
        $reporte = $query->getResult();

        $cont = 2;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:G1');

        $color = new \PhpOffice\PhpSpreadsheet\Style\Color('#4472C4');
        $sheet->getStyle("A1:G1")->getFont()->setBold(true)->setName('Calibri')->setSize(11)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle("A1:G1")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle("A1:G1")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('4472C4');
        $sheet->getStyle("A1:G1")->getBorders()->getTop()->setColor($color);

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $sheet->setTitle($title);

        $sheet->setCellValue('A1', 'USUARIO');
        $sheet->setCellValue('B1', 'ITEM');
        $sheet->setCellValue('C1', 'EPICOR');
        $sheet->setCellValue('D1', 'PRODUCTO');
        $sheet->setCellValue('E1', 'CANTIDAD');
        $sheet->setCellValue('F1', 'OBSERVACIONES');
        $sheet->setCellValue('G1', $date_colum);


        foreach ($reporte as $key => $value) {
            $celdaA = 'A' . $cont;
            $celdaB = 'B' . $cont;
            $celdaC = 'C' . $cont;
            $celdaD = 'D' . $cont;
            $celdaE = 'E' . $cont;
            $celdaF = 'F' . $cont;
            $celdaG = 'G' . $cont;

            $sheet->setCellValue($celdaA, $value->id_user);
            $sheet->setCellValue($celdaB, $value->id_product);
            $sheet->setCellValue($celdaC, $value->code_epicor);
            $sheet->setCellValue($celdaD, $value->product);
            $sheet->setCellValue($celdaE, $value->amount);
            $sheet->setCellValue($celdaF, $value->observations);
            $sheet->setCellValue($celdaG, $value->created_at);


            $cont++;
        }


        $writer = new Xlsx($spreadsheet);
        $writer->save($NombreArchivo);
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=" . basename($NombreArchivo));
        header("Expires:0");
        header("Cache-Control: must-revalidate");
        header("Pragma: public");
        header("Content-Length:" . filesize($NombreArchivo));
        flush();
        readfile($NombreArchivo);
        exit;
    }



    public function entriesAll()
    {
        try {
            $builder = $this->db->table('tbl_stationery_entries');
            $builder->select('id,product,id_product,code_epicor,amount,observations,created_at');
            $builder->where('active_status', 1);
            $builder->limit(1000);
            $data = $builder->get()->getResult();
            return (count($data) > 0) ? json_encode($data) : json_encode("error");
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }
    public function departuresAll()
    {
        try {
            $builder = $this->db->table('tbl_stationery_departures');
            $builder->select('id,product,id_product,code_epicor,amount,observations,created_at');
            $builder->where('active_status', 1);
            $builder->limit(1000);
            $data = $builder->get()->getResult();
            return (count($data) > 0) ? json_encode($data) : json_encode("error");
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }
    /*-----^-^-^-^-^-^-^-^-^-^-^-^----*/
    public function allRequests()
    {
        try {
            $builder = $this->db->table('tbl_stationery_requests');
            $builder->select('id_request,name,email,cost_center,departament,created_at,request_status');
            $builder->where('active_status', 1);
            $builder->limit(1000);
            $data = $builder->get()->getResult();
            return (count($data) > 0) ? json_encode($data) : json_encode("error");
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    /*------------vvvvvvvvvvvv-------------*/
    public function allRequestsVH()
    {
        try {
            $builder = $this->db->table('tbl_stationery_requests_vh');
            $builder->select('id_request,name,email,cost_center,departament,created_at,request_status');
            $builder->where('active_status', 1);
            $builder->limit(1000);
            $data = $builder->get()->getResult();
            return (count($data) > 0) ? json_encode($data) : json_encode("error");
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }
    /*----------^^^^^^^^^^^^^^-----------*/

    public function myRequests()
    {
        try {
            $builder = $this->db->table('tbl_stationery_requests');
            $builder->select('id_request,name,cost_center,departament,created_at,request_status');
            $builder->where('id_user', session()->id_user);
            $builder->where('active_status', 1);
            $builder->limit(1000);
            $data = $builder->get()->getResult();
            return (count($data) > 0) ? json_encode($data) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    /*------------v-v-v-v-v-v-v-v-v-v-v-v-------------*/
    public function myRequestsVH()
    {
        $builder = $this->db->table('tbl_stationery_requests_vh');
        $builder->select('id_request,name,cost_center,departament,created_at,request_status');
        $builder->where('id_user', session()->id_user);
        $builder->where('active_status', 1);
        $builder->limit(1000);
        $data = $builder->get()->getResult();
        return (count($data) > 0) ? json_encode($data) : json_encode("error");
    }
    /*-----------^-^-^-^-^-^-^-^-^-^-^-^-^-^----------*/

    public function answerRequest()
    {
        try {
            $id_folio = trim($this->request->getPost('id_folio'));
            $date = trim($this->request->getPost('fecha_entrega'));
            $obs = trim($this->request->getPost('obs_entrega'));
            $email = trim($this->request->getPost('email'));
            $status = trim($this->request->getPost('estado'));
            $user = trim($this->request->getPost('usuario'));


            ($status == 3) ? $date = "" : $date;

            $dataUpdate = [
                "request_status" => $status,
                "obs_stationery" => $obs,
                "delivery_date" => $date,
                "id_answer" => session()->id_user,
                "answer_at" => date("Y-d-m H:i:s"),
            ];

            if ($status == 4) {

                $builder = $this->db->table('tbl_stationery_items');
                $builder->select('id_product,quantity');
                $builder->where('id_request', $id_folio);
                $dataRequest = $builder->get()->getResult();

                foreach ($dataRequest as $key => $value) {

                    $builder = $this->db->table('tbl_stationery_inventory');
                    $builder->set('stock_product', 'stock_product +' . $value->quantity, false);
                    $builder->where('id_product', $value->id_product);
                    $builder->update();
                }
            }

            $this->stationeryRequestModel->update($id_folio, $dataUpdate);

            $data = [
                "folio" => $id_folio,
                "request_status" => $status,
                "obs_stationery" => $obs,
                "delivery_date" => $date
            ];

            $res = $this->emailRespuesta($email, $user, $data);

            return ($res == true) ? $res : false;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function emailRespuesta($email = null, $user = null, $data = null)
    {
        /* USAMOS EL HELPER CAMBIAR EMAIL PARA LOS CORREOS DE GRUPOWALWORTH */
        $email = changeEmail($email);

        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";
        try {

            //Set SMTP Options
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            //Server settings
            // Enable verbose debug output
            //$mail->SMTPDebug = 2;
            // Set mailer to use SMTP
            $mail->isSMTP();
            // Enable SMTP authentication
            $mail->SMTPAuth = false;
            // Specify main and backup SMTP servers
            $mail->Host = 'localhost';
            // SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
            // $mail->Username = 'requisiciones@grupowalworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
           // $mail->Password = '2contodo';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;

            //Recipients
            $mail->setFrom('requisiciones@walworth.com', 'Respuesta|Papelería');
            // Add a recipient
            $mail->addAddress($email, $user);
            // Name is optional
            //$mail->addAddress('adgonzalez@grupowalworth.com', 'Adolfo Gonzalez');
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
           // $mail->addBCC('gmendoza@walworth.com.mx');
            $mail->addBCC('rcruz@walworth.com.mx');
            $mail->addAddress('bmartinez@walworth.com.mx', 'Berenice Martinez');
            
            $mail->addAddress('krubio@walworth.com.mx', 'Karen Rubio');
            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //    $mail->addAttachment($data["imss"]);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/papeleria_respuesta', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  'Respuesta Requisición de Papelería';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function authorizedStationeryAll()
    {
        try {
            $id_user = session()->id_user;
            $query = $this->db->query("SELECT id_request,payroll_number,name,email,cost_center,departament,request_status,created_at,active_status
                                        FROM
                                        tbl_stationery_requests
                                        WHERE
                                         payroll_number IN (
                                        SELECT DISTINCT
                                            payroll_number
                                        FROM
                                        tbl_stationery_permissions
                                        WHERE
                                        id_manager = $id_user OR id_director = $id_user) AND active_status = 1");

            $data = $query->getResult();

            return (count($data)) ? json_encode($data) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function authorizedStationery()
    {
        try {

            $id_request = trim($this->request->getPost('id_request'));
            $query1 = $this->db->query("SELECT a.id_request_item,a.quantity,b.description_product,c.category AS categoria
                                        FROM
                                        tbl_stationery_items AS a
                                        LEFT JOIN tbl_stationery_inventory AS b
                                        ON  a.id_product = b.id_product
                                        LEFT JOIN cat_stationery_category AS c
                                        ON  c.id_cat = b.id_cat
                                        WHERE a.id_request=" . $id_request);
            $dataRequest =  $query1->getResultArray();

            foreach ($dataRequest as $key => $value) {
                $groups[$value['categoria']][$value['description_product']] = $value['quantity'];
            }

            $data = ["pedido" => $groups];
            return (count($data)) ? json_encode($groups) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    public function authorized()
    {
        $id_request = trim($this->request->getPost('id_folio'));
        $status = trim($this->request->getPost('autorizacion'));

        $data = [
            "request_status" => $status,
            "id_authorize" => session()->id_user,
            "authorize_at" => date("Y-m-d H:i:s"),
        ];

        $insertData = $this->stationeryRequestModel->update($id_request, $data);

        if ($status == 4) {

            $builder = $this->db->table('tbl_stationery_items');
            $builder->select('id_product,quantity');
            $builder->where('id_request', $id_request);
            $dataRequest = $builder->get()->getResult();

            foreach ($dataRequest as $key => $value) {

                $builder = $this->db->table('tbl_stationery_inventory');
                $builder->set('stock_product', 'stock_product +' . $value->quantity, false);
                $builder->where('id_product', $value->id_product);
                $builder->update();
            }
        }

        $this->emailNotification($id_request, null, null, 2);

        return ($insertData) ? json_encode(true) : json_encode(false);
    }


    public function inventaryAll()
    {
        $builder = $this->db->table('tbl_stationery_inventory');
        $builder->select('*');
        $builder->where('active_status', 1);
        $data = $builder->get()->getResult();
        return (count($data) > 0) ? json_encode($data) : json_encode("error");
    }

    /*----------VVVVVVVVVVVVVVVVVVVVVVVVVVVV----------------*/
    public function inventaryAllVH()
    {
        $builder = $this->db->table('tbl_stationery_inventory_vh');
        $builder->select('*');
        $data = $builder->get()->getResult();
        return (count($data) > 0) ? json_encode($data) : json_encode("error");
    }
    /*---------^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^---------------*/

    public function editProduct($id_product)
    {
        $userData = $this->inventoryModel->find($id_product);
        return ($userData) ? json_encode($userData) : 'error';
    }
    /*---------vvvvvvvvvvvvvvvvvvvvvvv-----------*/
    public function editProductVH($id_product)
    {
        $userData = $this->inventoryModelVH->find($id_product);
        return ($userData) ? json_encode($userData) : 'error';
    }
    /*----------^^^^^^^^^^^^^^^^^^^^^^^----------*/
    public function stationeryCategory()
    {
        $id_category = trim($this->request->getPost('id_categoria'));
        $builder = $this->db->table('tbl_stationery_inventory');
        $builder->select('id_product,description_product,image_product,stock_product');
        $builder->where('id_cat', $id_category);
        $builder->where('active_status', 1);
        $data = $builder->get()->getResult();
        return (count($data) > 0) ? json_encode($data) : json_encode("error");
    }
    public function imageStationery()
    {
        $id = trim($this->request->getPost('id_producto'));
        $data = $this->inventoryModel->where('id_product', $id)->first();
        return ($data != null) ? json_encode($data) : json_encode("error");
    }
    public function InventoryStationery()
    {
        $id = trim($this->request->getPost('id_producto'));
        $nom = trim($this->request->getPost('description_product'));
        if ($id != null) {
            $data = $this->inventoryModel->select('stock_product')->where('id_product', $id)->first();
        }
        if ($nom != null) {
            $data = $this->inventoryModel->select('stock_product')->where('description_product', $nom)->first();
        }
        return ($data != null) ? json_encode($data) : json_encode("error");
    }

    /*----------vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv--------*/
    public function stationeryCategoryVH()
    {
        $id_category = trim($this->request->getPost('id_categoria'));
        $builder = $this->db->table('tbl_stationery_inventory_vh');
        $builder->select('id_product,description_product,image_product,stock_product');
        $builder->where('id_cat', $id_category);
        $builder->where('active_status', 1);
        $data = $builder->get()->getResult();
        return (count($data) > 0) ? json_encode($data) : json_encode("error");
    }
    public function imageStationeryVH()
    {
        $id = trim($this->request->getPost('id_producto'));
        $data = $this->inventoryModelVH->where('id_product', $id)->first();
        return ($data != null) ? json_encode($data) : json_encode("error");
    }
    public function InventoryStationeryVH()
    {
        $id = trim($this->request->getPost('id_producto'));
        $nom = trim($this->request->getPost('description_product'));
        if ($id != null) {
            $data = $this->inventoryModelVH->select('stock_product')->where('id_product', $id)->first();
        }
        if ($nom != null) {
            $data = $this->inventoryModelVH->select('stock_product')->where('description_product', $nom)->first();
        }
        return ($data != null) ? json_encode($data) : json_encode("error");
    }
    /*-------------^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^--------------*/



    public function parameters()
    {
        try {

            $id_folio = trim($this->request->getPost('id_folio'));
            $product = trim($this->request->getPost('producto'));
            $maximum = trim($this->request->getPost('maximo'));
            $minimum = trim($this->request->getPost('minimo'));
            $unit_of_measurement = trim($this->request->getPost('unidad_medida'));
            $date = date("Y-m-d H:i:s");

            $dataM = [
                "id_user" => session()->id_user,
                "id_product" => $id_folio,
                "product" => $product,
                "maximum" => $maximum,
                "minimum" => $minimum,
                "unit_of_measurement" => $unit_of_measurement,
                "created_at" => $date
            ];

            $insertData = $this->modificationStationeryModel->insert($dataM);
            $data = ['stock_max' => $maximum, "stock_min" => $minimum, "unit_of_measurement" => $unit_of_measurement];
            if ($insertData) {
                return ($this->inventoryModel->update($id_folio, $data)) ? json_encode(true) : json_encode('error');
            } else {
                return json_encode("error");
            }
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }
    /*--------------vvvvvvvvvvvvvvvvvvvvvv------*/
    public function parametersVH()
    {
        try {

            $id_folio = trim($this->request->getPost('id_folio'));
            $product = trim($this->request->getPost('producto'));
            $maximum = trim($this->request->getPost('maximo'));
            $minimum = trim($this->request->getPost('minimo'));
            $date = date("Y-m-d H:i:s");

            $dataM = [
                "id_user" => session()->id_user,
                "id_product" => $id_folio,
                "product" => $product,
                "maximum" => $maximum,
                "minimum" => $minimum,
                "created_at" => $date
            ];

            $insertData = $this->modificationStationeryModel->insert($dataM);
            $data = ['stock_max' => $maximum, "stock_min" => $minimum];
            if ($insertData) {
                return ($this->inventoryModelVH->update($id_folio, $data)) ? json_encode(true) : json_encode('error');
            } else {
                return json_encode("error");
            }
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }
    /*------^^^^^^^^^^^^^^^^^^^^^^^^------------*/

    public function entries()
    {
        try {
            $code_epicor = trim($this->request->getPost('code_epicor'));
            $id_folio = trim($this->request->getPost('id_producto'));
            $product = trim($this->request->getPost('producto'));
            $quantity = trim($this->request->getPost('cantidad'));
            $obs = trim($this->request->getPost('observacion'));

            if ($obs == "undefined") {
                $obs = "";
            }

            $date = date("Y-m-d H:i:s");

            $dataM = [
                "id_user" => session()->payroll_number,
                "code_epicor" => $code_epicor,
                "id_product" => $id_folio,
                "product" => $product,
                "amount" => $quantity,
                "operation" => "entrada",
                "observations" => $obs,
                "created_at" => $date
            ];

            $insertData = $this->stationeryEntriesModel->insert($dataM);

            $builder = $this->db->table('tbl_stationery_inventory');
            $builder->select('id_product,stock_product');
            $builder->where('id_product', $id_folio);
            $builder->where('active_status', 1);
            $datas = $builder->get()->getResult();

            foreach ($datas as $key => $value) {
                $data = ['stock_product' => $value->stock_product + $quantity];
            }


            if ($insertData) {
                return ($this->inventoryModel->update($id_folio, $data)) ? json_encode("ok") : json_encode('error');
            } else {
                return json_encode("error");
            }
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }
    /*-----------vvvvvvvvvvvvvvvvv-------------*/
    public function entriesVH()
    {
        try {

            $id_folio = trim($this->request->getPost('id_producto'));
            $product = trim($this->request->getPost('producto'));
            $quantity = trim($this->request->getPost('cantidad'));
            $obs = trim($this->request->getPost('observacion'));
            $date = date("Y-m-d H:i:s");

            $dataM = [
                "id_user" => session()->id_user,
                "id_product" => $id_folio,
                "product" => $product,
                "amount" => $quantity,
                "operation" => "entrada",
                "observations" => $obs,
                "created_at" => $date
            ];

            $insertData = $this->stationeryEntriesModelVH->insert($dataM);

            $builder = $this->db->table('tbl_stationery_inventory');
            $builder->select('id_product,stock_product');
            $builder->where('id_product', $id_folio);
            $builder->where('active_status', 1);
            $datas = $builder->get()->getResult();

            foreach ($datas as $key => $value) {
                $data = ['stock_product' => $value->stock_product + $quantity];
            }


            if ($insertData) {
                return ($this->inventoryModelVH->update($id_folio, $data)) ? json_encode("ok") : json_encode('error');
            } else {
                return json_encode("error");
            }
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }
    /*--------^^^^^^^^^^^^^^^^^^^^^^^^^^^^^-----*/

    public function mysRequests()
    {
        try {
            $data  = $this->stationeryRequestModel->where('id_user', session()->id_user)->findAll();
            return json_encode($data);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }
    public function stationeryRequest()
    {
        $payroll_number = trim($this->request->getPost('num_nomina'));
        $name = trim($this->request->getPost('nombre_pape'));
        $email = trim($this->request->getPost('email_pape'));
        $cost_center = trim($this->request->getPost('centro_costo'));
        $depto = trim($this->request->getPost('depto_pape'));
        $obs = trim($this->request->getPost('observaciones'));
        $ProductMin = [];
        $bandera = 0;
        $date = date("Y-m-d H:i:s");
        $data = [
            "id_user" => session()->id_user,
            "payroll_number" => $payroll_number,
            "name" => $name,
            "email" => $email,
            "departament" => $depto,
            "cost_center" => $cost_center,
            "obs_request" => $obs,
            "created_at" => $date
        ];

        $insertData = $this->stationeryRequestModel->insert($data);
        $id_request = $this->db->insertID();
        if ($insertData) {
            $category = $this->request->getPost('categoria');
            $description = $this->request->getPost('descripcion');
            $amount = $this->request->getPost('cantidad');
            $unit = $this->request->getPost('medida');

            $builder =  $this->stationeryitemsModel->table('tbl_stationery_items');

            for ($i = 0; $i < count($amount); $i++) {

                $dataItem = [
                    'id_request' => $id_request,
                    'category' => $category[$i],
                    'id_product' => $description[$i],
                    'quantity' => $amount[$i],
                    'unit' => $unit[$i],
                    'created_at' => $date
                ];
                $insertItem = $builder->insert($dataItem);

                $builder1 = $this->db->table('tbl_stationery_inventory');
                $builder1->select('id_product,description_product,stock_product,unit_of_measurement,stock_min');
                $builder1->where('id_product', $description[$i]);
                $builder1->where('active_status', 1);
                $dataStock = $builder1->get()->getResult();

                foreach ($dataStock as $key => $value) {
                    $amount2 = $value->stock_product - $amount[$i];

                    if ($amount2 <= $value->stock_min) {
                        $bandera = 1;
                        array_push($ProductMin, array("producto" => $value->description_product, "cantidad" => $amount2, "unidad" => $value->unit_of_measurement));
                    }


                    $data2 = ['stock_product' => $amount2];
                    $this->inventoryModel->update($description[$i], $data2);
                }
            }

            if ($bandera > 0) {
                $this->emailNotificationMinimo($ProductMin);
            }

            if ($insertItem) {

                /* $number = session()->payroll_number;
                $query = $this->db->query("SELECT DISTINCT id_manager
                FROM tbl_stationery_permissions WHERE payroll_number = $number");
                $idUser = $query->getResultArray();
                foreach ($idUser as $key => $value) {
                    $builder = $this->db->table('tbl_users');
                    $builder->select('email,name,surname');
                    $builder->where('id_user', $value["id_manager"]);
                    $builder->limit(1);
                    $email = $builder->get()->getResultArray();

                    foreach ($email as $key => $value1) {

                        $user_name = $value1["name"] . " " . $value1["surname"];

                        $this->emailNotification($id_request, $value1["email"], $user_name, 1);
                    }
                } */

                $this->emailNotification($id_request, null, null, 2);


            }

            return ($insertItem) ? json_encode(true) : json_encode(false);
        }
    }

    /*----------------vvvvvvvvvvvvvvvvvvvvvvvv--------------*/
    public function stationeryRequestVH()
    {
        $payroll_number = trim($this->request->getPost('num_nomina'));
        $name = trim($this->request->getPost('nombre_pape'));
        $email = trim($this->request->getPost('email_pape'));
        $cost_center = trim($this->request->getPost('centro_costo'));
        $depto = trim($this->request->getPost('depto_pape'));
        $date = date("Y-m-d H:i:s");
        $data = [
            "id_user" => session()->id_user,
            "payroll_number" => $payroll_number,
            "name" => $name,
            "email" => $email,
            "departament" => $depto,
            "cost_center" => $cost_center,
            "created_at" => $date
        ];

        $insertData = $this->stationeryRequestModelVH->insert($data);
        $id_request = $this->db->insertID();
        if ($insertData) {
            $category = $this->request->getPost('categoria');
            $description = $this->request->getPost('descripcion');
            $amount = $this->request->getPost('cantidad');

            $builder =  $this->stationeryitemsModelVH->table('tbl_stationery_items_vh');

            for ($i = 0; $i < count($amount); $i++) {

                $dataItem = [
                    'id_request' => $id_request,
                    'category' => $category[$i],
                    'id_product' => $description[$i],
                    'quantity' => $amount[$i],
                    'created_at' => $date
                ];
                $insertItem = $builder->insert($dataItem);

                $builder1 = $this->db->table('tbl_stationery_inventory_vh');
                $builder1->select('id_product,stock_product');
                $builder1->where('id_product', $description[$i]);
                $builder1->where('active_status', 1);
                $dataStock = $builder1->get()->getResult();

                foreach ($dataStock as $key => $value) {
                    $amount2 = $value->stock_product - $amount[$i];
                    $data2 = ['stock_product' => $amount2];
                    $this->inventoryModel->update($description[$i], $data2);
                }
            }


            $this->emailNotification($id_request);

            return ($insertItem) ? json_encode(true) : json_encode(false);
        }
    }
    /*---------^^^^^^^^^^^^^^^^^^^^^^^^------------*/

    public function departures()
    {

        try {
            $code_epicor = trim($this->request->getPost('code_epicor'));
            $id_folio = trim($this->request->getPost('id_producto'));
            $product = trim($this->request->getPost('producto'));
            $quantity = trim($this->request->getPost('cantidad'));
            $obs = trim($this->request->getPost('observacion'));
            $date = date("Y-m-d H:i:s");

            if ($obs == "undefined") {
                $obs = "";
            }


            $dataM = [
                "id_user" => session()->id_user,
                "id_product" => $id_folio,
                "code_epicor" => $code_epicor,
                "product" => $product,
                "amount" => $quantity,
                "operation" => "Salida",
                "observations" => $obs,
                "created_at" => $date
            ];

            $insertData = $this->stationeryDeparturesModel->insert($dataM);

            $builder = $this->db->table('tbl_stationery_inventory');
            $builder->select('id_product,stock_product');
            $builder->where('id_product', $id_folio);
            $builder->where('active_status', 1);
            $datas = $builder->get()->getResult();

            foreach ($datas as $key => $value) {
                $data = ['stock_product' => $value->stock_product - $quantity];
            }


            if ($insertData) {
                return ($this->inventoryModel->update($id_folio, $data)) ? json_encode(true) : json_encode('error');
            } else {
                return json_encode("error");
            }
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }
    /*---v-v-v-v-v-v-v-v-v-v-v-v-v-v-v-v-v-----*/
    public function departuresVH()
    {

        try {

            $id_folio = trim($this->request->getPost('id_producto'));
            $product = trim($this->request->getPost('producto'));
            $quantity = trim($this->request->getPost('cantidad'));
            $obs = trim($this->request->getPost('observacion'));
            $date = date("Y-m-d H:i:s");


            $dataM = [
                "id_user" => session()->id_user,
                "id_product" => $id_folio,
                "product" => $product,
                "amount" => $quantity,
                "operation" => "Salida",
                "observations" => $obs,
                "created_at" => $date
            ];

            $insertData = $this->stationeryDeparturesModel->insert($dataM);

            $builder = $this->db->table('tbl_stationery_inventory_vh');
            $builder->select('id_product,stock_product');
            $builder->where('id_product', $id_folio);
            $builder->where('active_status', 1);
            $datas = $builder->get()->getResult();

            foreach ($datas as $key => $value) {
                $data = ['stock_product' => $value->stock_product - $quantity];
            }


            if ($insertData) {
                return ($this->inventoryModelVH->update($id_folio, $data)) ? json_encode(true) : json_encode('error');
            } else {
                return json_encode("error");
            }
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }

    /*-------------^-^-^-^-^-^-^-^-^-^-^-^-^-^--*/
    public function emailNotification($id_request, $email = null, $user_name = null, $option)
    {
        $query = $this->db->query("SELECT *
                                        FROM
                                        tbl_stationery_requests
                                        WHERE id_request=" . $id_request);
        $dataPerson =  $query->getRow();
        $query1 = $this->db->query("SELECT a.id_request_item,a.quantity,b.description_product,c.category AS categoria
                                    FROM
                                    tbl_stationery_items AS a
                                    LEFT JOIN tbl_stationery_inventory AS b
                                    ON  a.id_product = b.id_product
                                    LEFT JOIN cat_stationery_category AS c
                                    ON  c.id_cat = b.id_cat
                                    WHERE a.id_request=" . $id_request);

        $dataRequest =  $query1->getResultArray();
        foreach ($dataRequest as $key => $value) {
            $groups[$value['categoria']][$value['description_product']] = $value['quantity'];
        }
        $data = [
            "request" => $dataPerson,
            "personal" => $groups,
            "option" => $option
        ];

        /* USAMOS EL HELPER CAMBIAR EMAIL PARA LOS CORREOS DE GRUPOWALWORTH */
        $email = changeEmail($email);


        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";
        try {

            //Set SMTP Options
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            //Server settings
            // Enable verbose debug output
            //$mail->SMTPDebug = 2;
            // Set mailer to use SMTP
            $mail->isSMTP();
            // Enable SMTP authentication
            $mail->SMTPAuth = false;
            // Specify main and backup SMTP servers
            $mail->Host = 'localhost';
            // SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
            //$mail->Username = 'requisiciones@grupowalworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            //$mail->Password = '2contodo';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;

            //Recipients
            $mail->setFrom('requisiciones@walworth.com', 'Solicitud|Papelería');

            if ($option == 1) {
                $mail->addAddress($email, $user_name);
                $title = 'Solicitud de Papelería';
            } else {
                $mail->addAddress('bmartinez@walworth.com.mx', 'Berenice Martinez');
               // $mail->addAddress('gmendoza@walworth.com.mx', 'Gerardo Mendoza');
                $mail->addAddress('krubio@walworth.com.mx', 'Karen Rubio');
                

               // $title = 'Se Autoriza Solicitud';
                $title = 'Solicitud de Papelería';
            }
            // Add a recipient

            // Name is optional
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('rcruz@walworth.com.mx');
        
            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //    $mail->addAttachment($data["imss"]);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template =  view('notificaciones/papeleria', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  $title;
            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }


    public function emailNotificationMinimo($data = null)
    {

        $data = ["request" => $data];

        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";
        try {

            //Set SMTP Options
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            //Server settings
            // Enable verbose debug output
            //$mail->SMTPDebug = 2;
            // Set mailer to use SMTP
            $mail->isSMTP();
            // Enable SMTP authentication
            $mail->SMTPAuth = false;
            // Specify main and backup SMTP servers
            $mail->Host = 'localhost';
            // SMTP username (This is smtp sender email. Create one on cpanel e.g noreply@your_domain.com)
            //$mail->Username = 'requisiciones@grupowalworth.com';
            // SMTP password (This is that emails' password (The email you created earlier) )
            //$mail->Password = '2contodo';
            // TCP port to connect to. the port for TLS is 587, for SSL is 465 and non-secure is 25
            $mail->Port = 25;

            //Recipients
            $mail->setFrom('requisiciones@walworth.com', 'Minimos|Papelería');

            $mail->addAddress('bmartinez@walworth.com.mx', 'Berenice Martinez');
           // $mail->addAddress('gmendoza@walworth.com.mx', 'Gerardo Mendoza');
            $mail->addAddress('krubio@walworth.com.mx', 'Karen Rubio');
            //$mail->addBCC('gberriozabal@walworth.com.mx');
            // $mail->addAddress('rcruz@walworth.com.mx', 'Rafael Cruz');
            $title = 'Articulos al Minimo';

            // Name is optional
            $mail->addReplyTo('rcruz@walworth.com.mx', 'Walworth IT');
            //$mail->addCC('cc@example.com');
            $mail->addBCC('rcruz@walworth.com.mx');
        
            //Attachments (Ensure you link to available attachments on your server to avoid errors)
            //    $mail->addAttachment($data["imss"]);         // Add attachments

            //$mail->addAttachment('/tmp/image.jpg', 'some_imaje.jpg');    // Optional name

            //Content
            $mail->isHTML(true);
            $email_template = view('notificaciones/papeleria_stock_minimo', $data);
            $mail->MsgHTML($email_template);                              // Set email format to HTML
            $mail->Subject =  $title;
            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }

    public function pdfRequests($id_request = null)
    {
        //CIFRADO
        $key = '5973C777%B7673309895AD%FC2BD1962C1062B9?3890FC277A04499¿54D18FC13677';


        $query = $this->db->query("SELECT *
                                    FROM
                                    tbl_stationery_requests
                                    WHERE MD5(concat('" . $key . "',id_request))='" . $id_request . "'");
        $dataPerson =  $query->getRow();

        $query1 = $this->db->query("SELECT a.id_request_item,a.quantity,b.description_product,c.category AS categoria
                                    FROM
                                    tbl_stationery_items AS a
                                    LEFT JOIN tbl_stationery_inventory AS b
                                    ON  a.id_product = b.id_product
                                    LEFT JOIN cat_stationery_category AS c
                                    ON  c.id_cat = b.id_cat
                                    WHERE MD5(concat('" . $key . "',a.id_request))='" . $id_request . "'");
        $dataRequest =  $query1->getResultArray();

        foreach ($dataRequest as $key => $value) {
            $groups[$value['categoria']][$value['description_product']] = $value['quantity'];
        }
        $data = [
            "request" => $dataPerson,
            "personal" => $groups
        ];

        $html2 = view('pdf/pdf_stationery', $data);

        $html = ob_get_clean();

        $html2pdf = new Html2Pdf('P', 'Letter', 'es', 'UTF-8');


        $html2pdf->pdf->SetTitle('Transferecias');

        $html2pdf->writeHTML($html2);

        ob_end_clean();
        $html2pdf->output('transferencia_' . $id_request . '.pdf', 'I');
    }

    public function generateReports()
    {


        $data = json_decode(stripslashes($this->request->getPost('data')));


        switch ($data->categoria) {

            case 1:
                $sql = " d.cost_center =" . $data->parametro . " AND d.created_at BETWEEN '" . $data->fecha_inicio . "' AND '" . $data->fecha_fin . "'";
                break;
            case 2:
                $sql = " d.payroll_number =" . $data->parametro . " AND d.created_at BETWEEN '" . $data->fecha_inicio . "' AND '" . $data->fecha_fin . "'";
                break;
            case 3:
                $sql = " d.created_at BETWEEN '" . $data->fecha_inicio . "' AND '" . $data->fecha_fin . "'";
                break;

            default:
                $sql = "";
                break;
        }
        $NombreArchivo = "requisiciones_papeleria.xlsx";

        $query = $this->db->query("SELECT
            d.cost_center, d.payroll_number, d.name, b.description_product, a.quantity,a.unit, c.category AS categoria, d.departament, d.id_request, d.created_at,
        CASE
            WHEN d.request_status = 1 THEN 'Pendiente'
            WHEN d.request_status = 2 THEN 'Autorizado'
            WHEN d.request_status = 3 THEN 'Completado'
            WHEN d.request_status = 4 THEN 'Rechazado'
            ELSE 'error'
        END AS estatus                                 
        FROM
            tbl_stationery_items as a
        LEFT JOIN tbl_stationery_inventory AS b ON a.id_product = b.id_product
        LEFT JOIN cat_stationery_category AS c ON c.id_cat = b.id_cat
        LEFT JOIN tbl_stationery_requests as d ON d.id_request = a.id_request
        WHERE
        $sql");
        $reporte = $query->getResult();

        $cont = 2;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setAutoFilter('A1:J1');
        $spreadsheet->getActiveSheet();
        $sheet->setTitle("requisiciones");

        $sheet->setCellValue('A1', 'FOLIO');
        $sheet->setCellValue('B1', 'NUMERO DE EMPLEADO');
        $sheet->setCellValue('C1', 'NOMBRE DEL SOLICITANTE');
        $sheet->setCellValue('D1', 'CENTRO DE COSTO');
        $sheet->setCellValue('E1', 'PRODUCTO');
        $sheet->setCellValue('F1', 'CANTIDAD');
        $sheet->setCellValue('G1', 'UNIDAD');
        $sheet->setCellValue('H1', 'CATEGORIA');
        $sheet->setCellValue('I1', 'DEPARTAMENTO');
        $sheet->setCellValue('J1', 'ESTADO');
        $sheet->setCellValue('K1', 'FECHA');

        foreach ($reporte as $key => $value) {
            $celdaA = 'A' . $cont;
            $celdaB = 'B' . $cont;
            $celdaC = 'C' . $cont;
            $celdaD = 'D' . $cont;
            $celdaE = 'E' . $cont;
            $celdaF = 'F' . $cont;
            $celdaG = 'G' . $cont;
            $celdaH = 'H' . $cont;
            $celdaI = 'I' . $cont;
            $celdaJ = 'J' . $cont;
            $celdaK = 'K' . $cont;

            $sheet->setCellValue($celdaA, $value->id_request);
            $sheet->setCellValue($celdaB, $value->payroll_number);
            $sheet->setCellValue($celdaC, $value->name);
            $sheet->setCellValue($celdaD, $value->cost_center);
            $sheet->setCellValue($celdaE, $value->description_product);
            $sheet->setCellValue($celdaF, $value->quantity);
            $sheet->setCellValue($celdaG, $value->unit);
            $sheet->setCellValue($celdaH, $value->categoria);
            $sheet->setCellValue($celdaI, $value->departament);
            $sheet->setCellValue($celdaJ, $value->estatus);
            $sheet->setCellValue($celdaK, $value->created_at);

            $cont++;
        }


        $writer = new Xlsx($spreadsheet);
        $writer->save($NombreArchivo);
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=" . basename($NombreArchivo));
        header("Expires:0");
        header("Cache-Control: must-revalidate");
        header("Pragma: public");
        header("Content-Length:" . filesize($NombreArchivo));
        flush();
        readfile($NombreArchivo);
        exit;
    }
    public function payrollsAll()
    {
        $id = trim($this->request->getPost('payroll_number'));
        $payrollData = $this->db->table('tbl_users')->select('payroll_number');
        $data = $payrollData->where('payroll_number', $id)->get()->getResult();

        return ($data != null) ? json_encode("encontrado") : json_encode("error");
    }

    public function productDelete()
    {
        try {
            $id_product = trim($this->request->getPost('id_producto'));
            $data = ["active_status" => 2, "id_user_delete" => session()->id_user];
            $result = $this->inventoryModel->update($id_product, $data);
            return ($result) ? json_encode(true) : json_encode(false);
        } catch (\Exception $e) {
            return ('Ha ocurrido un error en el servidor ' . $e);
        }
    }
}
