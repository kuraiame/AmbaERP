<?php defined('SYSPATH') or die('No direct script access.');

class Service extends Kohana
{
    /**
     * функция расчета стоимости
     * 
     * @param массив идентификаторов $id
     * @param массив колисчества элементов $count_item 
     */
    static public function calc_cost($id, $count_item, $actions_list)
    {
        $summa = 0;
        $pizza_count = 0;
        $sous_count = 0;
        $category = array('pizza'=>array(), 'sous'=>array(), 'napitok'=>array());
        
        for($i=0;$i<count($id);$i++)
        {
            $menu = ORM::factory('Menu')->where('id', '=', $id[$i])->find();
            $count = (double)$count_item[$i];
            $summa += (double)$menu->price * $count;
            
            for($j=0;$j<$count_item[$i];$j++)
                $category[$menu->category][] = (double)$menu->price;

        }
        
        $sale = 0;
        // вычитание бесплатных соусов
        if(count($category['sous']) > 0)
        {
            if(count($category['pizza']) > count($category['sous']))
                $sale += (double)count($category['sous']) * (double)$category['sous'][0];
            else
                $sale += (double)count($category['pizza']) * (double)$category['sous'][0];
        }
        
        // вычисление скидок по акциям
        for($i=0;$i<count($actions_list);$i++)
        {
            $action = ORM::factory('Action', $actions_list[$i]);
            
            if($action->loaded() == false)
                continue;
            
            if($action->state == 'disable')
                continue;
            
            $percent = (double)$action->percent / 100.0;
            $options = Service::parce_option_action($action->options);
            
//            if (count($category['pizza']) > 0)
//            {
//                if($options['category'] == 'pizza')
//                {
//                    if(count($category['pizza']) >= $options['count'])
//                        $sale += (double)$category['pizza'][0] * $percent * (double)$options['count'];
//                }
//            }
            
            if(count($category[$options['category']]) > 0)
            {
                $count = $options['count'];
                if(count($category[$options['category']]) < $options['count'])
                    $count = count($category[$options['category']]);
                
                $sale += (double)$category[$options['category']][0] * $percent * (double)$count;
            }
        }

        $result['summa_without_sale'] = $summa;
        $result['sale'] = $sale;
        $result['summa_with_sale'] = $summa - $sale;
        return $result;
    }
    
    /**
     * функция пересчета статуса заказа
     * 
     * @param идентификатор заказа $id
     */
    static public function recount_state_zakaz($id)
    {
        $zakaz = ORM::factory("Zakaz", $id);
        if($zakaz->loaded() == false)
            return;
        
        $zakaz_pos = ORM::factory("ZakazPosition")
                ->where('id_zakaz', '=', $id)
                ->find_all();
        
        $flag_process = false;
        $flag_done = false;
        foreach ($zakaz_pos as $item)
        {
            if($item->state == 'done')
            {
                $flag_done = true;
                break;
            }
            
            if($item->state == 'not_done' || $item->state == 'in_process')
                $flag_process = true;
        }
        
        if($flag_done)
            $state = 'in_process';
        else
            if($flag_process)
                $state = 'not_start';
            else
                $state = 'collected';

            // печать чека
        if($state == 'collected')
            Service::print_check ($id);
            
        $zakaz->state = $state;
        $zakaz->save();
    }
    
    // печать чека 
    static public function print_check($id_zakaz)
    {
        $orm_zakaz = ORM::factory('Zakaz', $id_zakaz);
        
        if($orm_zakaz->loaded() == false)
            return false;
        
        $zakaz_pos = ORM::factory('ZakazPosition')->get_list_item($id_zakaz);

        $result = array();
        foreach ($zakaz_pos as $zakaz)
        {
            $count = ORM::factory('ZakazPosition')
                    ->where('id_zakaz', '=', $id_zakaz)
                    ->and_where('id_menu', '=', $zakaz['id_menu'])
                    ->count_all();
            $menu_item = ORM::factory('Menu', $zakaz['id_menu']);
            $result[] = array(
                'name' => $menu_item->name.' '.$menu_item->size,
                'category' => $menu_item->category,
                'count' => $count,
                'price' => $menu_item->price,
                'summa' => $count * $menu_item->price
                );
        }

        KohanaPDF::createCheck($result, $orm_zakaz);
        return true;
    }
    
