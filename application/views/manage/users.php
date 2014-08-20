<div class="container content">
    <div class="row">
        <div class="span7 offset3">
            <form class="form-search">
                <input type="text" class="input-medium search-query" id="search" placeholder="Поиск" disabled>
                <label class="checkbox">
                        <input type="checkbox" id="show_blocked"> Отображать заблокированных
                </label>
            </form>
        </div>
        <div  class="span2">
            <a href="#addUserForm" role="button" class="btn btn-success" data-toggle="modal">Добавить</a> <!-- Загрузка модального окна -->
        </div>
        <div class="span10 offset2">
            <form action="" method="POST">
                <table class="table table-striped table-bordered table-hover" id="user_list">
                    <thead>
                        <tr>
                            <th class="">ID</th>
                            <th class="">Логин</th>
                            <th class="">Имя</th>
                            <th class="">Телефон</th>
                            <th class="">Группы</th>
                            <th class="">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?= @$table; ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="addUserForm" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Новый пользователь</h3>
    </div>
    <div class="modal-body">
        <form class="form-horizontal" id="newUserForm">
            <p class="text-error" id="newUserError"></p>
            <div class="control-group">
                <label class="control-label" for="inputUserName">Имя</label>
                <div class="controls">
                    <input type="text" id="inputUserName" placeholder="Ольга">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputUserLname">Фамилия</label>
                <div class="controls">
                    <input type="text" id="inputUserLname" placeholder="Смирнова">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputUserTel">Телефон</label>
                <div class="controls">
                    <input type="text" id="inputUserTel" placeholder="+7 (123) 456-78-90">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputUserLogin">Логин</label>
                <div class="controls">
                    <input type="text" id="inputUserLogin" placeholder="osmirnova">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputUserPassword">Пароль</label>
                <div class="controls">
                    <input type="password" id="inputUserPassword" placeholder="Password">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputUserRePassword">Ещё раз пароль</label>
                <div class="controls">
                    <input type="password" id="inputUserRePassword" placeholder="Password">
                </div>
            </div>            
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Отмена</button>
        <button class="btn btn-primary" id="addUserBtn" disabled>Сохранить</button>
    </div>
</div>