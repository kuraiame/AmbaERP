<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="/js/jquery.js"></script>
        <script language="JavaScript">
            
                if (navigator.geolocation)
                {
                    function geo_success(p)
                    {
                        $.post('welcome', {latitude: p.coords.latitude, longitude: p.coords.longitude});
                        //$('#d').append('<div>Широта: '+p.coords.latitude+' Долгота: '+p.coords.longitude+'</div>');
                    }
                    function geo_error()
                    {
                        alert('Не удалос!');
                    }
                    
                    function begin()
                    {
                        wId = navigator.geolocation.watchPosition(geo_success, geo_error);
                        siId = setInterval(geo_success, 10000);
                        $('#btn').text("Завершить отслеживание");
                    }
                    
                    function end()
                    {
                        navigator.geolocation.clearWatch(wId);
                        clearInterval(siId);
                        $('#btn').text("Начать отслеживание");
                    }
                    
                }
            $(document).ready(function(){
            //$('#btn').live("click", function(){
                        $('#btn').toggle(begin, end);
                    //});
            });
        </script>
    </head>
    <body>
        
        <button id="btn">Начать отслеживание</button>
        <div id="d"></div>

        <script src="/js/geo.js"></script>
    </body>
</html>
