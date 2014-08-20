<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Sborka extends Controller {

	public function action_index()
	{
			$auth = Auth::instance();
            if(!$auth->logged_in('admin') && !$auth->logged_in("sborschik"))
                return Controller::redirect('/');
            
            $mainView = View::factory('sborka');
            
            echo $mainView->render();
	}
        
	public function action_zakaz()
	{
		$mainView = View::factory('sborkaZakaz');
		
		$id_zakaz = Arr::get($_POST, 'id', 0);
		$zakaz_pos_done = ORM::factory("ZakazPosition")
			->where('id_zakaz', '=', $id_zakaz)
			->and_where('state', '=', 'done')
			->find_all();
		
		$zakaz_pos_not_done = ORM::factory("ZakazPosition")
			->where('id_zakaz', '=', $id_zakaz)
			->and_where_open()
			->where('state', '=', 'not_done')
			->or_where('state', '=', 'in_process')
			->where_close()
			->find_all();

		$zakaz_pos_collected = ORM::factory("ZakazPosition")
			->where('id_zakaz', '=', $id_zakaz)
			->and_where('state', '=', 'collected')
			->find_all();

		$zakaz = ORM::factory("Zakaz")
			->where('id', '=', $id_zakaz)
			->find();
		
		$mainView->done = $zakaz_pos_done;
		$mainView->not_done = $zakaz_pos_not_done;
		$mainView->collected = $zakaz_pos_collected;
		$mainView->id_zakaz = $id_zakaz;
				
		$mainView->koment = $zakaz->koment;
		echo $mainView->render(); 
	}

} // End Welcome
