<?php
declare(strict_types = 1);
use Illuminate\Routing\Router;

/* @var Router $router */
$router->get('', ['uses' => 'TemplateController@index', 'as' => 'index']);
$router->post('', ['uses' => 'TemplateController@grid', 'as' => 'grid']);

$router->get('create', ['uses' => 'TemplateController@create', 'as' => 'create']);
$router->post('create', ['uses' => 'TemplateController@store', 'as' => 'store']);

$router->get('edit/{id}', ['uses' => 'TemplateController@edit', 'as' => 'edit']);
$router->post('edit/{id}', ['uses' => 'TemplateController@update', 'as' => 'update']);

$router->post('delete/{id}', ['uses' => 'TemplateController@destroy', 'as' => 'delete']);