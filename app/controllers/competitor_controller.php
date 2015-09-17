<?php

class CompetitorController extends BaseController {
    public static function index() {
        $competitors = Competitor::all();
        View::make('competitor/index.html', array('competitors' => $competitors));
    }
    
    public static function show($id) {
        $competitor = Competitor::find($id);
        View::make('competitor/competitor.html', array('competitor' => $competitor));
    }
    
    public static function create() {
        View::make('competitor/new.html');
    }
    
    public static function store() {
        $params = $_POST;
        $competitor = new Competitor(array(
            'name' => $params['name'],
            'birthdate' => $params['birthdate'],
            'country' => $params['country']
        ));
        $competitor->save();
        Redirect::to('/competitor/' . $competitor->id, array('message' => 'Kilpailija lisätty'));
    }
}

