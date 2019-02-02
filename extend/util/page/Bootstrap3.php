<?php
namespace util\page;

use think\Paginator;

class Bootstrap3 extends Paginator
{

    public $rollPage=5;//分页栏每页显示的页数
    
    public $showPage=5;//总页数超过多少条时显示的首页末页
    /**
     * 上一页按钮
     * @param string $text
     * @return string
     */
    protected function getPreviousButton($text = "上一页")
    {

        if ($this->currentPage() <= 1) {
            return $this->getDisabledTextWrapper($text);
        }

        $url = $this->url(
            $this->currentPage() - 1
        );

        return $this->getPageLinkWrapper($url, $text);
    }

    /**
     * 下一页按钮
     * @param string $text
     * @return string
     */
    protected function getNextButton($text = '下一页')
    {
        if (!$this->hasMore) {
            return $this->getDisabledTextWrapper($text);
        }

        $url = $this->url($this->currentPage() + 1);

        return $this->getPageLinkWrapper($url, $text);
    }
    
    /**
     * 首页按钮
     * @param string $text
     * @return string
     */
    protected function getFirstButton($text = '首页')
    {
        if($this->lastPage > $this->showPage){
            if ($this->currentPage==1) {
                return $this->getDisabledTextWrapper($text);
            }
            $url = $this->url(1);
    
            return $this->getPageLinkWrapper($url, $text);
        }
    }
    
    /**
     * 末页按钮
     * @param string $text
     * @return string
     */
    protected function getLastButton($text = '末页')
    {
        if($this->lastPage > $this->showPage){
            if ($this->currentPage==$this->lastPage) {
                return $this->getDisabledTextWrapper($text);
            }
            $url = $this->url($this->lastPage);
        
            return $this->getPageLinkWrapper($url, $text);
        }
    }
    
    //统计信息
    protected function getInfo(){
//        return "<p class='pageRemark'>共<b>" . $this->lastPage .
//            "</b>页<b>" . $this->total . "</b>条数据</p>";
        return '<li>共<b> ' . $this->lastPage ." </b>页 <b> " . $this->total . " </b>条数据</p>". '</li>';
    }
    
    /**
     * 页码按钮
     * @return string
     */
    protected function getLinks()
    {
        if ($this->simple)
            return '';

        $block = [
            'first'  => null,
            'slider' => null,
            'last'   => null
        ];

        $rollPage = $this->rollPage;//分页栏每页显示的页数
        $nowPage = floor($rollPage/2);//计算分页临时变量
        
        if($this->lastPage <= $rollPage){
            $block['first'] = $this->getUrlRange(1, $this->lastPage);
        }else if($this->currentPage <= $nowPage){
            $block['first'] = $this->getUrlRange(1, $rollPage);
        }else if($this->currentPage >= ($this->lastPage - $nowPage)){
            $block['first'] = $this->getUrlRange($this->lastPage - $rollPage+1, $this->lastPage);
        }else{
            $block['first'] = $this->getUrlRange($this->currentPage - $nowPage, $this->currentPage + $nowPage);
        }
        $html = '';

        if (is_array($block['first'])) {
            $html .= $this->getUrlLinks($block['first']);
        }

        if (is_array($block['slider'])) {
            $html .= $this->getDots();
            $html .= $this->getUrlLinks($block['slider']);
        }

        if (is_array($block['last'])) {
            $html .= $this->getDots();
            $html .= $this->getUrlLinks($block['last']);
        }

        return $html;
    }

    /**
     * 渲染分页html
     * @return mixed
     */
    public function render()
    {
        if ($this->hasPages()) {
            if ($this->simple) {
                return sprintf(
                    '<div class="dataTables_paginate paging_simple_numbers"><ul class="pager">%s %s</ul></div>',
                    $this->getPreviousButton(),
                    $this->getNextButton()
                );
            } else {
                return sprintf(
                    '%s<div class="dataTables_paginate paging_simple_numbers"><ul class="pagination">%s %s %s %s %s %s </ul></div>',
                    $this->css(),
                    $this->getFirstButton(),
                    $this->getPreviousButton(),
                    $this->getLinks(),
                    $this->getNextButton(),
                    $this->getLastButton(),
                    $this->getInfo()
                );
            }
        }
    }

    /**
     * 生成一个可点击的按钮
     *
     * @param  string $url
     * @param  int    $page
     * @return string
     */
    protected function getAvailablePageWrapper($url, $page)
    {
        return '<li><a href="' . htmlentities($url) . '">' . $page . '</a></li>';
    }

    /**
     * 生成一个禁用的按钮
     *
     * @param  string $text
     * @return string
     */
    protected function getDisabledTextWrapper($text)
    {
        return '<li class="disabled"><span>' . $text . '</span></li>';
    }

    /**
     * 生成一个激活的按钮
     *
     * @param  string $text
     * @return string
     */
    protected function getActivePageWrapper($text)
    {
        return '<li class="active"><span>' . $text . '</span></li>';
    }

    /**
     * 生成省略号按钮
     *
     * @return string
     */
    protected function getDots()
    {
        return $this->getDisabledTextWrapper('...');
    }

    /**
     * 批量生成页码按钮.
     *
     * @param  array $urls
     * @return string
     */
    protected function getUrlLinks(array $urls)
    {
        $html = '';

        foreach ($urls as $page => $url) {
            $html .= $this->getPageLinkWrapper($url, $page);
        }

        return $html;
    }

    /**
     * 生成普通页码按钮
     *
     * @param  string $url
     * @param  int    $page
     * @return string
     */
    protected function getPageLinkWrapper($url, $page)
    {
        if ($page == $this->currentPage()) {
            return $this->getActivePageWrapper($page);
        }

        return $this->getAvailablePageWrapper($url, $page);
    }
    
    /**
     * 分页样式
     */
    protected function css(){
        return '  <style type="text/css">
            .pagination {}
            .pagination li {display: inline-block;margin-right: -1px;padding: 5px;border: 1px solid #e2e2e2;min-width: 20px;text-align: center;}
            .pagination li.active {background: #009688;color: #fff;border: 1px solid #009688;}
            .pagination li a {display: block;text-align: center;}
        </style>';
    }
}
