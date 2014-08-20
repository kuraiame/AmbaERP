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
            <a href="#addCur" role="button" class="btn btn-success" data-toggle="modal">Добавить</a> <!-- Загрузка модального окна -->
        </div>
        <div class="span10 offset2">
            <form action="" method="POST">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="">ID</th>
                            <th class="">Имя</th>
                            <th class="">Пароль</th>
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
<div id="addCur" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Добавить курьера</h3>
    </div>
    <div class="modal-body">
        <form class="form-horizontal"> <!-- Начало формы -->
            <div class="control-group">
                <label class="control-label" for="curFam">Фамилия</label>
                <div class="controls">
                    <input type="text" id="curFam" placeholder="Смирнова">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="curName">Имя</label>
                <div class="controls">
                    <input type="text" id="curName" placeholder="Ольга">
                </div>
          </div>
        </form> <!-- Конец формы -->

    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Отмена</button>
        <button class="btn btn-primary" id="addCourier" name="addCourier" disabled>Добавить</button>  <!-- Кнопка обрабатывается отдельно -->
    </div>
        
</div>