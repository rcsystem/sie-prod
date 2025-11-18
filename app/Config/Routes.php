<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**public function viewVerification()
	{
		// return ($this->is_logged) ? view('travels/view_proof_of_expenditure') : redirect()->to(site_url());
		return ($this->is_logged) ? view('travels/view_tabla_folios') : redirect()->to(site_url());
	}
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
/*
 * --------------------------------------------------------------------
 * Rutas para Inicio de Sesión y enlaces del DashBoard
 * --------------------------------------------------------------------
 */
$routes->get('/', 'Auth/Login::index');
$routes->get('/dashboard', 'Auth/Login::dashBoard');


$routes->get('/directorio', 'Users/Users::view_directorio');
$routes->post('directorio/crear', 'Users/Users::crear');
$routes->get('/directorio/listar', 'Users/Users::listarDirectorio');



$routes->group('auth', ['namespace' => 'App\Controllers\Auth'], function ($routes) {

	$routes->post('check', 'Login::signin', ['as' => 'signin']);
	$routes->get('logout', 'Login::signout', ['as' => 'signout']);
	$routes->get('loginGoogle', 'Login::loginWithGoogle');
});

/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de Requisiciones Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */
$routes->group('requisiciones', ['namespace' => 'App\Controllers\Requisitions'], function ($routes) {
	$routes->get('generar', 'Requisitions::generate');
	$routes->get('autorizar', 'Requisitions::index');
	$routes->get('todas', 'Requisitions::requisitionsAll');
	$routes->post('todas_requisiciones', 'Requisitions::requisitions_all');
	$routes->post('insertar', 'Requisitions::insertRequisition');
	$routes->get('mis-requisiciones', 'Requisitions::requestsPerUser');
	$routes->post('por_usuario', 'Requisitions::PerUser');
	$routes->get('ver-requisicion/(:any)', 'Requisitions::pdfSeeRequisition/$1');
	$routes->post('ver-requisiciones', 'Requisitions::pdfSeeRequisitionDesc');
	$routes->post('centro-costo', 'Requisitions::searchCostCenter');
	$routes->post('editar_requisicion', 'Requisitions::editRequisition');
	$routes->post('editar_item', 'Requisitions::edit_Requisition');
	$routes->post('actualizar_requisicion', 'Requisitions::updateRequisition');
	$routes->post('autorizar', 'Requisitions::authorizeRequisition');
	$routes->post('autorizar-item', 'Requisitions::authorize_Requisition');
	$routes->post('areas_asignadas', 'Requisitions::assignAreas');
	$routes->post('asignar_areas', 'Requisitions::assign_areas');
	$routes->get('asignar', 'Requisitions::asignation');

	$routes->get('notificar/(:any)', 'Requisitions::notificar/$1');

	$routes->post('datos_personal', 'Requisitions::dataAsignation');
	$routes->post('editar_datos_personal', 'Requisitions::dataAsignationEdit');
});
/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de Permisos Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */

$routes->group('permisos', ['namespace' => 'App\Controllers\Permissions'], function ($routes) {
	$routes->get('crear', 'Permissions::create');
	$routes->get('todos', 'Permissions::permissionsAll');
	$routes->get('mis-permisos', 'Permissions::myPermits');
	$routes->get('ver-permisos/(:any)', 'Permissions::pdfSeePermissions/$1');
	$routes->get('autorizar', 'Permissions::authorizePermissions');
	$routes->get('autorizar_new', 'Permissions::authorizePermissionsNew');
	$routes->get('autorizar-direcion-general', 'Permissions::authorizePermissionsDirector');
	$routes->get('vacaciones/(:any)', 'Permissions::pdfVacationPermissions/$1');
	$routes->get('reportes', 'Permissions::reports');
	$routes->get('administrar-permisos', 'Permissions::adminPermissions');
	$routes->get('pago-horas', 'Permissions::viewTimePay');
	$routes->get('autorizar-pago-tiempo', 'Permissions::viewAuthorizeTimePay');
	$routes->get('administrar-permisos-especiales', 'Permissions::viewSpecialPermission');
	// $routes->get('generar_reportes/(:any)', 'Permissions::reportsGenerate/$1');
	$routes->post('generar', 'Permissions::generate');
	$routes->post('generar_des', 'Permissions::generateNew');
	$routes->post('entrada-salida', 'Permissions::permissions_all');
	$routes->post('vacaciones-todos', 'Permissions::vacations_all');
	$routes->post('entrada_salida_fecha', 'Permissions::permissionsDate');
	$routes->post('vacaciones_todos_fecha', 'Permissions::vacationsDate');
	$routes->post('por_usuario', 'Permissions::my_Permits');
	$routes->post('listado_permisos', 'Permissions::listPermissions');
	$routes->post('vacaciones', 'Permissions::vacationPermission');
	$routes->post('autorizar-permisos', 'Permissions::authorize_permissions');
	$routes->post('autorizacion-permisos', 'Permissions::authorize_permissionsNew');
	$routes->post('autorizacion-5to-permisos', 'Permissions::authorize_permissionsDirector');
	$routes->post('mis-vacaciones', 'Permissions::myVacations');
	$routes->post('autorizar-vacaciones', 'Permissions::authorize_vacations');
	$routes->post('autorizacion-vacaciones', 'Permissions::authorize_vacationsNew');
	$routes->post('editar_permiso', 'Permissions::permission_edit');
	$routes->post('autorizacion', 'Permissions::authorization');
	$routes->post('generar_reportes', 'Permissions::reportsGenerate');
	$routes->post('generar_reportes_ant', 'Permissions::reportsGenerateAnt');
	$routes->post('check', 'Login::signin', ['as' => 'signin']);
	$routes->post('autorizar_permiso', 'Permissions::authorizePermission');
	$routes->post('editar_vacaciones', 'Permissions::editVacations');
	$routes->post('autoriza-vacaciones', 'Permissions::authorizeVacation');
	$routes->post('editar_permiso_vacations/(:any)', 'Permissions::editPermissionVacations/$1');
	$routes->post('autorizacion_vacaciones', 'Permissions::authorizationVacations');
	$routes->post('eliminar_permisos', 'Permissions::deletePermissions');
	$routes->post('eliminar_vacaciones', 'Permissions::deleteVacations');
	$routes->post('permisos_autorizados', 'Permissions::authorizedPermissions');
	$routes->post('permisos_autorizados_villa', 'Permissions::authorizedPermissionsVilla');
	$routes->post('vacaciones_autorizadas', 'Permissions::authorizedVacations');
	$routes->post('departamentos', 'Permissions::departamentsAll');
	$routes->post('direccion', 'Permissions::directionsAll');
	$routes->post('genera_reportes_director', 'Permissions::generateReportsForDirection');
	$routes->post('reporte_global', 'Permissions::globalReport');
	$routes->post('reporte_individual', 'Permissions::individualReport');
	$routes->post('reporte_vacaciones_global', 'Permissions::reportVacationGlobal');
	$routes->post('info_personal', 'Permissions::personalData');
	$routes->post('info_personal_doc', 'Permissions::personalDataPT2');
	$routes->post('reporte_datos_generales', 'Permissions::reportGeneralData');
	$routes->post('guardar_editar', 'Permissions::editSave');
	$routes->post('guardar_editar_vacaciones', 'Permissions::editSaveVacation');
	$routes->post('validar_cantidad', 'Permissions::validateAmountPermissions');
	$routes->post('hora_entrada', 'Permissions::timeOfEntry');
	$routes->post('actualizar_dias_new', 'Permissions::updateDaysVacationsNew');
	$routes->post('motivo_festivo', 'Permissions::listdayFestive');
	$routes->post('motivo_festivo_array', 'Permissions::listdayFestiveArray');
	$routes->post('motivo_trafico', 'Permissions::listdayTraffic');
	$routes->post('motivo_trafico_array', 'Permissions::listdayTrafficArray');

	$routes->post('tabla_permisos_usuarios', 'Permissions::tablePermissions');
	$routes->post('tabla_permisos_vacaciones', 'Permissions::tableVacations');
	$routes->post('tabla_vacaciones_directores', 'Permissions::tableDirectors');

	// $routes->post('autorizar-permiso', 'Permissions::authorizePermission'); RUTA PARA PERMISOS anteriores
	// $routes->post('permisos_usuarios', 'Permissions::validateAmountPermissionsUsers'); TABLA USUARIOS
	// $routes->post('permiso_extra','Permissions::updateAmountPermissionsUser'); ON/OFF PERMISO EXTRA
	// $routes->post('permisos_desactivar','Permissions::offPermissionsAllUsers'); DESACTIVAR TODOS

	$routes->post('graficar_permisos', 'Permissions::plotLeave');
	$routes->post('horarios', 'Permissions::shiftHours');
	$routes->post('generar_pago_tiempo', 'Permissions::insertTimePayment');
	$routes->post('datos_pago_tiempo', 'Permissions::dataTimePayment');
	$routes->post('datos_pago_tiempo_todos', 'Permissions::dataTimePaymentALL');
	$routes->post('actualizar_pago_tiempo', 'Permissions::updateStatusTimePayment');
	$routes->post('total_permisos', 'Permissions::permisssionTotal');


	// ***** formulario permisos + pago tiempo ***** 

	$routes->post('lista_pago_tiempo', 'Permissions::listOfTimePay');
	$routes->post('lista_horarios', 'Permissions::listOfTurns');
	$routes->post('validar_pago_tiempo', 'Permissions::validatePaymentTime');
	$routes->post('validar_deuda_tiempo', 'Permissions::validateDebtTime');
	$routes->post('dias_vacaciones', 'Permissions::listDaysVacation');
	$routes->post('eliminar_pago_tiempo', 'Permissions::deletePaymentTime');
	$routes->post('datos_item_pago_tiempo', 'Permissions::dataItemPaymentTime');
	$routes->post('editar_pago_tiempo', 'Permissions::editTimePayment');
	$routes->post('mis_pago_tiempo', 'Permissions::myTimePayment');
	$routes->post('registrar_permiso_especial', 'Permissions::insertPermissSpecial');
	$routes->post('fechas_permiso_especial', 'Permissions::listTablePermissSpecial');
	$routes->post('activar_desactivar_permisos_especiales', 'Permissions::onOffPermissSpecial');
	$routes->post('eliminar_permisos_especiales', 'Permissions::deletePermissSpecial');
});

