<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Manage extends Controller
{
    private $mainTpl;
    public function before() {
        parent::before();
        
        $auth = Auth::instance();
        
        if (!$auth->logged_in('admin'))
        {
            $this->redirect(URL::site('/'));
        }
        
        $this->mainTpl = View::factory('template_main');
        
        $this->mainTpl->css_array = array('/css/bootstrap.css', '/css/bootstrap-responsive.min.css');
        $this->mainTpl->script_array = array('/js/jquery.js', '/js/bootstrap.min.js');
    }
    
    public function action_index()
    {
        
    }
    
    public function action_delivery_boy()
    {
        $this->mainTpl->title= "Управление Курьерами";
        $this->mainTpl->access_array=array('zakaz'=>1, 'curier'=>1, 'menu'=>1, 'report'=>1, 'manage'=>1);
        
        $this->mainTpl->script_array[] = "/js/script_delivery_boy.js";
        $show_blocked = Arr::get($_POST, 'sb', False);
        
        if ($show_blocked == 1) {
            $couriers = ORM::factory('Courier')
                    ->find_all();            
        }
        else {
            $couriers = ORM::factory('Courier')
                    ->where('status', 'LIKE', 'active')
                    ->find_all();           
        }

        $table = null;
        
        
        foreach($couriers as $courier)
        {
            $data['id'] = $courier->id;
            $data['name'] = $courier->name;
            $data['password'] = $courier->password;
            
            //Обновляем пароли если прощло больше суток
            if (time() > strtotime($courier->timestamp)+Date::DAY)
            {
                $newPass = rand(11111, 99999);
                $courier->password = $newPass;
                $courier->timestamp = date('Y-m-d H:i:s', time());
                $courier->save();
                
                $data['password'] = $newPass;
            }
            
            $table .= View::factory('manage/table_delivery_boy', $data);
        }
        
        $this->mainTpl->content = View::factory('manage/delivery_boy', array('table' => $table));
    }
    
    public function action_users()
    {
        $this->mainTpl->title= "Управление пользователями";
        $this->mainTpl->access_array=array('zakaz'=>1, 'curier'=>1, 'menu'=>1, 'report'=>1, 'manage'=>1);
        
        $this->mainTpl->script_array[] = "/js/script_users.js";
        
        $table = null;
        
        $this->mainTpl->content = View::factory('manage/users', array('table' => $table));
    }

    public function after()
    {
        $this->response->body($this->mainTpl->render());
    }
}
