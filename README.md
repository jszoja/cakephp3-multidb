# Dynamic database connection in CakePHP 3.x
This sample project illustrates how your application can use a separate database per model/table.

* It is assumed that the all dynamically created datasource connections are on the same host as defined in the default datasource
* The database name is passed within the session

## App\Model\Table\BaseTable
This class has the logic for handling dynamic datasource connections based on the session variable, which is defined in **BasicTable::$_defaultDb** static variable.
You can amend the static variables to adjust other settings:

```php
/**
  * @var bool if TRUE then table will use the default datasource connection
  */
 protected static $_defaultDb = false;

 /**
  * @var string name of the session variable, which contains database name for a connection
  */
 private static $__SESSION_CONNECTION_VAR = 'company';

 /**
  * @var string name of the datasource connection for ConnectionManager
  */
 private static $__CONNECTION_NAME = 'company';
 ```
 
 All tables which want to support this behavior must extend the BaseTable and specify if they want to use the default connection or the dynamic one based on the session.
 
 ## Baking models
 To make it easier you may want to customize your baking template for tables, to have them all extend the BaseTable rather than the Table class. It is subject to your requirements.
 The customized template is placed in src/Template/Bake/Model/table.twig. From this location it is respected by the baking shell. Remove the file if you prefer the default shell. 
 
 Alternatively you can use the [theme](https://book.cakephp.org/3.0/en/bake/development.html#creating-a-bake-theme) for bake.
 
 ## How to test
#### 1. Create two databases with the 'users' table and populate theirs records:
 ```mysql
 CREATE TABLE `users` (
   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
   `username` varchar(45) NOT NULL,
   `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
   PRIMARY KEY (`id`),
   UNIQUE KEY `username_UNIQUE` (`username`),
   UNIQUE KEY `id_UNIQUE` (`id`)
 ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
```
#### 2. Go to url /users/index/client1
Page should list users from the database **client1**
#### 3. Go to url /users/index
Page should list users from the database **client1** as we set this database in session in the previous step.
#### 4. Amend UsersTable settings
Set the BasicTable::$_defaultDb to TRUE
#### 5. Go to url /users/index
Page should list users from the default database this time
 

 