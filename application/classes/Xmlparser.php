<?php defined('SYSPATH') or die('No direct script access.');

class Xmlparser extends Kohana
{
    //Vars
    private $xml_parser;
    private $file;
    private $fp;
    private $number_file;


    /**
     * Массив с импортируемыми значениями
     */
    private $need = array(
        'ул','пер','пр-кт'/**,'АО', 'Аобл', 'г', 'край', 'обл',
        'округ', 'Респ', 'Чувашия', 'р-н',
        'п', 'пгт', 'д', 'нп', 'остров', 'с',
        'х', 'ж/д_ст', 'км'*/
        );
    
    /**
     * функция работы с данными
     * 
     * @param type $parser
     * @param string $data 
     */
    public function data($parser, $data)
    {
        // Данные тега
        
    }
    
    
    /**
     *  функция открывающих тегов
     * 
     * @param string $name
     * @param string $attrs
     */
    public function startElement($parser, $name, $attrs)
    {
        // Название и аттрибуты тэга
        $this->attr_parse($attrs, $this->file);

    }
    
    /**
     * функция закрывающих тегов
     * 
     * @param string $name
     */
    public function endElement($parser, $name)
    {
        
        //Имя закрывающегося тэга
    }
    
    
    /**
     * Парсер файлов
     * 
     * @param file $file
     */
    public function parse($file)
    {
        
        $this->file = basename($file);
        preg_match("/^[a-z]{2}_[a-z]+_[a-z0-9_\.-]+([1-8]{1})[XML\.]{4}$/i", basename($file), $this->number_file);
        if (!file_exists('/var/www/omis/log/position' . $this->number_file[1] . '.log'))
        {
            $pos = fopen ('/var/www/omis/log/position' . $this->number_file[1] . '.log', "w");
            fwrite($pos, '0');
            fclose ($pos);
        }
        $this->xml_parser = xml_parser_create();
        xml_parser_set_option($this->xml_parser, XML_OPTION_CASE_FOLDING, true);

        // указываем какие функции будут работать при открытии и закрытии тегов
        xml_set_element_handler($this->xml_parser, array(&$this, "startElement"), array(&$this, "endElement"));

        // указываем функцию для работы с данными
        xml_set_character_data_handler($this->xml_parser, array(&$this, "data"));


        // открываем файл
        $this->fp = fopen($file, "r");
        $pos = fopen ('/var/www/omis/log/position' . $this->number_file[1] . '.log', "r");
	$position = fgets ($pos);
        if ($position < 1073740000) 
        {
                
	    fseek ($this->fp,(int)$position);

            $perviy_vxod = 1; // флаг для проверки первого входа в файл
            $data = "";  // сюда собираем частями данные из файла и отправляем в разборщик xml

               // проверяем, если это первый вход в файл, то удалим все, что находится до тега <?
               // так как иногда может встретиться мусор до начала XML (корявые редакторы, либо файл получен скриптом с другого сервера)
            if($perviy_vxod)
            {
              $data="<Houses>";
              $perviy_vxod=0;
               if ($position)
               xml_parse($this->xml_parser, $data, feof($this->fp));
              $data = '';
            }
        
            // цикл пока не найден конец файла
            while ( ! feof($this->fp) and $this->fp)
            {
                $simvol = fgetc($this->fp); // читаем один символ из файла
                $data .= $simvol; // добавляем этот символ к данным для отправки

                // если символ не завершающий тег, то вернемся к началу цикла и добавим еще один символ к данным, и так до тех пор, пока не будет найден закрывающий тег
                if($simvol != '>')
                {
                    continue;
                }
                // если закрывающий тег был найден, теперь отправим эти собранные данные в обработку

                if ($data[0] != '<')
                {
                    $data="<Houses>";
                    xml_parse($this->xml_parser, $data, feof($this->fp));
                    $data = '';
                    continue;
                }
                
                
			
              if (!xml_parse($this->xml_parser, $data, feof($this->fp)))
              {

                   // здесь можно обработать и получить ошибки на валидность...
                   // как только встретится ошибка, разбор прекращается
                   echo "<br>XML Error: ".xml_error_string(xml_get_error_code($this->xml_parser));
                   echo " at line ".xml_get_current_line_number($this->xml_parser);
                   echo $data;
                   break;
               }
			
               // после разбора скидываем собранные данные для следующего шага цикла.
              $data="";
           }
            fclose($this->fp);
            fclose($pos);
           xml_parser_free($this->xml_parser);
        }
    }
    
    /**
     * Функция которая распилит большой файл на несколько
     * 
     */
    
