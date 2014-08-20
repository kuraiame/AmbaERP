<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Контроллер для работы с кассовым аппаратом
 */
class Controller_Kassa extends Controller {

    public $serial;
    
    public function before() 
    {
        $this->serial = new Serial();
                
        
    }    
    
    public function action_print()
    {
        //Подключение к последовательному порту через USB 
        $this->serial->deviceSet ('/dev/ttyUSB0');
        
        // Настройка соединения
        $this->serial->confBaudRate(4800); // скорость обмена 9600 бод
        $this->serial->confParity("none");  // проверка на четность отсутствует
        $this->serial->confCharacterLength(8); // длина 8 бит
        $this->serial->confStopBits(1);  // 1 стоп бит
        $this->serial->confFlowControl("none"); 
        
        $this->serial->deviceOpen();
        
        $this->serial->sendMessage('0x02 0x01 0xFC 0xFD');
        
        $this->serial->deviceClose();
    }
    
    

} // End Welcome
