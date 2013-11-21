<?php

/**
 *  @postRoute View
 *  @Last
 */
function view_filter($req, $args, $params)
{
    if (empty($args)) {
        throw new \RuntimeException("@View expects one argument");
    }

    $view = Service::get("view")->get(current($args), []);
    echo $view->render($params);
    return $params;
}
