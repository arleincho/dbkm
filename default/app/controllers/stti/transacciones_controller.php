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

Load::models('stti/punto', 'stti/transacciones', 'stti/duenio', 'sistema/usuario');

class TransaccionesController extends BackendController {
    
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Gestión de Transacciones';
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
    public function listar($order='order.punto_id.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $transacciones = new Transacciones();
        $this->transacciones = $transacciones->getListadoTransacciones('todos', $order, $page);
        $this->order = $order;        
        $this->page_title = 'Listado de Transacciones para los Puntos';
    }

    /**
     * Método para agregar
     */
    public function agregar() {
        if(Input::hasPost('transaccion')) {
            if(Transacciones::setTransaccion('create', Input::post('transaccion'), array('estado' => Transacciones::PENDIENTE))){
                DwMessage::valid('La Transacción se ha registrado correctamente!');
                return DwRedirect::toAction('listar');
            }          
        }
        $this->page_title = 'Agregar Transacciones a los Puntos';
    }


    /**
     * Método para ver
     */
    public function ver($key, $order='order.valor.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;     
        if(!$id = DwSecurity::isValidKey($key, 'show_transaccion', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $transaccion = new Transacciones();
        if(!$transaccion->getInformacionTransaccion($id)) {
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }        
        
        $this->transaccion = $transaccion;
        $this->order = $order;
        $this->page_title = 'Información de la Transacción';
        $this->key = $key;
    }

    /**
     * Método para editar
     */
    public function editar($key) {
        if(!$id = DwSecurity::isValidKey($key, 'upd_transaccion', 'int')) {
            return DwRedirect::toAction('listar');
        }
        
        $transaccion = new Transacciones();
        if(!$transaccion->getInformacionTransaccion($id)) {
            DwMessage::get('id_no_found');    
            return DwRedirect::toAction('listar');
        }                
        
        if(Input::hasPost('transaccion')) {
            if(DwSecurity::isValidKey(Input::post('transaccion_id_key'), 'form_key')) {
                ActiveRecord::beginTrans();
                if(Transacciones::setTransaccion('update', Input::post('transaccion'), array('id'=>$transaccion->id))) {
                    ActiveRecord::commitTrans();
                    DwMessage::valid('La Transacción se ha actualizado correctamente.');
                    return DwRedirect::toAction('listar');
                }
            }
        }
        $this->transaccion = $transaccion;
        $this->page_title = 'Actualizar Transaccion';
        
    }

    /**
     * Método para aprobar/rechazar
     */
    public function estado($tipo, $key) {
        if(Session::get('perfil_id') == Perfil::SUPER_USUARIO || Session::get('perfil_id') == Perfil::ADMIN){
            if(!$id = DwSecurity::isValidKey($key, $tipo.'_transaccion', 'int')) {
                return DwRedirect::toAction('listar');
            }
            $transaccion = new Transacciones();
            if(!$transaccion->find_first($id)) {
                DwMessage::get('id_no_found');            
            } else {
                if($tipo=='pagar' && $transaccion->estado == Transacciones::PAGADA) {
                    DwMessage::info('La transacción ya se encuentra pagada');
                } else if($tipo!=='pagar') {
                    DwMessage::info('No se pude realizar la accion solicitada');
                } else {
                    $estado = Transacciones::PAGADA;
                    if(Transacciones::setTransaccion('update', $transaccion->to_array(), array('id'=>$id, 'estado' => $estado))){
                        DwMessage::valid('La Transacción se pago correctamente!');
                    }
                }
            }
        }else{
            DwMessage::valid('Ocurrio un error al realizar la acción');
        }
        return DwRedirect::toAction('listar');
    }
}