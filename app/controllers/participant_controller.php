<?php

class ParticipantController extends BaseController {

    public static function competition_participants($competition_id) {
        $participants = Participant::get_competition_participants($competition_id);
        $competition = Competition::find($competition_id);
        self::index_view($competition, $participants, true);
    }

    private static function index_view($header_object, $participants, $boolean) {
        View::make('/participant/index.html', array(
            'header_object' => $header_object, 'participants' => $participants,
            'competition_participants' => $boolean));
    }

    public static function competitor_participations($competitor_id) {
        $participations = Participant::get_competitor_participations($competitor_id);
        $competitor = Competitor::find($competitor_id);
        self::index_view($competitor, $participations, false);
    }

    public static function create($competition_id) {
        self::check_logged_in();
        self::creation_view(
                array(Competition::find($competition_id)), Competitor::all(), true, array());
    }

    private static function creation_view($competitions, $competitors, $boolean, $errors) {
        View::make('participant/new.html', array(
            'competitions' => $competitions, 'competitors' => $competitors,
            'came_from_competition' => $boolean, 'errors' => $errors
        ));
    }

    public static function store() {
        self::check_logged_in();
        $attributes = self::get_attributes();
        $participant = new Participant($attributes);
        $errors = $participant->errors();

        if (count($errors) == 0) {
            $participant->save();
            Redirect::to('/competition/' . $participant->competition_id . '/participants', array('message' => 'Kilpailija lisätty kilpailuun'));
        } else {
            self::creation_view(array(Competition::find($participant->competition_id)), Competitor::all(), true, $errors);
        }
    }
    
    private static function get_attributes() {
        $params = $_POST;
        $attributes = array(
            'competition_id' => intval($params['competitionid']),
            'competitor_id' => intval($params['competitorid']),
            'number' => intval($params['participantnumber'])
        );
        return $attributes;
    }

    public static function edit($id) {
        self::check_logged_in();
        $participant = Participant::find($id);
        self::edit_view(
                array(Competition::find($participant->competition_id)), 
                array(Competitor::find($participant->competitor_id)), $participant, array());
    }
    
    private static function edit_view($competitions, $competitors, $attributes, $errors) {
        View::make('participant/edit.html', array(
            'competitions' => $competitions, 'competitors' => $competitors,
            'attributes' => $attributes, 'errors' => $errors
        ));
    }

    public static function update($id) {
        self::check_logged_in();
        $attributes = self::get_attributes();
        $attributes['id'] = $id;
        $attributes['competition_name'] = Competition::find($attributes['competition_id'])->name;
        $attributes['competitor_name'] = Competitor::find($attributes['competitor_id'])->name;
        $participant = new Participant($attributes);
        $errors = $participant->validate_number();

        if (count($errors) == 0) {
            $participant->update();
            Redirect::to('/competition/' . $participant->competition_id . '/participants', array('message' => 'Kilpailijan numeroa muokattu onnistuneesti!'));
        } else {
            self::edit_view(
                    array(Competition::find($participant->competition_id)), 
                    array(Competitor::find($participant->competitor_id)), 
                    $attributes, $errors);
        }
    }

    public static function destroy($id) {
        self::check_logged_in();
        $participant = Participant::find($id);
        Participant::delete($id);
        Redirect::to('/competition/' . $participant->competition_id . '/participants', array(
            'message' => 'Kilpailija ' . $participant->competitor_name .
            ' poistettiin onnistuneesti kilpailusta ' . $participant->competition_name
        ));
    }

}
