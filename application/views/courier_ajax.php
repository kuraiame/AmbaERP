
    <?php
    foreach ($zakazes_collected as $zakaz)
    {
        $enable_button = true;
        if($zakaz->id_courier == 0)
            $enable_button = false;
        
        $state = "";
        $flag_state = false;

        echo '<tr class="info">';

        echo '<td>'.$zakaz->id.'</td>';
        echo '<td>'. Service::get_full_adress($zakaz->id);
			echo Service::get_text_klient_time($zakaz);
            echo Service::get_text_klient_name($zakaz);
            echo Service::get_text_klient_tel($zakaz);
			echo Service::get_text_klient_summ($zakaz);
            echo Service::get_text_klient_sdacha($zakaz);
            echo Service::get_text_klient_koment($zakaz);
			
        echo '</td>';
        echo '<td>Собрано</td>';
        echo '<td id="courier_'.$zakaz->id.'">';
            if($zakaz->id_courier == 0)
                echo '<input id="z_'.$zakaz->id.'" type="text" class="input-xlarge" data-provide="typeahead" data-items="10" placeholder="Курьер" name="courier_input_id"/>';
            else           
                echo '<span >'.$zakaz->courier->id.', '.$zakaz->courier->name.'</span>';
        echo '</td>';
        echo '<td>';
                echo '  <input type="submit" name="success" onClick="change_state('.$zakaz->id.', \'success\')" value="Успешная"';
                if($enable_button == false) echo ' disabled ';
                echo '/>';
                echo '  <input type="submit" name="unsuccess" onClick="open_window('.$zakaz->id.')" value="Просроченная"';
                if($enable_button == false) echo ' disabled ';
                echo '/>';
                echo '  <input type="submit" name="cansel" onClick="change_state('.$zakaz->id.', \'cansel\')" value="Отменить"';
                if($enable_button == false) echo ' disabled ';
                echo '/>';
        echo '</td>';

        echo '</tr>';
    }
    
    foreach ($zakazes_in_process as $zakaz)
    {
        $state = "";
        $flag_state = false;

        echo '<tr class="info">';

        echo '<td>'.$zakaz->id.'</td>';
        echo '<td>'. Service::get_full_adress($zakaz->id);
			echo Service::get_text_klient_time($zakaz);
            echo Service::get_text_klient_name($zakaz);
            echo Service::get_text_klient_tel($zakaz);
			echo Service::get_text_klient_summ($zakaz);
            echo Service::get_text_klient_sdacha($zakaz);
            echo Service::get_text_klient_koment($zakaz);
        echo '</td>';
        echo '<td>В процессе</td>';
        echo '<td>'; //<td id="courier_'.$zakaz->id.'"> - для назначения курьера до сборки заказа
            if($zakaz->id_courier == 0)
                echo '<input id="z_'.$zakaz->id.'" type="text" class="input-xlarge" data-provide="typeahead" data-items="10" placeholder="Курьер" name="courier_input_id"/>';
            else           
                echo $zakaz->courier->id.', '.$zakaz->courier->name;
        echo '</td>';
        echo '<td></td>';

        echo '</tr>';
    }
    
    foreach ($zakazes_end as $zakaz)
    {
        $state = "";
        $flag_state = false;

        echo '<tr ';
        switch ($zakaz->state)
        {
            case 'not_start':   $state = 'Не начато';                                                             break;
            case 'collected':   $state = 'Собрано';                                      echo 'class="info"';     break;
            case 'success':     $state = 'Удачно доставленно';      $flag_state= true;   echo 'class="success"';  break;
            case 'unsuccess':   $state = 'Неудачно доставленно';    $flag_state= true;   echo 'class="warning"';  break;
            case 'in_process':  $state = 'В процессе';                                                            break;
            case 'cansel':      $state = 'Отменен';                                      echo 'class="error"';    break;
        }
        echo '>';

        echo '<td>'.$zakaz->id.'</td>';
        echo '<td>'. Service::get_full_adress($zakaz->id);
			echo Service::get_text_klient_time($zakaz);
            echo Service::get_text_klient_name($zakaz);
            echo Service::get_text_klient_tel($zakaz);
			echo Service::get_text_klient_summ($zakaz);
            echo Service::get_text_klient_sdacha($zakaz);
            echo Service::get_text_klient_koment($zakaz);
        echo '</td>';
        echo '<td>'.$state.'</td>';
        echo '<td>'.$zakaz->courier->id.', '.$zakaz->courier->name.'</td>';
        echo '<td>';
            if($flag_state)
            {
                echo '<form method="POST">';
                echo '  <input type="hidden" name="id" value="'.$zakaz->id.'">';
                echo '  <input type="submit" name="success" value="$"/>';
                echo '</form>';
            }
        echo '</td>';
                //TODO: Просмотр и правка заказов.
		//echo '<td>';
		//echo '<input type="button" class="btn btn-mini" id="id_zakaz='.$zakaz->id.'" name="view" value="Просмотр"  onClick="view_zakaz(\''.$zakaz->id.'\')"/>';
		//echo '</td>';
        echo '</tr>';
    }
    ?>
 
