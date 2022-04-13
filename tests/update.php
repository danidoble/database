<?php
/*
 * Created by  (c)danidoble 2022.
 */

use Danidoble\Database\Sql;

require_once "autoload.php";

/* *******************************************************************
*                      Update data with select
******************************************************************* */

echoCliWeb("Update data with select");
$user = Sql::from("users")->first();

$user->name = "filomena";
$user->last_name = "robles";
$user->password = password_hash("12345678", PASSWORD_DEFAULT);
$user->save();

dump($user);

/* *******************************************************************
*                      Update data without get
******************************************************************* */

echoCliWeb("Update data without get");
//$update = (new Sql())->table('users')
$update = Sql::from('users')
    ->debug(true)
    ->set('name', "Gabriel")
    ->set('last_name', "Bustos")
    ->where('name', 'fernanda', '<>')
    ->where('created_at', date('Y-m-d') . ' 00:00:00', '>=')
    ->update();

dd($update);
