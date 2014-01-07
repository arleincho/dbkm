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

Load::models('stti/duenio', 'stti/punto', 'stti/transacciones');

class PuntosController extends BackendController {
    
    /**
     * Método que se ejecuta antes de cualquier acción
     */
    protected function before_filter() {
        //Se cambia el nombre del módulo actual
        $this->page_module = 'Gestión de Puntos';
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
    public function listar($order='order.punto.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;
        $puntos = new Punto();
        $this->puntos = $puntos->getListadoPuntos('todos', $order, $page);        
        $this->order = $order;        
        $this->page_title = 'Listado de Puntos';
    }

    /**
     * Método para agregar
     */
    public function agregar() {
        if(Input::hasPost('punto')) {
            if(Punto::setPunto('create', Input::post('punto'), array())){
                DwMessage::valid('El Punto se ha registrado correctamente!');
                return DwRedirect::toAction('listar');
            }          
        }
        $this->page_title = 'Agregar Puntos de Atenci&oacute;n';
    }


    /**
     * Método para ver
     */
    public function ver($key, $order='order.punto.asc', $page='pag.1') { 
        $page = (Filter::get($page, 'page') > 0) ? Filter::get($page, 'page') : 1;     
        if(!$id = DwSecurity::isValidKey($key, 'show_punto', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $punto = new Punto();
        if(!$punto->getInformacionPunto($id)) {
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }

        $transacciones = new Transacciones();
        $this->transacciones = $transacciones->getTransaccionesPorPuntos($punto->id, $order, $page);

        $this->punto = $punto;
        $this->order = $order;
        $this->page_title = 'Información del Punto de Atencion';
        $this->key = $key;
    }

    /**
     * Método para editar
     */
    public function editar($key) {        
        if(!$id = DwSecurity::isValidKey($key, 'upd_punto', 'int')) {
            return DwRedirect::toAction('listar');
        }
        
        $punto = new Punto();
        if(!$punto->getInformacionPunto($id)) {
            DwMessage::get('id_no_found');    
            return DwRedirect::toAction('listar');
        }                
        
        if(Input::hasPost('punto')) {
            if(DwSecurity::isValidKey(Input::post('punto_id_key'), 'form_key')) {
                ActiveRecord::beginTrans();
                if(Punto::setPunto('update', Input::post('punto'), array('id'=>$punto->id))) {
                    ActiveRecord::commitTrans();
                    DwMessage::valid('El Punto se ha actualizado correctamente.');
                    return DwRedirect::toAction('listar');
                }
            }
        }
        $this->punto = $punto;
        $this->page_title = 'Actualizar Puntos de Atencion';
        
    }

    /**
     * Método para eliminar
     */
    public function eliminar($key) {         
        if(!$id = DwSecurity::isValidKey($key, 'eliminar_punto', 'int')) {
            return DwRedirect::toAction('listar');
        }        
        
        $punto = new Punto();
        if(!$punto->find_first($id)) {
            DwMessage::get('id_no_found');
            return DwRedirect::toAction('listar');
        }              
        try {
            if($punto->delete()) {
                DwMessage::valid('El Punto se ha eliminado correctamente!');
            } else {
                DwMessage::warning('Lo sentimos, pero este Punto no se puede eliminar.');
            }
        } catch(KumbiaException $e) {
            DwMessage::error('Este Punto no se puede eliminar porque se encuentra relacionado con otro registro.');
        }
        return DwRedirect::toAction('listar');
    }
}