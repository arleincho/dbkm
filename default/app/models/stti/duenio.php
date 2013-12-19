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

class Duenio extends ActiveRecord {
    
    /**
     * Constante para definir un duenio como aprobado
     */
    const APROBADO = 1;
    
    /**
     * Constante para definir un duenio como rechazado
     */
    const RECHAZADO = 2;
    
    /**
     * Método para definir las relaciones y validaciones
     */
    protected function initialize() {
        $this->has_many('punto');
        $this->belongs_to('banco');
        $this->belongs_to('tipo_cuenta');
    }
    
    /**
     * Método para obtener el listado de los duenios de puntos en el sistema
     * @param type $estado
     * @param type $order
     * @param type $page
     * @return type
     */
    public function getListadoDuenios($estado='todos', $order='', $page=0) {
        $columns = 'duenio.*, COUNT(punto.id) AS puntos';        
        $join = 'LEFT JOIN punto ON duenio.id = punto.duenio_id ';
        $conditions = 'duenio.id IS NOT NULL';
        if($estado!='todos') {
            $conditions.= ($estado == self::APROBADO) ? " AND aprobado=".self::APROBADO : " AND aprobado=".self::RECHAZADO;
        }
        
        $order = $this->get_order($order, 'razon_social', array(            
            'puntos' => array(
                'ASC' => 'puntos ASC, duenio.razon_social ASC',
                'DESC' => 'puntos DESC, duenio.razon_social DESC'
            ),
            'aprobado' => array(
                'ASC' => 'duenio.aprobado ASC, duenio.razon_social ASC',
                'DESC' => 'duenio.aprobado DESC, duenio.razon_social DESC'
            ),
            'identificacion' => array(
                'ASC' => 'duenio.identificacion ASC, duenio.razon_social ASC',
                'DESC' => 'duenio.identificacion DESC, duenio.razon_social DESC'
            )
        ));
        $group = 'duenio.id';
        if($page) {            
            return $this->paginated("columns: $columns", "join: $join", "conditions: $conditions", "group: $group", "order: $order", "page: $page");
        }
        return $this->find("columns: $columns", "join: $join", "conditions: $conditions", "group: $group", "order: $order");
    }

    public static function setDuenio($method, $data, $optData=null) {        
        $obj = new Duenio($data); //Se carga los datos con los de las tablas        
        if($optData) { //Se carga información adicional al objeto
            $obj->dump_result_self($optData);
        }                               
        //Verifico que no exista otro menu, y si se encuentra inactivo lo active
        $conditions = empty($obj->id) ? "razon_social = '$obj->razon_social'" : "razon_social = '$obj->razon_social' AND id != '$obj->id'";
        $old = new Duenio();
        if($old->find_first($conditions)) {            
            if($method=='create' && $old->aprobado != Duenio::APROBADO) {
                $obj->id = $old->id;
                $obj->aprobado = Duenio::APROBADO;
                $method = 'update';
            } else {
                DwMessage::info('Ya existe un Dueño registrado con ese Nombre.');
                return FALSE;
            }
        }
        return ($obj->$method()) ? $obj : FALSE;
    }

    /**
     * Método para obtener la información de un dueño
     * @return type
     */
    public function getInformacionDuenio($duenio) {
        $duenio = Filter::get($duenio, 'int');
        if(!$duenio) {
            return NULL;
        }
        $columnas = 'duenio.*, banco.nombre as banco, tipo_cuenta.tipo_cuenta';
        $join = 'INNER JOIN banco ON banco.id = duenio.banco_id ';
        $join .= 'INNER JOIN tipo_cuenta ON tipo_cuenta.id = duenio.tipo_cuenta_id ';
        $condicion = "duenio.id = $duenio";
        return $this->find_first("columns: $columnas", "join: $join", "conditions: $condicion");
    } 

    /**
     * Callback que se ejecuta antes de guardar/modificar
     */
    protected function before_save() {
        $this->tipo_cuenta_id = Filter::get($this->tipo_cuenta_id, 'int');
        $this->banco_id = Filter::get($this->banco_id, 'int');
        $this->numero_cuenta = Filter::get($this->numero_cuenta, 'int');
        //Verifico si el Dueño está disponible
        if($this->_getRegisteredField('razon_social', $this->razon_social, $this->id)) {
            DwMessage::error('El nombre del Dueño ingresado ya fue registrado.');
            return 'cancel';
        }
        //Verifico si ya se encuentra registrado
        if($this->_getRegisteredField('identificacion', $this->identificacion, $this->id)) {
            DwMessage::error('La n&uacute;mero de identificaci&oacute;n ingresado ya fue registrado.');
            return 'cancel';
        }        
    }
}