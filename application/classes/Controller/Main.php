<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Main extends Controller {

    public function action_index()
	{
            $auth = Auth::instance();
            //$auth->force_login("margo");
                    // Проверям, вдруг пользователь уже зашел
            //$auth->login("margo", "margo");
            
            if ($_SERVER['REMOTE_ADDR'] == '192.168.1.55')
                $auth->force_login("vincenzo");
            
            if($auth->logged_in("devochka"))
                return Controller::redirect('/zakaz');
            if($auth->logged_in("povar"))
                return Controller::redirect('/povar');
            if($auth->logged_in("sborschik"))
                return Controller::redirect('/sborka');
            if($auth->logged_in("courier"))
                return Controller::redirect('/courier');
            if($auth->logged_in("admin"))
                return Controller::redirect('/reports');
            if($auth->logged_in("manager"))
                return Controller::redirect('/reports');
            if ($auth->logged_in('root'))
                return $this->redirect(URL::site('/root'));

            $error = '';
            // Если же пользователь не зашел, но данные на страницу пришли, то:
            if ($_POST)
            {
                // Создаем переменную, отвечающую за связь с моделью данных User
                $user = ORM::factory('User');
                // в $status помещаем результат функции login
                $status = Auth::instance()->login($_POST['username'], $_POST['password'], true);
                // Если логин успешен, то
                if ($status)
                {
                    // Отправляем пользователя на его страницу
                    Controller::redirect('/');
                }
                else
                {
                    // Иначе ничего не получилось, пишем failed
                    $error = '<div class="alert">Не верный логин или пароль. Попробуйте еще раз.</div>';
                }
            }
            // Грузим view логина
          
            $mainView = View::factory('login');
            $mainView->error = $error;
            
            echo $mainView->render();
	}
   // Регистрация пользователей
    public function action_register()
    {
    // Если есть данные, присланные методом POST
    if ($_POST)
        {
            // Создаем переменную, отвечающую за связь с моделью данных User
            $model = ORM::factory('User');
            // Вносим в эту переменную значения, переданные из POST
            $model->values(array(
               'username' => $_POST['username'],
               'email' => $_POST['email'],
               'password' => $_POST['password'],
               'password_confirm' => $_POST['password_confirm'],
            ));
            try
            {
                // Пытаемся сохранить пользователя (то есть, добавить в базу)
                $model->save();
                // Назначаем ему роли
                $model->add('roles', ORM::factory('Role')->where('name', '=', 'login')->find());
                // И отправляем его на страницу пользователя
                    $this->response->body('hello, Index!');
            }
            catch (ORM_Validation_Exception $e)
            {
                // Это если возникли какие-то ошибки
                echo $e;
            }
        }
        // Загрузка формы логина
        $this->response->body(View::factory('register'));
    }

    // Просмотр пользовательских данных
    public function action_view()
    {
    // Проверям, залогинен ли пользователь
    if(Auth::instance()->logged_in())
            {
            // Если да, то здороваемся и предлагаем выйти. Это можно было и в виде view реализовать
            echo 'Добро пожаловать, '.Auth::instance()->get_user()->username.'!';
            echo '<br /><a href=\'logout\'>logout</a>';
            }
    else
        {
            // А если он не залогинен, отправляем логиниться
            return $this->request->redirect('member/login');
        }

    }

    // Метод разлогивания
    public function action_logout()
    {
    // Пытаемся разлогиниться
    if (Auth::instance()->logout())
        {
            // Если получается, то предлагаем снова залогиниться
            return Controller::redirect('/');
        }
    else
        {
            // А иначе - пишем что не удалось
            echo 'fail logout';
        }
    }
    
    public function action_phpinfo() {
        phpinfo();
    }

} // End Welcome
