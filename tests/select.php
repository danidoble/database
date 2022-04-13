<?php
/*
 * Created by  (c)danidoble 2022.
 */

use Danidoble\Database\Sql;

require_once "autoload.php";

/* *******************************************************************
 *              Conditions where, multiple responses
 ******************************************************************* */

echoCliWeb("Paginated");
$users = Sql::from("users")
    //->debug(true)
    ->where('created_at', date('Y-m-d') . ' 00:00:00', "<=")
    ->where('name', '%fer%', 'like')
    ->paginate(6,1);

dump($users);

/* *******************************************************************
 *              Conditions where, multiple responses
 ******************************************************************* */

echoCliWeb("Conditions where, multiple responses");
$users = Sql::from("users")
    ->where('created_at', date('Y-m-d') . ' 00:00:00', "<=")
    ->where('name', '%fer%', 'like')
    ->limit(5)
    ->get();

dump($users);


/* *******************************************************************
 *              Conditions with limit and offset
 ******************************************************************* */

echoCliWeb("Conditions with limit and offset");
$users = Sql::from("users")
    ->limit(10)
    ->offset(10)
    ->get();

dump($users);

/* *******************************************************************
 *                  First, only give one register
 ******************************************************************* */

echoCliWeb("First, only give one register");
$user = Sql::from("users")
    ->where('created_at', date('Y-m-d') . ' 00:00:00', "<=")
    ->where('name', '%fer%', 'like')
    ->first();

dump($user);


/* *******************************************************************
 *                      First, return no data
 ******************************************************************* */

echoCliWeb("First, return no data");
$user = Sql::from("users")->first();
dump($user);


/* *******************************************************************
 *                      First, return no data
 ******************************************************************* */

echoCliWeb("Find by Id");
$user = Sql::from("users")->find(1);
dump($user);

