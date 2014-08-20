<?php defined('SYSPATH') or die('No direct script access.');
#Hui Pizda Jigurda
class Controller_Zakaz extends Controller_Template 
{

    public $template = 'template_main';
    
    public function before() {
        parent::before();
        
        $auth = Auth::instance();
        if(!$auth->logged_in('admin') && !$auth->logged_in("manager") && !$auth->logged_in("devochka"))
            return Controller::redirect('/');
        
        $this->template->title = "Прием заказов";
        $css_array = array('/css/bootstrap.css', '/css/bootstrap-responsive.min.css');
        $script_array = array('/js/jquery.js', '/js/bootstrap.min.js', '/js/scripts.js');
        $this->template->css_array    = $css_array;
        $this->template->script_array = $script_array;
        $this->template->access_array = array(
            'zakaz' => true,
            'curier'=> true,
            'menu'  => true,
            'report'=> false,
            );
        if($auth->logged_in("admin"))
            $this->template->access_array['report'] = true;
            $this->template->access_array['manage'] = true;
    }

    public function action_index()
	{
            
            
            // вывод меню
            $pizzas = DB::query(Database::SELECT, 'SELECT DISTINCT name FROM menus WHERE category="pizza"')->execute()->as_array();
            
            $pizza_list = "";
            foreach($pizzas as $pizza)
            {
                $sizes = ORM::factory('Menu')->where('name', '=', $pizza['name'])->where('category', '=', 'pizza')->find_all()->as_array();
                $size_list = "";
                foreach ($sizes as $size)
                    $size_list .= ', "'.$size->size.'" : {"'.$size->price.'" : ["'.$size->id.'"]}';
                $size_list[0] = " ";
				//Выводим только используемые позиции
				if ($size->state == 'use')
					$pizza_list .= ',"'.addslashes($pizza['name']).'" : {'.$size_list.'}';   
            }
                
            $pizza_list[0] = " ";
            
            $souses = ORM::factory('Menu')->where('category', '=', 'sous')->find_all()->as_array();

            $sous_list = "";
            foreach($souses as $sous)
                $sous_list .= ',"'.addslashes($sous->name).'" : {"'.$sous->price.'" : ["'.$sous->id.'"]}';
            $sous_list[0] = " ";
            
            $napitoks = DB::query(Database::SELECT, 'SELECT DISTINCT name FROM menus WHERE category="napitok"')->execute()->as_array();
            
            $napitok_list = "";
            foreach($napitoks as $napitok)
            {
                $sizes = ORM::factory('Menu')->where('name', '=', $napitok['name'])->where('category', '=', 'napitok')->find_all()->as_array();
                $size_list = "";
                foreach ($sizes as $size)
                    $size_list .= ', "'.$size->size.'" : {"'.$size->price.'" : ["'.$size->id.'"]}';
                $size_list[0] = " ";
                $napitok_list .= ',"'.addslashes($napitok['name']).'" : {'.$size_list.'}';   
            }
                
            $napitok_list[0] = " ";

            
            $mainView = View::factory('zakaz');
            $mainView->pizza_list = $pizza_list;
            $mainView->sous_list = $sous_list;
            $mainView->napitok_list = $napitok_list;
            
//            echo $mainView->render();
            $this->template->content = $mainView;
	}
        public function action_test() {
            
        }
        

} // End Welcome
