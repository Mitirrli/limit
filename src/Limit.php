<?php

/*
 * This file is part of the mitirrli/Limit.php
 *
 * (c) mitirrli <512663808@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Mitirrli\Limit;

use Predis\Client;

/**
 * 限制接口调用次数
 * Class Limit
 * @package Mitirrli\Limit
 */
class Limit
{
    /**
     * redis配置项
     *
     * @var array
     */
    protected $redisOptions = [];

    /**
     * 设置每多少秒为一个临界值
     *
     * @var int
     */
    protected $second;

    /**
     * 多少秒可以调用接口的次数
     *
     * @var int
     */
    protected $num;

    /**
     * Redis实例
     *
     * @var Client
     */
    protected $redis;

    /**
     * Redis键名
     *
     * @var string
     */
    protected $keyName = '';

    /**
     * Limit constructor.
     * @param int $second
     * @param int $num
     */
    public function __construct($second = 60, $num = 100)
    {
        $this->redis = new Client($this->redisOptions);

        $this->time = $second;
        $this->num = $num;
        $this->keyName = 'Mi_Limit_' . $this->time . $_SERVER['REMOTE_ADDR'];
    }

    /**
     * @return Client
     */
    public function getRedisClient()
    {
        return $this->redis;
    }

    /**
     * @param array $options
     */
    public function setRedisOptions(array $options)
    {
        $this->redisOptions = $options;
    }

    /**
     * 写入并判断是否可以调用
     */
    public function run()
    {
        $result = $this->get();

        return ($this->set($result) === false) ? false : true;
    }

    /**
     * @return bool|string
     */
    public function get()
    {
        if ($this->redis->exists($this->keyName)) {
            return $this->redis->get($this->keyName);
        } else {
            return false;
        }
    }

    /**
     * @param $result
     * @return bool
     */
    public function set($result)
    {
        if ($result !== false && $result >= $this->num) {
            return false;
        }
        if ($result === false) {
            $this->redis->set($this->keyName, 1, 'EX', $this->second);
        } else {
            $this->redis->incr($this->keyName);
        }
        return true;
    }
}
