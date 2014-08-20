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
                <table class="table table-striped well table-hover">
                    <tbody>
                        <tr>
                            <th><center>Выбор отчета</center></th>
                            <th>
                                <div class="btn-group">
                                    <button class="btn"><?php echo $report_name ?></button>
                                    <button class="btn dropdown-toggle" data-toggle="dropdown">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/reports/zakaz_day">По заказам</a></li>
                                        <li><a href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/reports/courier_day">По курьерам</a></li>
                                    </ul>
                                </div>
                            </th>
                            <th/>
                        </tr>
                    <form method="post" action="">
                        <tr>
                            <?php echo $other_par ?>
                        </tr>
                        <tr>
                                <th><center>Начальная дата</center></th>
                                <td><input id="begin_date" name="begin_date" type="text" class="input-small" placeholder="Начальная дата" value="<?php echo $select_begin_date; ?>"/></td>
                                <td><input type="submit" value="Получить отчет" class="btn btn-info"/></td>
                        </tr>
                    </form>
                        <tr>
                            <th><center><a href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/reports/<?php echo $report_type;?>_day">За день</a></center></th>
                            <th><center><a href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/reports/<?php echo $report_type;?>_week">За неделю</a></center></th>
                            <th><center><a href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/reports/<?php echo $report_type;?>_mounth">За месяц</a></center></th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php echo $content; ?>
        </div>
    </div>
</div>