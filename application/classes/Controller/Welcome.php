<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {

    public function before()
    {
        if ($_POST && $this->request->is_ajax()){
            
                $coord = ORM::factory('Coord');
                
                $coord->latitude = Arr::get($_POST, 'latitude');
                $coord->longtitude = Arr::get($_POST, 'longitude');
                
                $coord->save();
        }
    }

    public function action_index()
    {
            $this->response->body(View::factory('nav')->render());
    }
    
    public function action_sms()
    {
        $sms = Smspilot::factory();
        
        echo $sms->balance();
    }

} // End Welcome
