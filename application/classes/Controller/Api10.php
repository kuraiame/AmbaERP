<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Api10 extends Controller
{
	/**
	* Ключ API
	*/
	private $_apikey;

	public function before() {
		$this->_apikey = $this->request->param('apikey');
		if ($this->_apikey !== '834uHFBAIYb8woqnddqrd45CEfljwopf76wfvj')
			die('Wrong apikey!');
	}

	/**
	* Функция возвращает все данные таблицы из Menu содержащие 
	*
	* Пример запроса : http://devel.ambapizza.ru/api10/apikey/sync_products
	* 
	* @return JSON array
	*/
	public function action_sync_products() {
		$uts = $this->request->param('id');
		
		$menuORM = ORM::factory('Menu')->find_all();

		$result = array();
		foreach($menuORM as $value)
		{
			$result[] = array(
				'id' => $value->id,
				'name' => $value->name,
				'category' => $value->category,
				'size' => $value->size,
				'price' => $value->price,
				'state' => $value->state,
				);
		}

		 echo json_encode($result);
		 return;
	}

	/**
	* Принимает в формате JSON данные пользователя зарегистрированного в магазине.
	* 
	* 
	* Пример данных:
	* { 
	* id_house - идентификатор дома пользователя по справочнику ФИАС,
	* name - имя клиента,
	* tel - телефон клиента,
	* podiezd - подъезд,
	* flat - номер квартиры,
	* floor - этаж
	* }
	*
	* Возвращает данные
	* {'result' - как и очевидно результат выполнения операции, 'id' - идентификатор пользователя из КИС при успешной операции,
	* 'errno' - если операция выполнена неуспешо}
	* 
	* Пример запроса: http://devel.ambapizza.ru/api10/apikey/register_klient
	*
	* @return array JSON
	*/
	public function action_register_klient() {
		if ($_POST)
		{
			$val = array(
			'id_house' 	=> $this->request->post('id_house'),
			'name' 		=> $this->request->post('name'),
			'tel' 		=> $this->request->post('tel'),
			'podiezd' 	=> $this->request->post('podiezd'),
			'flat' 		=> $this->request->post('flat'),
			'floor' 	=> $this->request->post('floor')
			);

			$KlientORM = ORM::factory('Klient')->values($val)->save();

			if ($KlientORM->saved())
			{
				echo json_encode(array('result'=> 'success', 'id' => mysql_insert_id()));
			} else {
				echo json_encode(array('result' => 'error', 'errno' => NULL));
			}

			return;
		}
	}

	/**
	* Метод добавления заказа в КИС
	*
	*
	*
	* Пример запроса:
	*
	* http://devel.ambapizza.ru/api10/apikey/add_order
	*
	* array(
	*
	* 'nomer' - номер заказа. Взять можно по адресу http://devel.ambapizza.ru/api10/apikey/ordernumber,
	*
	* 'id_klient' - идентификатор клиента, возвращается функцией добавления клиента. Значит метод должен вызываться после нее,
	*
	* 'id_promo' - идентификатор промо-кода, получить можно http://devel.ambapizza.ru/api10/apikey/check_promo,
	*
	* 'date_dostavka' - передаем ТОЛЬКО количество секунд которое должно пройти к моменту доставки (не меньше 3600), это необходимо для избежания тупаницы со временем доставки если у серверов будут поразному настроены часы.
	*
	* 'summa' - сумма к уплате
	*
	* 'sale' - скидка
	*
	* 'sdacha' - передается сумма с которой нужно подготовить сдачу, именно сумма, а не разница между суммой к оплате и суммой с которой нужно подготовить сдачу.
	*
	* 'komment' - комментарий к заказу. Например: "позвонить когда курьер подъедет к подъезду" или "домофон не работает"
	*
	* 'positions' - массив с массивами в которых находится перечень пунктов меню
	* 
	* Состав массива positions
	*
	* array(
	*
	* array(
	*
	* 'id_zakaz' - идентификатор заказа, получить можно обратившись по адресу http://devel.ambapizza.ru/api10/apikey/ordernumber, 
	*
	* 'id_menu' - идентификатор товара, содержится в таблице Menu,
	*
	* 'komment' - комментарий к товару. Например: "Без лука",
	*
	* 'date_dostavka' - Время доставки, если ближайшее то 3600, если нет, то указываем количество секунд которое должно пройти с момента заказа до момента доставки, но не меньше часа,
	*
	* СОУС ПЕРЕДАЕТСЯ ОТДЕЛЬНОЙ ПОЗИЦИЕЙ В ЭТОМ МАССИВЕ
	*
	* ), array(...), array(...)
	*
	*
	* )
	*
	* 
	*
	* )
	* @return array JSON
	*/
	public function action_add_order() {
		if ($_POST)
		{
			$order = array(
				'nomer' 			=> $this->request->post('nomer'),
				'id_klient'			=> $this->request->post('id_klient'),
				'id_courier' 		=> 0,
				'id_promo' 			=> $this->request->post('id_promo'),
				'date_zakaz' 		=> date('Y-m-d H:i:s', time()),
				'summa' 			=> $this->request->post('summa'),
				'sale' 				=> $this->request->post('sale'),
				'sdacha' 			=> $this->request->post('sdacha'),
				'date_dostavka'		=> date('Y-m-d H:i:s', time() + (int) $this->request->post('date_dostavka')),
				'state' 			=> 'in_process',
				'date_end_sborka' 	=> NULL,
				'date_fact_dost' 	=> NULL,
				'koment' 			=> $this->request->post('komment')
				);

			$orderPositionJSON = $this->request->post('positions');

			foreach($orderPositionJSON as $opJSON) {
				$orderPosition[] = array(
				'id_zakaz' => $opJSON['id_zakaz'],
				'id_menu' => $opJSON['id_menu'],
				'koment' => $opJSON['komment'],
				'date_dostavka' => date('Y-m-d H:i:s', time() + (int) $opJSON['date_dostavka'])
				);
			}

			$orderORM 			= ORM::factory('Zakaz');
			$orderPositionORM 	= ORM::factory('Zakazposition');

			$orderORM->values($order)->save();

			if ($orderORM->saved())
			{

				foreach ($orderPositionJSON as $op)
				{
					$orderPositionORM->values($op)->save();
				}
				
				// Сохраняем +1 в таблицу с номером заказа
				Service::get_nomer_zakaz();

				//Работаем с промо-кодом
				//если поле промо-кода заполнено, значит нужно его закрыть
				if ($this->request->post('id_promo'))
				{
					$promocodeORM = ORM::factory('Promocode', array('id' => $this->request->post('id_promo')));
					$promocodeORM->state = 'closed';
					$promocodeORM->save();
				}

				echo json_encode(array('result' => 'success'));
				return;
			}
			else
			{
				echo json_encode(array('result' => 'error'));
				return;
			}

		}
	}


	/**
	* Возвращает номер следующего по очереди заказа.
	*
	* Во избежание накладок, должна вызываться только из скрипта, который сразу же отправит эти данные серверу.
	*
	*
	* TODO: Нужны транзакции
	* @return integer
	*/
	public function action_ordernumber()
	{
		echo Service::get_nomer_zakaz_without_saving();
		return;
	}

	/**
	* Проверяет промо-код
	*
	* Возвращает массив со следующими значениями:
	*
	* 'result' - результат выполнения запроса (success - в случае успеха, error - если такого промо-кода нет или он уже был использован),
	* 'promoId' - идентификатор промо-кода возвращаемый в случае если промо-код найден и активирован.
	*
	* @return array JSON
	*/
	public function action_check_promo()
	{
		if ($_POST)
		{
			$promo = $this->request->post('promo');

			$promocodeORM = ORM::factory('Promocode', array('name' => strtoupper($promo), 'state' => 'activated', 'id_action' => '1', 'type' => 'one'));

			if ($promocodeORM->loaded())
			{
				echo json_encode(array('result' => 'success', 'promoId' => $promocodeORM->id));
				return;
			}
			else
			{
				echo json_encode(array('result' => 'error'));
				return;
			}
		}
	}

}