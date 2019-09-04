<?php

/*
 * This file is part of the mitirrli/UserRedis.php
 *
 * (c) mitirrli <512663808@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mitirrli\Limit\Redis;

use Mitirrli\Limit\Contracts\RedisInterface;
use Predis\Client;

class UserRedis implements RedisInterface
{
    /**
     * Redis Key Name.
     */
    const KEY_NAME = 'mi_Limit_%s_%s';

    /**
     * redis Option.
     *
     * @var
     */
    private $redis;

    /**
     * @var
     */
    private $second = 60;

    /**
     * @var
     */
    private $num = 50;

    /**
     * @var Client
     */
    private $instance;

    /**
     * UserRedis constructor.
     *
     * @param $redisOptions
     * @param $second
     */
    public function __construct(array $attributes)
    {
        foreach ($attributes as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    /**
     * Get Redis Instance.
     *
     * @return mixed|void
     */
    public function getRedisClient()
    {
        $this->instance = new Client($this->redis);
    }

    /**
     * Get Key.
     *
     * @return bool|mixed|string
     */
    public function getKey()
    {
        return ($this->instance->exists($this->keyName())) ? $this->instance->get($this->keyName()) : false;
    }

    /**
     * Set Key.
     *
     * @param $result
     *
     * @return bool|mixed
     */
    public function setKey($result)
    {
        if ($result !== false && $result >= $this->num) {
            return false;
        }

        if ($result === false) {
            $this->instance->setex($this->keyName(), $this->second, 1);
        } else {
            $this->instance->incr($this->keyName());
        }

        return true;
    }

    /**
     * Run.
     *
     * @return bool|mixed
     */
    public function run()
    {
        $this->getRedisClient();

        $result = $this->getKey();

        return $this->setKey($result);
    }

    /**
     * Return Key Name.
     *
     * @return string
     */
    public function keyName()
    {
        return sprintf(self::KEY_NAME, $this->second, $_SERVER['REMOTE_ADDR']);
    }
}
