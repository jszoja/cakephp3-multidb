# Dynamic database connection in CakePHP 3.x
This is a illustrates how your application can use separate database per model/table.

* It is assumed that the all dynamically created datasource connections are on the same host as defined in the default datasource
* The database name is passed within the session

## App\Model\Table\BaseTable
This class has the logic for handling dynamic datasource connections based on the session variable, which is defined in **BasicTable::$_defaultDb** static variable.
You can amend the static variables to adjust other settings:

```
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
 
 All tables which want to support this behavior must extend the BaseTable and define the if they want to use the default connection.
 
 ## Baking models
 To make it easier you may want to customize your baking template for tables, to have them all extend the BaseTable rather than Table class. It is subject to your requirements.
 The customized template is placed in src/Template/Bake/Model/table.twig.
 
 Alternatively you can use the [theme](https://book.cakephp.org/3.0/en/bake/development.html#creating-a-bake-theme) for bake.