<?php
/*
 * Created by (c)danidoble 2022.
 */

namespace Danidoble\Database\Interfaces;

use Danidoble\Database\Exceptions\DatabaseCredentialsException;
use PDO;

interface DatabaseCredentials
{

    /**
     * @param string $db_host
     * @return void
     */
    public function setHost(string $db_host): void;


    /**
     * @param string $db_user
     * @return void
     */
    public function setUser(string $db_user): void;


    /**
     * @param string $db_pass
     * @return void
     */
    public function setPass(string $db_pass): void;


    /**
     * @param string $db_name
     * @return void
     */
    public function setName(string $db_name): void;

    /**
     * @param int $error_mode
     * @return void
     */
    public function setPDOErrorMode(int $error_mode = PDO::ERRMODE_EXCEPTION);

    /**
     * @return ?string
     */
    public function getHost(): ?string;


    /**
     * @return ?string
     */
    public function getUser(): ?string;


    /**
     * @return ?string
     */
    public function getPass(): ?string;


    /**
     * @return ?string
     */
    public function getName(): ?string;

    /**
     * @return int
     */
    public function getPDOErrorMode(): int;

    /**
     * @return void
     * @throws DatabaseCredentialsException
     */
    public function check(): void;

}