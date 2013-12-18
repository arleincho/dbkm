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

Load::models('stti/duenio');

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
            if(Duenio::setDuenio('create', Input::post('duenio'), array('aprobado'=>Duenio::APROBADO))){
                DwMessage::valid('El Dueño se ha registrado correctamente!');
                return DwRedirect::toAction('listar');
            }          
        }
        $this->page_title = 'Agregar Dueño de Puntos';
    }
}