<?php
/*
 * Created by  (c)danidoble 2021.
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
     * @return mixed
     */
    public function setDbHost($db_host);

    /**
     * @param $db_name
     * @return mixed
     */
    public function SetDbName($db_name);

    /**
     * @param $db_username
     * @return mixed
     */
    public function SetDbUsername($db_username);

    /**
     * @param $db_password
     * @return mixed
     */
    public function SetDbPassword($db_password);

    /**
     * @return mixed
     */
    public function closeConnection();

    /**
     * @param $exception
     * @return mixed
     */
    public function connect($exception);

}