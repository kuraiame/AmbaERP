function add_tr(data) {
    
    switch(data.state) {
        case 'in_process':
            w = 'warning';
            data.state = 'В процессе';
        break;
        
        case 'collected':
            w = 'success';
            data.state = 'В пути';
        break;
        
        case 'cash':
            w = 'error';
            data.state = 'Доставлено';
        break;
    }
    
    var tr = document.createElement('tr');
    var td_no = document.createElement('td');
    var td_addr = document.createElement('td');
    var td_time = document.createElement('td');
    var td_phone = document.createElement('td');
    var td_contact = document.createElement('td');
    var td_summ = document.createElement('td');
    var td_sdacha = document.createElement('td');
    var td_comment = document.createElement('td');
    var td_state = document.createElement('td');
    var td_cur = document.createElement('td');
    var td_act = document.createElement('td');
    $(td_no).append(data.no);
    $(td_addr).append(data.addr);
    $(td_time).append(data.time);
    $(td_phone).append(data.phone);
    $(td_contact).append(data.contact);
    $(td_summ).append(data.summ);
    $(td_sdacha).append(data.sdacha);
    $(td_comment).append(data.comment);
    $(td_state).append(data.state);
    $(td_cur).append(data.cur);
    $(tr).append(td_no)
            .append(td_addr)
            .append(td_time)
            .append(td_phone)
            .append(td_contact)
            .append(td_summ)
            .append(td_sdacha)
            .append(td_comment)
            .append(td_state)
            .append(td_cur)
            .append(td_act)
            .addClass(w)
            .attr('id', 'order_'+data.no);
    $('#my_table').append(tr);
}
$(document).ready(function() {
    $.ajax({  
        url: "ajax1/get_order_ids",  
        dataType: 'json',
        type: 'POST',
        success: function(json){  
                    collected = json.collected;
                    process = json.process;
                    cash = json.cash;
                        $.post("ajax1/get_orders", { ids: json}, function(data) {
                        for (i=0; i<data.length; i++) {
                            add_tr(data[i], 'success');
                        }
                    }, 'json');
                }
        });
    
    setInterval(update, 10000);
});
function update() {
    $.ajax({  
        url: "ajax1/get_new_order_id",  
        dataType: 'json',
        type: 'POST',
        data: {
            oldProcess: process
        },
        success: function(json){
            var process_new = json.process;
            
        } 
    });
}

