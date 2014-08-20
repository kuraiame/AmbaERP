<?php defined('SYSPATH') or die('No direct script access.');

class Model_Klient extends ORM
{
       function rules()
    {
        return array(
            'id_house' => array(
                array('not_empty'),
            ),
            'tel' => array(
                array('not_empty'),
            ),
            'podiezd' => array(
                array('not_empty'),
            ),
            'flat' => array(
                array('not_empty'),
            ),
        );
    }
 
        protected $_belongs_to = array(  // связь много к одному для сокращений в таблице street
            'house' => array(
                'model' => 'House',
                'foreign_key' => 'id_house',
            ),
    );
}