/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de Permisos Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */
//$routes->resource('permiso', ["controller" => 'Api\Permisos\Permisos']);
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {
	$routes->group('auth', function ($routes) {
		$routes->post('auth', 'Login\Login::index');
	});
	$routes->get('permisos', 'Permisos\Permisos::index');

	// API SANTOS
	$routes->group('pdfservice', ['namespace' => 'App\Controllers\Api\PDFService'], function ($routes) {
		$routes->post('mergePDF', 'MergePDF::mergepdf');
    });
});


/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de Usuarios Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */
$routes->group('encuesta', ['namespace' => 'App\Controllers\Survey'], function ($routes) {

	$routes->get('/', 'Users::index');
	$routes->get('editar_depto/(:any)', 'Users::editDepto/$1');
	$routes->get('formulario', 'Survey::viewSurvey');
	$routes->get('comprobantes', 'Survey::viewSurveyPT2');
	$routes->post('eliminar-usuario', 'Users::userDelete');
});

/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de Usuarios Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */
$routes->group('usuarios', ['namespace' => 'App\Controllers\Users'], function ($routes) {
	$routes->get('todos', 'Users::usersAll');
	$routes->get('reportes-contratos', 'Users::usersContractsAll');
	$routes->get('departamentos', 'Users::departaments');
	$routes->get('deptos', 'Users::departamentsAll');
	$routes->get('registrar_usuario', 'Users::register_user');
	$routes->get('encuesta', 'Users::survey');
	$routes->get('info', 'Users::info');
	$routes->get('info-todos', 'Users::viewInfoUsers');
	$routes->get('/', 'Users::index');
	$routes->get('editar_depto/(:any)', 'Users::editDepto/$1');
	$routes->get('alta-usuario', 'Users::registerUser');
	$routes->get('contratos', 'Users::viewContracts');
	$routes->get('ver-contratos/(:any)', 'Users::userContracts/$1', ['filter' => 'firstContract']);
	$routes->get('ver-contrato/(:any)', 'Users::pdfUserContract/$1');
	$routes->get('admin-ver-contratos/(:any)', 'Users::viewContractsAdmin/$1');
	$routes->get('todos-contratos', 'Users::viewContractsAll');
	$routes->get('primer-contrato/(:any)', 'Users::primeryContract/$1');
	$routes->get('autorizar-planta', 'Users::viewAuthorizePlant');

	$routes->post('todos_contratos_temp', 'Users::userContractTemp'); // DEL USUARIO, VISTA ADMIN
	$routes->post('editar_contratos_temp', 'Users::userEditContractTemp');
	$routes->post('datos_editar_contratos_temp', 'Users::userEditContractTempData');
	$routes->post('eliminar_contrato_temporal', 'Users::userDeletContractTemp');
	$routes->post('tipo_usuario', 'Users::userType');
	$routes->post('asignar', 'Users::assign');
	$routes->post('eliminar-usuario', 'Users::userDelete');
	$routes->post('iterar_registrar_usuario', 'Users::setRegisterUser');
	$routes->post('info_usuario', 'Users::userData');
	$routes->post('info_user', 'Users::userInfo');
	$routes->post('actualizar_gerente', 'Users::updateManager');
	$routes->post('solicitar_menu', 'Users::usersMenu');
	$routes->post('actualizar_acceso', 'Users::accessUpdate');
	$routes->post('datos_personal', 'Users::personalData');
	$routes->post('datos_personal_guardar', 'Users::personalDataSave');
	$routes->post('contacto_emergancia', 'Users::emergencyContact');
	$routes->post('contacto_emergancia_guardar', 'Users::emergencyContactSave');
	$routes->post('familia', 'Users::parent');
	$routes->post('familia_guardar', 'Users::parentSave');
	$routes->post('documentos', 'Users::documents');
	$routes->post('documentos_guardar', 'Users::documentsSave');
	$routes->post('check_doc', 'Users::checkDocument');
	$routes->post('check_doc_save', 'Users::checkDocumentSave');
	$routes->post('todo-info', 'Users::infoUsersAll');
	$routes->post('contacto-emergencia', 'Users::emergencyContacts');
	$routes->post('info_general', 'Users::reportGeneralData');
	$routes->post('insertar_depto', 'Users::insertDepartament');
	$routes->post('todos_documentos', 'Users::documentsALL');
	$routes->post('todos', 'Users::usersAll');
	$routes->post('usuarios_contratados', 'Users::contractedUsers');
	$routes->post('registrar_contrato', 'Users::registerContract');
	$routes->post('contratos_temp', 'Users::contractedUsersAll');
	$routes->post('registrar_primer_contrato', 'Users::registerPrimeryContract');
	$routes->post('toda_informacion_usuario', 'Users::informationUserALL');
	$routes->post('genera_reportes_contratos', 'Users::generateContractReportsXlsx');
	$routes->post('contratos_planta', 'Users::contractedUndefined');
	$routes->post('actualizar_contratos_planta', 'Users::updatePermanentContracts');
	$routes->post('generar_contratados_masivos', 'Users::generateMassiveContracts');
});
/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de Papeleria Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */
$routes->group('papeleria', ['namespace' => 'App\Controllers\Stationery'], function ($routes) {

	$routes->get('crear', 'Stationery::stationery');
	$routes->get('reportes', 'Stationery::viewReports');
	$routes->get('inventario', 'Stationery::viewInventary');
	$routes->get('editar_producto/(:any)', 'Stationery::editProduct/$1');
	$routes->get('mis-solicitudes', 'Stationery::viewMyRequests');
	$routes->get('todas-solicitudes', 'Stationery::viewAllRequests');
	$routes->get('ver-requisicion/(:any)', 'Stationery::pdfRequests/$1');
	$routes->get('autorizar', 'Stationery::viewAuthorize');
	$routes->get('entradas', 'Stationery::viewEntries');


	$routes->post('reporte_entradas', 'Stationery::reportEntries');
	$routes->post('todas_entradas', 'Stationery::entriesAll');
	$routes->post('todas_salidas', 'Stationery::departuresAll');
	$routes->post('categoria_pape', 'Stationery::stationeryCategory');
	$routes->post('imagen_pape', 'Stationery::imageStationery');
	$routes->post('solicitud_papeleria', 'Stationery::stationeryRequest');
	$routes->post('inventario_total', 'Stationery::inventaryAll');
	$routes->post('parametros', 'Stationery::parameters');
	$routes->post('entrada', 'Stationery::entries');
	$routes->post('salidas', 'Stationery::departures');
	$routes->post('solicitudes', 'Stationery::mysRequests');
	$routes->post('todas_solicitudes', 'Stationery::allRequests');
	$routes->post('mis_solicitudes', 'Stationery::myRequests');
	$routes->post('request_entrega', 'Stationery::answerRequest');
	$routes->post('nuevo_articulo', 'Stationery::newArticle');
	$routes->post('genera_reportes', 'Stationery::generateReports');
	$routes->post('inventario_solicitud', 'Stationery::InventoryStationery');
	$routes->post('nominas', 'Stationery::payrollsAll');
	$routes->post('eliminar_producto', 'Stationery::productDelete');
	$routes->post('autorizar-papeleria', 'Stationery::authorizedStationeryAll');
	$routes->post('autorizar_pape', 'Stationery::authorizedStationery');
	$routes->post('autorizacion', 'Stationery::authorized');

	$routes->get('crear-vh', 'Stationery::stationeryVH');
	$routes->get('reportes-vh', 'Stationery::viewReportsVH');
	$routes->get('inventario-vh', 'Stationery::viewInventaryVH');
	$routes->get('mis-solicitudes-vh', 'Stationery::viewMyRequestsVH');
	$routes->get('todas-solicitudes-vh', 'Stationery::viewAllRequestsVH');
	$routes->post('todas_solicitudes_vh', 'Stationery::allRequestsVH');
	$routes->post('categoria_pape_vh', 'Stationery::stationeryCategoryVH');
	$routes->post('imagen_pape_vh', 'Stationery::imageStationeryVH');
	$routes->post('inventario_solicitud_vh', 'Stationery::InventoryStationeryVH');
	$routes->post('solicitud_papeleria_vh', 'Stationery::stationeryRequestVH');
	$routes->post('inventario_total_vh', 'Stationery::inventaryAllVH');
	$routes->get('editar_producto_vh/(:any)', 'Stationery::editProductVH/$1');
	$routes->post('parametros_vh', 'Stationery::parametersVH');
	$routes->post('entrada_vh', 'Stationery::entriesVH');
	$routes->post('salidas_vh', 'Stationery::departuresVH');
	$routes->post('nuevo_articulo_vh', 'Stationery::newArticleVH');
	$routes->post('mis_solicitudes_vh', 'Stationery::myRequestsVH');
});
/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de Suministros Sistemas Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */

