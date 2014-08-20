<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/the-modal.css" rel="stylesheet">

<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
        <script src="js/jquery.js"></script>
        <script src="js/jquery.jqplugin.js"></script>
        <script src="js/jquery.the-modal"></script>
    </head>

   
<body>
                <a href="" class="pull-right"> обновить </a>
                <h4 id="count"></h4> 

                <div class="container">
                    <div class="row" id="q">

                    </div>
                </div>
                
      <div class="shim">
      <div class="my_modal" id="modal_window" style="display: none">
      </div>
      </div>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/scripts.js"></script>
        <script>

            var id_interval;
            var id_zakaz;
            function create_item(id, time, type)
            {
                var style;
                if(type == 'not_start')
                    style = 'alert-error';
                if(type == 'in_process')
                    style = 'alert-success';
                $('#q').append($('<div/>', {class: 'span3 alert '+style, id: 'div_'+id})
                                .append($('<h3/>').append($('<span/>').append(time)))
                                .append($('<center/>').append($('<h1/>').append(id)))
                            );
                $('#div_'+id).click(function(){
                    $('#modal_window').my_modal().open({
                            onOpen: function(el, options){
                                    id_zakaz = id;
                                    load_zakaz();
                                    id_interval = setInterval('load_zakaz()', 1000);
                                },
                            onClose: function(){clearInterval(id_interval);} 
                        });
                        
                });
                        
             }
             
            function load_zakaz()
            {
                $.post('/sborka/zakaz',{'id':id_zakaz}, function(data){$('#modal_window').html(data)});
            }

            function re_load()
            {
                $.getJSON("ajax/sborka_get_zakaz", {}, function(json){
                    $('#q').text("");
//                    $('#count').text('В очереди '+json.count+' заказов');
                    var data = json.data;
                    $.each(data, function(i, obj){
                        create_item(obj.id, obj.time, obj.state);
                    });
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