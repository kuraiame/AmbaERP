<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Abc extends Controller
{
    private $abcView;
    private $_session;
    public function before()
    {
        parent::before();
        
        $this->abcView = View::factory('abc');
        $this->_session = Session::instance();
        
        if ($_POST)
        {
            $curValid = Validation::factory($_POST);
            
            $curValid->rules('id', array(
                array('not_empty'),
                array('min_length', array(':value', 1)),
                array('max_length', array(':value', 4)),
                array('numeric')
            ));
            if ($curValid->check())
            {
                $cur = ORM::factory('Courier');

                $cur->where_open()
                        ->where('id', '=', Arr::get($_POST, 'id'))
                        ->where('password', '=', Arr::get($_POST, 'password'))
                        ->where_close()
                        ->find();
                if ($cur->loaded())
                {
                    $this->_session->set('auth', 1);
                    $this->_session->set('id', $cur->id);
                    $this->redirect(URL::site('/abc/work'));
                } else {
                    $this->abcView->errors = 'Такой пользователь не обнаружен';
                }
            } else {
                $this->abcView->errors = 'Не правильно заполнены поля';
            }
        }
    }
    
    public function action_index()
    {
        if ($this->_session->get('auth') == 1)
            $this->redirect(URL::site('/abc/work'));
        $this->response->body($this->abcView->render());
    }
    
    public function action_work()
    {
        if ($this->_session->get('auth') !== 1)
            $this->redirect(URL::site('/abc'));
        
        $workView = View::factory('work');
        
        
        
        $this->response->body($workView->render());
    }
    
    public function action_spy()
    {
        if ($this->request->is_ajax())
        {
            if ($this->_session->get('auth') === 1)
            {
                $coord = ORM::factory('Coord');
                $coord->where('id_courier', '=', $this->_session->get('id'))->find();
                
                $lat = Arr::get($_POST, 'latitude', NULL);
                $lon = Arr::get($_POST, 'longitude', NULL);
                
                if(!$coord->loaded())
                {
                    $coord->id_courier = $this->_session->get('id');
                }
                
                $coord->latitude = $lat;
                $coord->longtitude= $lon;
                $coord->tstmp = date('Y-m-d H:i:s', time());
                
                $coord->save();
            }
        }
    }
}
