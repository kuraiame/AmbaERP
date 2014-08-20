<?php defined('SYSPATH') or die('No direct script access.');

class Admin extends Kohana
{
    //вывод всех курьеров
    public static function courier_view() {
        $view=DB::query(Database::SELECT, 'SELECT name, status FROM `couriers`')->execute()->as_array();
        return $view;
    }
    
    //добавление курьера
    public static function courier_add($name) {
        DB::query(Database::INSERT, 'INSERT INTO couriers (name,status) VALUES (\'' . $name . '\',active)')->execute();
    }
    
    //смена статуса курьера (блокирован,разблокирован)
    public static function courier_update($name, $status) {
        DB::query(Database::UPDATE, 'UPDATE couriers SET status=\'' . $status . '\' 
                                        WHERE name=\'' . $name . '\'')->execute();
    }
    
    
    static public function promo_generate($percent,$options,$count,$type) 
    {
         $ready=DB::query(Database::SELECT, 'SELECT * FROM `readypromos` ORDER BY RAND() LIMIT '. $count)->execute()->as_array();
         foreach ($ready as $code)
         {
             $promo = ORM::factory('PromoCode');
             $promo->values(array(
                 'name' => $code['name'],
                 'percent' => $percent,
                 'options' => $options,
                 'type' => $type,
             ));
             DB::query(Database::DELETE, 'DELETE FROM `readypromos` WHERE `id` = '. $code['id'])->execute();
             $promo->save();
         }
             
    }
    
    static public function promo_view() 
    {
         $view=DB::query(Database::SELECT, 'SELECT * FROM `readypromos` ORDER BY RAND() LIMIT '. $count)->execute()->as_array();
         return $view;
             
    }
}