$routes->group('sistemas', ['namespace' => 'App\Controllers\System'], function ($routes) {
	$routes->get('historial', 'System::timeLine');
	$routes->get('suministros', 'System::index');
	$routes->get('editar_suministro/(:any)', 'System::editSupplies/$1');
	$routes->post('todos_suministros', 'System::suppliesAll');
	$routes->post('alta_suministro', 'System::newSupplies');
	$routes->post('entrada_suministros', 'System::inputSupplies');
	$routes->post('salida_suministros', 'System::outletSupplies');
	$routes->post('actualizar_suministros', 'System::updateSupplies');
	$routes->post('cantidad_suministros', 'System::quantityOfSupplies');
	$routes->get('tickets', 'System::viewTickets');
	$routes->get('equipos', 'System::viewEquipment');
	$routes->post('buscar-usuario', 'System::searchUser');
	$routes->post('generar-ticket', 'System::generateTicket');
	$routes->get('mis-tickets', 'System::myTickets');
	$routes->post('mis_tickets', 'System::my_Tickets');
	$routes->get('tickets-todos', 'System::Tickets');

	// $routes->post('todos_tickets', 'System::ticketsAll');
	// $routes->post('todos_tickets_it', 'System::ticketsAllIt');
	$routes->get('ver-actividades/(:any)', 'System::pdfTickets/$1');
	$routes->get('ver-actividad/(:any)', 'System::pdfActivitys/$1');
	$routes->get('eliminar-depto/(:any)', 'System::deleteCostCenter/$1');
	$routes->post('guardar-ticket-it', 'System::ticket_it');
	$routes->post('crear-ticket-it', 'System::create_ticket_it');
	$routes->post('mis_actividades', 'System::my_activitys');
	$routes->post('borrar_activity', 'System::deleteActivity');
	$routes->post('actualizar_activity', 'System::editActivity');

	$routes->post('usuario-editar', 'System::userEdit');
	$routes->post('usuario_credencial', 'System::userDataCard');

	$routes->post('nuevo-password', 'System::newPassword');
	$routes->post('active-password', 'System::activePassword');
	$routes->post('eliminar_suministro', 'System::suppliesDelete');
	$routes->post('actualizar_usuario', 'System::updateUser');
	$routes->post('eliminar_usuario', 'System::userDelete');
	$routes->get('contestar-request/(:any)', 'System::answer_request/$1');

	$routes->get('equipos-asignar', 'System::viewEquipamentAdmin');
	$routes->get('reportes', 'System::viewReports');
	$routes->get('ver-asignacion/(:any)', 'System::pdfRequestEquip/$1');
	$routes->post('excel_equipos_asignados', 'System::xlsxReports');
	$routes->post('registrar_equipo', 'System::insertEquipament');
	$routes->post('lista_equipos', 'System::listEquipamentALL');
	$routes->post('datos_usuario', 'System::userData');
	$routes->post('datos_usuario_actualizado', 'System::userDataNew');
	$routes->post('datos_horarios', 'System::turnsByTypeUser');
	$routes->post('datos_equipo', 'System::equipamentData');
	$routes->post('datos_equipo_asignacion_recoleccion', 'System::requestDataCollectionAllocation');
	$routes->post('editar_datos_equipo', 'System::equipamentUpdate');
	$routes->post('procesos_equipo', 'System::equipamentDelivery');
	$routes->post('retirar_equipo', 'System::equipamentReception');
	$routes->post('historial_equipo_no_serial', 'System::equipamentHistoryById');
	/* $routes->post('historial_equipo_departamento','System::equipamentHistoryByDepto'); */
	$routes->post('historial_equipo_nomina', 'System::equipamentHistoryByPayroll');
	$routes->post('historial_asinacion', 'System::equipAsigHisroty');
	$routes->post('datos_equipo_modal', 'System::dataAsig');
	$routes->post('responsiba', 'System::dataAsigUpdate');
	$routes->get('pdf-responsiva-asignacion/(:any)', 'System::openPDFRequest/$1');
	$routes->get('pdf-responsiva-reasignacion/(:any)', 'System::openPDFRequestReasig/$1');
	$routes->post('equipos_lista_pz_existentes', 'System::listExistingPzEquip');
	$routes->post('datos_productos_asignados', 'System::listRequestInventory');
	$routes->get('ver-responsiva-inventario/(:any)', 'System::openPDFRequestInventory/$1');

	$routes->get('inventario', 'System::viewInventory');
	$routes->post('datos_productos', 'System::productData');
	$routes->post('datos_productos_inventario', 'System::productDataInventory');
	$routes->post('salida_productos', 'System::productOut');
	$routes->post('alta_producto', 'System::productIn');
	$routes->post('eliminar_producto', 'System::productDelet');
	$routes->post('editar_producto', 'System::productEdit');
	$routes->post('reporte_producto', 'System::productXlsx');
	$routes->post('check_form', 'System::checkFromActive');
	$routes->post('save_form', 'System::enableDisableForm');
	$routes->post('equipos_lbl_almacenados_y_usuarios', 'System::listEquipsStored');
	$routes->post('lbl_equipos_accesorios', 'System::listEquipsAcesoris');
	$routes->post('lista_equipo_por_usuarios_recoleccion', 'System::listEquipByUserRecolet');

	/* 
	* -------- new registro equipos -------------
	*/

	$routes->get('registrar-equipos-v1', 'System::viewRegisterEquipment');
	$routes->post('equipos-asignar-usuario', 'System::insertTeamsAssignUser');

	/* 
	* -------- prestamos -------------
	*/

	$routes->get('prestamos-equipos', 'System::viewLoan');
	$routes->get('pdf-responsiva-prestamo/(:any)', 'System::openPDFRequestLoan/$1');

	$routes->post('registrar_prestamo', 'System::insertRequestLoan');
	$routes->post('todos_prestamos', 'System::listRequestLoanALL');
	$routes->post('confirmar_devolucion', 'System::updateDateReturn');
	$routes->post('eliminar_registro', 'System::deleteRequestLoan');
	/* 
	* -------- prestamos -------------
	*/
	//$routes->get('ver-responsiva-prestamo/(:any)', 'System::openPDFRequestLoan/$1');
	//$routes->post('datos_equipo_mantenimiento', 'System::equipamentMaintenanceData');
	$routes->post('registrar_mantenimiento', 'System::insertMaintenance');
	$routes->post('subir_pdf_mantenimiento', 'System::subirPdfMantto');
	$routes->post('todos_mantenimientos', 'System::listMaintenanceALL');
	$routes->post('editar_mantenimiento', 'System::editMaintenance');
	$routes->post('eliminar_mantenimiento', 'System::deleteMaintenance');
	$routes->get('calendario', 'System::viewMaintenanceAll');

	$routes->get('datos_mantenimiento', 'System::maintenanceData');
	$routes->post('usuarios_asignados', 'System::usersData');
	$routes->post('cambiar_mantenimiento', 'System::updateMaintenance');
	$routes->post('cancelar_mantenimiento', 'System::cancelMaintenance');
	$routes->post('listado_mantenimientos', 'System::listMaintenance');
	$routes->get('ver-mantenimiento/(:any)', 'System::openPDFRequestMaintenance/$1');
	$routes->post('eliminar_mantenimiento', 'System::deleteMaintenance');

});

/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de WPS Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */
$routes->group('wps', ['namespace' => 'App\Controllers\Wps'], function ($routes) {
	$routes->get('material-base', 'Wps::baseMaterial');
	$routes->get('uniones', 'Wps::permanentUnions');
});


/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de Valija Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */
$routes->group('valija', ['namespace' => 'App\Controllers\Valija'], function ($routes) {
	$routes->get('crear', 'Valija::index');
	$routes->post('insertar', 'Valija::valijaRequest');
	$routes->get('mis-solicitudes', 'Valija::viewMyRequest');
	$routes->post('solicitudes_usuario', 'Valija::RequestsUser');
	$routes->get('ver-solicitudes/(:any)', 'Valija::pdfRequestValija/$1');
	$routes->get('todas-solicitudes', 'Valija::viewRequestAll');
	$routes->post('todas_solicitudes', 'Valija::RequestsAll');
	$routes->post('editar-valija', 'Valija::editRequests');
	$routes->post('autorizar-valija', 'Valija::authorizeRequests');
	$routes->post('genera_reportes', 'Valija::generateValijaReportsXlsx');
});



