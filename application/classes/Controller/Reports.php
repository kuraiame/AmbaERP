<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Reports extends Controller_Template {

    public $template = 'template_main';

    public function before() 
    {
        parent::before();

        $auth = Auth::instance();
        
        if(!$auth->logged_in("admin") && !$auth->logged_in("manager"))
            return Controller::redirect("/");
        
        $this->template->title = "Отчеты";
        $css_array = array('/css/bootstrap.css', '/css/bootstrap-responsive.min.css', '/css/calendar.css');
        $script_array = array('/js/jquery.js', '/js/bootstrap.min.js', '/js/scripts.js', '/js/mootools.js', '/js/calendar.rc4.js');
        $this->template->css_array = $css_array;
        $this->template->script_array = $script_array;
        $this->template->access_array = array(
            'zakaz' => true,
            'curier'=> true,
            'menu'  => true,
            'report'=> true,
            'manage'=> true,
            );
    }

    public function action_index() 
    {

        $mainView = View::factory('report_index');
        $mainView->flag_exist = false;

        $begin_date = Arr::get($_REQUEST, 'begin_date', date('Y-m-d', time()));
        $begin_time = Arr::get($_REQUEST, 'begin_time', '00:00:00');

        $end_date = Arr::get($_REQUEST, 'end_date', date('Y-m-d', time()));
        $end_time = Arr::get($_REQUEST, 'end_time', '23:59:59');

        $begin_date_time = $begin_date . ' ' . $begin_time;
        $end_date_time = $end_date . ' ' . $end_time;

        if ($begin_date_time != ' ' && $end_date_time != ' ') {
            $zakaz_count_all = ORM::factory('Zakaz')->where('date_zakaz', '>=', $begin_date_time)
                    ->and_where('date_zakaz', '<=', $end_date_time)
                    ->and_where('state', 'in', array('success', 'unsuccess', 'cansel'))
                    ->count_all();
            $zakaz_summa_all = DB::query(Database::SELECT, 'SELECT SUM( Summa ) AS summa FROM `zakazes` WHERE `date_zakaz` >= \'' . $begin_date_time . '\'  AND  `date_zakaz`  <=  \'' . $end_date_time . '\' AND  `state` IN ( \'success\', \'unsuccess\',  \'cansel\')')->execute()->get('summa');
            if ($zakaz_summa_all == '')
                $zakaz_summa_all = '0';
// тут беда какая-то           
//            $zakaz_summa_all= DB::select(array(SUM('summa'), 'summa'))
//                    ->from('zakazes')
//                    ->where('date_zakaz', '>=', $time_begin)
//                    ->and_where('date_zakaz', '<=', $time_end)
//                    ->and_where('state', 'in', array('success','unsuccess','cansel'))
//                    ->execute()
//                    ->get('summa');
            $zakaz_success = ORM::factory('Zakaz')->where('date_zakaz', '>=', $begin_date_time)
                    ->and_where('date_zakaz', '<=', $end_date_time)
                    ->and_where('state', '=', 'success')
                    ->count_all();
            $zakaz_summa_success = DB::query(Database::SELECT, 'SELECT SUM( Summa ) AS summa FROM `zakazes` WHERE `date_zakaz` >= \'' . $begin_date_time . '\'  AND  `date_zakaz`  <=  \'' . $end_date_time . '\' AND  `state` = \'success\' ')->execute()->get('summa');
            if ($zakaz_summa_success == '')
                $zakaz_summa_success = '0';
            $zakaz_unsuccess = ORM::factory('Zakaz')->where('date_zakaz', '>=', $begin_date_time)
                    ->and_where('date_zakaz', '<=', $end_date_time)
                    ->and_where('state', '=', 'unsuccess')
                    ->count_all();
            $zakaz_summa_unsuccess = DB::query(Database::SELECT, 'SELECT SUM( Summa ) AS summa FROM `zakazes` WHERE `date_zakaz` >= \'' . $begin_date_time . '\'  AND  `date_zakaz`  <=  \'' . $end_date_time . '\' AND  `state` = \'unsuccess\' ')->execute()->get('summa');
            if ($zakaz_summa_unsuccess == '')
                $zakaz_summa_unsuccess = '0';
            $zakaz_cansel = ORM::factory('Zakaz')->where('date_zakaz', '>=', $begin_date_time)
                    ->and_where('date_zakaz', '<=', $end_date_time)
                    ->and_where('state', '=', 'cansel')
                    ->count_all();
            $zakaz_summa_cansel = DB::query(Database::SELECT, 'SELECT SUM( Summa ) AS summa FROM `zakazes` WHERE `date_zakaz` >= \'' . $begin_date_time . '\'  AND  `date_zakaz`  <=  \'' . $end_date_time . '\' AND  `state` = \'cansel\' ')->execute()->get('summa');
            if ($zakaz_summa_cansel == '')
                $zakaz_summa_cansel = '0';

            $uniq_couriers = DB::select('id_courier')
                    ->distinct(true)
                    ->from('zakazes')
                    ->where('date_zakaz', '>=', $begin_date_time)
                    ->and_where('date_zakaz', '<=', $end_date_time)
                    ->and_where('id_courier', '!=', 0)
                    ->execute()
                    ->as_array();

            $couriers_result = array();
            foreach ($uniq_couriers as $courier) {
                $couriers_result[] = array(
                    'id' => $courier['id_courier'],
                    'name' => ORM::factory('Courier', $courier['id_courier'])->name,
                    'all' => ORM::factory('Zakaz')
                            ->where('id_courier', '=', $courier['id_courier'])
                            ->and_where('date_zakaz', '>=', $begin_date_time)
                            ->and_where('date_zakaz', '<=', $end_date_time)
                            ->and_where('state', 'in', array('success', 'unsuccess', 'cansel'))
                            ->count_all(),
                    'success' => ORM::factory('Zakaz')
                            ->where('id_courier', '=', $courier['id_courier'])
                            ->and_where('date_zakaz', '>=', $begin_date_time)
                            ->and_where('date_zakaz', '<=', $end_date_time)
                            ->and_where('state', '=', 'success')
                            ->count_all(),
                    'unsuccess' => ORM::factory('Zakaz')
                            ->where('id_courier', '=', $courier['id_courier'])
                            ->and_where('date_zakaz', '>=', $begin_date_time)
                            ->and_where('date_zakaz', '<=', $end_date_time)
                            ->and_where('state', '=', 'unsuccess')
                            ->count_all(),
                    'cansel' => ORM::factory('Zakaz')
                            ->where('id_courier', '=', $courier['id_courier'])
                            ->and_where('date_zakaz', '>=', $begin_date_time)
                            ->and_where('date_zakaz', '<=', $end_date_time)
                            ->and_where('state', '=', 'cansel')
                            ->count_all(),
                );
            }

            $mainView->flag_exist = true;
            $mainView->zakaz_count_all = $zakaz_count_all;
            $mainView->zakaz_success = $zakaz_success;
            $mainView->zakaz_unsuccess = $zakaz_unsuccess;
            $mainView->zakaz_cansel = $zakaz_cansel;
            $mainView->couriers_result = $couriers_result;
            $mainView->zakaz_summa_all = $zakaz_summa_all;
            $mainView->zakaz_summa_success = $zakaz_summa_success;
            $mainView->zakaz_summa_unsuccess = $zakaz_summa_unsuccess;
            $mainView->zakaz_summa_cansel = $zakaz_summa_cansel;
        }
        $mainView->begin_date = $begin_date;
        $mainView->begin_time = $begin_time;
        $mainView->end_date = $end_date;
        $mainView->end_time = $end_time;
        $this->template->content = $mainView;
    }

    public function action_zakaz_day() 
    {
        $week = 604800;
        $day  = 86400;
        
        $recive_begin_date = Arr::get($_REQUEST, 'begin_date', date('Y-m-d', time()));
        $date = explode('-', $recive_begin_date);
        $begin_date_int = mktime(0, 0, 0, $date[1], $date[2], $date[0]);
        $begin_date = date('Y-m-d', $begin_date_int);
        $end_date = date('Y-m-d', $begin_date_int);

        $prev_begin_date = date('Y-m-d', $begin_date_int - $day);
        $prev_end_date = date('Y-m-d', $begin_date_int - $day);

        $flag_prev_period = true;
        
        $template_report = View::factory('template_report');
        $template_report->select_begin_date = $begin_date;
        $template_report->report_name = 'По заказам';
        $template_report->report_type = 'zakaz';
        $template_report->other_par = '';
        $template_report->content = Report::create_view_report_zakaz($begin_date, $end_date, $prev_begin_date, $prev_end_date, $flag_prev_period, 'day', 'день');
        
        $this->template->content = $template_report;
    }
    
    public function action_zakaz_week() 
    {
        $week = 604800;
        $day  = 86400;
        
        $recive_begin_date = Arr::get($_REQUEST, 'begin_date', date('Y-m-d', time() - $week));
        $date = explode('-', $recive_begin_date);
        $begin_date_int = mktime(0, 0, 0, $date[1], $date[2], $date[0]);
        $begin_date = date('Y-m-d', $begin_date_int);
        $end_date = date('Y-m-d', $begin_date_int + $week - $day);

        $prev_begin_date = date('Y-m-d', $begin_date_int - $week);
        $prev_end_date = date('Y-m-d', $begin_date_int - $day);

        $flag_prev_period = false;
        if (date('w', $begin_date_int) == 1)
            $flag_prev_period = true;

        $template_report = View::factory('template_report');
        $template_report->select_begin_date = $begin_date;
        $template_report->report_name = 'По заказам';
        $template_report->report_type = 'zakaz';
        $template_report->other_par = '';
        $template_report->content = Report::create_view_report_zakaz($begin_date, $end_date, $prev_begin_date, $prev_end_date, $flag_prev_period, 'week', 'неделю');
        
        $this->template->content = $template_report;
    }

    public function action_zakaz_mounth() 
    {
        $week = 604800;
        $day  = 86400;
        
        $recive_begin_date = Arr::get($_REQUEST, 'begin_date', date('Y-m-d', time() - date('t', time() - ($day * date('d')))*$day ));
        $date = explode('-', $recive_begin_date);
        $begin_date_int = mktime(0, 0, 0, $date[1], $date[2], $date[0]);
        $begin_date = date('Y-m-d', $begin_date_int);
        $end_date = date('Y-m-d', $begin_date_int + (date('t', $begin_date_int) - 1)*$day);
 
        $prev_begin_date = date('Y-m-d', $begin_date_int - date('t', $begin_date_int - ($day * date('d', $begin_date_int)))*$day );
        $prev_end_date = date('Y-m-d', $begin_date_int - $day);

        $flag_prev_period = false;
        if (date('d', $begin_date_int) == 1)
            $flag_prev_period = true;

        $template_report = View::factory('template_report');
        $template_report->select_begin_date = $begin_date;
        $template_report->report_name = 'По заказам';
        $template_report->report_type = 'zakaz';
        $template_report->other_par = '';
        $template_report->content = Report::create_view_report_zakaz($begin_date, $end_date, $prev_begin_date, $prev_end_date, $flag_prev_period, 'mounth', 'месяц');
        
        $this->template->content = $template_report;
    }

    public function action_courier_day()
    {
        $week = 604800;
        $day  = 86400;

        $recive_begin_date = Arr::get($_REQUEST, 'begin_date', date('Y-m-d', time()));
        $date = explode('-', $recive_begin_date);
        $begin_date_int = mktime(0, 0, 0, $date[1], $date[2], $date[0]);
        $begin_date = date('Y-m-d', $begin_date_int);
        $end_date = date('Y-m-d', $begin_date_int);

        $id_courier = Arr::get($_REQUEST, "id_courier", 0);

        $courier_list = View::factory('report_courier_list');
        $courier_list->courier_list = ORM::factory("Courier")->find_all();
        $courier_list->courier_id = $id_courier;

        $template_report = View::factory('template_report');
        $template_report->select_begin_date = $begin_date;
        $template_report->report_name = 'По курьерам';
        $template_report->report_type = 'courier';
        $template_report->other_par   = $courier_list; 
        $template_report->content = Report::create_view_report_courier($begin_date, $end_date, $id_courier, 'day', 'день');

        $this->template->content = $template_report;
    }
   
    public function action_courier_week() 
    {
        $week = 604800;
        $day  = 86400;

        $recive_begin_date = Arr::get($_REQUEST, 'begin_date', date('Y-m-d', time() - $week));
        $date = explode('-', $recive_begin_date);
        $begin_date_int = mktime(0, 0, 0, $date[1], $date[2], $date[0]);
        $begin_date = date('Y-m-d', $begin_date_int);
        $end_date = date('Y-m-d', $begin_date_int + $week - $day);

        $id_courier = Arr::get($_REQUEST, "id_courier", 0);

        $courier_list = View::factory('report_courier_list');
        $courier_list->courier_list = ORM::factory("Courier")->find_all();
        $courier_list->courier_id = $id_courier;

        $template_report = View::factory('template_report');
        $template_report->select_begin_date = $begin_date;
        $template_report->report_name = 'По курьерам';
        $template_report->report_type = 'courier';
        $template_report->other_par   = $courier_list; 
        $template_report->content = Report::create_view_report_courier($begin_date, $end_date, $id_courier, 'week', 'неделю');

        $this->template->content = $template_report;
    }

    public function action_courier_mounth() 
    {
        $week = 604800;
        $day  = 86400;
        
        $recive_begin_date = Arr::get($_REQUEST, 'begin_date', date('Y-m-d', time() - date('t', time() - ($day * date('d')))*$day ));
        $date = explode('-', $recive_begin_date);
        $begin_date_int = mktime(0, 0, 0, $date[1], $date[2], $date[0]);
        $begin_date = date('Y-m-d', $begin_date_int);
        $end_date = date('Y-m-d', $begin_date_int + (date('t', $begin_date_int) - 1)*$day);
 
        $id_courier = Arr::get($_REQUEST, "id_courier", 0);

        $courier_list = View::factory('report_courier_list');
        $courier_list->courier_list = ORM::factory("Courier")->find_all();
        $courier_list->courier_id = $id_courier;

        $template_report = View::factory('template_report');
        $template_report->select_begin_date = $begin_date;
        $template_report->report_name = 'По курьерам';
        $template_report->report_type = 'courier';
        $template_report->other_par   = $courier_list; 
        $template_report->content = Report::create_view_report_courier($begin_date, $end_date, $id_courier, 'mounth', 'месяц');

        $this->template->content = $template_report;
    }
    
}