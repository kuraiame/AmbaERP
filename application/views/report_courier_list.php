            
        <th><center>Выбор курьера</center></th>
        <td calspan="2">
            <select name="id_courier">
            <?php foreach ($courier_list as $courier) {?>
                <option value="<?php echo $courier->id; ?>" <?php if($courier_id == $courier->id) echo 'selected'; ?>><?php echo $courier->name ?></option>
            <?php } ?>    
            </select>
        </td>