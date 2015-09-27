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
        $attributes = array(
            'name' => $params['name'],
            'birthdate' => $params['birthdate'],
            'country' => $params['country']
        );
        $competitor = new Competitor($attributes);
        $errors = $competitor->errors();

        if (count($errors) == 0) {
            $competitor->save();
            Redirect::to('/competitor/' . $competitor->id, array('message' => 'Kilpailija lisätty'));
        } else {
            View::make('/competitor/new.html', array('errors' => $errors, 'attributes' => $attributes));
        }
    }

    public static function edit($id) {
        $competitor = Competitor::find($id);
        View::make('competitor/edit.html', array('attributes' => $competitor));
    }
    
    public static function update($id) {
        $params = $_POST;
        $attributes = array(
            'id' => $id,
            'name' => $params['name'],
            'birthdate' => $params['birthdate'],
            'country' => $params['country']
        );
        
        $competitor = new Competitor($attributes);
        $errors = $competitor->errors();
        
        if(count($errors) > 0) {
            View::make('competitor/edit.html', array('errors' => $errors, 'attributes' => $attributes));
        } else {
            $competitor->update();
            Redirect::to('/competitor/' . $competitor->id, array('message' => 'Kilpailijaa muokattu onnistuneesti!'));
        }
    }
    
    public static function destroy($id) {
        Competitor::delete($id);
        Redirect::to('/competitor', array('message' => 'Kilpailija on poistettu onnistuneesti!'));
    }
}
