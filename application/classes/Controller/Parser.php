<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Parser extends Controller {

	public function action_index()
	{
		$a = new Xmlparser;
		$a->readxml('/../../../../../../../../var/fias');
	}

}