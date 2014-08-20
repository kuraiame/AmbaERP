<?php defined('SYSPATH') or die('No direct script access.');

class Model_Street extends ORM
{
    protected $_belongs_to = array(  // связь много к одному для сокращений в таблице street
		'socr' => array(
                    'model' => 'Socr',
                    'foreign_key' => 'id_socr',
                ),
       );
}