<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Root extends Controller
{
    public function before()
    {
        parent::before();
        
        $auth = Auth::instance();
        
         if (!$auth->logged_in('root')) {
             $this->redirect(URL::site('/'));
         }
    }
    
    public function action_index()
    {
        
    }
    
    private function action_add() 
    {
        $user = ORM::factory('User');

        $arr = array(
            'username' => 'root',
            'password' => '',
            'password_confirm' => '',
            'email' => '2745590@gmail.com');
        $user->create_user($arr, array('username', 'password', 'email'));

        //name = название роли
        $user->add('roles', ORM::factory('Role')->where('name', '=', 'login')->find());
    }
    
    public function action_pass_cur()
    {
        $id = Arr::get($_GET, 'id', NULL);
        $pass = Arr::get($_GET, 'pass', NULL);
        
        $cur = ORM::factory('Courier', $id);
        
        if ($cur->loaded())
        {
            $cur->password = $pass;

            $cur->save();
        }
    }
}
