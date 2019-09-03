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
use Mitirrli\YunPian\Exceptions\LimitException;

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

        $this->second = $second;
        $this->num = $num;
        $this->keyName = 'Mi_Limit_' . $this->second . '_' . $_SERVER['REMOTE_ADDR'];
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
     * 判断是否可以调用
     * @throws LimitException
     */
    public function run()
    {
        $result = $this->get();

        $this->set($result);
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
     * @throws LimitException
     */
    public function set($result)
    {
        if ($result !== false && $result >= $this->num) {
            throw new LimitException('当前调用接口次数过多,请稍候再试', 1004);
        }
        if ($result === false) {
            $this->redis->setex($this->keyName, $this->second, 1);
        } else {
            $this->redis->incr($this->keyName);
        }
    }
}
