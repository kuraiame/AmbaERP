<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Menu extends Controller_Template {

        public $template = 'template_main';
        
        public function before() {
            parent::before();
            
            $this->template->title = "Меню";
            $css_array = array('/css/bootstrap.css', '/css/bootstrap-responsive.min.css');
            $script_array = array('/js/jquery.js', '/js/bootstrap.min.js', '/js/jquery.jqplugin.js');
            $this->template->css_array    = $css_array;
            $this->template->script_array = $script_array;
            $this->template->access_array = array(
                'zakaz' => true,
                'curier'=> true,
                'menu'  => true,
                'report'=> false,
                );
        }
        
        public function action_index()
	{
            $mainView = View::factory('menu');
            $this->template->content = $mainView;
	}


} // End Welcome
