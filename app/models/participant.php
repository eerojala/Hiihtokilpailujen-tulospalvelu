<?php

class Participant extends BaseModel {
    public $id, $competition_id, $competition_name, $competitor_id, $competitor_name, $number;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public function save() {
        
    }
    
    private function queryValues() {
        $values = array();
        $values['competitionid'] = $this->competition_id;
        $values['competitoriid'] = $this->competitor_id;
        $values['patricipantnumber'] = $this->number;
        return $values;
    }
    
    public static function all() {
        $query = DB::connection()->prepare("SELECT * FROM Participant");
        $query->execute();
        return Participant::participant_list($query);
    }
    
    private static function participant_list($query) {
        $rows = $query->fetchAll();
        $participants = array();
        foreach ($rows as $row) {
            $participants[] = new Participant(Participant::getAttributes($row));
        }
        return $participants;
    }
    
    public static function getAttributes($row) {
        $attributes = array();
        $attributes['id'] = $row['id'];
        $attributes['competition_id'] = $row['competitionid'];
        $attributes['competition_name'] = Competition::find($attributes['competition_id'])->name;
        $attributes['competitor_id'] = $row['competitorid'];
        $attributes['competitor_name'] = Competitor::find($attributes['competitor_id'])->name;
        $attributes['number'] = $row['participantnumber'];
        return $attributes;
    }
    
    public static function competition_participants($compId) {
        $query = DB::connection()->prepare(
                "SELECT * FROM Participant WHERE competitionid = :compid");
        $query->execute(array('compid' => $compId));
        return Participant::participant_list($query);
    }
}

