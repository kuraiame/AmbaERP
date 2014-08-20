<?php defined('SYSPATH') or die('No direct script access.');

class Task_Gen extends Minion_Task {

    protected function _execute(array $params)
        {
            Generate::generate_code();
        }

}