    public function split($file)
    {
        $this->file = basename($file);
        
        $this->fp = fopen($file, "r");
        $data = ""; //переменная для резервирования части данных
        $file_num = 1; //
        $counter=0; //счетчик на ограничения длины файла
        
        $file_split = fopen("/var/fias.inactive/AS_HOUSE_20130318_c7a2bebe-4981-404a-89c8-9374f903b53c" . $file_num .  ".XML", "w");
        
        while ( ! feof($this->fp) and $this->fp)
        {
            $counter++;
            $simvol = fgetc($this->fp); // читаем один символ из файла
            fwrite($file_split, $simvol);
            
            if ($counter > 1073741824)
            {
                $data .= $simvol;
            }
            
            if ($counter == 1073742324)
            {
                fclose ($file_split);
                $file_num++;
                $file_split = fopen("/var/fias.inactive/AS_HOUSE_20130318_c7a2bebe-4981-404a-89c8-9374f903b53c" . $file_num .  ".XML", "w");
                fwrite($file_split, $data);
                $data = "";
                $counter = 0;
                
            }
        }
        fclose ($file_split);
        fclose ($file);
    }


    
    /**
     * Функция которая будет все делать
     */ 
    public function readxml($directory)
    {
        $directory = APPPATH.$directory;
        
        if (!is_dir($directory))
            throw new Kohana_Exception("Необходимо указать директорию с файлами ФИАС.");
        
        $sdir = scandir($directory);

        
        foreach ($sdir as $filename)
        {
            if (is_file($directory.'/'.$filename))
            {
                $this->parse($directory.'/'.$filename); // парсим
                //$this->split($directory.'/'.$filename); // режем
            }
        }
        
    }
    
    public function attr_parse($attrs, $file = NULL)
    {
        //Файл не должен быть пустым. Иначе нихуя не выйдет.
        if ($file === NULL)
            throw new Kohana_Exception('You must specify a file!');
        
        //Определяем имя файла на основании которого выбираем нужный обработчик
        preg_match("/^[a-z]{2}_([a-z]+)_[a-z0-9_\.-]+$/i", $file, $name);
        
        //Переводим все символы в верхний регистр
        $name = strtoupper($name[1]);
        
        //Делаем выбор
        switch ($name)
        {
          /*  case 'ADDROBJ':
                $this->addrobj($attrs);
            break; 
        
            case 'SOCRBASE':
                $this->socr($attrs);
            break; */
			
			case 'HOUSE':
                $this->house($attrs);
            break;
        
          /*  default:
                throw new Kohana_Exception('Не найден обработчик для данных.');
            break; */
        
        }
    }
    
    
    
    /**
     * Обработчик файла с адресами
     * 
     * @param type $attrs
     */
    private function addrobj($attrs)
    {
        if (!is_array($attrs))
            throw new Kohana_Exception('Attributes must be array!');
        
        $offname   = & $attrs['OFFNAME'];   // Название
        
        // Если вдруг нет официального названия, заимствуем формальное
        if ($offname === NULL)
        {
            $offname = & $attrs['FORMALNAME'];
        }
        
        $shortname = & $attrs['SHORTNAME']; // Тип места
 
        $areacode   = & $attrs['AREACODE'];   // Код района
        $citycode   = & $attrs['CITYCODE'];   // Код города
        $regioncode = & $attrs['REGIONCODE']; // Код региона
        $placecode  = & $attrs['PLACECODE'];  // Код населенного пункта
        $streetcode = & $attrs['STREETCODE']; // Код улицы
        $actstatus  = & $attrs['ACTSTATUS'];  // Статус актуальности
      //$aoid       = & $attrs['AOID'];  
        $aoguid     = & $attrs['AOGUID']; 	  // Глобальный уникальный идентификатор адресного объекта 
        $parentguid = & $attrs['PARENTGUID']; // id "родителя" (aoguid родительской записи)
        
		
		//поиск улиц города владивосток по родительскому id
        if ($parentguid == '7b6de6a5-86d0-4735-b11a-499081111af8') 
        {

            // Это регион
            
            // В справочнике много не нужного, поэтому вставляем только нужные
            // и актуальные наименования
			
            $query=DB::select('id')->from('socrs')->where('socr_name', '=', $shortname)->as_assoc()->execute();



            if ($query)
            {
                $id_socr=$query->as_array('id');

                if ($actstatus)
                {
                    DB::insert()
                        ->table('streets')
                        ->columns(array('id', 'id_socr', 'name', 'aoguid'))
                        ->values(array('', $id_socr, $offname, $aoguid))
                        ->execute();
                }	
            }
        }
		
        elseif ((int) $regioncode != 0 && (int) $areacode != 0 && (int) $citycode == 0 && (int) $placecode == 0)
        {
            // Это район
            
            // В справочнике много не нужного, поэтому вставляем только нужные
            // и актуальные наименования
            if (in_array($shortname, $this->need) && $actstatus)
            {
                DB::insert()
                        ->table('areas')
                        ->columns(array('id', 'name', 'region_id', 'socr', 'area_id'))
                        ->values(array('', $offname, $regioncode, $shortname, $areacode))
                        ->execute();
            }
        }
        else
        {
            // Это населенные пункты
            
            // В справочнике много не нужного, поэтому вставляем только нужные
            // и актуальные наименования
            if (in_array($shortname, $this->need) && $actstatus)
            {
                DB::insert()
                    ->table('cities')
                    ->columns(array('id', 'region_id', 'name', 'socr', 'area_id'))
                    ->values(array('', $regioncode, $offname, $shortname, $areacode))
                    ->execute();
            }
        } 
    }
    
