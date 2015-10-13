<?php

class HelloWorldController extends BaseController {

    public static function index() {
        View::make('suunnitelmat/front_page.html');
    }

    public static function sandbox() {
        $user = User::find(2);
        Kint::dump($user->has_rights_to_competition_splits(1));
        Kint::dump($user->has_rights_to_competition_splits(2));
    }

    public static function competition_edit() {
        View::make('suunnitelmat/competition_edit.html');
    }

    public static function competition_list() {
        View::make('suunnitelmat/competition_list.html');
    }

    public static function competition_show() {
        View::make('suunnitelmat/competition_show.html');
    }

    public static function competitor_edit() {
        View::make('suunnitelmat/competitor_edit.html');
    }

    public static function competitor_list() {
        View::make('suunnitelmat/competitor_list.html');
    }

    public static function competitor_show() {
        View::make('suunnitelmat/competitor_show.html');
    }

    public static function front_page() {
        View::make('suunnitelmat/front_page.html');
    }

    public static function login() {
        View::make('suunnitelmat/login.html');
    }

    public static function split_table() {
        View::make('suunnitelmat/split_table.html');
    }

}
