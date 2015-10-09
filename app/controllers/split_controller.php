<?php

class SplitController extends BaseController {

    public static function competition_splits($competition_id) {
        $participants = Participant::get_competition_participants($competition_id);
        $first_splits = Split::xth_competition_splits($competition_id, 1);
        $second_splits = Split::xth_competition_splits($competition_id, 2);
        $final_splits = Split::xth_competition_splits($competition_id, 3);
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
        $latest_split = Split::latest_split($participant_id);
        $participant = Participant::find($participant_id);
        
        if ($latest_split == null) {
            self::create_xth_split(1, $participant);
        } else if ($latest_split == 3) {
            $competition_id = $participant->competition_id;
            Redirect::to('/competition/' . $competition_id . '/splits', array('message' => 'Kilpailijalle on jo kirjattu kaikki ajat.'));
        } else {
            self::create_xth_split($latest_split + 1, $participant);
        }
    }

    private static function create_xth_split($split_number, $participant) {
        $attributes = array(
            'split_number' => $split_number, 'participant_id' => $participant->id);
        self::creation_view($participant, $attributes, array());
    }
    
    private static function creation_view($participant, $attributes, $errors) {
        View::make('split/new.html', array(
            'participant' => $participant, 'attributes' => $attributes, 'errors' => $errors
        ));
    }
    
    public static function store() {
        self::check_logged_in();
        $attributes = self::get_attributes();
        $split = new Split($attributes);
        $participant = Participant::find($split->participant_id);
        $competition_id = Competition::find($participant->competition_id)->id;
        $split->save();
        Redirect::to('/competition/' . $competition_id . '/splits', array(
            'message' => 'Väliaika lisätty.'
        ));
    }
    
    private static function get_attributes() {
        $params = $_POST;
        $attributes = array(
            'participant_id' => $params['participantid'],
            'split_number' => $params['splitnumber'],
            'split_time' => $params['splittime']
        );
        return $attributes;
    }
}
