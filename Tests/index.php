<?php
include __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $users_select = new \Danidoble\Database\Sql();

    $users = $users_select->from("usuarios")
        ->where("fecha_nacimiento","1997-01-01",">=")
        //->where(1,1)
        ->where("estudios","licenciatura")
        //->where("nacionalidad","%mexicana%","like")
        ->where("nombre","%pedrin%","like")
        //->orderBy("nombre","desc")
        //->orderBy("id",'asc')
        ->first();

    dump($users);
    dump($users_select);
}
catch (Exception $e){
    dd($e);
}