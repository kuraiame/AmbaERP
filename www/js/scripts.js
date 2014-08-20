//var price_list = [];

function recount_price() //пересчитать стоимость
{
//    var picca_count = 0;
//    var pizza_price = 0;
//    $('input[id^=type_picca_]').each(function() {
//        picca_count = parseInt(picca_count) + parseInt($('#count_'+get_id(this)).val());
//        pizza_price = parseInt($('#price_one_'+get_id(this)).text());
//    });
//    var sous_count = 0;
//    var sous_price = 0;
//    $('input[id^=type_sous_]').each(function() {
//        sous_count = parseInt(sous_count) + parseInt($('#count_'+get_id(this)).val());
//        sous_price = parseInt($('#price_one_'+get_id(this)).text());
//    });
//    var count = parseInt(0);
//    $.each(price_list,function() {
//        count += parseInt(this);
//    });
//    var free_sous = parseInt(sous_count);
//    if(parseInt(sous_count) > parseInt(picca_count))
//        free_sous = parseInt(picca_count);
//    count = parseInt(count) - parseInt(free_sous)*parseInt(sous_price);
//    if(free_pizza == true)
//        count = count - pizza_price;
//    summa = count;
//    
    jQuery.ajax({ 
        url:     "ajax/zakaz_get_summa", //Адрес подгружаемой страницы 
        type:     "POST", //Тип запроса 
        dataType: "json", //Тип данных 
        data: jQuery("#myform").serialize(),  
        success: function(response) 
        {
            summa = response.summa;
            $('#price').text(response.summa_without_sale+'р');
            $('#sale').text(response.sale+'р');
            $('#summa').text(response.summa_with_sale+'р');
        }, 
        error: function(response) { //Если ошибка 
            alert(response);
        } 
    });
  
//    $('#price').text(count+'р');
}

function recount_price_all(id)
{
    var pr = parseInt($('#price_one_'+id).text()) * parseInt($('#count_'+id).val());
    $('#price_all_'+id).text(pr);
    //price_list[id] = pr;
    recount_price();
}

function change_price_one(id, val)
{
    $('#price_one_'+id).text(val);
    recount_price_all(id);
}

function change_pizza_id(id)
{
    $('#id_'+id).val(items_picca_name[$('#picca_name_'+id).val()][$('#picca_size_'+id).val()][$('#price_one_'+id).text()]);
	recount_price();
    //$('#price').text(items_picca_name[$('#picca_name_'+id).val()][$('#picca_size_'+id).val()][$('#price_one_'+id).text()]);
}

function change_sous_id(id)
{
    $('#id_'+id).val(items_sous_name[$('#sous_name_'+id).val()][$('#price_one_'+id).text()]);
	recount_price();
}

function change_napitok_id(id)
{
    $('#id_'+id).val(items_napitok_name[$('#napitok_name_'+id).val()][$('#napitok_size_'+id).val()][$('#price_one_'+id).text()]);
	recount_price();
    //$('#price').text(items_picca_name[$('#picca_name_'+id).val()][$('#picca_size_'+id).val()][$('#price_one_'+id).text()]);
}

function change_size_pizza(id)
{
   var pr = get_key(items_picca_name[$('#picca_name_'+id).val()][$('#picca_size_'+id).val()]);
   change_price_one(id, pr);
   change_pizza_id(id);
}

function change_size_napitok(id)
{
   var pr = get_key(items_napitok_name[$('#napitok_name_'+id).val()][$('#napitok_size_'+id).val()]);
   change_price_one(id, pr);
   change_napitok_id(id);
}

function change_sous_name(id)
{
    change_price_one(id, get_key(items_sous_name[$('#sous_name_'+id).val()]));
    change_sous_id(id);
}

function load_size_pizza(obj)
{
    id = get_id(obj);
    $('#picca_size_'+id+' option').remove();
    var pizza_size_list = get_key(items_picca_name[$('#picca_name_'+id).val()]);
    $.each(pizza_size_list,function() {
    $('<option/>', {
        val:  this,
        text: this
        }).appendTo(obj);
    });
    //obj.val(pizza_size_list[0]);
}

function load_size_napitok(obj)
{
    id = get_id(obj);
    $('#napitok_size_'+id+' option').remove();
    var napitok_size_list = get_key(items_napitok_name[$('#napitok_name_'+id).val()]);
    $.each(napitok_size_list,function() {
    $('<option/>', {
        val:  this,
        text: this
        }).appendTo(obj);
    });
}

function get_key(object)
{
    var keys = [];

    $.each(object, function(index, value){
           keys.push(index);
    });
    return keys;
}

