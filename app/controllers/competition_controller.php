<?php

class CompetitionController extends BaseController {

    public static function index() {
        $competitions = Competition::all();
        View::make('competition/index.html', array('competitions' => $competitions));
    }

    public static function show($id) {
        $competition = Competition::find($id);
        View::make('competition/competition.html', array('competition' => $competition));
    }

    public static function create() {
        self::check_logged_in();
        View::make('competition/new.html');
    }

    public static function store() {
        self::check_logged_in();
        $params = $_POST;
        $attributes = new Competition(array(
            'name' => $params['name'],
            'location' => $params['location'],
            'startsAt' => $params['startsAt'],
            'endsAt' => $params['endsAt'],
        ));
        $competition = new Competition($attributes);
        $errors = $competition->errors();

        if (count($errors) == 0) {
            $competition->save();
            Redirect::to('/competition/' . $competition->id, array('message' => 'Kilpailu lisätty'));
        } else {
            View::make('/competition/new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function edit($id) {
        self::check_logged_in();
        $competition = Competition::find($id);
        View::make('competition/edit.html', array('attributes' => $competition));
    }

    public static function update($id) {
        self::check_logged_in();
        $params = $_POST;
        $attributes = array(
            'id' => $id,
            'name' => $params['name'],
            'location' => $params['location'],
            'startsAt' => $params['startsAt'],
            'endsAt' => $params['endsAt']
        );

        $competition= new Competition($attributes);
        $errors = $competition->errors();

        if (count($errors) > 0) {
            View::make('competition/edit.html', array('errors' => $errors, 'attributes' => $attributes));
        } else {
            $competition->update();
            Redirect::to('/competition/' . $competition->id, array('message' => 'Kilpailua muokattu onnistuneesti!'));
        }
    }
    
    public static function destroy($id) {
        self::check_logged_in();
        Competition::delete($id);
        Redirect::to('/competition', array('message' => 'Kilpailu on poistettu onnistuneesti!'));
    }
    
    public static function participants($id) {
        $participants = Participant::competition_participants($id);
        $name = Competition::find($id)->name;
        View::make('/participant/index.html', array(
            'header' => $name . ' osallistujat', 'participants' => $participants));
    }

}
