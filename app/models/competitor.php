<?php

class Competitor extends BaseModel {

    Public $id, $name, $birthdate, $country;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Competitor (competitorname, '
                . 'birthdate, country) VALUES (:competitorname, :birthdate, :country) '
                . 'RETURNING id');
        $query->execute(array('competitorname' => $this->name, 
            'birthdate' => $this->birthdate, 'country' => $this->country));
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Competitor');
        $query->execute();
        $rows = $query->fetchAll();
        $competitors = array();
        foreach ($rows as $row) {
            $competitors[] = new Competitor(array(
                'id' => $row['id'],
                'name' => $row['competitorname'],
                'birthdate' => $row['birthdate'],
                'country' => $row['country']
            ));
        }
        return $competitors;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Competitor WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $competitor = new Competitor(array(
                'id' => $row['id'],
                'name' => $row['competitorname'],
                'birthdate' => $row['birthdate'],
                'country' => $row['country']
            ));
            return $competitor;
        }
        return null;
    }

}
