<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link href="css/bootstrap.css" rel="stylesheet">

<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
        <script src="js/jquery.js"></script>
    </head>

    <body>
        <a href="" class="pull-right"> обновить </a>
        <h4 id="count"></h4> 
        
        <div class="container">
            <div class="row" id="q">

            </div>
        </div>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>
        <script>
	
            function create_item(id, time, name, size, type, koment)
            {
                var style;
                if(type == 'not_done')
                    style = 'alert-error';
                if(type == 'in_process')
                    style = 'alert-success';
                if(type == 'done')
                    style = 'alert-info';
                $('#q').append($('<div/>', {class: 'span3 alert '+style, id: 'div_'+id})
                                .append($('<h3/>').append($('<span/>').append(time)).append($('<span/>',{class: 'pull-right'}).append(size)))
                                .append($('<h2/>', {class: 'pagination-centered'}).append(name))
                                .append($('<br/>'))
                                .append($('<div/>', {style: 'height: 70px;'}).append($('<h4/>',{style: 'font-size: 2em;'}).append(koment)))
                            );
                            if(type == 'not_done')
                                $('#div_'+id).click(function(){begin_pizza(id);});
                            if(type == 'in_process')
                                $('#div_'+id).click(function(){end_pizza(id);});
            }
            
            function begin_pizza(id)
            {
                //$('#div_'+id).click(function(){end_pizza(id);});
                
                $.post('ajax/povar_begin_cook', {'id':id}, 
                     function (response) 
                     {
                         if(response == '1')
                         {
                                $('#btn_'+id).removeClass('btn-danger');
                                $('#btn_'+id).addClass('btn-success');
                                $('#div_'+id).removeClass('alert-error');
                                $('#div_'+id).addClass('alert-success');
                                $('#div_'+id).unbind('click');
                                $('#div_'+id).click(function(){end_pizza(id);});
                            }
                        },
                     'json'
                     );
//                     alert('begin');
            }
            
            function end_pizza(id)
            {
                $.post('ajax/povar_end_cook', {'id':id}, 
                     function (response) {if(response == '1'){
                                re_load();
                            }
                        },
                     'json'
                     );
//                     alert('end');
            }
            
	    var last_value = 0; 
	    var first_load = FALSE; 
	 
            function re_load()
            {
                $.getJSON("ajax/povar_get_zakaz_position", {}, function(json){
                    $('#q').text("");
                    $('#count').text('В очереди '+json.count+' заказов');
		    firstload = TRUE;
		    var tmp_count = json.count;
		    $.each(json, function(i, obj){
			tmp_count = tmp_count + 1;
                        if(i != 'count')
                            create_item(obj.id, obj.time, obj.item_menu, obj.size, obj.state, obj.koment);
                    });
		    // проверка числа заказов, фанфары если появился новый заказ
                });
            }
            
            $(document).ready(function() 
            {
                re_load();
                setInterval('re_load()', 1000);
            });

        </script>
    </body>
</html>