$(document).ready(function(){
    ymaps.ready(init);
    var map;
    var pm;
    var coord;
    var trigger;
    function init() {
        map = new ymaps.Map("map", {center: [44.115215, 131.889318], zoom: 13});
        map.controls.add(new ymaps.control.ZoomControl());
        map.controls.add(new ymaps.control.TrafficControl({shown: true}, {visible: false}));
        
        var button = new ymaps.control.Button('<i class="icon-move"></i>');
        button.events
            .add('click', function() {
                if (button.isSelected())
                {
                    
                }
            })
            .add('select', function() {
                trigger = true;
            })
            .add('deselect', function() {
                trigger = false;
            });
        map.controls.add(button);
        
        pm = new ymaps.GeoObject({
            geometry: {
                type: "Point"
            }
        });
    
        map.geoObjects.add(pm);

        map.events.add('boundschange', function(event) {
            var newCoord = event.get('newCenter');
            var lat = newCoord[0].toString().substring(0, coord[0].toString().length);
            var lon = newCoord[1].toString().substring(0, coord[1].toString().length);
            if (coord[0].toString() !== lat && coord[1].toString() !== lon)
            {
                button.deselect();
            }
        });
        
        button.select();

        setInterval(function () 
            {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "/ajax/coord",
                    success: function (crd) {
                        coord = crd;
                        pm.geometry.setCoordinates(coord);
                        if (trigger)
                        {
                            map.setCenter(coord);
                        }
                    }
                });
            }, 5000);
                
    }
    
});