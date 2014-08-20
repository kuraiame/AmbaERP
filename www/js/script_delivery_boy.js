jQuery(function($) {
    $(document).ready(function(){
        
        $('button[name="gen"').click(function() {  // Обработка нажатия кнопки "Обновить"
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                url: "/ajax/generate",
                data: ({
                    id_courier: id
                }),
                success: function (data) {
                    if (data !== 'FALSE')
                        {
                            $('#cur_'+id).text(data.toString());
                        }
                }
            });
        });

        $('button[name="cur_block"').click(function() {  // Обработка нажатия кнопки "Заблокировать"
            var id = $(this).attr('id');
            var name = $('#name_'+id).text();
            if (confirm('Вы действительно хотите заблокировать курьера ' + name + '?')) {
                
                $.ajax({
                    type: "POST",
                    url: "/ajax/cur_block",
                    data: ({
                        id_courier: id
                    }),
                    success: function (data) {
                        if (data !== 'FALSE')
                            {
                                location.reload();
                            }
                    }
                });
            }
            else {
                // Do nothing!
            }

        });

        $('button[name="addCourier"').click(function() {  // Обработка нажатия кнопки "Заблокировать"
            var fam = $('#curFam').val();
            var name = $('#curName').val();
            var cur_name = fam + ' ' + name
            $.ajax({
                    type: "POST",
                    url: "/ajax/cur_add",
                    data: ({
                        name_courier: cur_name
                    }),
                    success: function (data) {
                        if (data !== 'FALSE')
                            {
                                location.reload();
                            }
                    }
                });
        });
        // Валидация данных нового курьера.
        $('#curFam').keyup(function() {
            var fam = $('#curFam').val();
            var name = $('#curName').val();

            if (fam != '' & name != ''){
                $('#addCourier').prop('disabled', false);
            }
            else {
                $('#addCourier').prop('disabled', true);
            }
        });

        $('#curName').keyup(function() {
            var fam = $('#curFam').val();
            var name = $('#curName').val();

            if (fam != '' & name != ''){
                $('#addCourier').prop('disabled', false);
            }
            else {
                $('#addCourier').prop('disabled', true);
            }
        });
        // Конец валидации данных нового курьера

        $('#show_blocked').click(function() {
            if ($('#show_blocked').attr("checked") == 'checked'){
                $('#search').prop('disabled', false);
                $.ajax({
                    type: "POST",
                    url: "/manage/delivery_boy",
                    data: ({
                        sb: 1
                    })
                });
            }
            else{
                $('#search').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: "/manage/delivery_boy",
                    data: ({
                        sb: 0
                    })
                
                });
            }
            location.reload();
        });
    });
});