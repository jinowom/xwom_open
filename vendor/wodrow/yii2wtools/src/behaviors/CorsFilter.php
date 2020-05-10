<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 18-12-19
 * Time: 上午9:04
 */

namespace wodrow\yii2wtools\behaviors;


use yii\filters\Cors;

class CorsFilter extends Cors
{
    public $cors = [
        'Origin' => ['*'],
        // restrict access to
        'Access-Control-Allow-Origin' => ['*'],
        'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        // Allow only POST and PUT methods
        'Access-Control-Request-Headers' => ['*'],
        // Allow only headers 'X-Wsse'
        'Access-Control-Allow-Credentials' => true,
        // Allow OPTIONS caching
//        'Access-Control-Max-Age' => 86400,
        // Allow the X-Pagination-Current-Page header to be exposed to the browser.
//        'Access-Control-Expose-Headers' => [],
    ];
}