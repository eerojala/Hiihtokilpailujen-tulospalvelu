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

    public function update() {
        $query = DB::connection()->prepare('UPDATE Split '
                . 'SET participantid = :participantid, splitnumber = :splitnumber, '
                . 'splittime = :splittime WHERE id = :id');
        $values = $this->query_values();
        $values['id'] = $this->id;
        $query->execute($values);
    }

    
    // returns an array of Xth splits from a competition where the array key is
    // the participants id and the value is the time of the split.
    public static function get_xth_competition_splits_by_participant_id($competition_id, $split_number) {
        $rows = self::get_xth_competition_splits($competition_id, $split_number);
        return Split::splits_by_participant_id($rows);
    }
    
    private static function get_xth_competition_splits($competition_id, $split_number) {
        $query = DB::connection()->prepare('SELECT Split.participantid, Split.splittime '
                . 'FROM Split INNER JOIN Participant '
                . 'ON Split.participantid = Participant.id AND '
                . 'Participant.competitionid = :compid AND Split.splitnumber = :number '
                . 'Order by splittime ASC');
        $query->execute(array('compid' => $competition_id, 'number' => $split_number));
        return $query->fetchAll();
    }

    private static function splits_by_participant_id($rows) {
        $splits = array();
        foreach ($rows as $row) {
            $participant_id = $row['participantid'];
            $split_time = $row['splittime'];
            $splits[$participant_id] = $split_time;
        }
        return $splits;
    }
//    
//    // returns an array of Xth splits from a competition where the array key is
//    // the time of the split and the value is the participants id.
//    public static function get_xth_competition_splits_by_split_time($competition_id, $split_number) {
//        $rows = self::get_xth_competition_splits($competition_id, $split_number);
//        return self::participant_ids_by_splits($rows);
//    }
    
//    private static function participant_ids_by_splits($rows) {
//        $splits = array();
//        foreach($rows as $row) {
//            $participant_id = $row['participantid'];
//            $split_time = $row['splittime'];
//            $splits[$split_time] = $participant_id;
//        }
//        return $splits;
//    }

    public static function latest_split_number($participant_id) {
        $query = DB::connection()->prepare('SELECT MAX(splitnumber) '
                . 'FROM Split where participantid = :id');
        $query->execute(array('id' => $participant_id));
        $row = $query->fetch();
        return $row['max'];
    }

    public static function latest_split($participant_id) {
        $query = DB::connection()->prepare('SELECT * FROM SPLIT '
                . 'WHERE participantid = :id '
                . 'ORDER BY splitnumber DESC LIMIT 1');
        $query->execute(array('id' => $participant_id));
        $row = $query->fetch();
        if ($row) {
            return new Split(self::get_attributes($row));
        }
        return null;
    }

    public static function get_numbers_of_splits_done_so_far($participant_id) {
        $latest_split = self::latest_split_number($participant_id);
        $split_numbers = array();
        for ($i = 1; $i <= $latest_split; $i++) {
            $split_numbers[] = $i;
        }
        return $split_numbers;
        // funktio palauttaa listan osallistujan suorittamien väliaikojen numeroista
        // esim jos kilpailija on suorittanut 2 väliaikaa,
        // palauttaa funktio listan joka koostuu numeroista 1 ja 2.
    }

    public static function participants_splits($participant_id) {
        $query = DB::connection()->prepare('SELECT * FROM Split '
                . 'WHERE participantid = :participant_id');
        $query->execute(array('participant_id' => $participant_id));
        $rows = $query->fetchAll();
        $splits = array();
        foreach ($rows as $row) {
            $splits[] = new Split(self::get_attributes($row));
        }
        return $splits;
    }

    private static function get_attributes($row) {
        $attributes = array();
        $attributes['id'] = $row['id'];
        $attributes['participant_id'] = $row['participantid'];
        $attributes['split_number'] = $row['splitnumber'];
        $attributes['split_time'] = $row['splittime'];
        return $attributes;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Split WHERE id = :id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            return new Split(self::get_attributes($row));
        }
        return null;
    }
    
    public function validate_split_time() {
        $errors = array();

        if (!self::interval_is_proper_format($this->split_time)) {
            $errors[] = 'Väliajan tulee olla millisekuntien tarkkuudella '
                    . 'muotoa hh:mi:ss.ms, esim 00:00:00.000';
        }

        return $errors;
    }

}