<script>
    $(document).ready(function() {
//        $("input[name='courier_input_id']").focus(function(){input_focus(this);})
//        $("input[name='courier_input_id']").blur(function(){input_blur(this);})
//        $("input[name='courier_input_id']").keypress(function(e){input_keyPress(e, this)}); 
        $("td[id^='courier_']").click(function(){
            var parts = $(this).attr('id').split('_');
            var id = parts[1];
            $(this).html('<input id="z_'+id+'" type="text" class="input-xlarge" data-provide="typeahead" data-items="10" placeholder="Курьер" name="courier_input_id"/>');
            var obj = $('#z_'+id);
            $(obj).focus(function(){input_focus(obj)});
            $(obj).blur(function(){input_blur(obj)});
            $(obj).keypress(function(e){input_keyPress(e, obj)});
            $(obj).typeahead({
                //источник данных
                source: function (query, process) {
                   return $.post('ajax/get_courier', {'text':query}, 
                         function (response) {
                              var data = new Array();
                              //преобразовываем данные из json в массив
                              $.each(response, function(i, obj)
                              {
                                data.push(i+'_'+obj.id+'_'+obj.name);
                              })
                              return process(data);
                            },
                         'json'
                         );
                  }
                  //источник данных
                  //вывод данных в выпадающем списке
                  , highlighter: function(item) {
                      var parts = item.split('_');
                      parts.shift();
                      return parts.join('_');
                  }
                  //вывод данных в выпадающем списке
                  //действие, выполняемое при выборе елемента из списка
                  , updater: function(item) {
                                var parts = item.split('_');
                                var id_courier = parts[1];
                                return id_courier;
                           }
                  //действие, выполняемое при выборе елемента из списка
                  }
            );
            $(obj).focus();
        });
        
    });
    
    function input_focus(obj)
    {
        stop_interval();
    }

    function input_blur(obj)
    {
        if($(obj).val() == '')
            start_interval();
    }    

    function input_keyPress(e, obj)
    {
            var id_courier = $(obj).val();
            var parts = $(obj).attr('id');
            id_zakaz = parts.split('_')[1];
            if(e.keyCode==13) 
            {
            jQuery.ajax({ 
                    url:     "ajax/courier_change_courier", //Адрес подгружаемой страницы 
                    type:     "POST", //Тип запроса 
                    dataType: "html", //Тип данных 
                    data: {'id_zakaz':id_zakaz, 'id_courier':id_courier},  
                    success: function(response) { //Если все нормально 
                            re_load();
                            start_interval();
                        }, 
                    error: function(response) { //Если ошибка 
                            re_load();
                            start_interval();
                            alert('Ошибка');
                        } 
                });
            }

    }
    
    
</script>