/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de WPS Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */
$routes->group('cafeteria', ['namespace' => 'App\Controllers\Coffee'], function ($routes) {
	$routes->get('crear', 'Coffee::viewGenerate');
	$routes->get('solicitudes', 'Coffee::viewMyRequest');
	$routes->get('autorizar', 'Coffee::viewAuthorize');
	$routes->get('autorizar-james', 'Coffee::viewAuthorizeJames');
	$routes->get('menus-admin', 'Coffee::viewManageMenu');
	$routes->post('mis-solicitudes', 'Coffee::myRequest');
	$routes->post('insertar', 'Coffee::insertItem');
	$routes->get('ver-solicitudes/(:any)', 'Coffee::pdfRequestCoffee/$1');
	$routes->post('request_estado', 'Coffee::requestAuthorize');
	$routes->post('todas-solicitudes', 'Coffee::requestAll');
	$routes->post('solicitudes_james', 'Coffee::requestJames');

	$routes->post('crear_menu', 'Coffee::createNewMenu');
	$routes->post('todos_menus', 'Coffee::MenusALL');
	$routes->post('borrar_menu', 'Coffee::deleteMenu');
	$routes->post('datos_editar_menu', 'Coffee::dateEditMenuALL');
	$routes->post('editar_menu', 'Coffee::editMenu');
	$routes->post('editar_comida', 'Coffee::editFood');
	$routes->post('pintar_menu', 'Coffee::printMenu');
	$routes->post('pintar_comida', 'Coffee::printFood');
	$routes->post('cancelar', 'Coffee::cancel');
});

/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de AUTOMOVILES Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */

$routes->group('autos', ['namespace' => 'App\Controllers\Cars'], function ($routes) {
	$routes->get('autorizar', 'Cars::viewAutorize');
	$routes->get('crear-solicitud', 'Cars::viewCreateRequest');
	$routes->get('mis-solicitudes', 'Cars::viewMyRequest');
	$routes->get('vehiculos', 'Cars::viewVehicle');
	$routes->get('ver-solicitudes/(:any)', 'Cars::pdfRequestCars/$1');
	$routes->get('todas-solicitudes', 'Cars::viewRequestAll');

	$routes->post('todos_vehiculos', 'Cars::carsALL');
	$routes->post('nuevo_vehiculo', 'Cars::insetCar');
	$routes->post('borrar_vehiculo', 'Cars::deleteCar');
	$routes->post('solicitar', 'Cars::insertRequest');
	$routes->post('mis-solicitudes', 'Cars::myRequest');
	$routes->post('todas-solicitudes', 'Cars::ALLRequest');
	$routes->post('autorisar', 'Cars::authorize');
	$routes->post('autorisar_jefe', 'Cars::authorizeManagement');
	$routes->post('todas-aprobadas', 'Cars::authorizeAll');
	$routes->post('datos_autos', 'Cars::dataCars');
	$routes->post('datos_autos_id', 'Cars::dataCarsID');
	$routes->post('informacion', 'Cars::dataCarsInfo');
	$routes->post('reporte_vehiculos', 'Cars::CarsXlsx');
});

/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de PAQUETERIA Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */

$routes->group('paqueteria', ['namespace' => 'App\Controllers\Packer'], function ($routes) {

	$routes->get('crear-solicitud', 'Packer::viewCreateRequest');
	$routes->get('mis-solicitudes', 'Packer::viewMyRequest');
	$routes->get('autorizar', 'Packer::viewAuthorize');
	$routes->get('ver-solicitudes/(:any)', 'Packer::pdfRequestPacker/$1');

	$routes->post('excel', 'Packer::xlsxRequestPacker');
	$routes->post('crear', 'Packer::createRequest');
	$routes->post('mis_solicitudes', 'Packer::myRequest');
	$routes->post('todas_solicitudes', 'Packer::allRequest');
	$routes->post('autorizar', 'Packer::authorize');
	$routes->post('todas-solicitudes', 'Packer::authorize');
});

/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de 	ANTICIPO Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */

$routes->group('anticipo', ['namespace' => 'App\Controllers\Advance'], function ($routes) {
	$routes->get('autorizar-solicitudes', 'Advance::viewAuthorize');
	$routes->get('crear-solicitud', 'Advance::viewCreateRequest');
	$routes->get('mis-solicitudes', 'Advance::viewMyRequest');
	$routes->get('todas-solicitudes', 'Advance::viewALLRequest');
	$routes->get('ver-solicitudes/(:any)', 'Advance::pdfRequestAdvance/$1');
	$routes->post('crear_solicitud', 'Advance::createRequest');
	$routes->post('mis_solicitudes', 'Advance::myRequest');
	$routes->post('autoriza_solicitudes', 'Advance::authorizeRequest');
	$routes->post('todas_solicitudes', 'Advance::ALLRequest');
	$routes->post('autorizar', 'Advance::authorize');
	$routes->post('concluir', 'Advance::concludeRequest');
	$routes->post('subir_comprobante', 'Advance::documentUpload');
});

/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de QHSE Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */
$routes->group('qhse', ['namespace' => 'App\Controllers\Qhse'], function ($routes) {
	$routes->get('generar', 'Qhse::baseMaterial');
	$routes->get('autorizar', 'Qhse::Authorize');
	$routes->get('horario-obscuro', 'Qhse::overTime');
	$routes->get('vigilancia', 'Qhse::permanentUnions');
	$routes->get('proveedores', 'Qhse::Suppliers');
	$routes->get('entrega-epp-almacen', 'Qhse::viewInventaryEppB');
	$routes->get('entrega-epp', 'Qhse::viewInventaryEpp');
	$routes->get('ver-permiso/(:any)', 'Qhse::pdfSeePermitions/$1');
	$routes->get('mis-permisos', 'Qhse::myPermissions');
	$routes->get('ver-tiempo-extra/(:any)', 'Qhse::pdfOverTime/$1');
	$routes->get('entrega-equipos', 'Qhse::viewEquipmentDelivery');
	$routes->get('tiempos-obscuros', 'Qhse::DarckTime');
	$routes->get('ver-solicitudes/(:any)', 'Qhse::pdfSeeRequest/$1');





	$routes->post('generar_permiso', 'Qhse::generatePermissions');
	$routes->post('permisos_proveedores_all', 'Qhse::permits_suppliers_all');
	$routes->post('permisos_proveedores_estadias_all', 'Qhse::permits_suppliers_stay_all');
	$routes->post('permiso_detalles', 'Qhse::suppliers_details');
	$routes->post('extra_detalles', 'Qhse::overtime_details');
	$routes->post('autorizar_permiso', 'Qhse::permits_authorize');
	$routes->post('tiempo_extra', 'Qhse::SaveOverTime');
	$routes->post('mis_permisos_proveedores', 'Qhse::my_permits_suppliers');
	$routes->post('tiempos_extras_all', 'Qhse::overtime_all');
	$routes->post('mis_tiempo_extra', 'Qhse::my_overtime');
	$routes->post('autorizar_tiempo_extra', 'Qhse::overtime_authorize');
	$routes->post('proveedores_visitas', 'Qhse::visit_suppliers');
	$routes->post('proveedores_visitas_estadias', 'Qhse::visit_suppliers_stay');
	$routes->post('tiempos_extra', 'Qhse::vg_over_time');
	$routes->post('tiempos_extras_autorizado', 'Qhse::overtimeAuthorize');
	$routes->post('tiempos_extras_all_user', 'Qhse::overtimeAllUser');
	$routes->post('genera_reportes_horaio', 'Qhse::overtimeXlsx');
	$routes->post('genera_reportes_visitas', 'Qhse::visitXlsx');

	$routes->get('editar_producto/(:any)', 'Qhse::editProductEpp/$1');
	$routes->get('todos-vales-epp', 'Qhse::viewRequestepp');
	$routes->post('listado_epp', 'Qhse::listEpp');
	$routes->post('listado_epp_nombre', 'Qhse::listEppName');
	$routes->post('entrega_epp', 'Qhse::insertDeliveryEpp');
	$routes->post('inventario_total', 'Qhse::inventoryEpp');
	$routes->post('listado_inventario_epp', 'Qhse::inventaryEpp');
	$routes->post('salidas_epp', 'Qhse::departuresEpp');
	$routes->post('inventario_item', 'Qhse::inventoryItemEpp');
	$routes->post('entrada', 'Qhse::entriesEpp');
	$routes->post('parametros', 'Qhse::parametersEpp');
	$routes->post('eliminar_producto', 'Qhse::deleteEpp');
	$routes->post('listado_solicitudes_epp', 'Qhse::listRequestEpp');
	/*RUTAS DE ENTRAGA DE EPP */

	$routes->post('lista_datos_vales_by_usuario', 'Qhse::listDataValesByUser');
	$routes->post('lista_datos_vales', 'Qhse::voucherList');
	$routes->post('lista_item_vales', 'Qhse::listDataValesByUser');


	$routes->post('confirmar_entrega_epp', 'Qhse::confirmDeliveryEPP');
	$routes->post('eliminar_epp', 'Qhse::deleteEpps');
	$routes->get('ver-epp/(:any)', 'Qhse::viewPdfEpp/$1');
	$routes->get('articulos-epp', 'Qhse::viewListEquipment');
	$routes->post('lista_articulos_epp', 'Qhse::listArticlesEpp');
	$routes->post('eliminar_articulo', 'Qhse::deleteArticlesEpp');
	$routes->post('nuevo_articulo', 'Qhse::newArticlesEpp');
	$routes->post('reporte-epp', 'Qhse::reportEpp');
	$routes->post('list_store_articles', 'Qhse::listStoreArticles');
	$routes->post('list_store_desc_articles', 'Qhse::listStoreItemArticles');

	$routes->get('todos-vales-almacen', 'Qhse::viewRequestAlm');


	$routes->post('agregar_menu', 'Qhse::addMenu');



	/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de RESPONSABILIDAD SOCIAL Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */


	$routes->get('voluntariado-evento', 'Qhse::viewVolunteeringEvent');
	$routes->get('permanente-evento', 'Qhse::viewPermanentEvent');
	$routes->get('voluntariado-solicitudes', 'Qhse::viewListEvent');
	$routes->get('ver-voluntarios/(:any)', 'Permissions::pdfEventVoluntering/$1');
	$routes->get('ver-permanente/(:any)', 'Permissions::pdfEventPermanent/$1');
	$routes->get('eventos-menus', 'Qhse::viewListMenus');
	$routes->get('carreras', 'Qhse::viewRaceWithCause');

	$routes->get('limpieza', 'Qhse::viewCleanupCampaign');
	$routes->get('reforestacion', 'Qhse::viewReforestar');


	$routes->get('centro', 'Qhse::barcodeCentroCosto');


	$routes->post('solicitud_evento', 'Qhse::createEvent');
	$routes->post('solicitud_evento_permanente', 'Qhse::createEventPermanent');
	$routes->post('listado_voluntariado', 'Qhse::listEvents');
	$routes->post('listado_menus', 'Qhse::listMenus');
	$routes->post('ver_listado_menus', 'Qhse::viewListMenu');

	$routes->post('listado_permanente', 'Qhse::listEventsPermanent');
	$routes->post('activar_menu', 'Qhse::updateMenus');
	$routes->post('eliminar_menu', 'Qhse::deleteMenu');
	$routes->post('eliminar_evento', 'Qhse::deleteEvent');
	$routes->post('eliminar_solicitud', 'Qhse::deleteRequest');




	/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de MARCADOR DE ACCIDENTES 
 * --------------------------------------------------------------------
 */

	$routes->post('dias_record', 'Qhse::daysRecord');
	$routes->post('guardar_dias_record', 'Qhse::saveDaysRecord');
	$routes->post('resetear_dias_record', 'Qhse::resetDaysRecord');
	$routes->get('incrementa_dias', 'Qhse::increaseDays');

	/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de insignias
 * --------------------------------------------------------------------
 */

	$routes->post('validar_solicitudes', 'Qhse::validateRequests');
	$routes->get('mis-insignias', 'Qhse::viewMyBadges');
	$routes->post('asistencia', 'Qhse::validateRequest');
});

