<?php
/*
 * Created by  (c)danidoble 2022.
 */

use Danidoble\Database\Sql;

require_once "autoload.php";

/* *******************************************************************
*                      soft delete data with select
******************************************************************* */

echoCliWeb("Update data with select");
$user = Sql::from("users")->first();
if (empty($user)) {
    dd("is empty");
}
$x = $user->delete();
dump($x);


/* *******************************************************************
*                      hard delete data with select
******************************************************************* */

echoCliWeb("Update data with select");
$user = Sql::from("users")->first();
if (empty($user)) {
    dd("is empty");
}
$x = $user->forceDelete();
dump($x);

