<?php

$routes->get('/hiekkalaatikko/front_page', function() {
    HelloWorldController::index();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/hiekkalaatikko/competition', function() {
    HelloWorldController::competition_list();
});

$routes->get('/hiekkalaatikko/competition/1', function() {
    HelloWorldController::competition_show();
});

$routes->get('/hiekkalaatikko/competition/1/edit', function() {
    HelloWorldController::competition_edit();
});

$routes->get('/hiekkalaatikko/competitor', function() {
    HelloWorldController::competitor_list();
});

$routes->get('/hiekkalaatikko/competitor/1', function() {
    HelloWorldController::competitor_show();
});

$routes->get('/hiekkalaatikko/competitor/1/edit', function() {
    HelloWorldController::competitor_edit();
});

$routes->get('/hiekkalaatikko/login', function() {
    HelloWorldController::login();
});

$routes->get('/hiekkalaatikko/split_table', function() {
    HelloWorldController::split_table();
});

$routes->get('/', function() {
    FrontPageController::index();
});

$routes->get('/login', function() {
    UserController::login();
});

$routes->post('/login', function() {
    UserController::handle_login();
});

$routes->post('/logout', function() {
    UserController::logout();
});

$routes->get('/user', function() {
    UserController::index();
});

$routes->post('/user', function() {
    UserController::store();
}); 

$routes->get('/register', function() {
    UserController::register();
});

$routes->get('/user/:id', function($id) {
    UserController::view($id);
});

$routes->get('/user/:id/admin_edit', function($id) {
    UserController::admin_edit($id);
});

$routes->post('/user/:id/edit_usertype', function($id) {
UserController::admin_edit_usertype($id);
});

$routes->post('/user/:id/give_recording_rights', function ($id) {
    UserController::give_recording_rights($id);
});

$routes->post('/user/:id/destroy', function($id) {
    UserController::destroy($id);
});

$routes->get('/competitor', function() {
    CompetitorController::index();
});

$routes->post('/competitor', function() {
    CompetitorController::store();
});

$routes->get('/competitor/new', function() {
    CompetitorController::create();
    ;
});

$routes->get('/competitor/:id', function($id) {
    CompetitorController::show($id);
});

$routes->get('/competitor/:id/edit', function($id) {
    CompetitorController::edit($id);
});

$routes->post('/competitor/:id/edit', function($id) {
    CompetitorController::update($id);
});

$routes->post('/competitor/:id/destroy', function($id) {
    CompetitorController::destroy($id);
});

$routes->get('/competition', function() {
    CompetitionController::index();
});

$routes->post('/competition', function() {
    CompetitionController::store();
});

$routes->get('/competition/new', function() {
    CompetitionController::create();
});

$routes->get('/competition/:id', function($id) {
    CompetitionController::show($id);
});

$routes->get('/competition/:id/edit', function($id) {
    CompetitionController::edit($id);
});

$routes->post('/competition/:id/edit', function($id) {
    CompetitionController::update($id);
});

$routes->post('/competition/:id/destroy', function($id) {
    CompetitionController::destroy($id);
});

$routes->get('/competition/:id/participants', function($id) {
    ParticipantController::competition_participants($id);
});

$routes->get('/competitor/:id/participations', function($id) {
    ParticipantController::competitor_participations($id);
});

$routes->post('/participant', function() {
    ParticipantController::store();
});

$routes->get('/competition/:id/participants/new', function($id) {
    ParticipantController::create($id);
});

$routes->get('/participant/:id/edit', function($id) {
    ParticipantController::edit($id);
});

$routes->post('/participant/:id/edit', function($id) {
    ParticipantController::update($id);
});

$routes->post('/participant/:id/destroy', function($id) {
    ParticipantController::destroy($id);
});

$routes->get('/competition/:id/splits', function($id) {
    SplitController::competition_splits($id);
});

$routes->post('/split', function() {
    SplitController::store();
});

$routes->get('/participant/:id/splits/new', function($id) {
    SplitController::create($id);
});

$routes->get('/participant/:id/splits/edit', function($id){
    SplitController::edit($id);
});

$routes->post('/split/edit', function() {
    SplitController::update();
});
