<?php

class FrontPageController extends BaseController {

    public static function index() {
        View::make('front_page.html');
    }

}
