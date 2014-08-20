<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="/css/bootstrap.css" rel="stylesheet">

<link href="/css/bootstrap-responsive.min.css" rel="stylesheet">
        <script src="/js/jquery.js"></script>
        <title>Авторизация</title>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="span3 offset4 well">
                    <form method="post" action="" accept-charset="utf-8">
                        <div class="control-group">
                            <label for="username" class="control-label">ID:</label>
                            <div class="controls">
                                <input id="username" class="input-xlarge" type="text" name="id" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="password" class="control-label">Password:</label>
                            <div class="controls">
                                <input id="password" class="input-xlarge" type="password" name="password" />
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button class="btn btn-success btn-block btn-large" type="submit">Authentication</button>
                            </div>
                        </div>
                        <?php if (@$errors) { ?>
                        <div class="alert alert-error">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>Ошибка!</strong> <?= @$errors; ?>
                        </div>
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
        <script src="/js/bootstrap.min.js"></script>
    </body>
</html>