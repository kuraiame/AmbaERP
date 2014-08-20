<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="/css/bootstrap.css" rel="stylesheet">

<link href="/css/bootstrap-responsive.min.css" rel="stylesheet">
        <script src="/js/jquery.js"></script>
        <script src="/js/script_spy.js"></script>
    </head>

    <body>
        <div class="container">
            <div class="row"  style='padding-top:100px;'>
                <div class="span8 offset2">
                    <h2>Отслеживание заказов "Амба Пицца"</h2>
                </div>
                <div class="span8 offset2 well">
                    <form method="post" action="<?= @$url; ?>" accept-charset="utf-8" class=''>
                        <div class="control-group">
                            <div class="controls">
                                <label for="order_id" class="control-label">Введите номер заказа:</label>
                                <div >
                                    <input type='text' name='order_id' id="order_id" placeholder="1234" class="span8">
                                </div>
                            </div>
                            <button type="submit" class="btn-block btn-large btn-primary"><i class="icon-plane icon-white"></i> Где мой заказ?</button>
                            <?= @$error; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="/js/bootstrap.min.js"></script>
    </body>
</html>