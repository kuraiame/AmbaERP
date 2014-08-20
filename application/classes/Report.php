<?php defined('SYSPATH') or die('No direct script access.');

class Report extends Kohana
{
    
    static function create_view_report_zakaz($begin_date, $end_date, $prev_begin_date, $prev_end_date, $flag_prev_period, $type, $title)
    {
        $report_all = Report::get_reports_filtr($begin_date, $end_date, $type, array('success', 'unsuccess'));
        $report_unsuccess = Report::get_reports_filtr($begin_date, $end_date, $type, array('unsuccess'));
        
        $mainView = View::factory('report_zakaz');
        $mainView->select_begin_date = $begin_date;
        $mainView->begin_date = Service::get_data($begin_date.' 00:00:00');
        $mainView->end_date = Service::get_data($end_date.' 23:59:59');
        $mainView->period_name = $title;
        $mainView->report_all_summa = $report_all[0]['summa'];
        $mainView->report_all_count = $report_all[0]['positions'];
        if ($report_all[0]['positions'] != 0)
            $mainView->report_all_middle = round($report_all[0]['summa'] / $report_all[0]['positions'], 2);
        else
            $mainView->report_all_middle = 0;
        $mainView->report_unsuccess_count = $report_unsuccess[0]['positions'];

        $mainView->prev_period = false;
        
        if ($flag_prev_period == true)
        {
            $mainView->prev_period = true;
            
            $prev_period_report_all = Report::get_reports_filtr($prev_begin_date, $prev_end_date, $type, array('success', 'unsuccess'));            
            $prev_report_all = 0;
            if($prev_period_report_all[0]['summa'] == 0 && $report_all[0]['summa'] == 0)
                $prev_report_all = 0;
            else
            {
                if($prev_period_report_all[0]['summa'] == 0)
                    $prev_report_all = '-';
                else
                    $prev_report_all = round((($report_all[0]['summa'] / $prev_period_report_all[0]['summa']) - 1) * 100);
            }
            
            $prev_report_position = 0;
            if($prev_period_report_all[0]['positions'] == 0 && $report_all[0]['positions'] == 0)
                $prev_report_position = 0;
            else
            {
                if($prev_period_report_all[0]['positions'] == 0)
                    $prev_report_position = '-';
                else
                    $prev_report_position = round((($report_all[0]['positions'] / $prev_period_report_all[0]['positions']) - 1) * 100);
            }
            
            $prev_period_report_unsuccess = Report::get_reports_filtr($prev_begin_date, $prev_end_date, $type, array('unsuccess'));            
            $prev_report_unsuccess_position = 0;
            if($prev_period_report_unsuccess[0]['positions'] == 0 && $report_unsuccess[0]['positions'] == 0)
                $prev_report_unsuccess_position = 0;
            else
            {
                if($prev_period_report_unsuccess[0]['positions'] == 0)
                    $prev_report_unsuccess_position = '-';
                else
                    $prev_report_unsuccess_position = round((($report_unsuccess[0]['positions'] / $prev_period_report_unsuccess[0]['positions']) - 1) * 100);
            }
            
            
            $mainView->prev_report_all      = $prev_report_all;
            $mainView->prev_report_position = $prev_report_position;
            $mainView->prev_report_unsuccess= $prev_report_unsuccess_position;
            $mainView->prev_begin_date      = Service::get_data($prev_begin_date.' 00:00:00');
            $mainView->prev_end_date        = Service::get_data($prev_end_date.' 23:59:59'); 
        }
        
        $mainView->report_positions = Report::get_reports_positions($begin_date, $end_date);
        
        return $mainView;
    }
    
