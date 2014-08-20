<?php defined('SYSPATH') or die('No direct script access.');

class Model_House extends ORM
{
        protected $_belongs_to = array(  // связь много к одному для сокращений в таблице street
            'street' => array(
                'model' => 'Street',
                'foreign_key' => 'id_street',
            ),
    );
}