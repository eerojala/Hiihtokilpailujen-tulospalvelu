<?php

class CompetitorController extends BaseController {

    public static function index() {
        $params = $_GET;
        $name = '';
        
        if (isset($params['name'])) {
            $name = $params['name'];
        }
        
        $competitors = Competitor::all($name);
        View::make('competitor/index.html', array('competitors' => $competitors));
    }

    public static function show($id) {
        $competitor = Competitor::find($id);
        View::make('competitor/competitor.html', array('competitor' => $competitor));
    }

    public static function create() {
        self::check_admin_logged_in();
        View::make('competitor/new.html');
    }

    public static function store() {
        self::check_admin_logged_in();
        $attributes = self::get_attributes();
        $competitor = new Competitor($attributes);
        $errors = $competitor->errors();

        if (count($errors) == 0) {
            $competitor->save();
            Redirect::to('/competitor/' . $competitor->id, array('message' => 'Kilpailija lisätty'));
        } else {
            View::make('/competitor/new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    private static function get_attributes() {
        $params = $_POST;
        $attributes = array(
            'name' => $params['name'],
            'birthdate' => $params['birthdate'],
            'country' => $params['country']
        );
        return $attributes;
    }

    public static function edit($id) {
        self::check_admin_logged_in();
        $competitor = Competitor::find($id);
        View::make('competitor/edit.html', array('attributes' => $competitor));
    }

    public static function update($id) {
        self::check_admin_logged_in();
        $attributes = self::get_attributes();
        $attributes['id'] = $id;
        $competitor = new Competitor($attributes);
        $errors = $competitor->errors();

        if (count($errors) > 0) {
            View::make('competitor/edit.html', array('errors' => $errors, 'attributes' => $attributes));
        } else {
            $competitor->update();
            Redirect::to('/competitor/' . $competitor->id, array('message' => 'Kilpailijaa muokattu onnistuneesti!'));
        }
    }

    public static function destroy($id) {
        self::check_admin_logged_in();
        Competitor::delete($id);
        Redirect::to('/competitor', array('message' => 'Kilpailija on poistettu onnistuneesti!'));
    }

}
