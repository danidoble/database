<?php
/*
 * Created by  (c)danidoble 2022.
 */

use Danidoble\Database\Sql;

require_once "autoload.php";

/* *******************************************************************
 *                          Insert new data
 ******************************************************************* */

echoCliWeb("Insert new data");

$user = new Sql();
$user->table("users");
$user->id("id");
$user->name = "tester";
$user->last_name = "tester";
$user->email = "test@example.com";
$user->password = password_hash('12345678', PASSWORD_DEFAULT);
$user = $user->save();
dump($user);