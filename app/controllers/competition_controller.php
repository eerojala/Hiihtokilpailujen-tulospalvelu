<?php

class CompetitionController extends BaseController {

    public static function index() {
        $name = self::get_search_term();
        $competitions = Competition::all($name);
        View::make('competition/index.html', array('competitions' => $competitions));
    }

    public static function show($id) {
        $competition = Competition::find($id);
        View::make('competition/competition.html', array('competition' => $competition));
    }

    public static function create() {
        self::check_admin_logged_in();
        View::make('competition/new.html');
    }

    public static function store() {
        self::check_admin_logged_in();
        $attributes = self::get_attributes();
        $competition = new Competition($attributes);
        $errors = $competition->errors();
        
        if (count($competition->errors()) == 0) {
            $competition->save();
            Redirect::to('/competition/' . $competition->id, array('message' => 'Kilpailu lisätty'));
        } else {
            View::make('/competition/new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }
    
    private static function get_attributes() {
        $params = $_POST;
        $attributes = array(
            'name' => $params['name'],
            'location' => $params['location'],
            'split_amount' => $params['split_amount'],
            'starts_at' => $params['starts_at'],
            'ends_at' => $params['ends_at']
        );
        return $attributes;
    }

    public static function edit($id) {
        self::check_admin_logged_in();
        $competition = Competition::find($id);
        View::make('competition/edit.html', array('attributes' => $competition));
    }

    public static function update($id) {
        self::check_admin_logged_in();
        $attributes = self::get_attributes();
        $attributes['id'] = $id;
        $competition = new Competition($attributes);
        $errors = $competition->errors();
        
        if (count($errors) > 0) {
            View::make('competition/edit.html', array('errors' => $errors, 'attributes' => $attributes));
        } else {
            $competition->update();
            Participant::nullify_and_update_competition_standings($id);
            Redirect::to('/competition/' . $competition->id, array('message' => 'Kilpailua muokattu onnistuneesti!'));
        }
    }

    public static function destroy($id) {
        self::check_admin_logged_in();
        Competition::delete($id);
        Redirect::to('/competition', array('message' => 'Kilpailu on poistettu onnistuneesti!'));
    }

}
