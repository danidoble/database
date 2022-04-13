<?php
/*
 * Created by (c)danidoble 2022.
 */

namespace Danidoble\Database;

use Danidoble\Database\Exceptions\DatabaseCredentialsException;
use Danidoble\Database\Exceptions\DatabaseException;
use Danidoble\Database\Interfaces\DatabaseCredentials as IDatabaseCredentials;
use PDO;

class DatabaseCredentials implements IDatabaseCredentials
{
    protected string $host;
    protected string $user;
    protected string $pass;
    protected string $name;
    protected int $pdo_error_mode = PDO::ERRMODE_EXCEPTION;

    /**
     * @param string|null $db_host
     * @param string|null $db_user
     * @param string|null $db_pass
     * @param string|null $db_name
     */
    public function __construct(?string $db_host = null, ?string $db_user = null, ?string $db_pass = null, ?string $db_name = null)
    {
        if ($db_host !== null) {
            $this->host = $db_host;
        }
        if ($db_name !== null) {
            $this->name = $db_name;
        }
        if ($db_user !== null) {
            $this->user = $db_user;
        }
        if ($db_pass !== null) {
            $this->pass = $db_pass;
        }
    }

    /**
     * @param string $db_host
     * @return void
     */
    public function setHost(string $db_host): void
    {
        $this->host = $db_host;
    }

    /**
     * @param string $db_user
     * @return void
     */
    public function setUser(string $db_user): void
    {
        $this->user = $db_user;
    }

    /**
     * @param string $db_pass
     * @return void
     */
    public function setPass(string $db_pass): void
    {
        $this->pass = $db_pass;
    }

    /**
     * @param string $db_name
     * @return void
     */
    public function setName(string $db_name): void
    {
        $this->name = $db_name;
    }

    /**
     * @param int $error_mode
     * @return void
     * @throws DatabaseException
     */
    public function setPDOErrorMode(int $error_mode = PDO::ERRMODE_EXCEPTION)
    {
        $this->pdo_error_mode = match ($error_mode) {
            PDO::ERRMODE_SILENT,
            PDO::ERRMODE_WARNING,
            PDO::ERRMODE_EXCEPTION
                => $error_mode,
            default => throw new DatabaseException("Error mode invalid, see: https://www.php.net/manual/es/pdo.error-handling.php"),
        };
    }

    /**
     * @return string|null
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * @return string|null
     */
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @return string|null
     */
    public function getPass(): ?string
    {
        return $this->pass;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPDOErrorMode(): int
    {
        return $this->pdo_error_mode;
    }

    /**
     * @return void
     * @throws DatabaseCredentialsException
     */
    public function check(): void
    {
        if (!$this->validate('host')) {
            throw new DatabaseCredentialsException("Name of database is required. Add param 'name'");
        }
        if (!$this->validate('pass', false)) {
            throw new DatabaseCredentialsException("Password of connection is required. Add param 'pass'");
        }
        if (!$this->validate('user')) {
            throw new DatabaseCredentialsException("Username of database is required. Add param 'user'");
        }
        if (!$this->validate('name')) {
            throw new DatabaseCredentialsException("Host of connection is required. Add param 'host'");
        }
    }

    /**
     * @param string $name
     * @param bool $trimmer
     * @return bool
     */
    private function validate(string $name, bool $trimmer = true): bool
    {
        if ($this->{$name} !== null && trim($this->{$name}) !== "") {
            return true;
        }
        if ($trimmer === false && $this->{$name} !== null) {
            return true;
        }
        return false;
    }
}