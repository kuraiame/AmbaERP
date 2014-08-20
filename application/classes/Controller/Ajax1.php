<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax1 extends Controller
{
    /**
     * Метод возвращает массив идентификаторов заказов для отображения их в интерфейсе "Заказы".
     * Содержит в себе три массива: Готовые, но не завершенные заказы, Заказы в процессе приготовления, и доставленные но выручка с которых еще не сдана.
     */
    public function action_get_order_ids() {

        $orderORM = ORM::factory('Zakaz');
        
        $idsCollected = $orderORM->where('state', '=', 'collected')->find_all();
        $idsProcess = $orderORM->where('state', '=', 'in_process')->find_all();
        $idsCash = $orderORM->where('state', '=', 'cash')->find_all();
        
        $res = array();
        
        
        foreach ($idsCollected as $value)
        {
            $res['collected'][] = $value->id;
        }
        
        foreach ($idsProcess as $value)
        {
            $res['process'][] = $value->id;
        }
        
        foreach ($idsCash as $value)
        {
            $res['cash'][] = $value->id;
        }
        
        echo json_encode($res);
        return;
    }
    
    public function action_get_new_order_id()
    {
        $idsProcess = ORM::factory('Zakaz')->where('state', '=', 'in_process')->find_all();
        $res = array();
        $oldProcess = Arr::get($_POST, 'oldProcess');
        foreach ($idsProcess as $value)
        {
            if (!in_array($value->id, $oldProcess))
            {
                $oldProcess[] = $value->id;
            }
        }
        
        //Удаляем элементы с другим статусом из массива oldCollected
        foreach ($oldProcess as $value) {
            $coll = ORM::factory('Zakaz', $value);
            if ($coll->state != 'in_process')
                unset($oldProcess[array_search($value,$oldProcess, true)]);
                sort($oldProcess);
        }
        var_dump($oldProcess);
        //echo json_encode($res);
        return;
    }
    
    public function action_get_orders() {
        
        foreach (Arr::get($_POST, 'ids') as $ids)
        {
            $ordersORM = ORM::factory('Zakaz')
                ->where('id', 'IN', $ids)
                ->order_by('id', 'DESC')
                ->find_all();
            foreach ($ordersORM as $value)
            {
                $arr[] = array( 'no'      => $value->id,
                                'addr'    => ($value->klient->house->street->socr->socr_name) ? $value->klient->house->street->socr->socr_name . '. ' . $value->klient->house->street->name . ' ' . $value->klient->house->name . ', кв.' . $value->klient->flat . ', п.' . $value->klient->podiezd . ', эт.' . $value->klient->floor : 'Самовывоз',
                                'time'    => (date('d', strtotime($value->date_dostavka)) == date('d',time())) ? date('H:i', strtotime($value->date_dostavka)): date('d.m, H:i', strtotime($value->date_dostavka)),
                                'phone'   => $value->klient->tel,
                                'contact' => $value->klient->name,
                                'summ'    => $value->summa,
                                'comment' => $value->koment,
                                'sdacha'  => $value->sdacha,
                                'state'   => $value->state,
                                'cur'     => $value->id_courier
                        );
            }
        }

        echo json_encode($arr);
        return;
    }
}
