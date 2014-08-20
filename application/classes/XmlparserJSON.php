<?php defined('SYSPATH') or die('No direct script access.');

class XmlparserJSON extends Kohana
{
    public function parse() {
        $postgre = Database::instance('postgre');
        setlocale(LC_NUMERIC, "C");
        for ($i=1;$i<2;$i++) {
            $houses = ORM::factory('House')
                     ->where('id', '=', $i)
                     ->find()
                     ->as_array();
            var_dump($houses);
        }
    }
}