    static public function get_street($id_zakaz)
    {
        $zakaz = ORM::factory('Zakaz', $id_zakaz);
        if($zakaz->loaded() == false)
            return '';
                
        if($zakaz->klient->id_house == 0)
            return 'Самовывоз';
       
        return $zakaz->klient->house->street->socr->name.' '.$zakaz->klient->house->street->name . 'д. '.$zakaz->klient->house->name;
    }
    
    static public function get_house($id_zakaz)
    {
        $zakaz = ORM::factory('Zakaz', $id_zakaz);
        if($zakaz->loaded() == false)
            return '';
                
        if($zakaz->klient->id_house == 0)
            return '';
       
        $flat = "";
        if($zakaz->klient->flat > 0)
            $flat = ', кв '.$zakaz->klient->flat;
        
        $podiezd = $zakaz->klient->podiezd;
        if($podiezd != 0)
            $podiezd = ', подъезд '.$podiezd;
        else
            $podiezd = '';
        
        $floor = $zakaz->klient->floor;
        if($floor != 0)
            $floor = ', этаж '.$floor;
        else
            $floor = '';
        
        
        return $flat . $podiezd . $floor;
    }
    
    static public function get_full_adress($id_zakaz)
    {
        $zakaz = ORM::factory('Zakaz', $id_zakaz);
        if($zakaz->loaded() == false)
            return '';
        $adress;
        
        if($zakaz->klient->id_house == 0)
            return 'Самовывоз';
       
        $flat = "";
        if($zakaz->klient->flat > 0)
            $flat = ', кв '.$zakaz->klient->flat;
        
        $podiezd = $zakaz->klient->podiezd;
        if($podiezd != 0)
            $podiezd = ', подъезд '.$podiezd;
        else
            $podiezd = '';
        
        $floor = $zakaz->klient->floor;
        if($floor != 0)
            $floor = ', этаж '.$floor;
        else
            $floor = '';
        
        
        return $zakaz->klient->house->street->socr->name.' '.$zakaz->klient->house->street->name.', д. '.$zakaz->klient->house->name . $flat . $podiezd . $floor;
    }
    
    static public function get_text_klient_name($zakaz)
    {
        $name = $zakaz->klient->name;
        if($name != '')
            return '<br> <b>Имя:</b> '.$name;
        return '';
    }
    
    static public function get_text_klient_tel($zakaz)
    {
        $tel = $zakaz->klient->tel;
        if($tel != '')
            return '<br> <b>Тел:</b> '.$tel;
        return '';
    }
    
    static public function get_text_klient_sdacha($zakaz)
    {
        $sdacha = (int) $zakaz->sdacha - (int) $zakaz->summa;
        if($sdacha >= 0)
            return '<br> <b>Сдача :</b> '.$sdacha.' р.';
        return '';
    }    
    
    static public function get_text_klient_summ($zakaz)
    {
        $summa = $zakaz->summa;
        if($summa != 0)
            return '<br> <br> <b>ИТОГО :</b> '.$summa.' р.';
        return '';
    }

    static public function get_text_klient_time($zakaz)
    {
            $time = date('H:i', strtotime($zakaz->date_dostavka)); //date('H:i', $zakaz->date_dostavka);
            return '<br> <b>Время доставки: </b>'.$time;
            return '';
    }
	
    static public function get_text_klient_koment($zakaz)
    {
        $koment = $zakaz->koment;
        if($koment != '')
            return '<br> <b>Комент:</b> '.$koment;
        return '';
    }
    
    static public function get_text_klient_floor($zakaz)
    {
        $floor = $zakaz->klient->floor;
        if($floor != 0)
            return '<br> <b>Этаж:</b> '.$floor;
        return '';
    }

