<?php

/*
|--------------------------------------------------------------------------
| Blog Routes
|--------------------------------------------------------------------------
|
*/

$router->get('/', [
    'as' => 'admin.blog',
    'uses' => 'BlogsController@index',
]);