<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Service extends Controller
{
    public function action_promo() {
        $id_action = Arr::get($_GET, 'id_action', 1);
        $state = Arr::get($_GET, 'state', 'generated');
        $count = Arr::get($_GET, 'count', 10);
        $type = Arr::get($_GET, 'type', 'one');
        Service::generate($id_action, $state, $count, $type);
    }
}