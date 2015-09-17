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
        View::make('competition/new.html');
    }
    
    public static function store() {
        $params = $_POST;
        $competition = new Competition(array(
            'name' => $params['name'],
            'location' => $params['location'],
            'startsAt' => $params['startsAt'],
            'endsAt' => $params['endsAt'],
        ));
        $competition->save();
        Redirect::to('/competition/' . $competition->id, array('message' => 'Kilpailu lisätty'));
    }
}