/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de ALMACEN Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */
$routes->group('almacen', ['namespace' => 'App\Controllers\Store'], function ($routes) {
	$routes->get('salidas', 'Store::Departures');
	$routes->get('listado', 'Store::listOfCodes');
	$routes->post('buscar', 'Store::Search');
	$routes->post('materia_prima', 'Store::saveRawMaterial');
	$routes->post('listado_material', 'Store::materialList');
	$routes->post('nuevo_codigo', 'Store::newCode');
	$routes->get('transferencias', 'Store::viewTransfers');
	$routes->post('listado_transferencias', 'Store::transfersList');
	$routes->get('ver-transferencia/(:any)', 'Store::pdfTransfers/$1');
	$routes->get('autorizar', 'Store::viewAuthorizeTransfers');
	$routes->post('autorizar_transferencia', 'Store::authorizeTransfers');
	$routes->post('buscar_codigo', 'Store::searchCode');
	$routes->get('generar_reportes/(:any)', 'Store::vouchersExcel/$1');
	$routes->post('rechazar_transferencia', 'Store::toRefuseTransfers');
	$routes->post('eliminar-codigo', 'Store::deleteCode');
	$routes->post('existe-codigo', 'Store::codeExists');
	$routes->get('reportes', 'Store::viewReports');
	$routes->post('genera_reportes', 'Store::generateReports');

		$routes->get('nomina', 'Store::Workbeat');

	/*scanner */
	$routes->get('scanner', 'Store::scanner');

	/*Vales */
	$routes->get('materiales', 'Store::viewVales');
	$routes->post('lista_vales', 'Store::listDataValesByUser');
	$routes->post('confirmar_entrega', 'Store::confirmDeliveryVale');

	/*Reporte pdf */
	$routes->post('generar_pdf', 'Store::generarPdf');

	/*Reporte Codigo de Barras */
	$routes->get('crear_barras', 'Store::crearCodigoBarras');

	/*Reporte Codigo */
	$routes->get('solicitudes', 'Store::viewRequests');
	$routes->post('tbl_solicitudes_almacen', 'Store::tblFacturasAlmacen');
	$routes->post('solicitud_factura', 'Store::solicitudFactura');
	$routes->get('obtener_archivos/(:any)', 'Store::obtenerArchivos/$1');
});

/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de VIATICOS Y GASTOS Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */
$routes->group('viajes', ['namespace' => 'App\Controllers\Travel'], function ($routes) {
	// $classe = (session()->id_user == 1063) ? 'Travel2' : 'Travel' ;
	$classe = 'Travel';
	$routes->get('solicitud', $classe . '::viewCreateRequest');
	$routes->get('mis-solicitudes', $classe . '::viewMyRequest');
	//$routes->get('solicitudes', $classe.'::viewRequestAll');
	$routes->get('autorizar', $classe . '::viewRequestAuthorize');
	$routes->get('reportes', $classe . '::viewReposrts');
	$routes->get('ver-solicitud/(:any)', $classe . '::pdfRequest/$1');
	$routes->post('insertar', $classe . '::insertRequest');
	$routes->post('mis-viajes', $classe . '::myRequest');
	$routes->post('todos-viajes', $classe . '::requestAll');
	$routes->post('autorizar-viajes', $classe . '::requestAuthorize');
	$routes->post('autorizar_viaje', $classe . '::authorized');
	$routes->post('editar_viaje', $classe . '::editRequest');
	$routes->post('editar_viaje_all', $classe . '::editRequestALL');
	$routes->post('guardar_documentos', $classe . '::saveDocument');
	$routes->post('aprovar_viaje', $classe . '::requestApprove');
	$routes->post('genera_reportes', $classe . '::generateReports');
	/* --- Fun para Modulo Viaticos y Gastos --- */
	$routes->get('solicitudes', $classe . '::viewRequestTravel');
	$routes->get('comprobacion', $classe . '::viewVerification');
	$routes->get('pruebas', $classe . '::xml');
	$routes->get('subir', $classe . '::subirXML');
	$routes->get('ver-solicitud-viaticos/(:any)', $classe . '::pdfRequestTravel/$1');
	$routes->get('ver-solicitud-gastos/(:any)', $classe . '::pdfRequestExpenses/$1');

	$routes->post('info-viaticos', $classe . '::infoTravels');
	$routes->post('todos_viaticos', $classe . '::requestTravelAll');
	$routes->post('datos_autoriza_viaticos', $classe . '::requestTravelAuthorized');
	$routes->post('mis-viaticos', $classe . '::requestMyTravel');
	$routes->post('editar_viaticos', $classe . '::editTravel');
	$routes->post('autorizar_viaticos', $classe . '::authorizedTravel');
	$routes->post('monto_viatico', $classe . '::perDiemTravel');
	$routes->post('registrar_gastos', $classe . '::insertRequestExpenses');
	$routes->post('buscar-datos', $classe . '::searchData');
	$routes->post('registrar-gastos', $classe . '::subirGastosXML');
	$routes->post('registrar-comprobantes', $classe . '::subirXML');
	$routes->post('registrar-comprobantes-notas', $classe . '::subirNotasXML');
	$routes->post('registrar-comprobantes_efEx', $classe . '::subirEfExXML');
	$routes->post('registrar-comprobantes_efExGastos', $classe . '::subirEfExGastosXML');
	$routes->post('todos_gastos', $classe . '::requestExpensesAll');
	$routes->post('datos_autoriza_gastos', $classe . '::requestExpensesAuthorized');
	$routes->post('descargar_gasto_zip', $classe . '::downloadZip');
	$routes->post('eliminar_viaticos', $classe . '::deleteTravels');
	$routes->post('eliminar_gastos', $classe . '::deleteExpenses');
	$routes->post('editar_gasto', $classe . '::editExpenses');
	$routes->post('autorizar_gasto', $classe . '::authorizedExpenses');
	$routes->post('mis-gastos', $classe . '::requestMyExpenses');
	$routes->post('actualizar_cuentas', $classe . '::updateAccounts');
	$routes->post('reporte_folios_comparativos', $classe . '::typeReport');





	/* --- controlador para comprobacion --- */
	$routes->get('ver_datos_folio/(:any)', $classe . '::viewDataRequest/$1');
	$routes->get('ver-datos-folio/(:any)', $classe . '::viewDataRequest/$1');
	$routes->get('autorizacion-comprobacion-fuera-tiempo', $classe . '::viewAccountAuthorization');
	$routes->post('mis_comprobaciones', $classe . '::myListChecks');
	$routes->post('datos_folio_tipo', $classe . '::dataRequestItemsByFolioType');
	$routes->post('reporte_folio_tipo', $classe . '::reportXlsxByRequestType');
	$routes->get('ver-estados-cuenta-por-folio/(:any)', $classe . '::viewAccountStatusByFolio/$1');
	$routes->get('ver-estados-cuenta-por-gastos/(:any)', $classe . '::viewAccountStatusByFolio/$1');
	$routes->post('datos_solicitud_cartas_cabeza', $classe . '::dataRequestHeadLetters');
	$routes->post('subir_estado_cuenta_masivo', $classe . '::insertTravelAccountMassive');
	$routes->post('subir_estado_cuenta_individual', $classe . '::insertTravelAccountIndividual');
	$routes->post('subir_estado_cuenta', $classe . '::insertTravelAccount');
	$routes->post('lista_estado_cuenta', $classe . '::listTravelAccount');
	$routes->post('descargar_pdf_xml_comprobacion', $classe . '::dowloadFileAccount');
	$routes->post('lista_comprobaciones_pendientes', $classe . '::listAccountStatusPendingCheck');
	$routes->post('actualizar_comprobacion_tardia', $classe . '::updateAccountStatusPendingCheck');
	$routes->post('eliminar_estados_de_cuenta', $classe . '::deletAccountStatus');
	$routes->post('eliminar_comprobacion_estados_de_cuenta', $classe . '::deletVerificationAccountStatus');
	$routes->post('notificar_estado_cuenta_masivo', $classe . '::notifyStatusAcounts');
	$routes->post('reporte_folios_activos_por_usuario', $classe . '::reportXlsxByUserRequestActive');
	$routes->post('reporte_folios_activos_por_fecha', $classe . '::reportXlsxByDateRequestActive');
	$routes->post('nombres_usuarios_archivo', $classe . '::nameUserByFile');


	/* --- nuevas funciones formulario --- */
	$routes->post('lista_internacional_grados', $classe . '::listInternationalDegree');
	$routes->post('calcular_viaticos', $classe . '::calculateTravelExpenses');
	$routes->post('viaticos', $classe . '::travelExpenses');


	/* --- Fun para otro Modulos --- */
	$routes->post('firma', $classe . '::firma');
	$routes->post('saveFirma', $classe . '::saveFirma');

	/*--- revisar los xml --- */

	$routes->post('revisar-comprobantes', $classe . '::revisarXML');

	$routes->get('ver-permisos/(:any)', $classe . '::pdfRequestTravelExpenses/$1');
});


