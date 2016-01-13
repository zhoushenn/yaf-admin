<?php

/**
 * 分页导航
 * @package Library
 */
class Util_Pagination
{
    static $pageCount;
    static $url;
    static $pg;
    static $countPerPage = 10;

    /**
     * 输出分页
     */
    public static function here(){
        echo self::$pg;
    }

    /**
     * 获取limit
     * @param $page
     * @return array
     */
    public static function getLimit($page){
        if(!$page) $page = 1;
        return [($page-1) * self::$countPerPage, self::$countPerPage];
    }

    /**
     * 配置分页
     * @param $count
     * @param $url
     * @param $currentPage
     * @param $params
     */
    public static function config($count, $url, $currentPage, array $params = [])
    {
        $pageCount = ceil($count / self::$countPerPage);
        self::$url = $url;
        self::$pageCount = $pageCount;

        if( $currentPage < (self::$countPerPage / 2) ){
            $start = 1;
        }else{
            $start = $currentPage - self::$countPerPage / 2;
        }

        $max = $start + self::$countPerPage -1;
        if($max > $pageCount){
            $max = $pageCount;
        }

        $queryString = '?';


        $liString = '';
        for($start; $start <= $max; $start++){
            $params['page'] = $start;
            $url = self::$url . '?' . http_build_query($params);
            if($start == $currentPage){
                $active = 'active';
            }else{
                $active = '';
            }
            $liString .= "<li class='{$active}'><a href='{$url}'>{$start}</a></li>";
        }

        $pre = $currentPage - 1;
        $next = $currentPage + 1;
        if($pre < 1){
            $pre = 1;
        }
        if($next >= self::$pageCount){
            $next = self::$pageCount;
        }
        $params['page'] = $pre;
        $preUrl  = self::$url . '?' . http_build_query($params);
        $params['page'] = $next;
        $nextUrl = self::$url . '?' . http_build_query($params);
        //TODO::修改导航类...
        self::$pg = <<<PG
<ul class="pagination pagination-sm no-margin pull-right">
    <li><a href="{$pre}">«上一页</a></li>
    {$liString}
    <li><a href="{$next}">下一页»</a></li>
</ul>
PG;

        return self::$pg;
    }
}