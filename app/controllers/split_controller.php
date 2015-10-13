<?php

class SplitController extends BaseController {

    public static function competition_splits($competition_id) {
        $participants = Participant::get_competition_participants($competition_id);
        $competition = Competition::find($competition_id);
        $splits = Split::get_every_competition_split($competition->id);

        self::index_view($participants, $splits, $competition, '');
    }

    private static function index_view($participants, $splits, $competition, $message) {
        View::make('split/index.html', array(
            'participants' => $participants, 'splits' => $splits,
            'competition' => $competition, 'message' => $message
        ));
    }

    public static function create($participant_id) {
        $participant = Participant::find($participant_id);
        self::check_admin_or_recorder_logged_in($participant->competition_id);
        $latest_split_number = Split::get_latest_split_number($participant_id);
        $competition = Competition::find($participant->competition_id);
        
        if ($latest_split_number == null) {
            self::create_xth_split(1, $participant);
        } else if ($latest_split_number == $competition->split_amount) {
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
        $attributes = self::get_attributes();
        $split = new Split($attributes);
        $participant = Participant::find($split->participant_id);
        self::check_admin_or_recorder_logged_in($participant->competition_id);
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
        $participant = Participant::find($participant_id);
        self::check_admin_or_recorder_logged_in($participant->competition_id);
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
        self::check_admin_logged_in();
        $attributes = self::get_attributes();
        $split_number = Split::find($attributes['id'])->split_number;
        $attributes['split_number'] = $split_number;
        $split = new Split($attributes);
        $participant = Participant::find($split->participant_id);
        self::check_admin_or_recorder_logged_in($participant->competition_id);
        $competition_id = Competition::find($participant->competition_id)->id;
        $errors = $split->validate_split_time();

        if (count($errors) == 0) {
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
