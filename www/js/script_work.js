if (navigator.geolocation)
                {
                    function geo_success(p)
                    {
                        $('#process').show();
                        $.ajax({
                                type: "POST",
                                url: "/abc/spy",
                                data: {
                                    latitude: p.coords.latitude,
                                    longitude: p.coords.longitude
                                },
                                success: function(msg){
                                },
                                complete: function() {
                                    $('#process').hide();
                                }
                        });
                    }
                    function geo_error()
                    {
                        
                    }
                    function geo()
                    {
                        navigator.geolocation.getCurrentPosition(geo_success);
                    }
                    function spy()
                    {
                        siId = setInterval(geo, 5000);
                        $('#spy').toggleClass("btn-danger");
                        $('#spy').toggleClass("btn-success");
                        $('#spy').html('<i class="icon-map-marker icon-white"></i> Завершить отслеживание');
                    }
                    
                    function do_not_spy()
                    {
                        //navigator.geolocation.clearWatch(wId);
                        clearInterval(siId);
                        $('#spy').toggleClass("btn-danger");
                        $('#spy').toggleClass("btn-success");
                        $('#spy').html('<i class="icon-map-marker icon-white"></i> Начать отслеживание');
                    }
                    
                }
$(document).ready(function(){
                        $('#process').hide();
                        $('#spy').toggle(spy, do_not_spy);
            });