<?php

class User extends BaseModel {

    public $id, $username, $password, $type;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public function has_rights_to_competition_splits($competition_id) {
        $query = DB::connection()->prepare('SELECT * FROM Recorder '
                . 'WHERE competitionid = :compid AND userid = :userid');
        $query->execute(array('compid' => $competition_id, 'userid' => $this->id));
        if ($query->fetch()) {
            return true;
        }
        return false;
    }

    public static function authenticate($username, $password) {
        $query = DB::connection()->prepare(
                'SELECT * FROM Operator WHERE username = :username '
                . 'AND password = :password LIMIT 1');
        $query->execute(array('username' => $username, 'password' => $password));
        $row = $query->fetch();
        if ($row) {
            return new User(self::get_attributes($row));
        }
        return null;
    }

    private static function get_attributes($row) {
        return array(
            'id' => $row['id'],
            'username' => $row['username'],
            'password' => $row['password'],
            'type' => $row['usertype']
        );
    }

    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Operator WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            return new User(self::get_attributes($row));
        }
        return null;
    }

}
