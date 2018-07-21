<?php
namespace App\Model\Table;

use Cake\Database\Exception\MissingConnectionException;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\Table;
use Cake\Datasource\Exception\MissingDatasourceConfigException;

/**
 * Class BaseTable
 *
 * Provided an interface for dynamic DB connections
 * based on the session variable
 *
 * @package App\Model\Table
 */
class BaseTable extends Table
{
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


    /**
     * Check what connection is used
     * - if defaultDb, then use the default connection
     * - otherwise use the company/dynamic connection
     *
     * Company connection is created on the fly
     * and only once, if it doesn't exists yet.
     * It is based on the session variable defined in self::$__SESSION_CONNECTION_VAR
     *
     * @return string
     */
    public static function defaultConnectionName()
    {
        // using default connection
        if( static::$_defaultDb )
            return 'default';

        try {
            // check if the company connection exists
            ConnectionManager::get(self::$__CONNECTION_NAME);
        } catch( MissingDatasourceConfigException $e )
        {
            // create the company connection on the fly
            self::__createCompanyConnection();
        }


        return self::$__CONNECTION_NAME;
    }


    /**
     * Creates the company/dynamic connection on the fly
     *
     * @throws MissingConnectionException
     */
    private static function __createCompanyConnection()
    {
        // check if there is relevant sessioin variable set
        if( empty( $_SESSION[ self::$__SESSION_CONNECTION_VAR ] ) )
            throw new MissingConnectionException('Missing company session for DB connection!');
        // copy the default connection and set the database acording to the session variable
        $dbConf = ConnectionManager::getConfig('default');
        $dbConf['database'] = $_SESSION[ self::$__SESSION_CONNECTION_VAR ];
        ConnectionManager::setConfig( self::$__CONNECTION_NAME, $dbConf );
    }

}
