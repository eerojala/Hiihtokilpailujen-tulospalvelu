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

    public static function get_every_competition_split($competition_id) {
        $query = DB::connection()->prepare(
                'SELECT Split.participantid, Split.splitnumber, Split.splittime '
                . 'FROM Split INNER JOIN Participant '
                . 'ON Split.participantid = Participant.id AND Participant.competitionid = :compid');
        $query->execute(array('compid' => $competition_id));
        return self::split_matrice($query->fetchAll());
    }

    private static function split_matrice($rows) {
        $matrice = array();
        foreach ($rows as $row) {
            $participant_id = $row['participantid'];
            $split_number = $row['splitnumber'];
            $split_time = $row['splittime'];
            $matrice[$participant_id][$split_number] = $split_time;
        }
        return $matrice;
    }

    public static function get_latest_split_number($participant_id) {
        $query = DB::connection()->prepare('SELECT MAX(splitnumber) '
                . 'FROM Split where participantid = :id');
        $query->execute(array('id' => $participant_id));
        $row = $query->fetch();
        return $row['max'];
    }

    public static function get_latest_split($participant_id) {
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
        $latest_split = self::get_latest_split_number($participant_id);
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
