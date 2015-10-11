<?php

class Competition extends BaseModel {

    public $id, $name, $location, $split_amount, $starts_at, $ends_at;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_name', 'validate_location',
            'validate_starts_at', 'validate_ends_at');
    }

    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Competition (competitionname, '
                . 'location, splitamount, startsat, endsat) VALUES (:competitionname, '
                . ':location, :split_amount, :starts_at, :ends_at) RETURNING id');
        $query->execute($this->queryValues());
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    private function queryValues() {
        $values = array();
        $values['competitionname'] = $this->name;
        $values['location'] = $this->location;
        $values['split_amount'] = $this->split_amount;
        $values['starts_at'] = $this->starts_at;
        $values['ends_at'] = $this->ends_at;
        return $values;
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Competition '
                . 'SET competitionname = :competitionname, location = :location, '
                . 'splitamount = :split_amount, startsat = :starts_at, endsat= :ends_at '
                . 'WHERE id = :id');
        $queryValues = $this->queryValues();
        $queryValues['id'] = $this->id;
        $query->execute($queryValues);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Competition');
        $query->execute();
        $rows = $query->fetchAll();
        $competitions = array();
        foreach ($rows as $row) {
            $competitions[] = new Competition(self::get_attributes($row));
        }
        return $competitions;
    }
    
    private static function get_attributes($row) {
        $attributes = array();
        $attributes['id'] = $row['id'];
        $attributes['name'] = $row['competitionname'];
        $attributes['location'] = $row['location'];
        $attributes['split_amount'] = $row['splitamount'];
        $attributes['starts_at'] = date_format(new DateTime($row['startsat']), "d.m.Y G:i");
        $attributes['ends_at'] = date_format(new DateTime($row['endsat']), "d.m.Y G:i");
        return $attributes;
    }
    
    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Competition WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            return new Competition(Competition::get_attributes($row));
        }
        return null;
    }
    
    public static function delete($id) {
        $query = DB::connection()->prepare('DELETE FROM Competition WHERE id = :id');
        $query->execute(array('id' => $id));
    }

    public function validate_name() {
        $errors = array();
        if (!BaseModel::string_not_null_or_empty($this->name)) {
            $errors[] = 'Kilpailun nimi ei saa olla tyhjä';
        }
        if (!BaseModel::string_is_proper_length($this->name, 1, 100)) {
            $errors[] = 'Kilpailun nimen pituuden tulee olla välillä 1-100';
        }
        return $errors;
    }

    public function validate_location() {
        $errors = array();
        if (!BaseModel::string_not_null_or_empty($this->location)) {
            $errors[] = 'Järjestämispaikan nimi ei saa olla tyhjä';
        }
        if (!BaseModel::string_is_proper_length($this->location, 1, 100)) {
            $errors[] = 'Järjestämispaikan nimen pituuden tulee olla välillä 1-100';
        }
        return $errors;
    }

    public function validate_starts_at() {
        $errors = array();
        if (!BaseModel::dateTime_is_proper_format($this->starts_at)) {
            $errors[] = 'Alkamisajankohdan tulee olla muotoa d.m.yyyy h:mi';
        }
        return $errors;
    }

    public function validate_ends_at() {
        $errors = array();
        if (!BaseModel::dateTime_is_proper_format($this->ends_at)) {
            $errors[] = 'Päättymisajankohdan tulee olla muotoa d.m.yyyy h:mi';
        }
        return $errors;
    }

}
