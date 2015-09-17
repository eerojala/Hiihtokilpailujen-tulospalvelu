<?php

class Competition extends BaseModel {

    public $id, $name, $location, $startsAt, $endsAt, $finished;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO Competition (competitionname,'
                . 'location, startsat, endsat, finished) VALUES (:competitionname,'
                . ':location, :startsat, :endsat, :finished) RETURNING id');
        $query->execute(array('competitionname' => $this->name, 'location' => $this->location,
            'startsat' => $this->startsAt, 'endsat' => $this->endsAt, 'finished' => $this->finished));
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM Competition');
        $query->execute();
        $rows = $query->fetchAll();
        $competitions = array();
        foreach ($rows as $row) {
            $competitions[] = new Competition(array(
                'id'=> $row['id'],
                'name' => $row['competitionname'],
                'location' => $row['location'],
                'startsAt' => $row['startsat'],
                'endsAt' => $row['endsat'],
                'finished' => $row['finished']
            ));
        }
        return $competitions;
    }
    
    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Competition WHERE id = :id LIMIT 1');
        $query->execute(array ('id' => $id));
        $row = $query->fetch();
        
        if ($row) {
            $competition = new Competition(array(
                'id'=> $row['id'],
                'name' => $row['competitionname'],
                'location' => $row['location'],
                'startsAt' => $row['startsat'],
                'endsAt' => $row['endsat'],
                'finished' => $row['finished']
            ));
            return $competition;
        }
        return null;
    }
}
