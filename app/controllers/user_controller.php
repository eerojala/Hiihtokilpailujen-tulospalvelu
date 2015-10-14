<?php

class UserController extends BaseController {

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
            self::login_and_redirect_to_home_page($user);
        }
    }

    private static function login_and_redirect_to_home_page($user) {
        $_SESSION['user'] = $user->id;
        Redirect::to('/', array('message' => 'Tervetuloa takaisin ' . $user->username . '!'));
    }

    public static function logout() {
        $_SESSION['user'] = null;
        Redirect::to('');
    }

    public static function index() {
        self::check_admin_logged_in();
        $name = self::get_search_term();
        $users = User::all($name);
        View::make('user/index.html', array('users' => $users));
    }

    public static function view($id) {
        self::check_admin_logged_in();
        $user = User::find($id);
        $competitions = array();
        if ($user->type == 'recorder') {
            $competitions = Competition::get_competitions_which_user_has_recording_rights_to($id);
        }
        View::make('user/user.html', array('user' => $user, 'competitions' => $competitions));
    }

    public static function register() {
        View::make('user/registration.html');
    }

    public static function store() {
        self::check_admin_logged_in();
        $attributes = self::get_attributes();
        $attributes['type'] = 'normal';
        $user = new User($attributes);
        $errors = $user->errors();

        if ($attributes['password'] != $attributes['password_validation']) {
            $errors[] = 'Salasana ja vahvistus eivät täsmää';
        }

        if (count($errors) == 0) {
            $user->register();
            self::login_and_redirect_to_home_page($user);
        } else {
            View::make('user/registration.html', array(
                'errors' => $errors, 'attributes' => $attributes));
        }
    }

    private static function get_attributes() {
        $params = $_POST;
        return array(
            'username' => $params['username'],
            'password' => $params['password1'],
            'password_validation' => $params['password2'],
            'real_name' => $params['name']
        );
    }
    
    public static function admin_edit($id, $errors = null) {
        self::check_admin_logged_in();
        $user = User::find($id);
        $competitions = array();
        
        if ($user->type == 'recorder') {
            $competitions = Competition::all('');
        }
        
        self::admin_edit_view($user, $competitions, $errors);
    }
    
    private static function admin_edit_view($user, $competitions = null, $errors = null) {
        View::make('user/edit_admin.html', array(
            'user' => $user, 'competitions' => $competitions, 'errors' => $errors));
    }
    
    public static function admin_edit_usertype($id) {
        self::check_admin_logged_in();
        $params = $_POST;
        $user = User::find($id);
        $user->type = $params['usertype'];
        $errors = $user->validate_usertype();
        
        if (count($errors) == 0) {
            $user->update();
            Redirect::to('/user/' .$user->id, array(
                'message' => 'Käyttäjätunnuksen oikeuksia muokattu onnistuneesti'));
        } else {
            self::admin_edit($id, $errors);
        }
    }
    
    public static function give_recording_rights($id) {
        self::check_admin_logged_in();
        $params = $_POST;
        $user = User::find($id);
        $competition_id = $params['competitionid'];
        
        if ($user->has_rights_to_competition_splits($competition_id)) {
            self::admin_edit($id, array('Käyttäjällä on jo mittausoikeudet tähän kilpailuun'));
        }
        $user->give_recording_rights($competition_id);
        Redirect::to('/user/' . $user->id, array(
            'message' => 'Käyttäjän mittausoikeuksien antaminen onnistui'));
    }
    
    public static function destroy($id) {
        self::check_admin_logged_in();
        User::delete($id);
        Redirect::to('/user', array('message' => 'Käyttäjätunnus poistettu onnistuneesti'));
    }
}
