<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/bootstrap.css" rel="stylesheet">

<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
        <script src="js/jquery.js"></script>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="span6 offset3">
                    <br/>
                    <br/>
                    <form method="post" accept-charset="utf-8" class="form-horizontal well">
                        <div class="control-group">
                            <label for="username" class="control-label">Логин:</label>
                            <div class="controls">
                                <input id="username" type="text" name="username" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="password" class="control-label">Пароль:</label>
                            <div class="controls">
                                <input id="password" type="password" name="password" />
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button class="btn btn-success" type="submit">Войти</button>
                            </div>
                        </div>
                        <?php echo $error ?>
                    </form>
                </div>
            </div>
        </div>
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>