<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Convert extends Controller
{
   public function __construct()
    {
      
    } 
    
   public function action_index() {
       /*$dsn = 'pgsql:host=localhost;dbname=omis';
       $username = 'omis';
       $password = '1';
       $bd = new PDO($dsn,$username,$password);
       $zapros = $bd->query('SELECT id FROM pghouses WHERE id = 1');
       var_dump($bd);
       var_dump($zapros);*/
       $postgre = Database::instance('postgre');
       setlocale(LC_NUMERIC, "C");
       for ($i=1;$i<10012;$i++) 
       {
              $houses = ORM::factory('House')
                     ->where('id', '=', $i)
                     ->find()
                     ->as_array();
              $lon = floatval($houses['lon']);
              $lat = floatval($houses['lat']);
              $id = $houses['id'];
              $id_socr = $houses['id_socr'];
              $id_street = $houses['id_street'];
              $name = $houses['name'];
              $postgre->query(Database::INSERT, 'INSERT INTO  pghouses VALUES ('. $id .', '. $id_socr .', '. $id_street .', \''. $name .'\', ST_GEOMFROMTEXT(\'POINT ('. $lon .' '. $lat .')\',0));');
       }
   }
}