    /**
     * Обработчик сокращений
     * 
     * @param array $attrs 
     */
    private function socr($attrs)
    {
        if (!is_array($attrs))
            throw new Kohana_Exception('Attributes must be array!');
        
        $fullname = & $attrs['SOCRNAME'];
        $scname   = & $attrs['SCNAME'];
        
        // Только не пустые
        if ($fullname !== NULL && $scname !== NULL)
        {
            
                //Проверка уникальности значения
                $int = DB::select()
                        ->from('socrs')
                        ->where('socr_name', '=', $scname)
                        ->execute()
                        ->count();
                
                if (!$int)
                {
                    DB::insert()
                            ->table('socrs')
                            ->columns(array('id', 'name', 'socr_name'))
                            ->values(array('', $fullname, $scname))
                            ->execute();
                }
            
        }
    }
    
	private $i = 0;
	private function house($attrs)
    {
        if (!is_array($attrs))
            throw new Kohana_Exception('Attributes must be array!');
        
        $aoguid   = & $attrs['AOGUID'];   // GUID записи родительского обьекта
        $housenum = & $attrs['HOUSENUM'];   // Номер 
		
        
	
		$query=DB::select('id','id_socr','name')->from('streets')->where('aoguid', '=', $aoguid)->as_assoc()->execute();
		
		$query=$query->as_array();
			
		
			
        if ($query)
        {
		//var_dump($query); die();
		
			$str_id=$query[0]['id'];
			$id_socr=$query[0]['id_socr'];
			$str_name=$query[0]['name'];
			$socr=DB::select('name')->from('socrs')->where('id', '=', $id_socr)->as_assoc()->execute();
			$socr=$socr[0]['name'];
			
                        //Проверяем наличие записи в базе
                        $check_house_in_db = DB::select('id')
                                ->from('houses')
                                ->where_open()
                                ->where('id_street', '=', $str_id)
                                ->where('name', '=', $housenum)
                                ->where_close()
                                ->as_assoc()
                                ->execute();
                        $check_house_in_db = $check_house_in_db->as_array();
                        //Если запись в базе найдена, выходим из функции и ждем следующего адреса
                        if ($check_house_in_db)
                        {
                            //Удаляем ненужные результаты запросов, чтобы они не забивали память
							unset($check_house_in_db, $socr, $query, $attrs);
							return;
                        }
                        
                        
			$yandex = @file('http://geocode-maps.yandex.ru/1.x/?geocode=Владивосток+' . $socr . '+' . $str_name . '+' . $housenum);
			
			while (($this->proverka($yandex)) or (!$yandex))
			{
                            $yandex = @file('http://geocode-maps.yandex.ru/1.x/?geocode=Владивосток+' . $socr . '+' . $str_name . '+' . $housenum);
                            sleep(3);
			}
			
                        try
                        {
                            foreach ($yandex as $cord)
				if (preg_match("/\<pos\>(\d*\.\d*) (\d*\.\d*)\<\/pos\>/i", $cord, $coord))
					break;
                        }
                        catch (Exception $e) 
                        {
                            var_dump ($yandex);
                            echo $e->getMessage();
                        }
 
			
			
			
			if (!$housenum)
			return;
                        
                        if (!count($coord)) {
                            var_dump($coord); echo chr(10);
                            var_dump($yandex); echo chr(10);
                            print 'http://geocode-maps.yandex.ru/1.x/?geocode=Владивосток+' . $socr . '+' . $str_name . '+' . $housenum;
                            die();
                        }
			DB::insert()
                            ->table('houses')
                            ->columns(array('id', 'id_street', 'id_socr', 'name', 'lon', 'lat'))
                            ->values(array('', $str_id, $id_socr, $housenum, $coord[1], $coord[2]))
                            ->execute();
			
			//die;
			//Удаляем ненужные результаты запросов, чтобы они не забивали память
			unset($check_house_in_db, $socr, $query, $attrs);
			$pos = fopen('/var/www/omis/log/position' . $this->number_file[1] . '.log', "w");
			if (fwrite($pos, ftell($this->fp)) === FALSE) {
				echo "АХТУНГ АХТУНГ!!! БЕДА БЕДА!!! Не могу произвести запись в файл";
			}
			fclose($pos);
		}
        
      
    }
    
    /**
     *  функция проверки
     */
    private function proverka($attrs)
    {
        return preg_match("/\w*(error)\w*/i", $attrs[1]);	
    }
}