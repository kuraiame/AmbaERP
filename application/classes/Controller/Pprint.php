<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Pprint extends Controller {
    
    public function action_index() {
        $items[] = array(
            'name' => 'Пицца Пеперони ХУЙ',
            'count' => '32',
            'summa' => '3 р.'
        );
       
        KohanaPDF::createCheck($items, '3 р.', 'ул. Фокина 150ц', '666');
    }
    public function action_makefont()
    {
	require_once dirname(__FILE__).'/../FPDF/font/makefont/makefont.php';
	MakeFont(dirname(__FILE__).'/../FPDF/AFM/aricyr.ttf',dirname(__FILE__).'/../FPDF/AFM/aricyr.afm', 'cp1251'); 
    }
}
