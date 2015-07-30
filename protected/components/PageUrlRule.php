<?php

class PageUrlRule extends CBaseUrlRule
{
    public $connectionID = 'db';

    public function createUrl($manager, $route, $params, $ampersand)
    {
        return false;  // не применяем данное правило
    }

    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {
//        var_dump($pathInfo);die();
        return false;  // не применяем данное правило
    }
}