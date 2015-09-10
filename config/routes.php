<?php

$routes->get('/', function() {
    HelloWorldController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/competitions', function() {
    HelloWorldController::competition_list();
});

$routes->get('/competitions/1', function() {
    HelloWorldController::competition_show();
});

$routes->get('/competitions/1/edit', function() {
    HelloWorldController::competition_edit();
});

$routes->get('/competitors', function() {
    HelloWorldController::competitor_list();
});

$routes->get('/competitors/1', function() {
    HelloWorldController::competitor_show();
});

$routes->get('/competitors/1/edit', function() {
    HelloWorldController::competitor_edit();
});

//$routes->get('/front_page', function() {
//    HelloWorldController::front_page();
//});

$routes->get('/login', function() {
    HelloWorldController::login();
});

$routes->get('/split_table', function() {
    HelloWorldController::split_table();
});
