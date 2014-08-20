<?php defined('SYSPATH') or die('No direct script access.');

class Model_Zakaz extends ORM
{
    function rules()
    {
        return array(
            'id_klient' => array(
                array('not_empty'),
            ),
        );
    }
    
    protected $_belongs_to = array(
        'klient' => array(
            'model' => 'Klient',
            'foreign_key' => 'id_klient',
        ),
        'courier' => array(
            'model' => 'Courier',
            'foreign_key' => 'id_courier'
        ),
        'promo' => array(
            'model' => 'PromoCode',
            'foreign_key' => 'id_promo'
        ),
    );
    
    protected $_has_many = array(
        'zakaz_position' => array(
            'model' => 'ZakazPosition',
            'foreign_key' => 'id_zakaz',
        ),
    );

}