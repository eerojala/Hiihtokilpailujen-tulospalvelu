<?php

class ParticipantController extends BaseController {

    public static function index() {
        $participants = Participant::all();
        View::make('participant/index.html', array(
            'header' => 'Kaikkien kilpailujen osallistujat', 'participants' => $participants));
    }
    
    public static function competition_participants($competition_id) {
        $participants = Participant::get_competition_participants($competition_id);
        $competition = Competition::find($competition_id);
        View::make('/participant/index.html', array(
            'competition' => $competition,
            'participants' => $participants));
    }

    public static function create($competition_id) {
        self::check_logged_in();
        View::make('participant/new.html', array(
            'competitions' => array(Competition::find($competition_id)), 'competitors' => Competitor::all()));
    }

    public static function store() {
        self::check_logged_in();
        $params = $_POST;
        $attributes = array(
            'competition_id' => intval($params['competitionid']),
            'competitor_id' => intval($params['competitorid']),
            'number' => intval($params['participantnumber'])
        );

        $participant = new Participant($attributes);
        $errors = $participant->errors();

        if (count($errors) == 0) {
            $participant->save();
            Redirect::to('/competition/' . $participant->competition_id . '/participants', array('message' => 'Kilpailija lisätty kilpailuun'));
        } else {
            View::make('/participant/new.html', array(
                'errors' => $errors, 'competitions' => array(Competition::find($participant->competition_id)), 'competitors' => Competitor::all()));
        }
    }

    public static function edit($id) {
        self::check_logged_in();
        $participant = Participant::find($id);
        View::make('participant/edit.html', array(
            'competitions' => array(Competition::find($participant->competition_id)),
            'competitors' => array(Competitor::find($participant->competitor_id)),
            'attributes' => $participant));
    }

    public static function update($id) {
        self::check_logged_in();
        $params = $_POST;
        $attributes = array(
            'id' => $id,
            'competition_id' => intval($params['competitionid']),
            'competitor_id' => intval($params['competitorid']),
            'number' => intval($params['participantnumber'])
        );

        $participant = new Participant($attributes);
        $errors = $participant->validate_number();

        if (count($errors) == 0) {
            $participant->update();
            Redirect::to('/competition/' . $participant->competition_id . '/participants', array('message' => 'Kilpailijan numeroa muokattu onnistuneesti!'));
        } else {
            View::make('/participant/edit.html', array(
                'errors' => $errors, 'competitions' => array(Competition::find($participant->competition_id)), 
                'competitors' => array(Competitor::find(($participant->competitor_id))),
                'attributes' => $attributes));
        }
    }
    
    public static function destroy($id) {
        self::check_logged_in();
        $participant = Participant::find($id);
        Participant::delete($id);
        Redirect::to('/competition/' .$participant->competition_id. '/participants', array(
            'message' => 'Kilpailija ' .$participant->competitor_name. 
            ' poistettiin onnistuneesti kilpailusta ' .$participant->competition_name
        ));
    }

}
