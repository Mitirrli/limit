<?php

/*
 * This file is part of the mitirrli/RedisInterface.php
 *
 * (c) mitirrli <512663808@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

interface RedisInterface
{
    /**
     * Get redis instance.
     *
     * @return mixed
     */
    public function getRedisClient();

    /**
     * Get Key.
     *
     * @return mixed
     */
    public function getKey();

    /**
     * Set Key.
     *
     * @param $result
     *
     * @return mixed
     */
    public function setKey($result);
}
