<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Spy extends Controller
{
    private $_view;
    private $_session;
    public function before()
    {
        
        $this->_session = Session::instance();
        
    }
    
    public function action_index()
    {
        $this->_view = View::factory('spy');
        
        if ($_POST)
        {
            $orderValid = Validation::factory($_POST);
            
            $orderValid->rules('order_id', array(
                array('numeric'),
            ));
            
            if ($orderValid->check())
            {
                $order = ORM::factory('Zakaz');
                
                $order->where('id', '=', Arr::get($_POST, 'order_id'))->find();
                
                if ($order->loaded())
                {
                    //Заказ найден
                    if ($order->state !== 'collected' && $order->id_courier)
                    {
                        $this->_session->set('id_zakaz', $order->id);
                        $this->redirect(URL::site('/spy/ok'));
                    } else {
                        //Заказ либо еще не готов, либо уже доставлен
                    }
                } else {
                    //Заказ не найден
                }
            } else {
                //Не пройдена валидация
            }
        }
    }
    
    public function action_ok()
    {
        $this->_view = View::factory('spy_map');
    }
    
    
    public function after()
    {
        $this->response->body($this->_view->render());
    }
}
