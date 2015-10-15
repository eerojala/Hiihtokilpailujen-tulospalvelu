<?php

class BaseController {

    public static function get_user_logged_in() {
        if (isset($_SESSION['user'])) {
            $user_id = $_SESSION['user'];
            return User::find($user_id);
        }
        return null;
    }

    protected static function check_admin_logged_in() {
        self::redirect_to_login_if_not_logged_in();

        if (self::get_user_logged_in()->type != 'admin') {
            self::redirect_to_previous_page();
        }
    }

    private static function redirect_to_login_if_not_logged_in() {
        if (!isset($_SESSION['user'])) {
            Redirect::to('/login');
        }
    }

    private static function redirect_to_previous_page() {
        Redirect::previous(array(
            'message' => 'Sinulla ei ole oikeuksia tähän toimintoon'));
    }

    protected static function check_admin_or_recorder_logged_in($competition_id) {
        self::redirect_to_login_if_not_logged_in();
        $user = self::get_user_logged_in();

        if (($user->type == 'recorder' && !$user->has_rights_to_competition_splits($competition_id))) {
            if ($user->type != 'admin') {
                self::redirect_to_previous_page();
            }
        }
    }

    protected static function get_search_term() {
        $params = $_GET;

        if (isset($params['name'])) {
            return $params['name'];
        }
        return '';
    }

}
