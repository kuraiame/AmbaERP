<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <?php
        foreach ($css_array as $css)
        {
            echo '<link href="http://'.$_SERVER['HTTP_HOST'].$css.'" rel="stylesheet">';
        }
        foreach ($script_array as $script)
        {
            echo '<script src="http://'.$_SERVER['HTTP_HOST'].$script.'"></script>';
        }
        ?>
    <style>
        body {
        padding-top: 40px;
        padding-bottom: 40px;
        }
        .content {
            margin-top: 30px;
        }
    </style>
    </head>


    <body>
        <div class="navbar navbar-fixed-top navbar-inverse">
            <div class="navbar-inner">
                <div class="container">

                  <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                  <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </a>

                  <!-- Be sure to leave the brand out there if you want it shown -->
                  <a class="brand" href="#"><?php echo $title; ?></a>

                  <div class="nav-collapse">
                      <ul class="nav">
                          <?php 
                          if($access_array['zakaz'])
                          { ?>
                            <li class="dropdown"><a href="#" class='dropdown-toggle' data-toggle="dropdown" target="_blank">Заказы<b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?= URL::site('/zakaz'); ?>" target="_blank">Прием</a></li>
                                    <li><a href="<?= URL::site('/courier'); ?>" target="_blank">Управление</a></li>
                                </ul>
                            </li>
                    <?php }
                          ?>
                          
                          <?php 
                          if($access_array['menu'])
                          { ?>
                            <li class="text-error"><a href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/menu" target="_blank">Меню</a></li>
                    <?php }
                          ?>
                          <?php 
                          if($access_array['report'])
                          { ?>
                            <li class="text-error"><a href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/reports" target="_blank">Отчеты</a></li>
                    <?php }
                          ?>
                          <?php 
                          if($access_array['manage'])
                          { ?>
                            <li class="dropdown"><a href="<?= URL::site('/manage'); ?>" class='dropdown-toggle' data-toggle="dropdown" target="_blank">Управление<b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?= URL::site('/manage/delivery_boy'); ?>">Управление курьерами</a></li>
                                    <li><a href="<?= URL::site('/manage/users'); ?>">Управление пользователями</a></li>
                                </ul>
                            </li>
                    <?php }
                          ?>
                      </ul>
                    <!-- .nav, .navbar-search, .navbar-form, etc -->
                  </div>

                  <!-- Everything you want hidden at 940px or less, place within here -->
                  <div class="nav-collapse">
                      <ul class="nav pull-right">
                          <li class="divider-vertical"></li>
                          <li><a href="http://<?php echo $_SERVER['HTTP_HOST'] ?>/main/logout" class="text-warning">Выйти</a></li>
                      </ul>
                    <!-- .nav, .navbar-search, .navbar-form, etc -->
                  </div>

                </div>
            </div>
        </div>
    <?php

    echo $content;
    
    ?>
        <div class="navbar navbar-fixed-bottom">
            <div class="navbar-inner">
                <div class="container">

                  <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                  <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </a>

                  <!-- Be sure to leave the brand out there if you want it shown -->
                  <a class="brand" href="#">Амба пицца </a>

                  <!-- Everything you want hidden at 940px or less, place within here -->
                  <div class="nav-collapse">
                      
                    <!-- .nav, .navbar-search, .navbar-form, etc -->
                  </div>

                </div>
            </div>
        </div>
    </body>
</html>        