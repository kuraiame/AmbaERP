<?php defined('SYSPATH') or die('No direct script access.');

class Generate extends Kohana
{
    public static function generate_code() 
    {
        $letters = array(
            'А','Б','К','С','Е','Л','Т','Я','Ю','Г','Ж','И','П','У',
        );
       
        
        for ($i=0;$i<(count($letters)-1);$i++) {
            for ($j=0;$j<(count($letters)-1);$j++) {
                for ($k=0;$k<10;$k++) {
                    for ($l=0;$l<10;$l++) {
                        for ($m=0;$m<10;$m++) {
                            $promo = ORM::factory('ReadyPromo');
                            $promo->values(array(
                                'name' => $letters[$i] . $letters[$j] . $k . $l . $m,
                            ));
                            $promo->save();
                        }
                    }
                }
            }
        }    
        
   }
}