<?php

class Split extends BaseModel {

    public $id, $participant_id, $split_number, $split_time;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Split '
                . '(participantid, splitnumber, splittime) '
                . 'VALUES (:participantid, :splitnumber, :splittime) '
                . 'RETURNING id');
        $query->execute($this->query_values());
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    private function query_values() {
        $values = array();
        $values['participantid'] = $this->participant_id;
        $values['splitnumber'] = $this->split_number;
        $values['splittime'] = $this->split_time;
        return $values;
    }

    public static function xth_competition_splits($competition_id, $split_number) {
        $query = DB::connection()->prepare('SELECT Split.participantid as participantid, '
                . 'Split.splittime as splittime '
                . 'FROM Split INNER JOIN Participant '
                . 'ON Split.participantid = Participant.id AND '
                . 'Participant.competitionid = :compid AND Split.splitnumber = :number');
        $query->execute(array('compid' => $competition_id, 'number' => $split_number));
        $rows = $query->fetchAll();
        return Split::splits_by_participantid($rows);
    }

    private static function splits_by_participantid($rows) {
        $splits = array();
        foreach ($rows as $row) {
            $participant_id = $row['participantid'];
            $split_time = $row['splittime'];
            $splits[$participant_id] = $split_time;
        }
        return $splits;
    }

    public static function latest_split($participant_id) {
        $query = DB::connection()->prepare('SELECT MAX(splitnumber) '
                . 'FROM Split where participantid = :id');
        $query->execute(array('id' => $participant_id));
        $row = $query->fetch();
        return $row['max'];
    }
//    private static function get_attributes($row) {
//        $attributes = array();
//        $attributes['id'] = $row['id'];
//        $attributes['participant_id'] = $row['participantid'];
//        $attributes['split_number'] = $row['splitnumber'];
//        $attributes['split_time'] = $row['splittime'];
//        return $attributes;
//    }
}
