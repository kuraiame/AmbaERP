<script type="text/javascript">
//<![CDATA[
        window.addEvent('domready', function() {
                myCal1 = new Calendar({ begin_date: 'Y-m-d' }, { direction: 0, tweak: {x: 6, y: 0} });
                myCal2 = new Calendar({ end_date: 'Y-m-d' }, { direction: 0, tweak: {x: 6, y: 0} });
        });
//]]>
</script>
        
<div class="container">
    <div class="row">
        <div class="span8 offset2">
            <div class="well">
                <form method="post" action="http://<?php echo $_SERVER['HTTP_HOST'] ?>/reports">
                    <table class="table table-striped well table-hover">
                        <thead>
                            <tr>
                                <th><center>Начальная дата</center></th>
                                <th><center>Начальное время</center></th>
                                <th><center>Конечная дата</center></th>
                                <th><center>Конечное время</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><center><input id="begin_date" name="begin_date" type="text" class="input-small" placeholder="Начальная дата" value="<?php echo $begin_date; ?>"/></center></td>
                                <td><center><input name="begin_time" type="text" class="input-small" placeholder="Начальное время" value="<?php echo $begin_time; ?>"/></center></td>
                                <td><center><input id="end_date" name="end_date" type="text" class="input-small" placeholder="Конечная дата" value="<?php echo $end_date; ?>"/></center></td>
                                <td><center><input name="end_time" type="text" class="input-small" placeholder="Конечное время" value="<?php echo $end_time; ?>"/></center></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="submit" value="Получить отчет" class="btn btn-info btn-block"/>
                </form>
            </div>

            <?php
            if($flag_exist)
            { ?>
            <table class="table table-striped well table-hover">
                <caption><h1>Заказы</h1></caption>
                <thead>
                    <tr>
                        <th class="input-large"></th>
                        <th class="input-large"><center>Количество</center></th>
                        <th class="input-large"><center>Сумма</center></th>
                        <th class="input-small"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Всего заказов:</td>
                        <td><center><p class="text-info"><strong><?php echo $zakaz_count_all; ?> шт.</strong></p></center></td>
                        <td><center><p class="text-info"><strong><?php echo $zakaz_summa_all; ?> руб.</strong></p></center></td>
                        <td><strong><a class="btn btn-mini btn-info pull-right">Просмотр</a></strong></td>
                    </tr>     
                    <tr>
                        <td>Доставленные заказы:</td>
                        <td><center><p class="text-success"><strong><?php echo $zakaz_success; ?> шт.</strong></p></center></td>
                        <td><center><p class="text-success"><strong><?php echo $zakaz_summa_success; ?> руб.</strong></p></center></td>
                        <td><strong><a class="btn btn-mini btn-info pull-right">Просмотр</a></strong></td>
                    </tr>     
                    <tr>
                        <td>Недоставленные заказы:</td>
                        <td><center><p class="text-error"><strong><?php echo $zakaz_unsuccess; ?> шт.</strong></p></center></td>
                        <td><center><p class="text-error"><strong><?php echo $zakaz_summa_unsuccess; ?> руб.</strong></p></center></td>
                        <td><strong><a class="btn btn-mini btn-info pull-right">Просмотр</a></strong></td>
                    </tr>     
                    <tr>
                        <td>Отмененные заказы:</td>
                        <td><center><p class="text-error"><strong><?php echo $zakaz_cansel; ?> шт.</strong></p></center></td>
                        <td><center><p class="text-error"><strong><?php echo $zakaz_summa_cansel; ?> руб.</strong></p></center></td>
                        <td><strong><a class="btn btn-mini btn-info pull-right">Просмотр</a></strong></td>
                    </tr>     
                </tbody>
            </table>

            <table class="table table-striped well table-hover">
                <caption><h1>Курьеры</h1></caption>
                <thead>
                    <tr>
                        <th class="input-large"><center>Курьер</center></th>
                        <th class="input-small"><center>Доставил</center></th>
                        <th class="input-small"><center>Удачно</center></th>
                        <th class="input-small"><center>Неудачно</center></th>
                        <th class="input-small"><center>Отмененые</center></th>
                        <th class="input-small"></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($couriers_result as $courier)
                {
                ?>
                    <tr>
                        <td><center><p class="text-info"><strong><?php echo $courier['id'].' '.$courier['name']; ?></strong></p></center></td>
                        <td><center><p class="text-info"><strong><?php echo $courier['all']; ?> шт.</strong></p></center></td>
                        <td><center><p class="text-success"><strong><?php echo $courier['success']; ?> шт.</strong></p></center></td>
                        <td><center><p class="text-error"><strong><?php echo $courier['unsuccess']; ?> шт.</strong></p></center></td>
                        <td><center><p class="text-error"><strong><?php echo $courier['cansel']; ?> шт.</strong></p></center></td>
                        <td><strong><a class="btn btn-mini btn-info pull-right">Просмотр</a></strong></td>
                    </tr>     
                <?php
                }
                ?>
                </tbody>
            </table>
            <?php } ?>
        </div>
    </div>
</div>