            <table class="table table-striped well table-hover">
                <caption><h1>Заказы за <?php echo $period_name; ?></h1></caption>
                <thead>
                    <tr>
                        <th class="input-large"></th>
                        <th class="input-large"><center>Текущеий период</center></th>
                        <th class="input-large"><center>Прирост за прошлый период</center></th>
                    </tr>
                    <tr>
                        <th class="input-large"></th>
                        <th class="input-large"><center>c <?php echo $begin_date; ?> <br> по <?php echo $end_date; ?></center></th>
                        <th class="input-large"><center>
                        <?php
                            if($prev_period == true)
                                echo 'с ' . $prev_begin_date . '<br> по ' . $prev_end_date;
                        ?>
                        </center></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Выручка:</td>
                        <td><center><p class="text-info"><strong><?php echo $report_all_summa; ?> р</strong></p></center></td>
                        <td><center>
                        <?php
                            if($prev_period == true)
                            {
                                if($prev_report_all < 0)
                                    echo '<p class="text-error">';
                                if($prev_report_all > 0)
                                    echo '<p class="text-success">';
                                if($prev_report_all == 0)
                                    echo '<p class="text-info">';
                                echo '<strong>( '.$prev_report_all.' % )';
                            }
                            else
                                echo '<p class="text-info"><strong> ( - )';
                        ?>
                        </strong></p></center></td>
                    </tr>     
                    <tr>
                        <td>Количество заказов:</td>
                        <td><center><p class="text-info"><strong><?php echo $report_all_count; ?> шт.</strong></p></center></td>
                        <td><center>
                        <?php
                            if($prev_period == true)
                            {
                                if($prev_report_position < 0)
                                    echo '<p class="text-error">';
                                if($prev_report_position > 0)
                                    echo '<p class="text-success">';
                                if($prev_report_position == 0)
                                    echo '<p class="text-info">';
                                echo '<strong>( '.$prev_report_position.' % )';
                            }
                            else
                                echo '<p class="text-info"><strong>( - )';
                        ?>
                        </strong></p></center></td>
                    </tr>     
                    <tr>
                        <td>Количество невыполненых заказов:</td>
                        <td><center><p class="text-info"><strong><?php echo $report_unsuccess_count; ?> шт.</strong></p></center></td>
                        <td><center>
                        <?php
                            if($prev_period == true)
                            {
                                if($prev_report_unsuccess > 0)
                                    echo '<p class="text-error">';
                                if($prev_report_unsuccess < 0)
                                    echo '<p class="text-success">';
                                if($prev_report_unsuccess == 0)
                                    echo '<p class="text-info">';
                                echo '<strong>( '.$prev_report_unsuccess.' % )';
                            }
                            else
                                echo '<p class="text-info"><strong>( - )';
                        ?>
                        </strong></p></center></td>
                    </tr>     
                    <tr>
                        <td>Средний чек:</td>
                        <td><center><p class="text-info"><strong><?php echo $report_all_middle; ?> р.</strong></p></center></td>
                        <td></td>
                    </tr>     
                </tbody>
            </table>
            
            <br/>
            <table class="table table-striped well table-hover">
                <caption><h1>Позиции меню</h1></caption>
                <tr>
                    <th class="span3"><center>Наименование</center></th>
                    <th class="span5"><center>Количество</center></th>
                </tr>
                <?php
                foreach($report_positions as $report_position)
                {?>
                    <tr>
                        <td><?php echo $report_position['name']; ?></td>
                        <td><center><?php echo $report_position['count']; ?></center></td>
                    </tr>
                <?php
                }
                ?>
            </table>