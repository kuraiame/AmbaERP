<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Courier extends Controller_Template {

        public $template = 'template_main';
        
        public function before() {
            parent::before();
            
            $auth = Auth::instance();
            if(!$auth->logged_in('admin') && !$auth->logged_in("manager") && !$auth->logged_in("devochka"))
                return Controller::redirect('/');
        
            $this->template->title = "Курьер";
            $css_array = array('/css/bootstrap.css', '/css/bootstrap-responsive.min.css', '/css/the-modal.css');
            $script_array = array('/js/jquery.js', '/js/bootstrap.min.js', '/js/courier.js', '/js/jquery.jqplugin.js', '/js/jquery.the-modal.js');
            $this->template->css_array    = $css_array;
            $this->template->script_array = $script_array;
            $this->template->access_array = array(
                'zakaz' => true,
                'curier'=> true,
                'menu'  => true,
                'report'=> false,
                'manage' => true,
                );
            if($auth->logged_in("admin"))
                $this->template->access_array['report'] = true;

        }
        
        public function action_index()
	{
            $id = Arr::get($_POST, 'id', 0);
            if($id > 0)
            {
                $zakaz = ORM::factory("Zakaz", $id);
                if(isset ($_POST['success']))
                    $zakaz->state = "success";
                if(isset ($_POST['cansel']))
                    $zakaz->state = "cansel";
                if(isset ($_POST['unsuccess']))
                    $zakaz->state = "unsuccess";
                
                $zakaz->date_fact_dost = date('Y-m-d H:i:s', time());
                $zakaz->save();
            }
            $mainView = View::factory('courier');
            
            $zakazes_collected = ORM::factory("Zakaz")
                ->where('state', '=', 'collected')
                ->order_by('date_dostavka')
                ->find_all();
            
            $mainView->zakazes_collected = $zakazes_collected;
            $this->template->content = $mainView;
	}
        
} // End Welcome
