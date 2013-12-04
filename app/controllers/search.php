<?php

/**
 *  @Route "/busqueda/{text}"
 *  @Route "/busqueda/{text}/{page}"
 *  @Route "/busqueda"
 *  @View result.tpl
 */
function get_corruptos_medios($req)
{

    $index = Service::get('esearch');
    $page  = intval($req->get('page'));
    $text  = urldecode($req->get('text') ?: '');

    if (!empty($_GET['q'])) {
        header("Location: /busqueda/" . urlencode($_GET['q']));
        exit;
    }
    if (empty($text)) {
        header("Location: /");
        exit;
    }

    $q = new \Elastica\Query\QueryString();
    $q->setDefaultOperator('AND');
    $q->setQuery($text);

    $query = new \Elastica\Query();
    //$query->setFilter($tfilter);
    $query->setQuery($q);
    $query->setFrom(($page)*20);    // Where to start?
    $query->setLimit(20);   // How many?

    $results = $index->search($query);
    $form = new crodas\Form\Form;
    $form->populate(['q' => $text]);

    $base = "/busquedas/" . urlencode($text);

    return compact('results', 'text', 'form', 'page', 'base');
}
