<?php
/**
 * Dailyscript - Web | App | Media
 *
 * Clase que gestiona lo relacionado con los tipos de identificacion
 *
 * @category    Parámetros
 * @package     Models
 * @author      Iván D. Meléndez (ivan.melendez@dailyscript.com.co)
 * @copyright   Copyright (c) 2013 Dailyscript Team (http://www.dailyscript.com.co) 
 */

class TipoPunto extends ActiveRecord {

    /**
     * Método contructor
     */
    public function initialize() {
        $this->has_many('punto');
    }

    /**
     * Método para listar los tipos de identificación
     * @return array
     */
    public function getListadoTipoPuntos() {
        return $this->find('order: tipo_punto ASC');
    }

}
?>