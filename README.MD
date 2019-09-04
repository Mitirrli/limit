<h1 align="center"> Limit </h1>
<p align="center">:rainbow: 基于redis的接口限制组件</p>

[![Build Status](https://travis-ci.org/overtrue/weather.svg?branch=master)](https://travis-ci.org/mitirrli/yunpian)
![StyleCI build status](https://github.styleci.io/repos/205969554/shield) 
[![Total Downloads](https://poser.pugx.org/mitirrli/limit/downloads)](https://packagist.org/packages/mitirrli/yunpian)
[![Latest Stable Version](https://poser.pugx.org/mitirrli/limit/v/stable)](https://packagist.org/packages/mitirrli/yunpian)
[![Latest Unstable Version](https://poser.pugx.org/mitirrli/limit/v/unstable)](https://packagist.org/packages/mitirrli/yunpian)
<a href="https://packagist.org/packages/mitirrli/limit"><img src="https://poser.pugx.org/mitirrli/limit/license" alt="License"></a>

## 环境需求

- PHP >= 5.6

## 安装

```shell
$ composer require "mitirrli/limit"
```

## 使用

```
$config = [
    'second' => 60,//多少秒
    'num' => 50,//接口请求次数
    'redis' => [//redis配置

    ],
];
        
$limit = new Limit($config);//限制接口请求次数
if (!$limit->run()) return['1005', '当前接口请求次数已达上限，请稍后重试'];
```
