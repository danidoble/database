<?php
/*
 * Created by  (c)danidoble 2021.
 */

namespace Danidoble\Database\Interfaces;
/**
 * Interface DObject
 * @package Danidoble\Database\Interfaces
 */
interface DObject
{
    /**
     * @param $items
     * @return mixed
     */
    public function assoc($items);

    /**
     * @param $items_arr
     * @return mixed
     */
    public function fetch($items_arr);

    /**
     * @return mixed
     */
    public function __toString();

    /**
     * @return mixed
     */
    public function __invoke();

    /**
     * DObject constructor.
     */
    public function __construct();
}