/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de VH Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */
$routes->group('suministros', ['namespace' => 'App\Controllers\Supplies'], function ($routes) {
	$routes->get('solicitud', 'Supplies::view_request');
	$routes->get('todas-solicitudes', 'Supplies::view_requestAll');
	$routes->get('ver-orden/(:any)', 'Supplies::pdfRequest/$1');


	$routes->post('buscar-partida', 'Supplies::searchItem');
	$routes->post('guardar-solicitud', 'Supplies::saveRequest');
	$routes->post('listar-ordenes', 'Supplies::requestAll');
	$routes->post('listar-items', 'Supplies::listItems');
	$routes->post('cerrar-partida', 'Supplies::closeLineItem');
	$routes->post('editar-orden-compra', 'Supplies::editPurchaseOrder');
	$routes->post('actualizar_orden', 'Supplies::updateOrder');
	$routes->post('eliminar_orden_compra', 'Supplies::deleteOrder');
	$routes->post('reporte-excel', 'Supplies::reportExcel');
});

/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de SERVICIO MEDICO Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */

$routes->group('medico', ['namespace' => 'App\Controllers\Medical'], function ($routes) {

	$routes->get('generar-permiso', 'Medical::index');
	$routes->get('todos-los-permisos', 'Medical::viewRequestAll');
	$routes->get('generar-consulta', 'Medical::consultation');
	$routes->get('examen-medico', 'Medical::viewMedicalExam');
	$routes->get('inventario-medicamentos', 'Medical::viewInventoriMedical');
	$routes->get('reportes-medicos', 'Medical::viewReports');
	$routes->get('ver-incapacidad-medica/(:any)', 'Medical::pdfRequestMedical/$1');
	$routes->get('ver-consulta/(:any)', 'Medical::pdfConsultRequestMedical/$1');

	$routes->post('generar', 'Medical::generateRequest');
	$routes->post('generar_consulta', 'Medical::generateConsultationRequest');
	$routes->post('todas_solicitudes', 'Medical::RequestAll');
	$routes->post('datos_usuario', 'Medical::userData');
	$routes->post('excel_incapacidad', 'Medical::xlsxRequest');
	$routes->post('excel_consultas', 'Medical::xlsxConsultRequest');
	$routes->post('datos_medicamento', 'Medical::dataMedicine');
	$routes->post('inventario_medicamentos', 'Medical::dataInventoryMedicine');
	$routes->post('alta_medicamento', 'Medical::insertMedicine');
	$routes->post('eliminar_medicamento', 'Medical::deletedMedicine');
	$routes->post('mover_medicamento', 'Medical::updateMedicine');
	$routes->post('todas_consultas_medicas', 'Medical::consultMedicALL');
	$routes->post('datos_consulta_medica', 'Medical::dataConsultMedic');
	$routes->post('actualizar_consulta_medica', 'Medical::updateConsultMedic');
	$routes->post('generar_examen', 'Medical::generateExam');
	$routes->post('todas_examenes_medicos', 'Medical::consultExamALL');
});

/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de RECORRIDOS DE HSE Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */
$routes->group('recorridos-HSE', ['namespace' => 'App\Controllers\ToursHSE'], function ($routes) {
	$routes->get('reportes-graficas', 'ToursHSE::viewReportsAll');
	$routes->get('resgistrar-incidencia', 'ToursHSE::viewSafetyToursForm');
	$routes->get('seguimiento-incidencia-condiciones-inseguras', 'ToursHSE::viewFollowIncidentsCondition');
	$routes->get('seguimiento-incidencia-actividades-inseguras', 'ToursHSE::viewFollowIncidentsActivitys');
	$routes->get('ver-detalles-incidencia/(:any)', 'ToursHSE::pdfRequest/$1');

	$routes->post('buscar_lista_categoria', 'ToursHSE::searchCategoryList');
	$routes->post('buscar_insidecias_anteriores', 'ToursHSE::searchCategoryList');
	$routes->post('registrar_recorido', 'ToursHSE::insertToursRquest');
	$routes->post('todas_insidencias_usuario', 'ToursHSE::searchRequestListByUsers');
	$routes->post('registrar_incidencia', 'ToursHSE::insertIncidentRquest');
	$routes->post('todas_incidencias_condiciones', 'ToursHSE::getALLIncidentesConditions');
	$routes->post('todas_incidencias_actividades', 'ToursHSE::getALLIncidentesActivitys');
	$routes->post('registrar_respuesta_incidencia', 'ToursHSE::updateIncidentRquestResponse');
	$routes->post('todas_incidencias_fechas', 'ToursHSE::getAllRequestIncidents');
	$routes->post('lista_incidencias_tipo_mes', 'ToursHSE::listRequestIncidentsByTypeAndDate');
	$routes->post('excel_todas_incidencias_fechas_depto', 'ToursHSE::XlsxRequestIncidentsByDeptoAndDate');
});

/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de ESTACIONAMIENTO Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */

$routes->group('estacionamiento', ['namespace' => 'App\Controllers\Parking'], function ($routes) {

	$routes->get('registro-de-usuarios', 'Parking::adminUsers');
	$routes->get('entradas-salidas', 'Parking::inOutControl');
	$routes->get('reportes', 'Parking::viewReports');
	$routes->get('registar-mi-vehiculo', 'Parking::viewRegisterMyVehicle');
	$routes->get('movimientos-de-vehiculos', 'Parking::viewMovementsVehicles');
	$routes->get('asignar-cajon', 'Parking::viewAssignmentDrawer');
	// $routes->get('ver-incapacidad-medica/(:any)', 'Parking::pdfRequestParking/$1');

	$routes->post('generar', 'Parking::generateRequest');
	$routes->post('descargar_reportes', 'Parking::xlsxRequest');
	$routes->post('generate_code', 'Parking::generateQRCodeH');
	$routes->post('todos_registros/(:any)', 'Parking::recordALLH/$1');
	$routes->post('datos_registros', 'Parking::recordData');
	$routes->post('datos_usuario', 'Parking::userDataForRegister');
	$routes->post('datos_marberte', 'Parking::dataTag');
	$routes->post('mis_vehiculos', 'Parking::myVehicles');
	$routes->post('generar_vehiculos_usuario', 'Parking::generateRegisterByUser');
	$routes->post('registar_entrada_salida/(:any)', 'Parking::recordInputOutput/$1');
	$routes->post('datos_movimientos_vehiculos', 'Parking::dataMovementsVehicles');
	$routes->post('estado_autorizacion', 'Parking::updateStatusAuthorization');
	$routes->post('datos_vehiculos_cajon', 'Parking::dataVehiclesDrawer');
	$routes->post('asignar_cajon', 'Parking::assingDrawer');
	$routes->post('ubicacion_qr', 'Parking::qrLocation');
	$routes->post('actualiza_poliza', 'Parking::updateArchiveExpiration');
	$routes->post('generar_nuevo_vehiculo', 'Parking::generateNewVehiculeItem');
	$routes->post('borrar_vehiculos/(:any)', 'Parking::deleteItem/$1');
	$routes->post('eliminar_marberte/(:any)', 'Parking::deleteRegister/$1');
	$routes->post('eliminar_vehiculo', 'Parking::deleteItems');
	$routes->post('marbetes_disponibles', 'Parking::listTags');
});

