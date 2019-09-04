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

use Mitirrli\Limit\Redis\UserRedis;

/**
 * 限制接口调用次数
 * Class Limit.
 */
class Limit
{
    /**
     * 配置项.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Limit constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->options = $attributes;
    }

    /**
     * 判断是否可以调用.
     *
     * @return bool
     */
    public function run()
    {
        $redis = new UserRedis($this->options);

        return $redis->run() || false;
    }
}
