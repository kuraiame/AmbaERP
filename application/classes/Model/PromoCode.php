<?php defined('SYSPATH') or die('No direct script access.');

class Model_PromoCode extends ORM
{

        protected $_belongs_to = array(  // связь много к одному для сокращений в таблице street
            'action' => array(
                'model' => 'Action',
                'foreign_key' => 'id_action',
            ),
        );
}