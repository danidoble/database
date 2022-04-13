<?php
/*
 * Created by (c)danidoble 2022.
 */

namespace Danidoble\Database\Interfaces;

use Danidoble\Database\Connection as CConnection;

/**
 * Interface Connection
 * @package Connection\Connection\Interfaces
 */
interface Connection
{
    /**
     * @param $db_host
     * @return CConnection
     */
    public function setDbHost($db_host): CConnection;

    /**
     * @param $db_name
     * @return CConnection
     */
    public function SetDbName($db_name): CConnection;

    /**
     * @param $db_username
     * @return CConnection
     */
    public function SetDbUsername($db_username): CConnection;

    /**
     * @param $db_password
     * @return CConnection
     */
    public function SetDbPassword($db_password): CConnection;

    /**
     * @return CConnection
     */
    public function closeConnection(): CConnection;

    /**
     * @param $exception
     * @return CConnection
     */
    public function connect($exception): CConnection;

}