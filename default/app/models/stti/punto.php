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

class Punto extends ActiveRecord {

    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
        $this->belongs_to('duenio');
    }

    public static function condition_exists($obj){
        return "punto = '$obj->punto' and duenio_id = $obj->duenio_id and tipo_punto_id = '$obj->tipo_punto_id' and direccion = '$obj->direccion' and ciudad_id = '$obj->ciudad_id'";
    }

    /**
     * Método para obtener el listado de los puntos en el sistema
     * @param type $estado
     * @param type $order
     * @param type $page
     * @return type
     */
    public function getListadoPuntos($estado='todos', $order='', $page=0) {                   
        $columns = 'punto.*, COUNT(transacciones.id) AS transacciones, sum(transacciones.valor) AS valor_transacciones, duenio.razon_social, tipo_punto.tipo_punto';        
        $join = 'INNER JOIN duenio ON duenio.id = punto.duenio_id AND duenio.aprobado = ' . Duenio::APROBADO . ' ';
        $join .= 'INNER JOIN tipo_punto ON tipo_punto.id = punto.tipo_punto_id ';
        $join .= 'LEFT JOIN transacciones ON transacciones.punto_id = punto.id ';
        $conditions = 'punto.id IS NOT NULL';     
        
        $order = $this->get_order($order, 'punto', array(            
            'tipo_punto' => array(
                'ASC' => 'tipo_punto.tipo_punto ASC, punto.punto ASC',
                'DESC' => 'tipo_punto.tipo_punto DESC, punto.punto DESC'
            ),
            'razon_social' => array(
                'ASC' => 'duenio.razon_social ASC, punto.punto ASC',
                'DESC' => 'duenio.razon_social DESC, punto.punto DESC'
            ),
            'direccion' => array(
                'ASC' => 'punto.direccion ASC, punto.punto ASC',
                'DESC' => 'punto.direccion DESC, punto.punto DESC'
            ),
            'ciudad_id' => array(
                'ASC' => 'punto.ciudad_id ASC, punto.punto ASC',
                'DESC' => 'punto.ciudad_id DESC, punto.punto DESC'
            ),
            'fecha' => array(
                'ASC' => 'punto.registrado_at ASC, punto.punto ASC',
                'DESC' => 'punto.registrado_at DESC, punto.punto DESC'
            ),
            'transacciones' => array(
                'ASC' => 'transacciones ASC, punto.punto ASC',
                'DESC' => 'transacciones DESC, punto.punto DESC'
            ),
            'valor' => array(
                'ASC' => 'valor_transacciones ASC, punto.punto ASC',
                'DESC' => 'valor_transacciones DESC, punto.punto DESC'
            )
        ));
        $group = 'punto.id';
        if($page) {            
            return $this->paginated("columns: $columns", "join: $join", "conditions: $conditions", "group: $group", "order: $order", "page: $page");
        }
        return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "group: $group", "order: $order");
    }

    public static function setPunto($method, $data, $optData=null) {        
        $obj = new Punto($data); //Se carga los datos con los de las tablas        
        if($optData) { //Se carga información adicional al objeto
            $obj->dump_result_self($optData);
        }                               
        //Verifico que no exista otro menu, y si se encuentra inactivo lo active
        $conditions = empty($obj->id) ? Punto::condition_exists($obj) : Punto::condition_exists($obj) . " AND id != '$obj->id'";
        $old = new Punto();
        if($old->find_first($conditions)) {
            DwMessage::info('Ya existe un Punto registrado con ese Nombre.');
            return FALSE;
        }
        return ($obj->$method()) ? $obj : FALSE;
    }

    /**
     * Método para obtener la información de un dueño
     * @return type
     */
    public function getInformacionPunto($punto) {
        $punto = Filter::get($punto, 'int');
        if(!$punto) {
            return NULL;
        }
        $columnas = 'punto.*, duenio.razon_social as duenio, tipo_punto.tipo_punto, ciudad.ciudad';
        $join = 'INNER JOIN duenio ON duenio.id = punto.duenio_id ';
        $join .= 'INNER JOIN tipo_punto ON tipo_punto.id = punto.tipo_punto_id ';
        $join .= 'INNER JOIN ciudad ON ciudad.id = punto.ciudad_id ';
        $condicion = "punto.id = $punto";
        return $this->find_first("columns: $columnas", "join: $join", "conditions: $condicion");
    } 
     /**
     * Método para listar los usuarios por perfil
     */
    public function getPuntosPorDuenio($duenio, $order='order.nombre.asc', $page=0) {
        $duenio = Filter::get($duenio, 'int');
        if(empty($duenio)) {
            return NULL;
        }
        $columns = 'punto.*, duenio.razon_social, ciudad.ciudad, tipo_punto.tipo_punto';
        $join = 'INNER JOIN duenio ON duenio.id = punto.duenio_id ';
        $join .= 'INNER JOIN ciudad ON ciudad.id = punto.ciudad_id ';
        $join .= 'INNER JOIN tipo_punto ON tipo_punto.id = punto.tipo_punto_id ';
        $conditions = "punto.duenio_id = $duenio";
        
        $order = $this->get_order($order, 'punto', array(                        
            'tipo_punto' => array(
                'ASC'=>'punto.tipo_punto ASC, punto.punto ASC', 
                'DESC'=>'punto.tipo_punto DESC, punto.punto DESC'
            ),
            'direccion' => array(
                'ASC'=>'punto.direccion ASC, punto.punto ASC', 
                'DESC'=>'punto.direccion DESC, punto.punto DESC'
            ),
            'ciudad' => array(
                'ASC'=>'ciudad.ciudad ASC, punto.punto ASC', 
                'DESC'=>'ciudad.ciudad DESC, punto.punto DESC'
            )
        ));
        
        if($page) {
            return $this->paginated("columns: $columns", "join: $join", "conditions: $conditions", "order: $order", "page: $page");
        } 
        return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "order: $order");
    }

    /**
     * Callback que se ejecuta antes de guardar/modificar
     * @return string
     */
    public function before_save() {
        $conditions = Punto::condition_exists($this);
        $conditions.= (isset($this->id)) ? " AND id != $this->id" : '';
        if($this->count($conditions)) {
            DwMessage::error('Lo sentimos, pero ya existe un Punto de Atenci&oacute;n con la misma informaci&oacute;n.');
            return 'cancel';
        }
    }
}