<?php

class Results extends BaseModel {

    public $id, $participant_id, $competition_id, $competition_name, $competitor_id,
            $competitor_name, $participant_number, $first_split, $second_split,
            $final_split, $standing;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Results');
        $query->execute();
        return Results::results_list($query->fetchAll());
    }

    private static function results_list($rows) {
        $results = array();
        foreach ($rows as $row) {
            $results[] = new Results(Results::get_attributes($row));
        }
        return $results;
    }

    private static function get_attributes($row) {
        $attributes = array();
        $attributes['id'] = $row['id'];
        $participant_id = $row['participantid'];
        $participant = Participant::find($participant_id);
        $attributes['participant_id'] = $participant_id;
        $attributes['competition_id'] = $participant->competition_id;
        $attributes['competition_name'] = $participant->competition_name;
        $attributes['competitor_id'] = $participant->competitor_id;
        $attributes['competitor_name'] = $participant->competitor_name;
        $attributes['participant_number'] = $participant->number;
        $attributes['first_split'] = $row['firstsplit'];
        $attributes['second_split'] = $row['secondsplit'];
        $attributes['final_split'] = $row['finalsplit'];
        $attributes['standing'] = $row['standing'];
        return $attributes;
    }

    public static function get_competition_results($competition_id) {
        $query = DB::connection()->prepare('SELECT * FROM Results INNER JOIN Participant '
                . 'ON Results.participantid = Participant.id '
                . 'AND Participant.competitionid = :compid');
        $query->execute(array('compid' => $competition_id));
        return Results::results_list($query->fetchAll());
    }
    
    public static function get_competitor_results($competitor_id) {
        $query = DB::connection()->prepare('SELECT * FROM Results INNER JOIN Participant '
                . 'ON Results.participantid = Participant.id '
                . 'AND Participant.competitorid = :compid');
        $query->execute(array('compid' => $competitor_id));
        return Results::results_list($query->fetchAll());
    }
    
    public static function 

}
