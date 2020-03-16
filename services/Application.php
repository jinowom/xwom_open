<?php

namespace services;

use common\components\Service;

/**
 * Class Application
 *
 * @package services
 * @property \services\backend\BackendService $backend 系统
 */
class Application extends Service
{
    /**
     * @var array
     */
    public $childService = [
        /** ------ 系统 ------ **/
        'backend' => 'services\backend\BackendService',
        /** ------ 用户 ------ **/

        /** ------ 企业账户 ------ **/

        /** ------ API ------ **/

        /** ------ 公用部分 ------ **/

        /** ------ oauth2 ------ **/

    ];
}