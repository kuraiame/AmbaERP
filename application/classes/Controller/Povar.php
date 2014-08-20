<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Povar extends Controller {

	public function action_index()
	{
            if(!Auth::instance()->logged_in("povar"))
                return Controller::redirect('/');
            
            $mainView = View::factory('povar');
            
            echo $mainView->render();
	}

} // End Welcome