    static function create_view_report_courier($begin_date, $end_date, $id_courier, $type, $title)
    {
        $begin_date_time = $begin_date . ' 00:00:00';
        $end_date_time = $end_date . ' 23:59:59';
        
        $mainView = View::factory('report_courier');
        $mainView->select_begin_date = $begin_date;
        $mainView->begin_date = Service::get_data($begin_date.' 00:00:00');
        $mainView->end_date = Service::get_data($end_date.' 23:59:59');
        $mainView->period_name = $title;

        $result = Report::get_main_reports_courier($begin_date, $end_date, $id_courier);
//        var_dump($result);
        
//        $couriers = ORM::factory('Courier')->where('status', '=', 'active')->find_all();
//        foreach ($couriers as $courier)
//        {
//            $res[] = array(
//                'name' => $courier->name,
//                'success' => ORM::factory('Zakaz') 
//                    ->where('date_zakaz', 'between', DB::expr("'".$begin_date_time."' AND '".$end_date_time."'"))
//                    ->and_where('id_courier', '=', $courier->id)
//                    ->and_where('state', '=', 'success')
//                    ->count_all(),
//                'unsuccess' => ORM::factory('Zakaz') 
//                    ->where('date_zakaz', 'between', DB::expr("'".$begin_date_time."' AND '".$end_date_time."'"))
//                    ->and_where('id_courier', '=', $courier->id)
//                    ->and_where('state', '=', 'unsuccess')
//                    ->count_all(),
//            );
//        }

        $res = array();
        $all_zakaz_success   = 0;
        $all_zakaz_unsuccess = 0;
        $all_summa = 0;
        $all_pizza = 0;
        $all_time  = 0;
        foreach ($result as $i)
        {
            $diff_time = Service::diff_date($i['date_fact_dost'], $i['data_assign_courier']);
            $res[] = array(
               'id' => $i['id'],
               'summa' => $i['summa'],
               'count' => $i['count'],
               'state' => $i['state'],
               'time_dostavka' => Service::time_int_to_str($diff_time),
            );
            $all_summa += $i['summa'];
            $all_pizza += $i['count'];
            $all_time  += $diff_time;
            if($i['state'] == 'success')
                $all_zakaz_success += 1;
            if($i['state'] == 'unsuccess')
                $all_zakaz_unsuccess += 1;
        }
        
        
        $mainView->report_couriers     = $res;
        $mainView->all_zakaz_success   = $all_zakaz_success;
        $mainView->all_zakaz_unsuccess = $all_zakaz_unsuccess;
        $mainView->all_summa           = $all_summa;
        $mainView->all_pizza           = $all_pizza;
        $mainView->all_time            = Service::time_int_to_str($all_time);
        
        return $mainView;
    }
    
    
    //отчет по заказам с фильтром по дням, неделям, месецам, за период 
    static public function get_reports_filtr($begin_date,$end_date,$filtr,$state=NULL)
    {
        $begin_date_time = $begin_date . ' 00:00:00';
        $end_date_time = $end_date . ' 23:59:59';
        $state_in = '';
        if ($state<>NULL) 
            $state_in = "AND `state` IN ( '".  implode("', '", $state)."')";
        $report=array('summa'=>'0','positions'=>'0');
        switch ($filtr) {
            case 'day':
                    $report = DB::query(Database::SELECT, 'SELECT date_zakaz, SUM( Summa ) AS summa, COUNT( date_zakaz ) AS positions 
                            FROM `zakazes`
                            WHERE `date_zakaz` between  \'' . $begin_date_time . '\' 
                            AND  \'' . $end_date_time . '\' 
                            ' . $state_in . ' 
                            GROUP BY LEFT (`date_zakaz`,10)')->execute()->as_array();
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
                            ' . $state_in . '
                            GROUP BY wk')->execute()->as_array();
                    break;
            case 'mounth':
                    $report = DB::query(Database::SELECT, 'SELECT  
                            SUM( Summa ) AS summa, 
                            MONTH(date_zakaz) AS mnth, 
                            COUNT( date_zakaz ) AS positions FROM `zakazes`
                            WHERE `date_zakaz` between  \'' . $begin_date_time . '\' 
                            AND  \'' . $end_date_time . '\'
                            ' . $state_in . '
                            GROUP BY mnth')->execute()->as_array();
                    break;
            case 'period':
                    $report = DB::query(Database::SELECT, 'SELECT  
                            SUM( Summa ) AS summa, 
                            COUNT( date_zakaz ) AS positions FROM `zakazes`
                            WHERE `date_zakaz` between  \'' . $begin_date_time . '\' 
                            AND  \'' . $end_date_time . '\'
                            ' . $state_in . '')->execute()->as_array();
                    break;
            default:
                break;
            
        }
        if(count($report) == 0 )
            $report[] = array('summa' => 0, 'positions' => 0);
        return $report;
    }
    
    
    //отчет по курьеру
    static public function get_main_reports_courier($begin_date,$end_date,$courier)
    {
        $begin_date_time = $begin_date . ' 00:00:00';
        $end_date_time = $end_date . ' 23:59:59';
        $report = DB::query(Database::SELECT, 'SELECT zk.id, zk.state, zk.summa, COUNT(zp.id_menu) AS count, zk.data_assign_courier, zk.date_fact_dost
                                      FROM  `zakazes` AS zk,  `zakazpositions` AS zp, `menus` as mn
                                      WHERE zk.date_zakaz BETWEEN  \'' . $begin_date_time . '\'
                                      AND  \'' . $end_date_time . '\'
                                      AND zk.id=zp.id_zakaz
                                      AND zp.id_menu=mn.id
                                      AND mn.category=\'pizza\'
                                      AND zk.id_courier = \'' . $courier . '\'
                                      GROUP BY zk.id')->execute()->as_array();
        return $report;
    }
    
    
    
    // отчет по курьерам с фильтром по дням, неделям, месецам, за период 
    static public function get_reports_courier($begin_date,$end_date,$filtr,$courier,$state=NULL)
    {
        $begin_date_time = $begin_date . ' 00:00:00';
        $end_date_time = $end_date . ' 23:59:59';
        $state_in = '';
        if ($state<>NULL) 
            $state_in = "AND `state` IN ( '".  implode("', '", $state)."')";
        $report=array('summa'=>'0','positions'=>'0');
        switch ($filtr) {
            case 'day':
                    $report = DB::query(Database::SELECT, 'SELECT date_zakaz, SUM( Summa ) AS summa, COUNT( date_zakaz ) AS positions 
                            FROM `zakazes`
                            WHERE `date_zakaz` between  \'' . $begin_date_time . '\' 
                            AND  \'' . $end_date_time . '\' 
                            ' . $state_in . ' 
                            AND `id_courier` = \'' . $courier . '\'
                            GROUP BY LEFT (`date_zakaz`,10)')->execute()->as_array();
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
                            ' . $state_in . '
                            AND `id_courier` = \'' . $courier . '\'
                            GROUP BY wk')->execute()->as_array();
                    break;
            case 'mounth':
                    $report = DB::query(Database::SELECT, 'SELECT  
                            SUM( Summa ) AS summa, 
                            MONTH(date_zakaz) AS mnth, 
                            COUNT( date_zakaz ) AS positions FROM `zakazes`
                            WHERE `date_zakaz` between  \'' . $begin_date_time . '\' 
                            AND  \'' . $end_date_time . '\'
                            ' . $state_in . '
                            AND `id_courier` = \'' . $courier . '\'
                            GROUP BY mnth')->execute()->as_array();
                    break;
            case 'period':
                    $report = DB::query(Database::SELECT, 'SELECT  
                            SUM( Summa ) AS summa, 
                            COUNT( date_zakaz ) AS positions FROM `zakazes`
                            WHERE `date_zakaz` between  \'' . $begin_date_time . '\' 
                            AND  \'' . $end_date_time . '\'
                            AND `id_courier` = \'' . $courier . '\'
                            ' . $state_in . '')->execute()->as_array();
                    break;
            default:
                break;
            
        }
        if(count($report) == 0 )
            $report[] = array('summa' => 0, 'positions' => 0);
        return $report;
    } 
    
    
    //отчет по позициям в заказах за период
    static public function get_reports_positions ($begin_date,$end_date,$is_desc=TRUE)
    {
        $begin_date_time = $begin_date . ' 00:00:00';
        $end_date_time = $end_date . ' 23:59:59';
        if ($is_desc) $str="DESC"; 
           else $str="ASC";
        $report = DB::query(Database::SELECT, "SELECT COUNT(zp.id_menu) AS count, m.name
                            FROM `zakazpositions` AS zp, `zakazes` AS zz, `menus` as m
                            WHERE zz.date_zakaz between   '" . $begin_date_time . "' 
                            AND  '" . $end_date_time . "' 
                            AND zz.id=zp.id_zakaz
                            AND zp.id_menu=m.id
                            GROUP BY zp.id_menu
                            ORDER BY count ". $str)->execute()->as_array();
        return $report;
    }
    
    
}

?>
