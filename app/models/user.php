<?php

class User extends BaseModel {

    public $id, $username, $password;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function authenticate($username, $password) {
        $query = DB::connection()->prepare('SELECT * FROM Operator WHERE username = :username AND password = :password LIMIT 1');
        $query->execute(array('username' => $username, 'password' => $password));
        $row = $query->fetch();
        if ($row) {
            return new User(array(
                'id' => $row['id'], 'username' => $row['username'], 
                'password' => $row['password']));
        }
        return null;
    }
    
    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM Operator WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            return new User(array(
                'id' => $row['id'], 'username' => $row['username'],
                'password' => $row['password']
            ));
        }
        return null;
    }

}
