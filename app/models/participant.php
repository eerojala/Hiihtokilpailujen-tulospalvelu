<?php

class Participant extends BaseModel {

    public $id, $competition_id, $competition_name, $competitor_id, $competitor_name,
            $number, $standing;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array(
            'validate_competition_id', 'validate_competitor_id', 'validate_number');
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO PARTICIPANT '
                . '(competitionid, competitorid, participantnumber) '
                . 'VALUES (:competitionid, :competitorid, :participantnumber) '
                . 'RETURNING id');
        $query->execute($this->query_values());
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    private function query_values() {
        $values = array();
        $values['competitionid'] = $this->competition_id;
        $values['competitorid'] = $this->competitor_id;
        $values['participantnumber'] = $this->number;
        return $values;
    }

    public function update() {
        $query = DB::connection()->prepare("UPDATE Participant "
                . "SET competitionid = :competitionid, competitorid = :competitorid, "
                . "participantnumber = :participantnumber, standing = :standing "
                . "WHERE id = :id");
        $queryValues = $this->query_values();
        $queryValues['id'] = $this->id;
        $queryValues['standing'] = $this->standing;
        $query->execute($queryValues);
    }

    public static function all() {
        $query = DB::connection()->prepare("SELECT * FROM Participant");
        $query->execute();
        return self::participant_list($query);
    }

    private static function participant_list($query) {
        $rows = $query->fetchAll();
        $participants = array();
        foreach ($rows as $row) {
            $participants[] = new Participant(self::get_attributes($row));
        }
        return $participants;
    }

    private static function get_attributes($row) {
        $attributes = array();
        $attributes['id'] = $row['id'];
        $attributes['competition_id'] = $row['competitionid'];
        $attributes['competition_name'] = Competition::find($attributes['competition_id'])->name;
        $attributes['competitor_id'] = $row['competitorid'];
        $attributes['competitor_name'] = Competitor::find($attributes['competitor_id'])->name;
        $attributes['number'] = $row['participantnumber'];
        $attributes['standing'] = $row['standing'];
        return $attributes;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Participant WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            return new Participant(self::get_attributes($row));
        }
        return null;
    }

    public static function get_competition_participants($competition_id) {
        $query = DB::connection()->prepare(
                "SELECT * FROM Participant WHERE competitionid = :compid");
        $query->execute(array('compid' => $competition_id));
        return self::participant_list($query);
    }

    public static function get_competitor_participations($competitor_id) {
        $query = DB::connection()->prepare(''
                . 'SELECT * FROM Participant WHERE competitorid = :compid');
        $query->execute(array('compid' => $competitor_id));
        return self::participant_list($query);
    }

    private static function get_competition_participants_competitor_ids($competition_id) {
        $participants = self::get_competition_participants($competition_id);
        $competitor_ids = array();
        foreach ($participants as $participant) {
            $competitor_ids[] = $participant->competitor_id;
        }
        return $competitor_ids;
    }

    private static function get_competition_participant_numbers($competition_id) {
        $participants = self::get_competition_participants($competition_id);
        $numbers = array();
        foreach ($participants as $participant) {
            $numbers[] = $participant->number;
        }
        return $numbers;
    }

    public static function update_competition_standings($competition_id) {
        $participant_ids = self::get_participant_ids_ordered_by_split_times($competition_id, 3);
        $standing = 1;
        foreach ($participant_ids as $id) {
            $participant = self::find($id);
            $participant->standing = $standing;
            $participant->update();
            $standing++;
        }
    }

    private static function get_participant_ids_ordered_by_split_times($competition_id, $split_number) {
        $query = DB::connection()->prepare('SELECT Participant.id FROM Participant '
                . 'INNER JOIN Split ON Participant.id = Split.participantid '
                . 'AND Split.splitnumber = :number AND Participant.competitionid = :id '
                . 'ORDER BY Split.splittime ASC');
        $query->execute(array('number' => $split_number, 'id' => $competition_id));
        $rows = $query->fetchAll();
        $ids = array();
        foreach ($rows as $row) {
            $ids[] = $row['id'];
        }
        return $ids;
    }

    public static function delete($id) {
        $query = DB::connection()->prepare('DELETE FROM Participant WHERE id = :id');
        $query->execute(array('id' => $id));
    }

    public function validate_competition_id() {
        $errors = array();
        if ($this->competition_id == 0) {
            $errors[] = 'Kilpailu id:n pitää olla nollaa suurempi kokonaisluku';
        }

        if (!in_array($this->competition_id, BaseModel::get_every_id('Competition'))) {
            $errors[] = 'Annettua kilpailu id:tä ei löytynyt tietokannasta';
        }
        return $errors;
    }

    public function validate_competitor_id() {
        $errors = array();

        if ($this->competitor_id == 0) {
            $errors[] = 'Kilpailija id:n pitää olla nollaa suurempi kokonaisluku';
        }

        if (!in_array($this->competitor_id, BaseModel::get_every_id('Competitor'))) {
            $errors[] = 'Annettua kilpaiija id:tä ei löytynyt tietokannasta';
        } else if (in_array($this->competitor_id, Participant::get_competition_participants_competitor_ids($this->competition_id))) {
            $errors[] = 'Tämä kilpailija on jo lisätty tähän kilpailuun.';
        }
        return $errors;
    }

    public function validate_number() {
        $errors = array();

        if ($this->number <= 0 || $this->number >= 10000) {
            $errors[] = 'Numeron tulee olla kokonaisluku väliltä 1-9999';
        } else if (in_array($this->number, Participant::get_competition_participant_numbers($this->competition_id))) {
            $errors[] = 'Tämä numero on jo annettu toiselle kilpailijalle tässä kilpailussa';
        }
        return $errors;
    }

}