function get_id(object)
{
    var t = ($(object).attr('id')).split('_');
    return parseInt(t[t.length-1]);
}

function change_count(id)
{
//    var object = $('#input_count_'+id);
    recount_price_all(id);
}

function remove(id)
{
    //price_list[id] = 0;
    $('#id_tr_'+id).remove();
    recount_price();
}

function create_picca()
{
    var id = 'id_tr_'+count;
    var my_tr = $('<tr/>', {
    id:     id
//    name:   'tr_picca[]'
    });
    $('#my_table').append($('<tbody/>').append(my_tr));

    // удалить
    var button_del = $('<button/>', {
        id:     'button_'+count,
        class:  'btn btn-mini',
        type:   'button'
    }).append($('<i/>',{class: 'icon-remove'}));
    $('#'+id).append($('<td/>').append(button_del));
    $('#button_'+count).click(function(){
        remove(get_id(this));
    });



    // название пиццы
    var select_picca_name = $('<select/>', {id: 'picca_name_'+count, class: 'input-xlarge'});
    var pizza_list = get_key(items_picca_name);
    $.each(pizza_list,function() {
    $('<option/>', {
        val:  this,
        text: this
        }).appendTo(select_picca_name);
    });
    select_picca_name.val(pizza_list[0]);
    select_picca_name.change(function(){
       load_size_pizza($('#picca_size_'+get_id(this)));
       change_size_pizza(get_id(this));
    });
    $('#'+id).append($('<td/>').append(select_picca_name));

    // размер пиццы
    var select_picca_size = $('<select/>', {id: 'picca_size_'+count, class: 'input-mini'});
    load_size_pizza(select_picca_size)
    select_picca_size.change(function(){
       change_size_pizza(get_id(this));
    });
    $('#'+id).append($("<td/>").append(select_picca_size));

    // количество
    var input_count = $('<input/>', {
        id:     'count_'+count,
        name:   'count[]',
        type:   'number',
        class:  'input-mini',
        min:    '1',
        value:   '1'
    });
    input_count.change(function(){
        change_count(get_id(this))
    });
    
    $('#'+id).append($('<td/>').append(input_count));

    // цена за единицу
    var price_one = $('<span/>',{
       id:      'price_one_'+count,
       class:   'label label-info'
    });
    $('#'+id).append($('<td/>').append(price_one));

    // стоимость
    var price_all = $('<span/>',{
       id:      'price_all_'+count,
       class:   'label label-important'
    });
    $('#'+id).append($('<td/>').append(price_all));

    // комент
    var input = $('<input/>', {
        id:     'input_koment_'+count,
        name:   'coment[]',
        type:   'text',
        class:  'input-large'
    });
    $('#'+id).append($('<td/>').append(input));
    
    var input_hidden = $('<input/>', {
        id:     'id_'+count,
        name:   'id[]',
        type:   'hidden'
    });
    
    $('#'+id).append(input_hidden);

    var input_hidden_type = $('<input/>', {
        id:     'type_picca_'+count,
        type:   'hidden'
    });
    
    $('#'+id).append(input_hidden_type);

    change_size_pizza(count);
    count = count+1;
    create_sous();
    recount_price();
}

function init_create_picca()
{
	$('#picca').click(function(){create_picca();})
}

function create_sous()
{
    var id = 'id_tr_'+count;
    var my_tr = $('<tr/>', {
    id:     id
    });
    $('#my_table').append($('<tbody/>').append(my_tr));

            // удалить
    var button_del = $('<button/>', {
            id:     'button_'+count,
            class:  'btn btn-mini',
            type: 'button'
    }).append($('<i/>',{class: 'icon-remove'}));
    $('#'+id).append($('<td/>').append(button_del));
    $('#button_'+count).click(function(){
            remove(get_id(this));
    });


    // название соуса
    var select_sous_name = $('<select/>', {id: 'sous_name_'+count, class: 'input-xlarge'});
    var sous_list = get_key(items_sous_name);
    $.each(sous_list,function() {
    $('<option/>', {
            val:  this,
            text: this
            }).appendTo(select_sous_name);
    });
    select_sous_name.change(function(){
        change_sous_name(get_id(this));
    })
    $('#'+id).append($('<td/>').append(select_sous_name));

    // пустое
    $('#'+id).append($('<td/>'));

    // количество
    var input_count = $('<input/>', {
        id:     'count_'+count,
        name:   'count[]',
        type:   'number',
        min:    '1',
        value:  '1',
        class:  'input-mini'
    })
    input_count.change(function(){
        change_count(get_id(this));
    })
    $('#'+id).append($('<td/>').append(input_count));
        
    // цена за единицу
    var price_one = $('<span/>',{
       id:      'price_one_'+count,
       class:   'label label-info'
    });
    $('#'+id).append($('<td/>').append(price_one));

    // стоимость
    var price_all = $('<span/>',{
       id:      'price_all_'+count,
       class:   'label label-important'
    });
    $('#'+id).append($('<td/>').append(price_all));

    // комент
    var input = $('<input/>', {
            name:   'coment[]',
            type:   'text',
            class:  'input-large'
    })
    $('#'+id).append($('<td/>').append(input));
    
    $('#'+id).append($("<td/>"));
    
    var input_hidden = $('<input/>', {
        id:     'id_'+count,
        name:   'id[]',
        type:   'hidden'
    });
    
    $('#'+id).append(input_hidden);

    var input_hidden_type = $('<input/>', {
        id:     'type_sous_'+count,
        type:   'hidden'
    });
    
    $('#'+id).append(input_hidden_type);

    change_sous_name(count);
    count = count+1;
    recount_price();
}
	