/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de TICKETS IT Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */

$routes->group('tickets', ['namespace' => 'App\Controllers\Tickets'], function ($routes) {

	$routes->get('tablero', 'Tickets::viewTable');
	$routes->get('tecnologias-informacion', 'Tickets::viewTable');
	$routes->get('video', 'Tickets::viewVideo');
	$routes->get('reporte-tickets', 'Tickets::viewReportes');
	$routes->get('ver-ticket-mantenimiento/(:any)', 'Tickets::pdfRequestTickets/$1');

	$routes->post('todos_tickets', 'Tickets::ticketsALL');
	$routes->post('buscar_tickets', 'Tickets::searchTickets');
	$routes->post('lista_inge_por_actividad', 'Tickets::dataToCreateTicket');
	$routes->post('actividad_inge_por_area', 'Tickets::dataToCreateTicket');

	$routes->post('depto_user', 'Tickets::deptoUser');
	$routes->post('generar_tickets', 'Tickets::insertTickets');
	$routes->post('detalles_ticket', 'Tickets::dataTicket');
	$routes->post('reasignar_ticket', 'Tickets::reasigTicket');
	$routes->post('prioridad_ticket', 'Tickets::priorityTicket');
	$routes->post('estado_ticket', 'Tickets::statusTicket');
	$routes->post('agregar_chat_ticket', 'Tickets::addChat');
	$routes->post('obtener_informacion_reportes', 'Tickets::getDataReports');
	$routes->post('buscar_fecha_tickets', 'Tickets::searchtTicketsData');
	$routes->post('cancelar_ticket', 'Tickets::cancelTicketForUser');

	/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de TICKETS MATENIMIENTO Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */

	$routes->get('mantenimiento', 'Tickets::viewTableMaintenance');
	// $routes->get('ver-incapacidad-medica/(:any)', 'Tickets::pdfRequestTickets/$1');

	$routes->post('todos_tickets_mantenimiento', 'Tickets::ticketsMaintenanceALL');
	$routes->post('mantenimiento_generar_ticket', 'Tickets::insertMaintenance');
	$routes->post('detalles_ticket_mantenimiento', 'Tickets::dataTicketMaintenance');
	$routes->post('mantenimiento_autorizar_ticket', 'Tickets::authorizeMaintenance');
	$routes->post('mantenimiento_asignar_ticket', 'Tickets::assigMaintenance');
	$routes->post('mantenimiento_concluir_ticket', 'Tickets::concludMaintenance');
	$routes->post('mantenimiento_cerrar_ticket', 'Tickets::clossedMaintenance');
	$routes->post('mantenimiento_cancelar_ticket', 'Tickets::cancelMaintenance');
	$routes->post('mantenimiento_refaccion_ticket', 'Tickets::sparePartMaintenance');
	$routes->post('mantenimiento_buscar_tickets', 'Tickets::searchTicketsMaintenance');
	$routes->post('mantenimiento_buscar_fecha_tickets', 'Tickets::searchTicketsForDateMaintenance');
	$routes->post('mantenimiento_buscar_folio_tickets', 'Tickets::searchFolioTicketsMaintenance');
	$routes->post('mantenimiento_datos_maquinas', 'Tickets::dataMachineMaintenance');

	/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de TICKETS SERVICIOS GENERAL Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */

	$routes->get('servicios-generales', 'Tickets::viewTableServiceGral');
	// $routes->get('ver-incapacidad-medica/(:any)', 'Tickets::pdfRequestTickets/$1');

	$routes->post('servicios_todos_tickets', 'Tickets::dataTiketsServiceGral');
	$routes->post('servicios_actividades', 'Tickets::dataActivitiesServiceGral');
	$routes->post('servicios_generar_tickets', 'Tickets::insertTicketServiceGral');
	$routes->post('servicios_detalles_ticket', 'Tickets::dataTicketServiceGral');
	$routes->post('servicios_estado_ticket', 'Tickets::statusTicketServiceGral');
	$routes->post('servicios_buscar_tickets', 'Tickets::searchTicketsServiceGral');
});


/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de VH Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */
$routes->group('corporativo', ['namespace' => 'App\Controllers\Corporation'], function ($routes) {
	//$routes->get('ver-orden/(:any)', 'Supplies::pdfRequest/$1');
	$routes->get('reportes-permisos', 'Corporation::viewReports');
	$routes->get('reportes-servicios', 'Corporation::viewReportsServices');
	$routes->get('reportes', 'Corporation::pdfReportsServices');

	$routes->post('reporte-indicadores', 'Corporation::pdfReportsServices');
	$routes->post('buscar-partida', 'Supplies::searchItem');
	$routes->post('generar_reportes', 'Corporation::reportsGenerate');
	$routes->post('entrada_salida_fecha', 'Corporation::permissionsDate');
	$routes->post('vacaciones_todos_fecha', 'Corporation::vacationsDate');
	$routes->post('generar_reportes_servicios', 'Corporation::reportServicio');
	$routes->post('generar_reportes_todos_servicios', 'Corporation::reportServiciosALL');
});


/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de Cron para ejecutar cada cierto tiempo
 * --------------------------------------------------------------------
 */

$routes->group('admin', ['namespace' => 'App\Controllers\Admin'], function ($routes) {

	$routes->get('vacaciones', 'Admin::CalcularVacaciones');
	$routes->get('contratos', 'Admin::notifySupply');

	$routes->get('facturas', 'Admin::fac');

	$routes->get('active_login', 'Admin::activeLogin');



	$routes->get('reset_permisos', 'Admin::permissionsReset');
	$routes->get('comprobar-gastos', 'Admin::notificationOfexpenses');
	$routes->get('comprobar-viaticos', 'Admin::travelNotification');
	$routes->get('tipo_cambio', 'Admin::obtener_tipo_cambio');

	$routes->get('cerrar_tickets', 'Admin::closedTickets');


	$routes->post('datos_factura', 'Admin::consultarEstatusCfdi');
});

/*
 * --------------------------------------------------------------------
 * Rutas para el Video de Talento
 * --------------------------------------------------------------------
 */

$routes->group('talento', ['namespace' => 'App\Controllers\Tickets'], function ($routes) {
	$routes->get('video', 'Tickets::viewVideo');
});

/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de Comedor Dining Room Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */
$routes->group('comedor', ['namespace' => 'App\Controllers\DiningRoom'], function ($routes) {
	$routes->get('calendario', 'DiningRoom::viewCalendar');
	$routes->get('obtener_eventos', 'DiningRoom::obtenerEventos');
	$routes->post('guardar_evento', 'DiningRoom::guardarEventos');
});

/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de Comedor Dining Room Creación, Rechazadas, Edición, Actualizacion 
 * --------------------------------------------------------------------
 */
