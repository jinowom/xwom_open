<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-5-10
 * Time: 下午3:20
 */

namespace wodrow\yii2wtools\rewrite\dmstr;


use Yii;

/**
 * Class Menu
 * Theme menu widget.
 */
class Menu extends \dmstr\widgets\Menu
{
    /**
     * Checks whether a menu item is active.
     * This is done by checking if [[route]] and [[params]] match that specified in the `url` option of the menu item.
     * When the `url` option of a menu item is specified in terms of an array, its first element is treated
     * as the route for the item and the rest of the elements are the associated parameters.
     * Only when its route and parameters match [[route]] and [[params]], respectively, will a menu item
     * be considered active.
     * @param array $item the menu item to be checked
     * @return boolean whether the menu item is active
     */
    protected function isItemActive($item)
    {
        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            $route = $item['url'][0];
            if ($route[0] !== '/' && Yii::$app->controller) {
                $route = ltrim(Yii::$app->controller->module->getUniqueId() . '/' . $route, '/');
            }
            $route = ltrim($route,'/');
            if (strpos($this->route, $route)===0){
                if($route == $this->route){}else{
                    if (strpos($this->route,$route."/")===0){}else{
                        return false;
                    }
                }
            }else{
                return false;
            }
            unset($item['url']['#']);
            if (count($item['url']) > 1) {
                foreach (array_splice($item['url'], 1) as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }
}