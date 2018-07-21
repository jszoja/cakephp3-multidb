# Dynamic database connection in CakePHP 3.x
This sample project illustrates how your application can use a separate database per model/table.

* It is assumed that the all dynamically created datasource connections are on the same host as defined in the default datasource
* The database name is passed within the session

## How it works
AppController::beforeFilter() creates the company datasource on the fly based on the session variable:
```php
 /**
  * Create the company connection based on the session var
  */
private function __createCompanyConnection()
{
    $company = $this->request->getSession()->read('company');
    if( empty($company) )
        return;

    $dbConf = ConnectionManager::getConfig('default');
    $dbConf['database'] = $company;
    ConnectionManager::setConfig( 'company', $dbConf );
}
```

Relevant Table classes require company connection within Table::defaultConnectionName():
```php
public static function defaultConnectionName()
{
    return 'company';
}
```
 
 
 ## Working sample
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
#### 2. Go to url /users/index?db=client1
Page should list users from the database **client1**
#### 3. Go to url /users/index
Page should list users from the database **client1** as we set this database in session in the previous step.
#### 4. Go to url /users/index?db=core
Page should list users from the default database this time
 

 