function init_create_sous()
{
	$('#sous').click(function(){create_sous();})
}
function create_napitok()
{
    var id = 'id_tr_'+count;
    var my_tr = $('<tr/>', {
    id:     id
    });
    $('#my_table').append($('<tbody/>').append(my_tr));

            // удалить
    var button_del = $('<button/>', {
            id:     'button_'+count,
            type:   'button',
            class:  'btn btn-mini'
    }).append($('<i/>',{class: 'icon-remove'}));
    $('#'+id).append($('<td/>').append(button_del));
    $('#button_'+count).click(function(){
            remove(get_id(this));
    })

    // название напитка
    var select_napitok_name = $('<select/>', {id: 'napitok_name_'+count, class: 'input-xlarge'});
    var napitok_list = get_key(items_napitok_name);
    $.each(napitok_list,function() {
    $('<option/>', {
        val:  this,
        text: this
        }).appendTo(select_napitok_name);
    });
    select_napitok_name.change(function(){
        var id = get_id(this);
        load_size_napitok($('#napitok_size_'+id));
        change_size_napitok(id);
    })
    $('#'+id).append($('<td/>').append(select_napitok_name));

    // объём напитка
    var select_napitok_size = $('<select/>', {id: 'napitok_size_'+count, class: 'input-mini'});
    $('#'+id).append($('<td/>').append(select_napitok_size));
    select_napitok_size.change(function(){
        change_size_napitok(get_id(this));
    })
    load_size_napitok(select_napitok_size);

    // количество
    var input_count = $('<input/>', {
        id:     'count_'+count,
        name:   'count[]',
        type:   'number',
        min:    '1',
        value:  '1',
        class:  'input-mini'
    });
    input_count.change(function(){
        recount_price_all(get_id(this));
    })
    $('#'+id).append($('<td/>').append(input_count));

    // цена за единицу
    var price_one = $('<span/>',{
       id:      'price_one_'+count,
       class:   'label label-info'
    });
    $('#'+id).append($('<td/>').append(price_one));

    // стоимость
    var price_all = $('<span/>',{
       id:      'price_all_'+count,
       class:   'label label-important'
    });
    $('#'+id).append($('<td/>').append(price_all));
    
    // комент
    var input = $('<input/>', {
            name:   'coment[]',
            type:   'text',
            class:  'input-large'
    })
    $('#'+id).append($('<td/>').append(input));

    var input_hidden = $('<input/>', {
        id:     'id_'+count,
        name:   'id[]',
        type:   'hidden'
    });
    
    $('#'+id).append(input_hidden);

    change_size_napitok(count);
    count = count+1;
    recount_price();
}

function init_create_napitok()
{
	$('#napitok').click(function(){create_napitok();})
}

function check_zakaz()
{
    var res = true;
//    $('#price').text($('#house_id').val());
    
    if($('#pickup').prop('checked') == false)
    {
        if($('#house_id').val() == "0")
            res = false;
//        if($('#flat').val() == "")
//            res = false;
//        if($('#podiezd').val() == "")
//            res = false;
    }
    if($('#time_nearest').prop('checked') == false)
    {
        if($('#time_h').val() == "")
            res = false;
        if($('#time_min').val() == "")
            res = false;
    }
    
//    if($('#contact').val() == "")
//        res = false;
    if($('#phone').val() == "")
        res = false;
    if($('#sdacha_check').prop('checked') == false)
    {
        if($('#sdacha').val() == '')
            res = false;
        else
            if(parseInt($('#sdacha').val()) < summa)
                res = false;
    }
    $('#buttion_smt').prop('disabled', !res);
}