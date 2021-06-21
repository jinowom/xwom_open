<?php
/**
 * @package   yii2-bootstrap4-dropdown
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015 - 2020
 * @version   1.0.1
 */

namespace kartik\bs4dropdown;

use yii\bootstrap4\Dropdown as Yii2Dropdown;

/**
 * Dropdown renders a Bootstrap dropdown menu component. This widget extends the default
 * `yii\bootstrap4\Dropdown` widget to include nested submenu behavior and styling.
 *
 * For example,
 *
 * ```php
 * <div class="dropdown">
 *     <?php
 *         echo \yii\helpers\Html::button('Dropdown Button', [
 *             'id' => 'dropdownMenuButton',
 *             'class' => 'btn btn-secondary dropdown-toggle'
 *             'data-toggle' => 'dropdown',
 *             'aria-haspopup' => 'true',
 *             'aria-expanded' => 'false'
 *         ]);
 *         echo Dropdown::widget([
 *             'items' => [
 *                 ['label' => 'Section 1', 'url' => '/'],
 *                 ['label' => 'Section 2', 'url' => '#'],
 *                 [
 *                      'label' => 'Section 3',
 *                      'items' => [
 *                          ['label' => 'Section 3.1', 'url' => '/'],
 *                          ['label' => 'Section 3.2', 'url' => '#'],
 *                          [
 *                              'label' => 'Section 3.3',
 *                              'items' => [
 *                                  ['label' => 'Section 3.3.1', 'url' => '/'],
 *                                  ['label' => 'Section 3.3.2', 'url' => '#'],
 *                              ],
 *                          ],
 *                      ],
 *                  ],
 *             ],
 *             'options' => ['aria-labelledby' => 'dropdownMenuButton']
 *         ]);
 *     ?>
 * </div>
 * ```
 * @see http://getbootstrap.com/javascript/#dropdowns
 */
class Dropdown extends Yii2Dropdown
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        DropdownAsset::register($this->getView());
        return parent::run();
    }
}
