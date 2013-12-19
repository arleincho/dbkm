<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Descripcion: Controlador que se encarga de la gestión de los usuarios del sistema
 *
 * @category    
 * @package     Controllers 
 * @author      Iván D. Meléndez (ivan.melendez@dailycript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co)
 */

Load::models('stti/duenio', 'stti/punto');

class DueniosController extends BackendController {
    
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Gestión de Dueños';
    }
    
    /**
     * Método principal
     */
    public function index() {
        DwRedirect::toAction('listar');
    }

    /**
     * Método para listar
     */
    public function listar($order='order.razon_social.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $duenios = new Duenio();
        $this->duenios = $duenios->getListadoDuenios('todos', $order, $page);        
        $this->order = $order;        
        $this->page_title = 'Listado de Dueños de Puntos';
    }

    /**
     * Método para agregar
     */
    public function agregar() {
        if(Input::hasPost('duenio')) {
            if(Duenio::setDuenio('create', Input::post('duenio'), array('aprobado' => Duenio::APROBADO))){
                DwMessage::valid('El Dueño se ha registrado correctamente!');
                return DwRedirect::toAction('listar');
            }          
        }
        $this->page_title = 'Agregar Dueño de Puntos';
    }


    /**
     * Método para ver
     */
    public function ver($key, $order='order.duenio.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;     
        if(!$id = DwSecurity::isValidKey($key, 'show_duenio', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $duenio = new Duenio();
        if(!$duenio->getInformacionDuenio($id)) {
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }
        
        $puntos = new Punto();
        $this->puntos = $puntos->getPuntosPorDuenio($duenio->id, $order, $page);

        $this->duenio = $duenio;
        $this->order = $order;
        $this->page_title = 'Información del Dueño de Puntos';
        $this->key = $key;
    }

    /**
     * Método para editar
     */
    public function editar($key) {
        if(!$id = DwSecurity::isValidKey($key, 'upd_duenio', 'int')) {
            return DwRedirect::toAction('listar');
        }
        
        $duenio = new Duenio();
        if(!$duenio->getInformacionDuenio($id)) {
            DwMessage::get('id_no_found');    
            return DwRedirect::toAction('listar');
        }                
        
        if(Input::hasPost('duenio')) {
            if(DwSecurity::isValidKey(Input::post('duenio_id_key'), 'form_key')) {
                ActiveRecord::beginTrans();
                if(Duenio::setDuenio('update', Input::post('duenio'), array('id'=>$duenio->id))) {
                    ActiveRecord::commitTrans();
                    DwMessage::valid('El Dueño se ha actualizado correctamente.');
                    return DwRedirect::toAction('listar');
                }
            }
        }
        $this->duenio = $duenio;
        $this->page_title = 'Actualizar Deuño de Puntos';
        
    }

     /**
     * Método para aprobar/rechazar
     */
    public function estado($tipo, $key) {
        if(Session::get('perfil_id') == Perfil::SUPER_USUARIO || Session::get('perfil_id') == Perfil::ADMIN){
            if(!$id = DwSecurity::isValidKey($key, $tipo.'_duenio', 'int')) {
                return DwRedirect::toAction('listar');
            }
            $duenio = new Duenio();
            if(!$duenio->find_first($id)) {
                DwMessage::get('id_no_found');            
            } else {
                if($tipo=='rechazar' && $duenio->aprobado == Duenio::RECHAZADO) {
                    DwMessage::info('El Dueño del punto ya se encuentra rechazado');
                } else if($tipo=='aprobar' && $duenio->aprobado == Duenio::APROBADO) {
                    DwMessage::info('El Dueño del punto ya se encuentra aprobado');
                } else {
                    $estado = ($tipo=='rechazar') ? Duenio::RECHAZADO : Duenio::APROBADO;
                    if(Duenio::setDuenio('update', $duenio->to_array(), array('id'=>$id, 'aprobado' => $estado))){
                        ($estado==Duenio::APROBADO) ? DwMessage::valid('El Dueño del Punto se ha aprobado correctamente!') : DwMessage::valid('El Deuño del Punto se ha rechazado correctamente!');
                    }
                }
            }
            return DwRedirect::toAction('listar');
        }else{
            DwMessage::valid('Ocurrio un error al realizar la acción');
        }
    }
}