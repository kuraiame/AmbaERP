jQuery(function($) {
    $(document).ready(function(){
        render();
    });

    $('#show_blocked').click(function() {  // Обработка чек-бокса "Отображать заблокированных"
        $("tr[id^='user_']").remove()
        render();
    });

    $('#addUserBtn').click(function() {


    });

    $("input[id^='inputUser']").keyup(function() {
        user_f_name = $("#inputUserName").val();
        user_l_name = $("#inputUserLname").val();
        user_tel = $("#inputUserTel").val();
        user_login = $("#inputUserLogin").val();
        user_pass = $("#inputUserPassword").val();
        user_repass = $("#inputUserRePassword").val();

        if( (user_f_name != '') & (user_l_name != '') & (user_tel != '') & (user_login != '') & (user_pass != '') & (user_repass != '') ){
            if (user_pass == user_repass){
                $('#newUserError').text('');
                $('#addUserBtn').prop('disabled', false);
            }
            else{
                $('#addUserBtn').prop('disabled', true);
                $('#newUserError').text('Проверьте пароль.');
            }
        }
        else{
            $('#addUserBtn').prop('disabled', true);
            $('#newUserError').text('Заполните все поля формы.');
        }
    });
});

function render(){
    var checked = $('#show_blocked').prop('checked');
    if(checked){
        $('#search').prop('disabled', false);
    }
    else{
        $('#search').prop('disabled', true);
    }
    $.ajax({
        type: "POST",
        url: "/ajax/render_users",
        dataType: "json",
        data:({
            blocked: checked
        }),
        success: function (json) {
            $.each(json, function (key, data) { //Получаем каждого пользователя
                create_tr(data);
            })
        }
    });  
}

function create_tr(user){
    var tr = document.createElement("tr");
    var td_id = document.createElement("td");
    var td_login = document.createElement("td");
    var td_name = document.createElement("td");
    var td_tel = document.createElement("td");
    var td_roles = document.createElement("td");
    var td_action = document.createElement("td");

    $(td_id).text(user.id);
    $(td_login).text(user.login);
    $(td_name).text(user.name);
    $(td_tel).text(user.tel);
    $(td_roles).text(user.roles);
    $(td_action).text(user.action);

    $(tr).append(td_id).append(td_login).append(td_name).append(td_tel).append(td_roles).append(td_action);
    $(tr).attr("id", "user_" + user.id)

    $("#user_list").append(tr);
}