$routes->group('finanzas', ['namespace' => 'App\Controllers\Finance'], function ($routes) {

	/*******************************
	 * RUTAS para INVENTARIO BLANCA *
	 *******************************/

	$routes->get('creaqr', 'Finance::activeQr');
	$routes->get('creaqrmobiliario', 'Finance::activeQrMobiliario');
	$routes->get('creadxf', 'Finance::activeQrDXF');
	$routes->get('creadxfmobiliario', 'Finance::activeQrDXFMobiliario');
	$routes->get('maquinaria', 'Finance::viewInventory');
	$routes->get('mobiliario', 'Finance::viewMobiliario');

	$routes->get('descargar_carpeta/(:any)', 'Finance::downloadData/$1');
	$routes->get('item-inventario/(:any)', 'Finance::viewQrInventory/$1');

	$routes->post('editar_activo', 'Finance::editInventory');
	$routes->post('graficar_activos', 'Finance::graphAssets');
	$routes->post('todo_inventario', 'Finance::inventoryAll');
	$routes->post('alta_activo', 'Finance::assetRegistration');
	$routes->post('actualizar_activo', 'Finance::updateInventory');
	$routes->post('activar_activo', 'Finance::activeInventoryItem');
	$routes->post('inventario_inactivo', 'Finance::inactiveInventory');
	$routes->post('desactivar_activo', 'Finance::inactiveInventoryItem');
	$routes->post('subir_comprobante', 'Finance::uploadReceipt');
	
	$routes->post('todo_inventario_mobiliario', 'Finance::inventoryMobiliarioAll');
	$routes->post('inventario_inactivo_mobiliario', 'Finance::inactiveInventoryMobiliario');
	$routes->post('alta_activo_mobiliario', 'Finance::assetRegistrationMobiliario');
	$routes->get('descargar_carpeta_mobiliario/(:any)', 'Finance::downloadDataMobiliario/$1');
	$routes->post('activar_mobiliario', 'Finance::activeInventoryItemMobiliario');
	$routes->post('desactivar_mobiliario', 'Finance::inactiveInventoryItemMobiliario');
	$routes->post('editar_mobiliario', 'Finance::editInventoryMobiliario');
	$routes->post('actualizar_mobiliario', 'Finance::updateInventoryMobiliario');
	$routes->get('item-mobiliario/(:any)', 'Finance::viewQrMobiliario/$1');


	/*******************************
	 * RUTAS para ADM. DE PERSONAL *
	 *******************************/
	$routes->get('solicitud_pago', 'Finance::viewAdmPago');
	$routes->get('pagar_solicitud', 'Finance::viewAdmPagar');
	$routes->get('aprobar_solicitud', 'Finance::viewAdmAprobar');
	$routes->get('solicitudes_pagadas', 'Finance::viewAdmPagarFinanzas');
	$routes->get('autorizar_solicitud', 'Finance::viewAdmAutorizar');
	$routes->get('descargar_solicitudes/(:any)', 'Finance::downloadDatas/$1');

	$routes->post('subir_pdf', 'Finance::uploadPdf');
	$routes->post('firmar_pdf', 'Finance::signPdf');
	$routes->post('autorizar_pdf', 'Finance::signPdfAutorize');
	$routes->post('pago_solicitud_pdf', 'Finance::payApplicationPdf');
	$routes->post('aprobar_soliciud_pdf', 'Finance::authorizationPdf');
	$routes->post('autorizar_solicitud_pdf', 'Finance::authorizationPdf');


	$routes->post('generar_solicitud_pago', 'Finance::savePaymentRequestAdm');
	$routes->post('solicitudes', 'Finance::getRequests');
	$routes->post('tbl_aprobar_solicitudes', 'Finance::getRequestsAprove');
	$routes->post('eliminar_solicitud', 'Finance::deletePaymentRequest');
	$routes->post('rechazar_solicitud', 'Finance::rejectPaymentRequest');
	$routes->post('tbl_autorizar_solicitudes', 'Finance::getRequestsAutorize');
	$routes->post('tbl_pagar_solicitudes', 'Finance::getRequestsTopay');

	$routes->post('tbl_solicitudes_pagadas', 'Finance::getPaidRequests');
	$routes->post('marcar_realizada', 'Finance::markAsCompleted');
	$routes->post('actualizar_id_epicor', 'Finance::updateIdEpicor');

	/**************** Percepciones y Deducciones ****************/

	$routes->get('percepciones_deducciones', 'AdminPersonal::viewPercepcionesDeducciones');


	/*******************************
	 * RUTAS para TALENTO *
	 *******************************/
	$routes->get('generar-pdf', 'Finance::generarTalentoPDF');
	$routes->get('solicitudes_pagos', 'Finance::viewTalentPago');
	$routes->get('autorizar_pagos', 'Finance::viewAutorizarPago');

	$routes->post('firmar_pdf_talento', 'Finance::signPdf');
	$routes->post('firmar_talento_pdf', 'Finance::signPdfTalento');
	$routes->post('tbl_autorizar_pagos', 'Finance::requestTable');
	$routes->post('autorizar_pago_pdf', 'Finance::signPdfAutorizeTalento');
	$routes->post('crear_solicitud', 'Finance::createPaymentRequest');
	$routes->post('solicitudes_talento', 'Finance::getRequestTalent');
	$routes->post('guardar_solicitud_pago', 'Finance::savePaymentRequestTalent');


	$routes->post('eliminar_solicitud_talento', 'Finance::deletePaymentRequestTalent');
	$routes->get('descargar_solicitudes_talento/(:any)', 'Finance::downloadDataTalent/$1');
	$routes->post('rechazar_solicitud_talento', 'Finance::rejectPaymentRequestTalent');
	$routes->post('autorizar_solicitud_talento_pdf', 'Finance::authorizationTalentoPdf');

	$routes->get('pagar_solicitudes', 'Finance::viewPagarSolicitudes');
	$routes->get('pago_solicitudes', 'Finance::viewPagoSolicitudesTalento');

	$routes->post('tbl_pagar_solicitudes_talento', 'Finance::getRequestsTopayTalent');

	$routes->post('pago_solicitud_pdf', 'Finance::payApplicationPdf');
	$routes->post('autoriza_pago_talento', 'Finance::signPdfAutorizeEnrico');
	$routes->post('pagar_solicitud_talento', 'Finance::payApplicationPdfTalento');

	$routes->post('subir_comprobante_talento', 'Finance::uploadReceiptTalento');
	$routes->post('crear_codigo/(:num)', 'Finance::createCode/$1');

	$routes->post('tbl_solicitudes_pagadas_talento', 'Finance::getPaidRequestsTalento');

	$routes->post('marcar_realizada_talento', 'Finance::markAsCompletedTalento');
	$routes->post('actualizar_id_epicor_talento', 'Finance::updateIdEpicorTalento');



	//$routes->get('ver-ticket-mantenimiento/(:any)', 'Tickets::pdfRequestTickets/$1');


});


/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de Liberacion de Recursos Humanos Creación, Rechazadas, Edición, Actualizacion Santos
 * --------------------------------------------------------------------
 */
$routes->group('liberacion', ['namespace' => 'App\Controllers\Liberation'], function ($routes) {
	
	//VISTA Items
	$routes->get('items', 'Liberation::viewItems');
	$routes->post('crear_item', 'Liberation::saveItem');  
	$routes->post('todos_los_items', 'Liberation::requestAllItems');
	$routes->post('desactivar_items', 'Liberation::deactivateItem');

    //VISTA Solicitudes
	$routes->get('solicitudes_liberacion', 'Liberation::viewUserRegistration');
	$routes->get('pruebas_notificacion', 'Liberation::pruebasNotify');
	$routes->get('pruebas_notificacion_complete', 'Liberation::pruebasNotifyComplete');
	$routes->post('companies_list', 'Liberation::getCompanies');
	$routes->get('users_by_company/(:num)', 'Liberation::getUsersByCompany/$1');
	$routes->post('crear_solicitud', 'Liberation::createLiberationRequest');
	$routes->post('todas_las_solicitudes', 'Liberation::requestLiberationAll');
	$routes->post('desactivar_solicitud', 'Liberation::deactivateRequest');
	$routes->post('subir_solicitudes_liberacion_masivo', 'Liberation::insertRequestLiberationMassive');
	$routes->get('ver-solicitud/(:any)', 'Liberation::pdfLiberation/$1');
	

    //VISTA Departamentos
	$routes->get('solicitudes_departamento', 'Liberation::viewDepartmentRequestLiberation');
	$routes->post('todos_las_solicitudes_del_departamento', 'Liberation::requestLiberationDepartmentAll');
	$routes->post('getItemsByRequestAndDepartment', 'Liberation::getItemsByRequestAndDepartment');
	$routes->post('updateItemSigned', 'Liberation::updateItemSigned');
	$routes->post('updateItemDelivered', 'Liberation::updateItemDelivered');
	$routes->post('actualizarTelefono', 'Liberation::updatePhone');
	$routes->post('guardarComentario', 'Liberation::saveComment');

	//TODO
	$routes->get('solicitud', 'Liberation::viewCreateRequest');
	$routes->get('encontrar', 'Liberation::buscarUsuario');
});

/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de Liberacion de Recursos Humanos Creación, Rechazadas, Edición, Actualizacion Santos
 * --------------------------------------------------------------------
 */
$routes->group('logistica', ['namespace' => 'App\Controllers\Logistica'], function ($routes) {

	//VISTA Items
	$routes->get('solicitudes', 'Logistica::viewSolicitudesMsi');
	$routes->post('crear_item', 'Logistica::saveItem');  
	$routes->post('todos_los_items', 'Logistica::requestAllItems');
	$routes->post('desactivar_items', 'Logistica::deactivateItem');
	$routes->get('conceptos', 'Logistica::listar');
	$routes->post('guardar_solicitud', 'Logistica::guardar');
	$routes->post('solicitudes_logistica', 'Logistica::todasLasSolicitudes');
	$routes->post('solicitudes/firmar', 'Logistica::firmar');
	$routes->post('solicitudes/avanzar', 'Logistica::avanzar');
	
	


  
});

/*
 * --------------------------------------------------------------------
 * Rutas para el modulo de Liberacion de Recursos Humanos Creación, Rechazadas, Edición, Actualizacion Santos
 * --------------------------------------------------------------------
 */
$routes->group('vigilancia', ['namespace' => 'App\Controllers\Vigilancia'], function ($routes) {

	//VISTA Items
	$routes->get('usuarios_estacionamiento', 'Vigilancia::viewListadoEstacionamientos');
	$routes->get('scanner','Vigilancia::viewScannerQr');
	$routes->post('tbl_usuarios_estacionamientos', 'Vigilancia::tblUsuariosEstacionamientos');  
	$routes->post('todos_los_items', 'Vigilancia::requestAllItems');
	$routes->post('desactivar_items', 'Vigilancia::deactivateItem');
	$routes->get('encontrar', 'Vigilancia::buscarUsuario');
	$routes->post('alta_usuario_estacionamiento', 'Vigilancia::altaUsuarioEstacionamiento');
	$routes->post('baja_usuario_estacionamiento', 'Vigilancia::bajaUsuarioEstacionamiento');	
	$routes->post('registrar', 'Vigilancia::registrarEntradaSalida');
	$routes->post('tbl_estacionamientos', 'Vigilancia::tblEstacionamientos');
	$routes->get('delete_estacionamiento/(:num)', 'Vigilancia::deleteEstacionamiento/$1');

	
  
});


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
