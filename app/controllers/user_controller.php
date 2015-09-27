<?php

class UserController extends BaseController{
    public static function login() {
        View::make('user/login.html');
    }
    
    public static function handle_login() {
        $params = $_POST;
        $user = User::authenticate($params['username'], $params['password']);
        if (!$user) {
            $errors = array();
            $errors[] = 'Väärä käyttäjätunnus tai salasana!';
            View::make('user/login.html', array('errors' => $errors,
                'username' => $params['username']));
        } else {
            $_SESSION['user'] = $user->id;
            Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $user->username . '!'));
        }
    }
}

