                    <tr>
                        <td><?= @$id; ?></td>
                        <td id="name_<?= @$id; ?>"><?= @$name; ?></td>
                        <td>
                            <span id="cur_<?= @$id; ?>">
                            <?= @$password; ?>
                            </span>
                        </td>
                        <td>
                            <?php
                                if (@$id != 0) {
                                    echo '
                                    <button class="btn btn-mini btn-success" type="button" name="gen" id="'.@$id.'" title="Сгенерировать новый пароль">
                                        <i class="icon-refresh icon-white"></i>
                                    </button>                                    
                                    <button class="btn btn-mini btn-danger" type="button" name="cur_block" id="'.@$id.'" title="Заблокировать доступ">
                                        <i class="icon-ban-circle icon-white"></i>
                                    </button>      
                                    ';
                                }
                            ?>                         
                        </td>
                    </tr>
