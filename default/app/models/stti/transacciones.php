<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Clase que gestiona lo relacionado con los tipos de cuenta
 *
 * @category    Parámetros
 * @package     Models
 * @author      Iván D. Meléndez (ivan.melendez@dailyscript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co) 
 */

class Transacciones extends ActiveRecord {


    /**
     * Constante para definir un duenio como aprobado
     */
    const PAGADA = 1;
    
    /**
     * Constante para definir un duenio como rechazado
     */
    const PENDIENTE = 2;

    /**
     * Constante para definir un duenio como rechazado
     */
    const RECHAZADA = 3;


    /**
     * Método contructor
     */
    public function initialize() {
        $this->belongs_to('punto');
    }

        /**
     * Método para obtener el listado de los puntos en el sistema
     * @param type $estado
     * @param type $order
     * @param type $page
     * @return type
     */
    public function getListadoTransacciones($estado='todos', $order='', $page=0) {                   
        $columns = 'transacciones.*, punto.punto';        
        $join = 'INNER JOIN punto ON punto.id = transacciones.punto_id ';
        $join .= 'INNER JOIN duenio ON duenio.id = punto.duenio_id AND duenio.aprobado = ' . Duenio::APROBADO . ' ';
        $conditions = 'transacciones.id IS NOT NULL';     
        
        $order = $this->get_order($order, 'valor', array(            
            'estado' => array(
                'ASC' => 'transacciones.estado ASC, transacciones.valor ASC',
                'DESC' => 'transacciones.estado DESC, transacciones.valor DESC'
            ),
            'punto' => array(
                'ASC' => 'punto.punto ASC',
                'DESC' => 'punto.punto DESC'
            ),
            'fecha' => array(
                'ASC' => 'transacciones.registrado_at ASC',
                'DESC' => 'transacciones.registrado_at DESC'
            ),
            'fecha_modificacion' => array(
                'ASC' => 'transacciones.modificado_in ASC',
                'DESC' => 'transacciones.modificado_in DESC'
            )
        ));
        $group = 'transacciones.id';
        if($page) {            
            return $this->paginated("columns: $columns", "join: $join", "conditions: $conditions", "group: $group", "order: $order", "page: $page");
        }
        return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "group: $group", "order: $order");
    }

    public static function setTransaccion($method, $data, $optData=null) {        
        $obj = new Transacciones($data); //Se carga los datos con los de las tablas        
        if($optData) { //Se carga información adicional al objeto
            $obj->dump_result_self($optData);
        }                               
        return ($obj->$method()) ? $obj : FALSE;
    }

    /**
     * Método para listar las transacciones por punto
     */
    public function getTransaccionesPorPuntos($punto, $order='order.nombre.asc', $page=0) {
        $punto = Filter::get($punto, 'int');
        if(empty($punto)) {
            return NULL;
        }
        $columns = 'transacciones.*, punto.punto';
        $join = 'INNER JOIN punto ON punto.id = transacciones.punto_id ';
        $conditions = "transacciones.punto_id = $punto";
        
        $order = $this->get_order($order, 'valor', array(                        
            'valor' => array(
                'ASC'=>'transacciones.valor ASC, punto.punto ASC', 
                'DESC'=>'transacciones.valor DESC, punto.punto DESC'
            )
        ));
        
        if($page) {
            return $this->paginated("columns: $columns", "join: $join", "conditions: $conditions", "order: $order", "page: $page");
        } 
        return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "order: $order");
    }

    
    /**
     * Método para obtener la información de la transaccion
     * @return type
     */
    public function getInformacionTransaccion($transaccion) {
        $transaccion = Filter::get($transaccion, 'int');
        if(!$transaccion) {
            return NULL;
        }
        $columnas = 'transacciones.*, punto.punto';
        $join = 'INNER JOIN punto ON punto.id = transacciones.punto_id ';
        $condicion = "transacciones.id = $transaccion";
        return $this->find_first("columns: $columnas", "join: $join", "conditions: $condicion");
    } 

}
?>