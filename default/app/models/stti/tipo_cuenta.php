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

class TipoCuenta extends ActiveRecord {

    /**
     * Método contructor
     */
    public function initialize() {
        $this->has_many('duenio');
    }

    /**
     * Método para listar los tipos de cuenta
     * @return array
     */
    public function getListadoTipoCuenta() {
        return $this->find('order: tipo_cuenta ASC');
    }

}
?>