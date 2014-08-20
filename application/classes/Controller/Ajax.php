<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller {

    public function before() 
    {
        //if (!$this->request->is_ajax())
        //    die('Must to be authenticate and request must be Ajax');
    }
    
    public function action_zakaz_add_zakaz()
    {
            if(!Auth::instance()->logged_in("devochka"))
                echo 0;
            
            if($_POST)
            {
                $id_house = addslashes(Arr::get($_POST, 'house', 0));
                if(Arr::get($_POST, 'pickup', false))
                    $id_house = 0;
                
                // добавляем нового клиента
                $klient = ORM::factory('Klient');
                $klient->values(array(
                    'id_house' => $id_house,
                    'name'  => addslashes(Arr::get($_POST, 'contact', '')),
                    'tel' => addslashes(Arr::get($_POST, 'phone', '')),
                    'podiezd' => addslashes(Arr::get($_POST, 'podiezd', 0)),
                    'flat' => addslashes(Arr::get($_POST, 'flat', 0)),
                    'floor' => addslashes(Arr::get($_POST, 'floor', 0)),
                ));
                if($klient->check() == false)
                {
                    echo 0;
                    return;
                }
                $klient->save();

                // добавляем новый заказ
                if(Arr::get($_POST, "near_time", false))
                    $date_dostavka = date('Y-m-d H:i:s', time()+3600);
                else
                {
                    $d = mktime((int)Arr::get ($_POST, 'time_h', 0), (int)Arr::get ($_POST, 'time_min', 0), 0, date('m'), date('d'), date('Y'));
                    if($d < time()+3600)
                    {
                        echo 0;
                        return;
                    }
                    $date_dostavka = date('Y-m-d H:i:s', $d);
//                    $date_dostavka = date('Y-m-d ', time()) . date('H:i:s', mktime ((int)Arr::get ($_POST, 'time_h', 0), (int)Arr::get ($_POST, 'time_min', 0)));
                    
                }
                
                $sdacha = 0;
                if(Arr::get($_POST, 'no_sdacha', false) == false)
                        $sdacha = addslashes(Arr::get($_POST, 'sdacha'));
                
                // расчет промо
                $promo_id = 0;
                $promo_obj = ORM::factory('PromoCode')
                        ->where('name', '=', strtoupper(addslashes(Arr::get($_POST, 'promo'))))
                        ->and_where('state', '=', 'activated')
                        ->find();
                $actions = array();
                if($promo_obj->loaded())
                {
                    $actions[] = $promo_obj->id_action;
                    $promo_id = $promo_obj->id;
                    
                    if($promo_obj->type == 'one')
                    {
                        $promo_obj->state = 'closed';
                        $promo_obj->save();
                    } 
                }
                
                $summa = Service::calc_cost(Arr::get($_POST, 'id'), Arr::get($_POST, 'count'), $actions);
                if($sdacha > 0)
                    if($summa['summa_with_sale'] > $sdacha)
                    {
                        echo 0;
                        return;
                    }
                
                $zakaz = ORM::factory('Zakaz');
                $zakaz->values(array(
                    'nomer' => Service::get_nomer_zakaz(),
                    'id_klient' => $klient->id,
                    'id_promo'  => $promo_id,
                    'summa' => $summa['summa_with_sale'],
                    'sale'  => $summa['sale'],
                    'sdacha' => $sdacha,
                    'state' => 'not_start',
                    'koment' => addslashes(Arr::get($_POST, 'coment_all')),
                    'date_dostavka' => $date_dostavka,
                    'date_fact_dost' => 'NULL',
                ));
                if($zakaz->check() == false)
                {
                    echo 0;
                    return;
                }
                $zakaz->save();
                
                // добавляем пункты заказа
                for($i=0;$i<count(Arr::get($_POST, 'id'));$i++)
                {
                    for($j=0;$j<Arr::get($_POST['count'], $i);$j++)
                    {
                        $zakaz_position = ORM::factory('ZakazPosition');
                        $zakaz_position->values(array(
                            'id_zakaz' => $zakaz->id,
                            'id_menu' => addslashes(Arr::get($_POST['id'], $i)),
                            'state' => 'done',
                            'koment' => addslashes(Arr::get($_POST['coment'],$i)),
                            'date_dostavka' => $date_dostavka,
                        ));
                        if($zakaz_position->menu->category == 'pizza')
                        {
                            $zakaz_position->state = 'not_done';
                        }
                        if ($zakaz_position->check())
                            $zakaz_position->save();
                        
                    }
                }
                Service::recount_state_zakaz($zakaz->id);
                echo 1;
            }

    }
    
    /**
     * Выводит список заказов для интерфейса "Заказы"
     */
    public function action_courier_get_zakaz()
    {
                $zakazes_collected = ORM::factory("Zakaz")
                    ->where('state', '=', 'collected')
                    ->order_by('date_dostavka')
                    ->find_all();
                $arr=array();
            foreach ($zakazes_collected as $value)
            {
                $arr[] = array( 'no'    => $value->id,
                                'addr'    => $value->klient->house->street->socr->socr_name . '. ' . $value->klient->house->street->name . ' ' . $value->klient->house->name . ', кв.' . $value->klient->flat . ', п.' . $value->klient->podiezd . ', эт.' . $value->klient->floor,
                                'time'    => $value->date_dostavka,
                                'phone'   => $value->klient->tel,
                                'contact' => $value->klient->name,
                                'summ'    => $value->summa,
                                'comment' => $value->koment,
                                'sdacha'  => $value->sdacha,
                                'state'   => $value->state,
                                'cur'     => $value->id_courier
                        );
            }
            var_dump($arr);
                $zakazes_in_process = ORM::factory("Zakaz")
                    ->where('state', 'IN', array('in_process','not_start'))
                    ->order_by('date_dostavka')
                    ->find_all();

                $zakazes_end = ORM::factory("Zakaz")
                    ->where('state', '=', 'success')
                    ->or_where('state', '=', 'unsuccess')
                    ->or_where('state', '=', 'cansel')
                    ->order_by('date_fact_dost', 'DESC')
                    ->limit(5)
                    ->find_all();
           
    }

    public function action_courier_change_state()
    {
        $id = Arr::get($_POST, 'id', 0);
        if($id > 0)
        {
            $zakaz = ORM::factory("Zakaz", $id);
            switch (Arr::get($_POST, 'state', 'not_done'))
            {
                case 'success':     $zakaz->state = "success";      break;
                case 'unsuccess':   $zakaz->state = "unsuccess";    break;
                case 'cansel':      $zakaz->state = "cansel";       break;
            }

            $zakaz->date_fact_dost = date('Y-m-d H:i:s', time());
            $zakaz->save();
        }
    }
        
    public function  action_view_zakaz()
    {
        $mainView = View::factory('view_zakaz'); 
        $zakaz = ORM::factory("Zakaz", Arr::get($_REQUEST, "id_zakaz", 0));
        if($zakaz->loaded())
        {
            $mainView->zakaz = $zakaz;
            echo $mainView->render();
        }
        else
            echo "ERROR";
    }
    
    public function action_courier_change_courier()
    {
        $zakaz = ORM::factory('Zakaz', Arr::get($_POST, 'id_zakaz', 0));
        if($zakaz->loaded())
        {
            if (!$zakaz->id_courier)
            {
                $text = "Заказ уже на пути к тебе. Номер: ".$zakaz->id.". Отследить: http://ambapizza.ru/spy";
                Smspilot::factory()->send($zakaz->klient->tel, $text);
            }
            
            $zakaz->id_courier = Arr::get($_POST, 'id_courier', 0);
			//$zakaz->data_assign_courier = date('Y-m-d H:i:s', time());
            $zakaz->save();
            echo "1";
        }
        else
            echo "0";
    }

    public function action_get_courier()
    {
        $couriers = ORM::factory('Courier')
                ->where_open()
                ->where('name', 'LIKE', '%'. Arr::get($_REQUEST, 'text').'%')
                ->or_where('id', 'LIKE', Arr::get($_REQUEST, 'text').'%')
                ->where_close()
                ->and_where('status', '=', 'active')
                ->find_all();
        $result = array();
        foreach ($couriers as $courier)
            $result[] = array(
                'id' => $courier->id,
                'name' => $courier->name,
            );
        echo json_encode($result);
    }
    
    public function action_get_adress()
    {
        $streets = ORM::factory('Street')
                     ->where('name', 'LIKE', '%' . Arr::get($_REQUEST, 'name') . '%')
                     ->limit(8)
                     ->order_by('name')
                     ->find_all();
        $result = array();
        foreach ($streets as $street)
            $result[] = array( 'id' => $street->id,
                                'name' => mb_strtolower($street->socr->name,'UTF-8') . ' ' . $street->name
                              );
        
        echo json_encode($result);
    }
    
    public function action_get_house()
    {
        $house = ORM::factory('House');
		$house->where_open()->where('name', 'LIKE ', Arr::get($_REQUEST, 'name').'%')->where('id_street', '=', Arr::get($_REQUEST, 'street'))->where_close()->find_all()->as_array();

        //$houses = $postgre->query(Database::SELECT, 'SELECT * FROM pghouses WHERE (("name" LIKE \''. Arr::get($_REQUEST, "name").'%\') AND ("id_street" =  \''.Arr::get($_REQUEST, "street").'\')) ORDER BY ("name") LIMIT(7);');
        echo json_encode($house);
    }
    
    public function action_check_area()
    {
        /*$postgre = Database::instance('postgre');
        $houses = $postgre->query(Database::SELECT, 'SELECT areas.id FROM pghouses, areas WHERE ((pghouses.id = \''.Arr::get($_REQUEST, "id_house").'\') AND (ST_WITHIN(pghouses.coord, areas.coord)=\'t\'));');
        $q = $houses->as_array();
       if (count($q) > 0)
            echo $q[0]['id'];
        else
            echo "0";*/
			echo '1';
    }
    
    // получить список позиций заказов
    public function action_povar_get_zakaz_position()
    {
        $zakaz_pos = ORM::factory("ZakazPosition")
                ->where_open()
                ->where('state', '=', 'not_done')
                ->or_where('state', '=', 'in_process')
                ->where_close()
                ->and_where('date_dostavka', '<', date('Y-m-d H:i:s', time()+3600))
//                ->or_where('state', '=', 'done')
                ->order_by('date_dostavka', 'ASC')
                ->limit(9)
                ->find_all();
        $result = array();
        $i = 0;
        foreach ($zakaz_pos as $zakaz)
        {
            $date_elements  = explode(" ",$zakaz->date_dostavka);
            $time = explode(":",$date_elements[1]);
            $result[$i++] = array(
                'id' => $zakaz->id,
                'time' => $time[0].":".$time[1],
                'item_menu' => $zakaz->menu->name,
                'size' => $zakaz->menu->size,
                'state' => $zakaz->state,
                'koment' => $zakaz->koment,
            );
        }
        $res = array();
        for($i=1;$i<=count($result);$i++)
            $res[] = $result[count($result)-$i];
        $zakaz_pos_count = ORM::factory("ZakazPosition")
                ->where('state', '=', 'not_done')
                ->or_where('state', '=', 'in_process')
                ->count_all();
        $res['count'] = $zakaz_pos_count - count($result);
        echo json_encode($res);
    }
    
    public function action_switch_status_zakaz_position()
    {
        $zakaz_pos = ORM::factory("ZakazPosition")
                ->where('id', '=', Arr::get($_REQUEST, 'id', 0))
                ->find();
        if($zakaz_pos)
        {
            $zakaz_pos->state = Arr::get($_REQUEST, 'state', 0);
            if($zakaz_pos->save())
            {
                echo 1;
                Service::recount_state_zakaz($zakaz_pos->id_zakaz);
            }
            else
                echo 0;
        }
        else
            echo 0;
    }
    
    // начал готовить
    public function action_povar_begin_cook()
    {
        $id = Arr::get($_REQUEST, 'id', 0);
        $zakaz_pos = ORM::factory("ZakazPosition", $id);
        if($zakaz_pos->loaded())
        {
            $zakaz_pos->date_begin_cook = date('Y-m-d H:i:s', time());
            $zakaz_pos->save();
            if(Service::switch_status_zakaz_position($id, 'in_process'))
                echo 1;
            else
                echo 0;
        }
        else
            echo 0;
    }
    
    // закончил готовить
    public function action_povar_end_cook()
    {
        $id = Arr::get($_REQUEST, 'id', 0);
        $zakaz_pos = ORM::factory("ZakazPosition", $id);
        if($zakaz_pos->loaded())
        {
            $zakaz_pos->date_end_cook = date('Y-m-d H:i:s', time());
            $zakaz_pos->save();
            if(Service::switch_status_zakaz_position($id, 'done'))
                echo 1;
            else
                echo 0;
        }
        else
            echo 0;
    }


    // закончил сборку
    public function action_sborka_end_sborka()
    {
        $id = Arr::get($_REQUEST, 'id', 0);
        $zakaz_pos = ORM::factory("ZakazPosition", $id);
        if($zakaz_pos->loaded())
        {
            $zakaz_pos->date_end_sborka = date('Y-m-d H:i:s', time());
            $zakaz_pos->save();
            if(Service::switch_status_zakaz_position($id, 'collected'))
                echo 1;
            else
                echo 0;
        }
        else
            echo 0;
    }
    
    // получить список заказов
    public function action_sborka_get_zakaz()
    {
        $zakazs = ORM::factory("Zakaz")
        ->where('state', '=', 'not_start')
        ->or_where('state', '=', 'in_process')
//        ->or_where('state', '=', 'done')
        ->order_by('date_dostavka', 'ASC')
        ->limit(9)
        ->find_all();
        $result = array();
        $i = 0;
        foreach ($zakazs as $zakaz)
        {
            $date_elements  = explode(" ",$zakaz->date_dostavka);
            $time = explode(":",$date_elements[1]);

            $result[$i++] = array(
                'id' => $zakaz->id,
                'time' => $time[0].":".$time[1],
                'state' => $zakaz->state,
            );
        }
        $res['data'] = array();
        for($i=1;$i<=count($result);$i++)
            $res['data'][] = $result[count($result)-$i];
//        $res['count'] = $zakaz_pos_count - count($result);
        $zakazs_collect = ORM::factory("Zakaz")
        ->where('state', '=', 'collected')
        ->order_by('date_dostavka', 'DESC')
        ->limit(12 - count($res['data']))
        ->find_all();
        
        foreach ($zakazs_collect as $zakaz)
        {
            $date_elements  = explode(" ",$zakaz->date_dostavka);
            $time = explode(":",$date_elements[1]);

            $res['data'][] = array(
                'id' => $zakaz->id,
                'time' => $time[0].":".$time[1],
                'state' => $zakaz->state,
            );
        }

        echo json_encode($res);
    }
    
    // печатать чек
    public function action_print_check()
    {
        $id_zakaz = Arr::get($_REQUEST, 'id_zakaz', 0);
        Service::print_check($id_zakaz);
//        $orm_zakaz = ORM::factory('Zakaz', $id_zakaz);
//        
//        if($orm_zakaz->loaded() == false)
//            return;
//        
////        $pizza_free = false;
//        
////        if($orm_zakaz->id_promo != 0)
////        {
////            if($orm_zakaz->promo->action->options == 'category=pizza')
////                $pizza_free = true;
////        }
//        
//        $zakaz_pos = ORM::factory('ZakazPosition')->get_list_item($id_zakaz);
//
//        $result = array();
////        $pizza_count = 0;
////        $pizza_price = 0;
//        foreach ($zakaz_pos as $zakaz)
//        {
//            $count = ORM::factory('ZakazPosition')
//                    ->where('id_zakaz', '=', $id_zakaz)
//                    ->and_where('id_menu', '=', $zakaz['id_menu'])
//                    ->count_all();
//            $menu_item = ORM::factory('Menu', $zakaz['id_menu']);
//            $result[] = array(
//                'name' => $menu_item->name.' '.$menu_item->size,
//                'category' => $menu_item->category,
//                'count' => $count,
//                'price' => $menu_item->price,
//                'summa' => $count * $menu_item->price
//                );
//            
////            if($menu_item->category == 'pizza')
////            {
////                $pizza_count += $count;
////                $pizza_price = $menu_item->price;
////            }
//        }
//
////        // вычитаем халявные соусы
////        for($i=0;$i<count($result);$i++)
////        {
////            if($result[$i]['category'] == 'sous')
////            {
////                if($pizza_count >= $result[$i]['count'])
////                {
////                    $result[$i]['summa'] = 0;
////                    $pizza_count = $pizza_count - $result[$i]['count'];
////                }
////                else
////                {
////                    $temp_val = $result[$i]['count'] - $pizza_count;
////                    $result[$i]['count'] = $pizza_count;
////                    $result[$i]['summa'] = 0;
////                    $result[] = array(
////                        'name' => $result[$i]['name'],
////                        'category' => $result[$i]['category'],
////                        'count' => $temp_val,
////                        'summa' => $temp_val * $result[$i]['price'],
////                    );
////                    $pizza_count = 0;
////                    break;
////                }
////            }
////        }
//        
////        $summa = 0;
////        for($i=0;$i<count($result);$i++)
////        {
////            $summa += $result[$i]['summa'];
////        }
////        
////        $sale = 0;
////        if($pizza_free == true)
////        {
////            $summa = $summa - $pizza_price;
////            $sale = $pizza_price;
////        }
//        
//        
//
////        KohanaPDF::createCheck($result, $orm_zakaz->summa, Service::get_full_adress($orm_zakaz->id), $orm_zakaz->nomer, Service::get_data($orm_zakaz->date_zakaz), $orm_zakaz->sale, $orm_zakaz->klient->tel);
//        KohanaPDF::createCheck($result, $orm_zakaz);
    }
    
    // возвращает id и состояние promo
    public function action_get_id_promo()
    {
        $promo = ORM::factory("PromoCode")
                ->where("name", "=", strtoupper(addslashes(Arr::get($_REQUEST, 'name'))))
                ->find();
        $res = array('id' => 0, 'about' => 'Промо код не найден.');
        if($promo->loaded())
        {
            if(($promo->state == 'activated') && ($promo->action->state == 'enable'))
                $res = array('id' => $promo->id, 'about' => $promo->action->about);
            else
                $res = array('id' => 0, 'about' => 'Промо код не активен.');
        }
        echo json_encode($res);
    }
    
    // позврашает список промокодов
    public function action_get_list_promo()
    {
        $name_promo = strtoupper(addslashes(Arr::get($_REQUEST, 'name', '')));
        $promo_state = strtoupper(addslashes(Arr::get($_REQUEST, 'state', 'generated')));
        
        $promos = ORM::factory("PromoCode")
                ->where("name", "LIKE", $name_promo.'%')
                ->and_where("state", "=", $promo_state)
                ->limit(10)
                ->find_all();
        $res = array();
        foreach ($promos as $promo)
            $res[] = array(
                'id' => $promo->id,
                'name' => $promo->name,
                'state' => $promo->state,
                );
        
        echo json_encode($res);
    }
    
    // установить состояние промо кода
    public function action_set_promo_state()
    {
        $id = addslashes(Arr::get($_REQUEST, 'id', 0));
        $state = addslashes(Arr::get($_REQUEST, 'state', 'closed'));
        $id_zakaz = addslashes(Arr::get($_REQUEST, 'id_zakaz'));
        
        Service::set_promo_state($id, $state, $id_zakaz);
    }
    
    /**
     *      Вывод подробного отчета по курьерам
     */
    public function action_reports_courier()
    {
        $begin_date_time = Arr::get($_REQUEST, "begin_date_time");
        $end_date_time = Arr::get($_REQUEST, "end_date_time");
        $courier_id = Arr::get($_REQUEST, "courier_id");
        $report = ORM::factory('Zakaz')
                                    ->where('id_courier', '=', $courier_id)
                                    ->and_where('date_zakaz', '>=', $begin_date_time)
                                    ->and_where('date_zakaz', '<=', $end_date_time)
                                    ->and_where('state', 'in', array('success','unsuccess','cansel'))
                                    ->find_all();
    }

    /**
     *      Вывод подробного отчета по статусу заказов (выполненные, 
     *          невыолненные, отмененные)
     */
    public function action_reports_state()
    {
        $begin_date_time = Arr::get($_REQUEST, "begin_date_time");
        $end_date_time = Arr::get($_REQUEST, "end_date_time");
        $state = Arr::get($_REQUEST, "state");
        switch ($state) {
            case 'success';
            case 'unsuccess';
            case 'cansel':
                $report = ORM::factory('Zakaz')
                                ->where('date_zakaz', '>=', $begin_date_time)
                                ->and_where('date_zakaz', '<=', $end_date_time)
                                ->and_where('state', '=', $state)
                                ->find_all();
                break;
            case 'all':
                $report = ORM::factory('Zakaz')
                                ->where('date_zakaz', '>=', $begin_date_time)
                                ->and_where('date_zakaz', '<=', $end_date_time)
                                ->and_where('state', 'in', array('success','unsuccess','cansel'))
                                ->find_all();
            default:
                break;
        }
    }
    
    
    public function action_reports_filtr()
    {
        $begin_date_time = Arr::get($_REQUEST, "begin_date_time");
        $end_date_time = Arr::get($_REQUEST, "end_date_time");
        $filtr = Arr::get($_REQUEST, "filtr");
        $report=array();
        switch ($filtr) {
            case 'day':
                    $report = DB::query(Database::SELECT, 'SELECT date_zakaz, SUM( Summa ) AS summa, COUNT( date_zakaz ) AS positions 
                            FROM `zakazes`
                            WHERE `date_zakaz` between  \'' . $begin_date_time . '\' 
                            AND  \'' . $end_date_time . '\' 
                            AND `state` IN ( \'success\', \'unsuccess\',  \'cansel\')
                            GROUP BY LEFT (`date_zakaz`,10)')->execute()->as_array();
//                for ($i=0;$i<=date('z',$end_date_time)-date('z',$begin_date_time);$i++){
//                    $day = date('Y-m-d', strtotime('+' . $i . ' days',$begin_date_time));
//                    $report = array (
//                        'date' => $day,
//                        'summa' => DB::query(Database::SELECT, 'SELECT SUM( Summa ) AS summa FROM `zakazes`
//                            WHERE `date_zakaz` >= \'' . $day . '\' 00:00:00  
//                            AND  `date_zakaz`  <=  \'' . $day . '\' 23:59:59 
//                            AND `state` IN ( \'success\', \'unsuccess\',  \'cansel\')')->execute()->get('summa'),
//                    );
//                };
                break;
            case 'week':
                    $report = DB::query(Database::SELECT, 'SELECT SUM( Summa ) AS summa, 
                            WEEK(date_zakaz,1) AS wk, 
                            COUNT( date_zakaz ) AS positions,
                            LEFT(date_sub(date_zakaz, interval (weekday(date_zakaz)) day),10) as dbeg,
                            LEFT(date_add(date_zakaz, interval (6-weekday(date_zakaz)) day),10) as dend 
                            FROM `zakazes`
                            WHERE `date_zakaz` between  \'' . $begin_date_time . '\' 
                            AND  \'' . $end_date_time . '\'
                            AND `state` IN ( \'success\', \'unsuccess\',  \'cansel\')
                            GROUP BY wk')->execute()->as_array();
                    break;
            case 'mounth':
                    $report = DB::query(Database::SELECT, 'SELECT  
                            SUM( Summa ) AS summa, 
                            MONTH(date_zakaz) AS mnth, 
                            COUNT( date_zakaz ) AS positions FROM `zakazes`
                            WHERE `date_zakaz` between  \'' . $begin_date_time . '\' 
                            AND  \'' . $end_date_time . '\'
                            AND `state` IN ( \'success\', \'unsuccess\',  \'cansel\')
                            GROUP BY mnth')->execute()->as_array();
                    break;
            case 'period':
                    $report = DB::query(Database::SELECT, 'SELECT  
                            SUM( Summa ) AS summa, 
                            COUNT( date_zakaz ) AS positions FROM `zakazes`
                            WHERE `date_zakaz` between  \'' . $begin_date_time . '\' 
                            AND  \'' . $end_date_time . '\'
                            AND `state` IN ( \'success\', \'unsuccess\',  \'cansel\')')->execute()->as_array();
                    break;
            default:
                break;
        }
    }
    
    public function action_zakaz_get_summa()
    {
        $promo = ORM::factory('PromoCode')
                ->where('name', '=', strtoupper(addslashes(Arr::get($_REQUEST, 'promo'))))
                ->and_where('state', '=', 'activated')
                ->find();
        $action = array();
        if($promo->loaded())
            $action[] = $promo->id_action;
        echo json_encode(Service::calc_cost(Arr::get($_REQUEST, 'id'), Arr::get($_REQUEST, 'count'), $action));
    }
    
    public function action_coord()
    {
            $coord = ORM::factory('Coord', array('id_courier'=>1));

                echo json_encode(array((float) $coord->latitude, (float) $coord->longtitude));
                return;
    }
    
    public function action_generate() {
        if ($this->request->is_ajax())
        {
            $id = Arr::get($_REQUEST, 'id_courier', NULL);
            
            $auth = Auth::instance();
            
            if ($auth->logged_in('manager') || $auth->logged_in("admin") || $auth->logged_in("root")) {
                $courier = ORM::factory('Courier', $id);
                
                if ($courier->loaded())
                {
                    $rand = rand(11111, 99999);
                    
                    $courier->password = $rand;
                    $courier->timestamp = date('Y-m-d H:i:s', time());
                    $courier->save();
                    
                    echo $rand;
                }
                
            } else {
                echo "FALSE";
            }
        } else {
            echo "FALSE";
        }
    }

    /**
     * Метод для блокировки курьера
     */
    public function action_cur_block() {
        if ($this->request->is_ajax())
        {
            $id = Arr::get($_REQUEST, 'id_courier', NULL);
            
            $auth = Auth::instance();
            
            if ($auth->logged_in('manager') || $auth->logged_in("admin") || $auth->logged_in("root")) {
                $courier = ORM::factory('Courier', $id);
                
                if ($courier->loaded())
                {                    
                    $courier->status = 'blocked';
                    $courier->save();
                    
                    echo "TRUE";
                }
                
            } else {
                echo "FALSE";
            }
        } else {
            echo "FALSE";
        }
    }

    /**
     * Добавление курьера в базу
     */
    public function action_cur_add() {
        if ($this->request->is_ajax())
        {
            $name = Arr::get($_REQUEST, 'name_courier', NULL);
            $rand = rand(11111, 99999);

            $auth = Auth::instance();
            
            if ($auth->logged_in('manager') || $auth->logged_in("admin") || $auth->logged_in("root")) {
                $courier = ORM::factory('Courier');
                
                $courier->status = 'active';
                $courier->name = $name;
                $courier->password = $rand;
                $courier->timestamp = date('Y-m-d H:i:s', time());
                $courier->save();
                
                echo "TRUE";

                
            } else {
                echo "FALSE";
            }
        } else {
            echo "FALSE";
        }
    }

    /**
     * Отрисовка списка пользователей
     */
    public function action_render_users()
    {

        $show_blocked = Arr::get($_POST, 'blocked', False);

        if ($show_blocked){
            $users = ORM::factory('User')
                        ->find_all();            
        }
        else{
            $users = ORM::factory('User')
                        ->where('status', '=', '0')
                        ->find_all();             
        }

        foreach($users as $user)
        {

            $id = 'user_'.$user->id;
            $data[$id]['id'] = $user->id;
            $data[$id]['login'] = $user->username;
            $data[$id]['name'] = $user->full_name;
            $data[$id]['tel'] = $user->tel;
            $data[$id]['status'] = $user->status;
            $data[$id]['roles'] = '';

            $user_roles = $user->roles->find_all();

                foreach ($user_roles as $user_role) {

                    $data[$id]['roles'] .= $user_role->description.', ';
                }
            $data[$id]['roles'] = rtrim($data[$id]['roles'], ', ');
        }

        echo json_encode($data);
    }

    public function action_add_user()
    {
        $user = ORM::factory('User');

        $user->values(array(
            'username' => $_POST['username'],
            ));
    }
}
