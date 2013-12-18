<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Descripcion: Modelo para el manejo de las copias de seguridad
 *
 * @category
 * @package     Models
 * @subpackage
 * @author      Iván D. Meléndez (ivan.melendez@dailyscript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co) 
 */

class Banco extends ActiveRecord {


    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
        $this->has_many('duenio');
    }


    public function getListadoBancos($estado='todos', $order='', $page=0) {                   
        $columns = 'banco.*';        
        $join = '';
        $conditions = 'banco.id IS NOT NULL';     
        
        $order = $this->get_order($order, 'nombre');
        $group = 'banco.id';
        if($page) {            
            return $this->paginated("columns: $columns", "join: $join", "conditions: $conditions", "group: $group", "order: $order", "page: $page");
        }
        return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "group: $group", "order: $order");
    }
}