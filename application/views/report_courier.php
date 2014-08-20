<?php if(count($report_couriers) != 0)
{ ?>
            <table class="table table-striped well table-hover">
                <caption><h1>Отчет по курьерам за <?php echo $period_name; ?></h1></caption>
                <tr>
                    <th class="span3"><center>Номер заказа</center></th>
                    <th class="span5"><center>Выручка</center></th>
                    <th class="span5"><center>Количество пицц</center></th>
                    <th class="span5"><center>Время доставки</center></th>
                </tr>
                <?php
                foreach($report_couriers as $report_courier)
                {
                    echo '<tr ';
                    switch ($report_courier['state'])
                    {
                        case 'success': echo 'class="success"'; break;
                        case 'unsuccess': echo 'class="error"'; break;
                    }
                    echo '>';
                    ?>
                        <td><?php echo $report_courier['id']; ?></td>
                        <td><center><?php echo $report_courier['summa']; ?></center></td>
                        <td><center><?php echo $report_courier['count']; ?></center></td>
                        <td><center><?php echo $report_courier['time_dostavka']; ?></center></td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td><center>Всего заказов</center></td>
                    <td><center>Выручка ИТОГО</center></td>
                    <td><center>Всего пицц</center></td>
                    <td><center>Общее время</center></td>
                </tr>
                <tr>
                    <td><center>(<span class="text-success"><?php echo $all_zakaz_success ?></span>)(<span class="text-error"><?php echo $all_zakaz_unsuccess ?></span>)</center></td>
                    <td><center><?php echo $all_summa ?></center></td>
                    <td><center><?php echo $all_pizza ?></center></td>
                    <td><center><?php echo $all_time ?></center></td>
                </tr>
            </table>
<?php }?>            
