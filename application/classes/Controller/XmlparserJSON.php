<?php defined('SYSPATH') or die('No direct script access.');

class Controller_XmlparserJSON extends Controller {

    public function action_index() {
        $postgre = Database::instance('postgre');
        setlocale(LC_NUMERIC, "C");
        for ($i=1;$i<2;$i++) {
            $streets = ORM::factory('Street')
                     ->where('id', '=', $i)
                     ->find()
                     ->as_array();
            $id_socr = $streets['id_socr'];
            $name = $streets['name'];
            $socr=DB::select('name')->from('socrs')->where('id', '=', $id_socr)->as_assoc()->execute();
            $full_socr=$socr[0]['name'];
            
            

	    $yandex = file('http://maps.yandex.ru/?page=index&text=%D0%A0%D0%BE%D1%81%D1%81%D0%B8%D1%8F+%D0%9F%D1%80%D0%B8%D0%BC%D0%BE%D1%80%D1%81%D0%BA%D0%B8%D0%B9+%D0%BA%D1%80%D0%B0%D0%B9+%D0%92%D0%BB%D0%B0%D0%B4%D0%B8%D0%B2%D0%BE%D1%81%D1%82%D0%BE%D0%BA+%D0%A3%D0%BB%D0%B8%D1%86%D0%B0+%D0%9A%D0%B0%D1%80%D0%BB%D0%B0+%D0%9B%D0%B8%D0%B1%D0%BA%D0%BD%D0%B5%D1%85%D1%82%D0%B0&vrb=1&key=y1f4d98a98f1a063721dd9c2a1d267503&output=json');
            //$yandex = file('http://maps.yandex.ru/?page=index&text=Россия+Приморский+край+Владивосток+' . urlencode($full_socr) . '+' . urlencode($name) . '&vrb=1&key=ybe3d161ebe4337ddf682c01a40369b43&output=json');
                      
            var_dump($yandex);
            
            
        }
    }
}