<?php

class Competitor extends BaseModel {

    Public $id, $name, $birthdate, $country;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_birthdate', 'validate_country');
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Competitor (competitorname, '
                . 'birthdate, country) VALUES (:competitorname, :birthdate, :country) '
                . 'RETURNING id');
        $query->execute($this->queryValues());
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    private function queryValues() {
        $values = array();
        $values['competitorname'] = $this->name;
        $values['birthdate'] = $this->birthdate;
        $values['country'] = $this->country;
        return $values;
    }
    
    public function update() {
        $query = DB::connection()->prepare('UPDATE Competitor '
                . 'SET competitorname = :competitorname, birthdate = :birthdate, country = :country '
                . 'WHERE id = :id');
        $queryValues = $this->queryValues();
        $queryValues['id'] = $this->id;
        $query->execute($queryValues);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Competitor');
        $query->execute();
        $rows = $query->fetchAll();
        $competitors = array();
        foreach ($rows as $row) {
            $competitors[] = new Competitor(self::get_attributes($row));
        }
        return $competitors;
    }
    
    private static function get_attributes($row) {
        $attributes = array();
        $attributes['id'] = $row['id'];
        $attributes['name'] = $row['competitorname'];
        $attributes['birthdate'] = date_format(new DateTime($row['birthdate']), "d.m.Y");
        $attributes['country'] = $row['country'];
        return $attributes;
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Competitor WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            return new Competitor(Competitor::get_attributes($row));
        }
        return null;
    }
    
    public static function delete($id) {
        $query = DB::connection()->prepare('DELETE FROM Competitor WHERE id = :id');
        $query->execute(array('id' => $id));
    }
    
    public function validate_name() {
        $errors = array();
        if (BaseModel::string_not_null_or_empty($this->name) == false) {
            $errors[] = 'Nimi ei saa olla tyhjä';
        }
        if (BaseModel::string_is_proper_length($this->name, 3, 50) == false) {
            $errors[] = 'Nimen pituuden tulee olla välillä 3-50';
        }
        return $errors;  
    }    
    
    public function validate_birthdate() {
        $errors = array();
        if (BaseModel::date_is_proper_format($this->birthdate) == false) {
            $errors[] = 'Syntymäpäivän tulee olla muotoa d.m.yyyy';
        }
        return $errors;
    }
    
    public function validate_country() {
        $errors = array();
        if (BaseModel::string_not_null_or_empty($this->country) == false) {
            $errors[] = 'Maan nimi ei saa olla tyhjä';
        }
        if (BaseModel::string_is_proper_length($this->country, 4, 30) == false) {
            $errors[] = 'Maan nimen pituuden tulee olla välillä 4-30';
        }
        return $errors;  
    }
}
