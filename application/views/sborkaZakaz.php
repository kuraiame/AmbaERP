<table class="table table-bordered">

    <?php 
    $flag = false;

    if($koment != '')
    {
        echo '<tr class="info">';
        echo '<td>Коментарий к заказу:<h4>'.$koment.'</h4></td>';
        echo '</tr>';
    }

    foreach ($done as $item)
    {
        echo '<tr class="success" onClick="set_state_collected('.$item->id.')">';
        echo '<td><span class="label label-success"><h4>'.$item->menu->name.' '.$item->menu->size.'</h4></span><br>';
        if ($item->koment != '') echo 'Коментарий:<h4>'.$item->koment.'</h4>';
        echo '</td>';
        echo '</tr>';
        $flag = true;
    }

    foreach ($not_done as $item)
    {
        echo '<tr class="warning">';
        echo '<td><span class="label label-warning"><h4>'.$item->menu->name.' '.$item->menu->size.'</h4></span><br>';
        if ($item->koment != '') echo 'Коментарий:<h4>'.$item->koment.'</h4>';
        echo '</td>';
        echo '</tr>';
        $flag = true;
    }

    foreach ($collected as $item)
    {
        echo '<tr class="info">';
        echo '<td><span class="label"><h4>'.$item->menu->name.' '.$item->menu->size.'</h4></span><br>';
        if ($item->koment != '') echo 'Коментарий:<h4>'.$item->koment.'</h4>';
        echo '</td>';
        echo '</tr>';
    }


?>
</table>
<?php
    if($flag == false)
        echo '<button class="bnt" onclick="print_check('.$id_zakaz.')">Печатать чек</button>'
?>
<script>
    function set_state_collected(id)
    {
        $.post('ajax/sborka_end_sborka', {'id':id}, 
                     function (response){
                         if(response == "1")
                             load_zakaz();
                     },
                     'json'
                     );
    }
    
    function print_check(id)
    {
        $.post('ajax/print_check', {'id_zakaz':id}, 
                     function (response){
                     },
                     'json'
                     );
    }
</script>
