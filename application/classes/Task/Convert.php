<?php defined('SYSPATH') or die('No direct script access.');

class Task_Convert extends Minion_Task {

	protected function _execute(array $params)
    {
        $a = new Controller_Convert(NULL);
	$a->action_index();
    }

}
