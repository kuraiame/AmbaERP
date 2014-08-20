<?php defined('SYSPATH') or die('No direct script access.');

class KohanaPDF extends Kohana {
    
    private static $stringWidth;
//    public static function createCheck($items, $itogo, $adress, $nomer, $date, $sale, $phone) {
    public static function createCheck($items, $zakaz) {
        define('FPDF_FONTPATH',dirname(__FILE__).'/FPDF/font/');
        require_once dirname(__FILE__).'/FPDF/fpdf.php';
        $fpdf = new FPDF;
        $fpdf->AddPage(); 
        //Шапка
        $fpdf->AddFont('ArialCyrMT', '', 'aricyr.php');
        $fpdf->SetFont('ArialCyrMT','', 9);
	$fpdf->Image(dirname(__FILE__).'/FPDF/check/logo.jpg', 10, 1, 30);
        $fpdf->Cell(80, 4, self::toCP1251('ООО "Пицца Ом"  ИНН: 2540189749'), 30, 5, '');
        //$fpdf->Ln();
        //$fpdf->Cell(80, 4, self::toCP1251('ИНН: 2540189749'));
        //$fpdf->Ln();
        $fpdf->Cell(80, 4, self::toCP1251('Адрес: г. Владивосток, Мыс Чумака 1а'));
        $fpdf->Ln();
        $fpdf->Cell(80, 4);
        $fpdf->Ln();
        $fpdf->Cell(80, 4, self::toCP1251('Адрес доставки:'));
        $fpdf->Ln();
        $fpdf->Cell(50, 4, self::toCP1251(Service::get_street($zakaz->id)), 0, 0);
        $fpdf->Ln();
        $fpdf->Cell(50, 4, self::toCP1251(Service::get_house($zakaz->id)), 0, 0);
        $fpdf->Ln();
        $fpdf->Cell(80, 4, self::toCP1251('Телефон клиента - ' . $zakaz->klient->tel), 0, 0);
        $fpdf->Ln();
        $fpdf->Cell(80, 4, self::toCP1251('Комментарий - ' . $zakaz->koment), 0, 0);
        $fpdf->Ln();
        $fpdf->Ln();
        $fpdf->Cell(50, 4, self::toCP1251('Заказ № ' . $zakaz->nomer), 0, 0);
        $fpdf->Cell(27, 4, self::toCP1251(Service::get_data($zakaz->date_zakaz)), 0, 0, 'R');
        $fpdf->Ln();
        $fpdf->Line(10, 45, 87, 45);
        $fpdf->Ln();
        $fpdf->Cell(5, 4, self::toCP1251('№'), 'B', 0);
        $fpdf->Cell(50, 4, self::toCP1251('Наименование'), 'B', 0);
        $fpdf->Cell(10, 4, self::toCP1251('К-во'), 'B', 0);
        $fpdf->Cell(12, 4, self::toCP1251("Сумма"), 'B', 0);
        $fpdf->Ln();
        $fpdf->Ln();
        
       
        //Позиции
        for($i=0;$i<count($items);$i++)
        { 
            $fpdf->Cell(5, 4, self::toCP1251($i+1 . '.'), 0, 0);
            if (strlen($items[$i]['name'])<=1000) 
            {
                $fpdf->Cell(50, 4, self::toCP1251($items[$i]['name']), 0, 0);
                $fpdf->Cell(10, 4, self::toCP1251($items[$i]['count'] . ' шт.'), 0, 0);
                $fpdf->Cell(12, 4, self::toCP1251($items[$i]['summa'] . ' р.'), 0, 0);
                $fpdf->Ln();
            } 
            else
            {
                $begin_caption = substr($items[$i]['name'], 0, 30);
                $end_caption = substr($items[$i]['name'], 30);
                $fpdf->Cell(50, 4, self::toCP1251($begin_caption), 0, 0);
                $fpdf->Cell(10, 4, self::toCP1251($items[$i]['count'] . ' шт.'), 0, 0);
                $fpdf->Cell(12, 4, self::toCP1251($items[$i]['summa'] . ' р.'), 0, 0);
                $fpdf->Ln();
                do {
                    $begin_caption = substr($end_caption, 0, 30);
                    $end_caption = substr($end_caption, 30);
                    $fpdf->Cell(5, 4);
                    $fpdf->Cell(50, 4, self::toCP1251($begin_caption), 0, 0);
                    $fpdf->Ln();
                } while (strlen($end_caption)>=30);
            };
        }
        
        
//        $fpdf->Cell(5, 4, self::toCP1251('1.'), 0, 0);
//        $fpdf->Cell(50, 4, self::toCP1251('Пицца "Пепперони" 30см.'), 0, 0);
//        $fpdf->Cell(10, 4, self::toCP1251('1 шт.'), 0, 0);
//        $fpdf->Cell(12, 4, self::toCP1251("390 р."), 0, 0);
//        $fpdf->Ln();
//        $fpdf->Cell(5, 4, self::toCP1251('2.'), 0, 0);
//        $fpdf->Cell(50, 4, self::toCP1251('Соус сырный'), 0, 0);
//        $fpdf->Cell(10, 4, self::toCP1251('1 шт.'), 0, 0);
//        $fpdf->Cell(12, 4, self::toCP1251("0 р."), 0, 0);
//        $fpdf->Ln();
//        $fpdf->Cell(5, 4, self::toCP1251('3.'), 0, 0);
//        $fpdf->Cell(50, 4, self::toCP1251('Кока-кола 0,5 л.'), 0, 0);
//        $fpdf->Cell(10, 4, self::toCP1251('2 шт.'), 0, 0);
//        $fpdf->Cell(12, 4, self::toCP1251("100 р."), 0, 0);
//        $fpdf->Ln();
//        $fpdf->Cell(5, 4, self::toCP1251('4.'), 0, 0);
//        $fpdf->Cell(50, 4, self::toCP1251('Пицца "Гавайская" 30см.'), 0, 0);
//        $fpdf->Cell(10, 4, self::toCP1251('3 шт.'), 0, 0);
//        $fpdf->Cell(12, 4, self::toCP1251("1560 р."), 0, 0);
//        $fpdf->Ln();
//        $fpdf->Cell(5, 4, self::toCP1251('5.'), 0, 0);
//        $fpdf->Cell(50, 4, self::toCP1251('Соус оригинальный'), 0, 0);
//        $fpdf->Cell(10, 4, self::toCP1251('3 шт.'), 0, 0);
//        $fpdf->Cell(12, 4, self::toCP1251("0 р."), 0, 0);
//        $fpdf->Ln();
        
        
        //Подвал
        $fpdf->Ln(); 
        if ($zakaz->sale != 0)
        {
            $fpdf->SetFont('ArialCyrMT','', 10);
            $fpdf->Cell(64, 4, self::toCP1251('СКИДКА'));
            $fpdf->SetFont('ArialCyrMT','', 9);
            $fpdf->Cell(13, 4, self::toCP1251($zakaz->sale . ' р.'));
            $fpdf->Ln();
        };
        if($zakaz->sdacha == 0)
        {
            $fpdf->SetFont('ArialCyrMT','', 11);
            $fpdf->Cell(64, 4, self::toCP1251('ИТОГО'), 'B');
            $fpdf->SetFont('ArialCyrMT','', 9);
            $fpdf->Cell(13, 4, self::toCP1251($zakaz->summa . ' р.'), 'B');
            $fpdf->Ln();
        }
        else
        {
            $fpdf->SetFont('ArialCyrMT','', 11);
            $fpdf->Cell(64, 4, self::toCP1251('ИТОГО'));
            $fpdf->SetFont('ArialCyrMT','', 9);
            $fpdf->Cell(13, 4, self::toCP1251($zakaz->summa . ' р.'));
            $fpdf->Ln();
            $fpdf->SetFont('ArialCyrMT','', 10);
            $fpdf->Cell(64, 4, self::toCP1251('СДАЧА'), 'B');
            $fpdf->SetFont('ArialCyrMT','', 9);
            $fpdf->Cell(13, 4, self::toCP1251($zakaz->sdacha - $zakaz->summa . ' р.'), 'B');
            $fpdf->Ln();
        }

        $fpdf->Cell(80, 4, self::toCP1251('Спасибо за покупку. Мы рады Вам всегда!'), 0);
        $fpdf->Ln();
        $fpdf->Cell(80, 4, self::toCP1251('Телефон бесплатной доставки 20-77-002.'), 0);
        //Вывод
//        $fpdf->Output('/var/Dropbox/OmIS/check' . $zakaz->nomer . '.pdf', FALSE);
        $fpdf->Output('/tmp/check' . $zakaz->nomer . '.pdf', FALSE);
        system('lp -d posiflex /tmp/check' . $zakaz->nomer . '.pdf');// rm /tmp/check' . $zakaz->nomer . '.pdf');
    }
    
    /**
     * 
     * Возвращает строку в кодировке WINDOWS-1251
     */
    public static function toCP1251($string) {
	return iconv('UTF-8', 'WINDOWS-1251', $string);
    }
    
}
