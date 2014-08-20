<link href="http://devel.ambapizza.ru/css/bootstrap.css" rel="stylesheet">
<link href="http://devel.ambapizza.ru/css/bootstrap-responsive.min.css" rel="stylesheet">
<script src="http://devel.ambapizza.ru/js/jquery.js"></script>
<script src="http://devel.ambapizza.ru/js/bootstrap.min.js"></script>
<div class="well">
	<table class="table table-striped well table-hover">
		<caption><h1>Заказ №<?php echo $zakaz->nomer; ?></h1></caption>
		<tbody>
			<tr>
				<td><p class="pull-right">Адресс доставки</p></td>
				<td><p class="text-info"><strong><?php echo Service::get_full_adress($zakaz->id); ?> </strong></p></td>
				<td><input type="button" class="btn btn-mini btn-info pull-right" value="Править" disabled></td>
			</tr>
			<tr>
				<td><p class="pull-right">Время доставки</p></td>
				<td><p class="text-info"><strong><?php echo date('d.m.Y H:i', strtotime($zakaz->date_dostavka)); ?> </strong></p></td>
				<td><input type="button" class="btn btn-mini btn-info pull-right" value="Править" disabled></td>
			</tr>
			<tr>
				<td><p class="pull-right">Имя клиента</p></td>
				<td><p class="text-info"><strong><?php echo $zakaz->klient->name; ?> </strong></p></td>
				<td><input type="button" class="btn btn-mini btn-info pull-right" value="Править" disabled></td>
			</tr>
			<tr>
				<td><p class="pull-right">Телефон клиента</p></td>
				<td><p class="text-info"><strong><?php echo $zakaz->klient->tel; ?> </strong></p></td>
				<td><input type="button" class="btn btn-mini btn-info pull-right" value="Править" disabled></td>
			</tr>
			<tr>
				<td><p class="pull-right">Сдача с</p></td>
				<td><p class="text-info"><strong><?php echo $zakaz->sdacha; ?> </strong></p></td>
				<td><input type="button" class="btn btn-mini btn-info pull-right" value="Править" disabled></td>
			</tr>
			<tr>
				<td><p class="pull-right">Коментарий</p></td>
				<td><p class="text-info"><strong><?php echo $zakaz->koment; ?> </strong></p></td>
				<td><input type="button" class="btn btn-mini btn-info pull-right" value="Править" disabled></td>
			</tr>
			<tr>
				<td><p class="pull-right">Состояние</p></td>
				<td><p class="text-info"><strong><?php echo $zakaz->state; ?> </strong></p></td>
				<td><input type="button" class="btn btn-mini btn-info pull-right" value="Править" disabled></td>
			</tr>
		</tbody>
	</table>
	<br>
	<table class="table table-striped well table-hover">
		<caption><h1>Позиции заказа</h1></caption>
		<thead>
			<tr>
				<th class="input-large">Наименование</th>
				<th class="input-small">Размер</th>
				<th class="input-large"><center>Коментарий</center></th>
				<th class="input-small">Статус</th>
				<th class="input-small"></th>
			</tr>
		</thead>
		<tbody>
		<?php
			$positions = $zakaz->zakaz_position->find_all();
			foreach ($positions as $position)
			{ ?>
				<tr>
					<td> <p class="pull-left"><?php echo $position->menu->name; ?></p></td>
					<td> <p class="pull-left"><?php echo $position->menu->size; ?></p></td>
					<td> <p class="pull-left"><?php echo $position->koment; ?></p></td>
					<td> <p class="pull-left"><?php echo $position->state; ?></p></td>
					<td><input type="button" class="btn btn-mini btn-info pull-right" value="Править" disabled></td>
				</tr>
	  <?php } ?>
		</tbody>
	</table>
</div>


