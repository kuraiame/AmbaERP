<?php defined('SYSPATH') or die('No direct script access.');

class Task_Parser extends Minion_Task {

	protected function _execute(array $params)
    {
        $a = new Xmlparser;
	$a->readxml('../../../../../../../../../../../var/fias.inactive');
    }

}
