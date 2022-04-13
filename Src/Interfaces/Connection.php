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
     * @return $this
     */
    public function setDbHost($db_host): static;

    /**
     * @param $db_name
     * @return $this
     */
    public function SetDbName($db_name): static;

    /**
     * @param $db_username
     * @return $this
     */
    public function SetDbUsername($db_username): static;

    /**
     * @param $db_password
     * @return $this
     */
    public function SetDbPassword($db_password): static;

    /**
     * @return $this
     */
    public function closeConnection(): static;

    /**
     * @param $exception
     * @return $this
     */
    public function connect($exception): static;

}