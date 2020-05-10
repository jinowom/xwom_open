<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-5-10
 * Time: 下午3:14
 */

namespace wodrow\yii2wtools\rewrite\yii2bootstrap;


use yii\helpers\ArrayHelper;

class Nav extends \yii\bootstrap\Nav
{
    protected function isItemActive($item)
    {
        if (isset($item['url']) && isset($item['url'][0])) {
            if (is_array($item['url'])){
                $route = $item['url'][0];
                if ($route[0] !== '/' && \Yii::$app->controller) {
                    $route = \Yii::$app->controller->module->getUniqueId() . '/' . $route;
                }
                $_route = ltrim($route, '/');
                if (strpos($this->route, $_route)===0){
                    if($_route == $this->route){}else{
                        if (strpos($this->route,$_route."/")===0){}else{
                            return false;
                        }
                    }
                }else{
                    return false;
                }
                unset($item['url']['#']);
                if (count($item['url']) > 1) {
                    $params = $item['url'];
                    unset($params[0]);
                    foreach ($params as $name => $value) {
                        if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                            return false;
                        }
                    }
                }
                return true;
            }else{
                $x = \Yii::$app->controller->route;
                $y = ArrayHelper::str2arr($x, '/');
                $z = $y[0];
                $_active_url_pre = \Yii::$app->homeUrl."/{$z}";
                $item['url'];
                if (strpos($item['url'], $_active_url_pre) === false){}else{
                    return true;
                }
            }
        }

        return false;
    }
}