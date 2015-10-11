<?php

class SplitController extends BaseController {

    public static function competition_splits($competition_id) {
        $participants = Participant::get_competition_participants($competition_id);
        $first_splits = Split::get_xth_competition_splits_by_participant_id($competition_id, 1);
        $second_splits = Split::get_xth_competition_splits_by_participant_id($competition_id, 2);
        $final_splits = Split::get_xth_competition_splits_by_participant_id($competition_id, 3);
        $competition = Competition::find($competition_id);
        self::index_view($participants, $first_splits, $second_splits, $final_splits, $competition, '');
    }

    private static function index_view($participants, $first_splits, $second_splits, $final_splits, $competition, $message) {
        View::make('split/index.html', array('participants' => $participants,
            'first_splits' => $first_splits, 'second_splits' => $second_splits,
            'final_splits' => $final_splits, 'competition' => $competition,
            'message' => $message));
    }

    public static function create($participant_id) {
        self::check_logged_in();
        $latest_split_number = Split::latest_split_number($participant_id);
        $participant = Participant::find($participant_id);

        if ($latest_split_number == null) {
            self::create_xth_split(1, $participant);
        } else if ($latest_split_number == 3) {
            $competition_id = $participant->competition_id;
            Redirect::to('/competition/' . $competition_id . '/splits', array('message' => 'Kilpailijalle on jo kirjattu kaikki ajat.'));
        } else {
            self::create_xth_split($latest_split_number + 1, $participant);
        }
    }

    private static function create_xth_split($split_number, $participant) {
        $attributes = array(
            'split_number' => $split_number, 'participant_id' => $participant->id);
        self::creation_view($participant, $attributes, array());
    }

    private static function creation_view($participant, $attributes, $errors) {
        View::make('split/new.html', array(
            'participant' => $participant, 'attributes' => $attributes,
            'errors' => $errors, 'edit' => false
        ));
    }

    public static function store() {
        self::check_logged_in();
        $attributes = self::get_attributes();
        $split = new Split($attributes);
        $participant = Participant::find($split->participant_id);
        $competition_id = Competition::find($participant->competition_id)->id;
        $errors = $split->validate_split_time();

        if (count($errors) == 0) {
            $split->save();
            Participant::update_competition_standings($competition_id);
            Redirect::to('/competition/' . $competition_id . '/splits', array(
                'message' => 'Väliaika lisätty.'
            ));
        } else {
            self::creation_view($participant, $attributes, $errors);
        }
    }

    private static function get_attributes() {
        $params = $_POST;
        $attributes = array(
            'participant_id' => $params['participantid'],
            'split_time' => $params['splittime']
        );
        
        try {
            $attributes['id'] = $params['splitid'];
        } catch (Exception $ex) {
           $attributes['split_number'] = $params['splitnumber']; 
        }    
        return $attributes;
    }

    public static function edit($participant_id) {
        self::check_logged_in();
        $participant = Participant::find($participant_id);
        $attributes = array('participant_id' => $participant_id);
        $splits = Split::participants_splits($participant_id);
        self::edit_view($participant, $attributes, $splits, array());
    }

    private static function edit_view($participant, $attributes, $splits, $errors) {
        View::make('split/edit.html', array(
            'participant' => $participant, 'attributes' => $attributes,
            'splits' => $splits, 'edit' => true, 'errors' => $errors
        ));
    }

    public static function update() {
        self::check_logged_in();
        $attributes = self::get_attributes();
        $split_number = Split::find($attributes['id'])->split_number;
        $attributes['split_number'] = $split_number;
        $split = new Split($attributes);
        $participant = Participant::find($split->participant_id);
        $competition_id = Competition::find($participant->competition_id)->id;
        $errors = $split->validate_split_time();
        
        if(count($errors) == 0) {
            $split->update();
            Participant::update_competition_standings($competition_id);
            Redirect::to('/competition/' . $competition_id . '/splits', array(
                'message' => 'Väliaikaa muokattu.'
            ));
        } else {
            $splits = Split::participants_splits($participant->id);
            self::edit_view($participant, $attributes, $splits, $errors);
        }     
    }

}
