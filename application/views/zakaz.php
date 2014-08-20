        <div class="container">
            <div class="row">
                <div class="span2">
                    <div class="affix">
                        <ul class="nav nav-list well">
                            <li class="nav-header">Добавить в заказ</li>
                            <li><button id="picca" class="btn btn-block">Пицца</button></li>
                            <li><button id="sous" class="btn btn-block">Соус</button></li>
                            <li><button id="napitok" class="btn btn-block">Напиток</button></li>
                            <li><button id="wok" class="btn btn-block">Вок</button></li>
                            <li><button id="zakuska" class="btn btn-block">Закуска</button></li>
                        </ul>
                        <ul class="nav nav-list well">
                            <li class="nav-header">Промо код</li>
                            <li><input id="promo" type="text" class="input-mini" placeholder="Промо"/><span id="state_promo" class="label label-important">X</span></li>
                            <li><p id="promo_about"></p></li>
                        </ul>
                        <ul class="nav nav-list well">
                            <li class="nav-header">Сумма заказа</li>
                            <li><button id="price" class="btn btn-large btn-info disabled">0р</button></li>
                            <li class="nav-header">Скидка</li>
                            <li><button id="sale" class="btn btn-large btn btn-danger disabled">0р</button></li>
                            <li class="nav-header">К оплате</li>
                            <li><button id="summa" class="btn btn-large btn btn-warning disabled">0р</button></li>
                        </ul>
                    </div>
                </div>
                <div class="span10">
                    <form method="post" id="myform" class="form-horizontal" autocomplete="off">
                        <div id="div_items_table">
                            <table class="table table-striped well table-hover" id="my_table">
                                <thead>
                                    <tr>
                                        <th class="input-mini"> </th>
                                        <th class="input-xlarge">Наименование</th>
                                        <th class="input-mini">Рамер</th>
                                        <th class="input-mini">Количество</th>
                                        <th class="input-mini">Цена за ед.</th>
                                        <th class="input-mini">Стоимость</th>
                                        <th class="input-xlarge">Коментарий</th>
                                    </tr>
                                </thead>
                            </table>
                            <a class="btn btn-large btn-info pull-right" id="end_menu">Перейти к заполнению данных для доставки</a>
                        </div>
                        <div id="div_data" class="well">
                            <a class="btn btn-large btn-info pull-right" id="return_menu">Вернуться к заполнению меню</a>
                            <div class="control-group">
                                <label for="adress" class="control-label">Адрес:</label>
                                <div class="controls">
                                    <input id="adress" type="text" class="input-xlarge" data-provide="typeahead" data-items="10" placeholder="Улица"/>
                                </div>
                                <div class="controls">
                                    <input id="house" type="text" class="input-mini" placeholder="Дом"/>
                                    <input id="flat" name="flat" type="text" class="input-mini" placeholder="Квартира"/>
                                    <input id="podiezd" name="podiezd" type="text" class="input-mini" placeholder="Подъезд"/>
                                    <input id="floor" name="floor" type="text" class="input-mini" placeholder='Этаж'/>
                                    <span id="home_control" class="label label-important">X</span>
                                    <label class="checkbox add-on" id="pickup_label">
                                        <input type="checkbox" id="pickup" class="add-on" name="pickup"/>Самовывоз
                                    </label>
                                </div>
                                <input id="house_id" type="hidden" value="0" name="house"/>
                            </div>
                            <div class="control-group">
                                <label for="time_delivery" class="control-label">Время доставки:</label>
                                <div class="controls">
                                    <div id="time_delivery">
                                        <div class="input-prepend input-append">
                                            <span class="add-on">Часы:</span><input id="time_h" type="text" maxlength="2" name="time_h" class="input-mini" placeholder="чч"/>
                                            <span class="add-on">Мин:</span><input id="time_min" type="text" maxlength="2" name="time_min" class="input-mini" placeholder="мм"/>
                                        </div>    
                                        <label class="checkbox add-on" id="time_nearest_label">
                                            <input type="checkbox" id="time_nearest" class="add-on" name="near_time"/>Ближайшее время
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="contact" class="control-label">Контакт:</label>
                                <div class="controls">
                                    <input id="contact" type="text" name="contact" class="input-xlarge"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="phone" class="control-label">Телефон:</label>
                                <div class="controls">
                                    <input id="phone" type="text" name="phone" class="input-xlarge"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="sdacha" class="control-label">Сдача:</label>
                                <div class="controls">
                                    <input id="sdacha" type="text" name="sdacha" class="input-mini"/>
                                    <label class="checkbox" id="sdacha_label">
                                        <input type="checkbox" id="sdacha_check" name="no_sdacha"> Без сдачи
                                    </label>
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="coment" class="control-label">Коментарий:</label>
                                <div class="controls">
                                    <input id="coment_all" type="text" name="coment_all" class="input-xlarge"/>
                                </div>
                            </div>
                            <input id="buttion_smt" type="button" value="Принять заказ" class="btn btn-info btn-large " disabled/>
                        </div>
                        <input id="promo_hidden" type="hidden" name="promo"/>
                    </form>
                </div>
            </div>
        </div>

        <script>
            var count = 0;
            var summa = 0;
            var sale = 0;
            var items_picca_name = {<?php echo $pizza_list ?>};
            var items_sous_name = {<?php echo $sous_list ?>};
            var items_napitok_name = {<?php echo $napitok_list ?>};
            var items_wok_name = {<?php echo $wok_list ?>};
            var items_zakuska_name = {<?php echo $zakuska_list ?>};
            var street_id;
            
            $(document).ready(function() {
                init_create_picca();
                init_create_sous();
                init_create_napitok();
                init_create_wok();
                init_create_zakuska();
                create_picca();
                //create_sous();
                //recount_price();
                
                $('input').keyup(function(){
                   check_zakaz(); 
                });
                $('#time_h').click(function(){$(this).select()});
                $('#time_h').keyup(function(event){
                    if(parseInt($(this).val()) > 23)
                        $(this).val('23');
                    if(parseInt($(this).val()) < 0)
                        $(this).val('0');
                    var text = $(this).val();
                    if (text.length == 2)
                        $('#time_min').focus();
                    check_zakaz();
                });
                $('#time_min').click(function(){$(this).select()});
                $('#time_min').focus(function(){$(this).select()});
                $('#time_min').keyup(function(){
                    if(parseInt($(this).val()) > 59)
                        $(this).val('59');
                    if(parseInt($(this).val()) < 0)
                        $(this).val('0');
                    check_zakaz();
                });
                $('#pickup').change(function(){
                   var checked = $(this).prop('checked');
                   $('#adress').prop('disabled', checked);
                   $('#house').prop('disabled', checked);
                   $('#flat').prop('disabled', checked);
                   $('#podiezd').prop('disabled', checked);
                   check_zakaz();
                });
                
                $('#time_nearest').change(function(){
                    var checked = $(this).prop('checked');
                    $('#time_h').prop('disabled', checked);
                    $('#time_min').prop('disabled', checked);
                    check_zakaz();
                });
                $('#sdacha_check').change(function(){
                    var checked = $(this).prop('checked');
                    $('#sdacha').prop('disabled', checked);
                    check_zakaz();
                });
                $('#adress').keydown(function(){
                    $('#house_id').val('0');
                    check_zakaz();
                });
                $('#house').keydown(function(){
                    $('#house_id').val('0');
                    check_zakaz();
                });
                
                $('#div_data').hide();
                $('#end_menu').click(function(){
                    $('#div_items_table').hide(600);
                    $('#div_data').show(600);
                    $('#picca').prop('disabled', true);
                    $('#sous').prop('disabled', true);
                    $('#napitok').prop('disabled', true);
                });
                $('#return_menu').click(function(){
                    $('#div_items_table').show(600);
                    $('#div_data').hide(600);
                    $('#picca').prop('disabled', false);
                    $('#sous').prop('disabled', false);
                    $('#napitok').prop('disabled', false);
                });
                
                $('#adress').typeahead({
                        //источник данных
                        source: function (query, process) {
                           return $.post('ajax/get_adress', {'name':query}, 
                                 function (response) {
                                      var data = new Array();
                                      //преобразовываем данные из json в массив
                                      $.each(response, function(i, obj)
                                      {
                                        data.push(obj.id+'_'+obj.name);
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
                                     street_id = parts.shift();
                                     $('#house').val("");
                                     $('#house').focus();
                                     return parts.join('_');
                                   }
                          }
                );
            });
            
            $('#promo').keyup(function(){
                $('#state_promo').removeClass('label-success');
                $('#state_promo').addClass('label-important');
                $('#state_promo').text('X');
                $('#promo_hidden').val('');
                free_pizza = false;
                var name = $(this).val();
                $.getJSON("ajax/get_id_promo", {'name':name}, function(obj)
                {
                    if(obj.id > 0)
                    {
                        $('#state_promo').addClass('label-success');
                        $('#state_promo').removeClass('label-important');
                        $('#state_promo').text('✓');
                        $('#promo_hidden').val(name);
                    }
                    $('#promo_about').text(obj.about);
                    recount_price();
                });
                recount_price();
            });
       
            $('#buttion_smt').click(function(){
                jQuery.ajax({ 
                    url:     "ajax/zakaz_add_zakaz", //Адрес подгружаемой страницы 
                    type:     "POST", //Тип запроса 
                    dataType: "html", //Тип данных 
                    data: jQuery("#myform").serialize(),  
                    success: function(response) { //Если все нормально 
                        if(response == 1)
                        {
                            alert('Заказ добавлен');
                            location.reload();
                        }
                        else
                            alert('Некоректные данные. Заказ не добавлен. '+response);
                }, 
                error: function(response) { //Если ошибка 
                    alert('Произошла ошибка. Заказ не добавлен. '+response);
                } 
                });
            });
             
            $('#house').typeahead({
                        //источник данных
                        source: function (query, process) {
                           return $.post('ajax/get_house', {'name':query, 'street':street_id}, 
                                 function (response) {
                                      var data = new Array();
                                      var d;
                                      //преобразовываем данные из json в массив
                                      $.each(response, function(i, obj)
                                      {
                                        data.push(obj.id+'_'+obj.name);
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
                                     var id = parts.shift();
//                                     $('#house_id').val(parts.shift());
                                     $.post('ajax/check_area', {'id_house':id},
                                        function(response) 
                                        {
                                            if(response == '0')
                                            {
                                                $('#home_control').removeClass('label-success');
                                                $('#home_control').addClass('label-important');
                                                $('#home_control').text('X');
                                                $('#house_id').val('0');
                                            }
                                            else
                                            {
                                                $('#home_control').removeClass('label-important');
                                                $('#home_control').addClass('label-success');
                                                $('#home_control').text('✓');
                                                $('#house_id').val(id);
                                            }
                                        },
                                        'json'
                                     );

                                     $('#flat').focus();
                                     check_zakaz();
                                     return parts.join('_');
                                   }
                          //действие, выполняемое при выборе елемента из списка
                          }
                );
        </script>