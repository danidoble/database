<?php
/*
 * Created by (c)danidoble 2022.
 */

namespace Danidoble\Database\Interfaces;

/**
 * Interface Connection
 * @package Connection\Connection\Interfaces
 */
interface Connection
{
    /**
     * @param $db_host
     * @return Connection
     */
    public function setDbHost($db_host): Connection;

    /**
     * @param $db_name
     * @return Connection
     */
    public function SetDbName($db_name): Connection;

    /**
     * @param $db_username
     * @return Connection
     */
    public function SetDbUsername($db_username): Connection;

    /**
     * @param $db_password
     * @return Connection
     */
    public function SetDbPassword($db_password): Connection;

    /**
     * @return Connection
     */
    public function closeConnection(): Connection;

    /**
     * @param $exception
     * @return Connection
     */
    public function connect($exception): Connection;

}