    static public function get_data($data_text)
    {
        $date_elements  = explode(" ",$data_text);
        $data = explode("-", $date_elements[0]);
        $time = explode(":",$date_elements[1]);
        return $time[0].':'.$time[1].', '.$data[2].'.'.$data[1].'.'.$data[0];
    }
    
    static public function generate($id_action,$state,$count,$type) 
    {
         $ready=DB::query(Database::SELECT, 'SELECT * FROM `readypromos` ORDER BY RAND() LIMIT '. $count)->execute()->as_array();
         foreach ($ready as $code)
         {
             $promo = ORM::factory('PromoCode');
             $promo->values(array(
                 'name' => $code['name'],
                 'id_action' => $id_action,
                 'state' => $state,
                 'type' => $type,
             ));
             DB::query(Database::DELETE, 'DELETE FROM `readypromos` WHERE `id` = '. $code['id'])->execute();
             $promo->save();
         }
             
    }
   /**
    * Выводит номер заказа и записывает в таблицу +1 к последнему номеру.
    * Гриша сделал костыль!!!
    * !!!НЕ ИСПОЛЬЗОВАТЬ ДВАЖДЫ В ОДНОМ МЕТОДЕ!!!
    */
    static public function get_nomer_zakaz()
    {
        $nomer = ORM::factory('Service', 1);
        $res = $nomer->nomer;
        $nomer->nomer = $nomer->nomer + 1;
        $nomer->save();
        return $res;
    }
    
    /**
     * Просто возвращает номер текущего заказа
     * 
     * @return integer
     */
    static public function get_nomer_zakaz_without_saving()
    {
        $nomer = ORM::factory('Service', 1);
        $res = $nomer->nomer;
        return $res;
    }
    
    // изменение статуса позиции заказа
    static public function switch_status_zakaz_position($id, $state)
    {
        $zakaz_pos = ORM::factory("ZakazPosition", $id);
        if($zakaz_pos->loaded())
        {
            $zakaz_pos->state = $state;
            if($zakaz_pos->save())
            {
                Service::recount_state_zakaz($zakaz_pos->id_zakaz);
                return true;
            }
        }
        return false;
    }

    // распарсить строку с опциями акции
    static public function parce_option_action($text)
    {
        $tmp = explode('&', $text);
        $result = array();
        for($i=0;$i<count($tmp);$i++)
        {
            $par = explode('=', $tmp[$i]);
            $result[$par[0]] = $par[1];
        }
        return $result;
    }
    
    // установить состояние промо кода
    static public function set_promo_state($id, $state, $id_zakaz)
    {
        $promo = ORM::factory("PromoCode", $id);
        if($promo->loaded())
        {
            $promo->state = $state;
            
            if ($state == 'activated')
                $promo->id_order_activated = ($id_zakaz)? $id_zakaz : '123';
            
            $promo->save();
        }
    }
    
    // перевод даты в число (yyyy-mm-dd hh:mm:ss -> int)
    static public function date_str_to_int($date)
    {
        if($date == "")
            return 0;
        $tmp = explode(" ", $date);
        if(count($tmp) != 2)
            return 0;
        $tmp_date = explode("-", $tmp[0]);
        $tmp_time = explode(":", $tmp[1]);
        return mktime($tmp_time[0], $tmp_time[1], $tmp_time[2], $tmp_date[2], $tmp_date[1], $tmp_date[0]);
    }
    
    // возврат часов и минут из количества секунд (int -> hh:mm)
    static public function time_int_to_str($time)
    {
        $h = (int)($time / 3600);
        $m = (int)($time / 60) % 60;
        return $h.':'.$m;
    }
    
    // возвращает разницу между датами (даты формата YYYY-mm-dd hh:mm:ss)
    static public function diff_date($date_1, $date_2)
    {
        $tmp = Service::date_str_to_int($date_1) - Service::date_str_to_int($date_2);
        return $tmp;
        return date('H:i', $tmp);
    }
}

?>
