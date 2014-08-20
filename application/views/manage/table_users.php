                    <tr id="<?= @$status; ?>">
                        <td><?= @$id; ?></td>
                        <td><?= @$login; ?></td>
                        <td><?= @$name; ?></td>
                        <td><?= @$tel; ?></td>
                        <td><?= @$roles ?></td>
                        <td><?php
                                if (@$id != 9) {
                                    echo '
                                    <button class="btn btn-mini btn-success" type="button" name="change_password" id="'.@$id.'" title="Обновить">
                                        <i class="icon-refresh icon-white"></i>
                                    </button>                                    
                                    
                                    <button class="btn btn-mini btn-warning" type="button" name="change_groups" id="'.@$id.'" title="Изменить группы">
                                        <i class="icon-list icon-white"></i>
                                    </button>

                                    <button class="btn btn-mini btn-danger" type="button" name="block_user" id="'.@$id.'" title="Заблокировать доступ">
                                        <i class="icon-ban-circle icon-white"></i>
                                    </button>
                                    ';
                                }
                                else{
                                    echo '';
                                }
                            ?></td>
                    </tr>
