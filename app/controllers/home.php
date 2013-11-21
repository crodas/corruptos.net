<?php

/** @Filter uri */
function uri($req, $name, $value)
{
    $db  = Service::get('db');
    $doc = $db->GetCollection('corrupto')->findOne(['uri' => $value]);
    if (empty($doc)) {
        return false;
    }
    $req->set($name, $doc);
    return true;
}

/**
 *  @Route /
 *  @View layout.tpl
 */
function get_home($req)
{
    $db = Service::get('db');
    $corruptos = $db->getCollection('corrupto')->find()->sort(['hits' => -1]);
    return compact('corruptos');
}

/**
 *  @Route "/{uri:corrupto}"
 *  @Route "/{uri:corrupto}/{page}"
 *  @View corrupto.tpl
 */
function get_corruptos($req)
{
    $corrupto = $req->get('corrupto');
    $page     = $req->get('page') ?: 0;
    return compact('corrupto', 'page');
}
