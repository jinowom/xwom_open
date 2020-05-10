<?php
/**
 * Created by PhpStorm.
 * User: wodrow
 * Date: 19-5-10
 * Time: 下午2:38
 */

namespace wodrow\yii2wtools\rewrite\yii2widgets;


class LinkPager extends \yii\widgets\LinkPager
{
    // 是否包含跳转功能跳转 默认false
    public $go = false;

    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }
        $buttons = [];
        $currentPage = $this->pagination->getPage();
        // first page
        $firstPageLabel = $this->firstPageLabel === true ? '1' : $this->firstPageLabel;
        if ($firstPageLabel !== false) {
            $buttons[] = $this->renderPageButton($firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);
        }
        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, false);
        }
        // internal pages
        list($beginPage, $endPage) = $this->getPageRange();
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->renderPageButton($i + 1, $i, null, false, $i == $currentPage);
        }
        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);
        }
        // last page
        $lastPageLabel = $this->lastPageLabel === true ? $pageCount : $this->lastPageLabel;
        if ($lastPageLabel !== false) {
            $buttons[] = $this->renderPageButton($lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);
        }
        // go
        if ($this->go) {
            $goPage = $currentPage + 2;
            $goHtml = <<<goHtml
                <div class="form" style="float: left; color: #999; margin-left: 10px; font-size: 12px;">
                    <span class="text">共 {$pageCount} 页</span>
                    <span class="text">到第</span>
                    <input class="input" type="number" value="{$goPage}" min="1" max="{$pageCount}" aria-label="页码输入框" style="text-align: center; height: 25px; line-height: 20px; margin-top: 5px; width: 46px;">
                    <span class="text">页</span>
                    <span class="btn go-page" role="button" tabindex="0" style="border: solid 1px #ccc; padding: 0px; height: 25px; width: 46px; line-height: 25px;">确定</span>
                </div>  
goHtml;
            $buttons[] = $goHtml;
            $pageLink = $this->pagination->createUrl(false);
            $goJs = <<<goJs
        $(document).on('click', '.go-page', function () {
                    var _this = $(this),
                        _pageInput = _this.siblings("input"),
                        goPage = _pageInput.val(),
                        pageLink = "{$pageLink}";
                        pageLink = pageLink.replace("{$this->pagination->pageParam}=1", "{$this->pagination->pageParam}="+goPage);
                    if (goPage >= 1 && goPage <= {$pageCount}) {
                        window.location.href=pageLink;
                    } else {
                        _pageInput.focus();
                    }
                });
goJs;
            $this->view->registerJs($goJs);
        }
        return Html::tag('ul', implode("\n", $buttons), $this->options);
    }
}