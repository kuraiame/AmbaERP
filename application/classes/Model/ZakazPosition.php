<?php defined('SYSPATH') or die('No direct script access.');

class Model_ZakazPosition extends ORM
{
//    protected $_table_name = 'zakaz_positions';
    
    function rules()
    {
        return array(
            'id_zakaz' => array(
                array('not_empty'),
            ),
            'id_menu' => array(
                array('not_empty'),
            ),
        );
    }
    
    protected $_belongs_to = array(  // связь много к одному для сокращений в таблице street
            'menu' => array(
                'model' => 'Menu',
                'foreign_key' => 'id_menu',
            ),
    );

    
    // получить список элементов заказа.. без повторений
    public function get_list_item($id_zakaz)
    {
        return DB::select("id_menu")
             ->distinct(true)
             ->from($this->_table_name)
             ->where('id_zakaz','=',$id_zakaz)
             ->execute();;
    }
    
}