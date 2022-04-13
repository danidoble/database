# database

Mini ORM

## installation

```
composer require danidoble/database
```

or

```
composer.phar require danidoble/database
```

## Configuration

If you use a loader of credentials like ```vlucas/phpdotenv``` add inside of file ```.env``` add this credentials

* ```DB_NAME="YOUR_DB_NAME"```
* ```DB_USER="YOUR_DB_USER"```
* ```DB_HOST="YOUR_DB_HOST"```
* ```DB_PASS="YOUR_DB_PASS"```

If you don't use libraries to load credentials you can add in your code ``` NOT RECOMMENDED```

* ```$_ENV['DB_NAME']="YOUR_DB_NAME";```
* ```$_ENV['DB_USER']="YOUR_DB_USER";```
* ```$_ENV['DB_HOST']="YOUR_DB_HOST";```
* ```$_ENV['DB_PASS']="YOUR_DB_PASS";```

## How to use

You can configurate name of id doing: ```Sql::from('users')->id('name_id')```

### GET

To get all data

```php 
use Danidoble\Database\Sql;

$users = Sql::from('users')->get();

var_dump($users);
```

To paginate data

```php 
use Danidoble\Database\Sql;

$users = Sql::from('users')->get();

var_dump($users);
```

Get only one

```php 
use Danidoble\Database\Sql;

$user = Sql::from('users')->first();

var_dump($user);
```

Get only selected by id

```php 
use Danidoble\Database\Sql;

$user = Sql::from('users')->find(1);

var_dump($user);
```

### INSERT

```php 
use Danidoble\Database\Sql;

$user = new Sql();
$user->name = "Gregory";
$user->last_name = "Hui";
$user->save();
```

### UPDATE

update after get

```php 
use Danidoble\Database\Sql;

$user = Sql::from('users')->id('name_id')->find(1);
$user->name = "somthing else";
$user->save();
```

update without get

```php 
use Danidoble\Database\Sql;

$user = Sql::from('users')
    ->set('name','Pedro')
    ->set('last_name','Crox')
    ->where('name','Gregory','=')
    ->update();
```

### DELETE

soft delete (needed field ```deleted_at``` in database) otherwise is deleted

```php 
use Danidoble\Database\Sql;

$user = Sql::from('users')->find(1);
$user->delete();
```

hard delete

```php 
use Danidoble\Database\Sql;

$user = Sql::from('users')->find(1);
$user->forceDelete();
```
