<?php

use Danidoble\Database\Sql;

include __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {

/*
   $users = Sql::from("usuarios")
        //->debug(true)
        //->where("fecha_nacimiento","1997-01-01",">=")
        //->where("estudios","licenciatura")
        //->where("nacionalidad","%mexicana%","like")
        //->where("nombre","%pedrin%","like")
        //->orderBy("nombre","desc")
        //->orderBy("id",'asc')
        //->paginate(1,$_GET['page'])
        //->first()
        ->get()
        //->count()
;
   foreach($users as $user){
       dd($user,$users);
   }*/
   //echo $users;
    //dump($users);
/*
$user = new Sql();
$user->table("usuarios");
//$user->debug(true);
$user->nombre = "vaya vaya";
$user->fecha_nacimiento = "2000-01-01";
$user->nacionalidad = "japonesa";
$user->estudios = "preparatoria";
$user->id("id");
//$user->id = 123;
    //->id()// solo para actualizar
if($user->save()){
    echo $user->getItems();
}else{
    dd($user->getErrors());
}
*/

/*
    foreach ($users as $user) {
        echo $user;
        echo "<br>";
    }
    dump($users);
*/
}
catch (Exception $e){
    dd($e);
}