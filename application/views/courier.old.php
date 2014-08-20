    <table class="table table-striped well table-hover">
        <thead>
            <tr>
                <th class="input-mini"><center> Номер </center></th>
                <th class="input-xlarge"><center>Адрес </center></th>
                <th class="input-large">Статус</th>
                <th class="input-large">Курьер</th>
                <th> Изменить статус </th>
            </tr>
            <tr>
                <th class="input-mini"><input type="text" class="input-mini search-query" id="search_id"></th>
                <th class="input-xlarge"><input type="text" class="search-query" id="search_adress"></th>
                <th class="input-large"></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody id="my_table">
            
        </tbody>
    </table>
<div class="shim">
	<div class="my_modal" id="modal_window" style="display: none">
		<input id="promo_code" type="text" class="input-xlarge" data-provide="typeahead" data-items="10" placeholder="Промо-код"/>
		<input id="button_unsuccess" type="button" value="Завершить" class="btn btn-info btn-large " disabled/>
	</div>
</div>

<div class="shim">
	<div class="my_modal" id="modal_window_view" style="display: none">
	</div>
</div>

<script>
    
    var id_interval;
    var id_promo_unsuccess = 0;
    var id_zakaz = 0;
    function re_load()
    {
        $.ajax({  
        url: "ajax/courier_get_zakaz",  
        cache: false,  
        success: function(html){  
            $('#my_table').html(html);  
            }  
        });
    }

    function set_enable_btn_unsuccess(b)
    {
        $("#button_unsuccess").prop('disabled', !b);
    }

    function connect_promo()
    {
        $('#promo_code').typeahead({
            //источник данных
            source: function (query, process) {
               id_promo_unsuccess = 0;
               set_enable_btn_unsuccess(false);
               return $.post('ajax/get_list_promo', {'name':query, 'state':'generated'}, 
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
                         id_promo_unsuccess = parts[0];
                         set_enable_btn_unsuccess(true);
                         parts.shift();
                         return parts.join('_');
                       }
         });
         
         $("#button_unsuccess").click(function(){
             change_state(id_zakaz, "unsuccess");
             set_promo_state(id_promo_unsuccess, 'activated', id_zakaz);
             $('#modal_window').my_modal().close();
         });
    }

    function start_interval()
    {
        re_load();
        stop_interval();
        id_interval = setInterval(re_load, 1000);
    }
    
    function stop_interval()
    {
        clearInterval(id_interval);
    }
    
    function open_window(id)
    {
        id_zakaz = id;
        $('#modal_window').my_modal().open({
            onOpen: function(el, options){
                stop_interval();
                connect_promo();
                },
            onClose: function(){start_interval();} 
        });
    }
    
    $(document).ready(function() 
    {
        start_interval();
        $('#search_adress').keyup(function(e){
            if($(this).val().length > 2) 
            {
                stop_interval();
                $.ajax({  
                url: "ajax/courier_get_zakaz?adress="+$(this).val(),  
                cache: false,  
                success: function(html){  
                    $('#my_table').html(html);  
                    }  
                });
            }
            else
            {
                start_interval(); 
            }
        }); 
        
        $('#search_id').keyup(function(e){
            if($(this).val().length > 0) 
            {
                stop_interval();
                $.ajax({  
                url: "ajax/courier_get_zakaz?nomer="+$(this).val(),  
                cache: false,  
                success: function(html){  
                    $('#my_table').html(html);  
                    }  
                });
            }
            else
            {
                start_interval();
            }
        });
        
    
      });

    function set_promo_state(id, state, id_zakaz)
    {
        $.post('ajax/set_promo_state', 
             {'id':id, 'state':state, 'id_zakaz':id_zakaz}, 
             function (response) {},
             'json'
             );
    }
    
    function change_state(id, state)
    {
        $.post('ajax/courier_change_state', 
            {'id':id, 'state':state}, 
            function (response) {
                re_load();
                 return ;
               },
            'json'
            );
    }
	
	function view_zakaz(id)
	{
		$('#modal_window_view').my_modal().open({
            onOpen: function(el, options){
					$.post('ajax/view_zakaz',{'id_zakaz':id}, function(data){$('#modal_window_view').html(data)});
                },
            onClose: function(){} 
        });
	}
</script>


