<?php

class User extends BaseModel {

    public $id, $username, $password, $type, $real_name;

    public function __construct($attributes) {
        parent::__construct($attributes);
        $this->validators = array('validate_username', 'validate_password',
            'validate_usertype', 'validate_real_name');
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

    public function register() {
        $query = DB::connection()->prepare(
                'INSERT INTO Operator (username, password, usertype, realname) '
                . 'VALUES (:username, :password, :type, :name) RETURNING id');
        $query->execute(self::query_values());
        $row = $query->fetch();
        $this->id = $row['id'];
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE Operator '
                . 'SET username = :username, password = :password, usertype = :type,'
                . 'realname = :name '
                . 'WHERE id = :id');
        $query_values = $this->query_values();
        $query_values['id'] = $this->id;
        $query->execute($query_values);
    }

    private function query_values() {
        return array(
            'username' => $this->username,
            'password' => $this->password,
            'type' => $this->type,
            'name' => $this->real_name
        );
    }

    public function give_recording_rights($competition_id) {
        $query = DB::connection()->prepare('INSERT INTO Recorder (competitionid, userid) '
                . 'VALUES (:competitionid, :userid)');
        $query->execute(array('competitionid' => $competition_id, 'userid' => $this->id));
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
            'type' => $row['usertype'],
            'real_name' => $row['realname']
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

    private static function nickname_already_registered($username) {
        $query = DB::connection()->prepare('SELECT username FROM Operator');
        $query->execute();
        $rows = $query->fetchAll();

        foreach ($rows as $row) {
            if ($row['username'] == $username) {
                return true;
            }
        }
        return false;
    }

    public static function all($search) {
        $rows = self::default_all('Operator', 'realname', 'username ILIKE :search OR realname ILIKE :search', $search);
        $users = array();
        foreach ($rows as $row) {
            $users[] = new User(self::get_attributes($row));
        }
        return $users;
    }

    public static function delete($id) {
        $query = DB::connection()->prepare('DELETE FROM Operator WHERE id = :id');
        $query->execute(array('id' => $id));
    }

    public function validate_username() {
        $errors = array();

        if (!self::string_not_null_or_empty($this->username)) {
            $errors[] = 'Käyttäjätunnus ei saa olla tyhjä';
        }

        if (!self::string_is_proper_length($this->username, 1, 20)) {
            $errors[] = 'Käyttäjätunnuksen pituuden tulee olla välillä 1-20';
        }

        if (self::nickname_already_registered($this->username)) {
            $errors[] = 'Tämä käyttäjätunnus on jo rekisteröity palveluun - '
                    . 'Valitse jokin toinen käyttäjätunnuksen nimi';
        }
        return $errors;
    }

    public function validate_real_name() {
        $errors = array();

        if (!self::string_not_null_or_empty($this->real_name)) {
            $errors[] = 'Nimi ei saa olla tyhjä';
        }

        if (!self::string_is_proper_length($this->real_name, 3, 50)) {
            $errors[] = 'Nimen pituuden tulee olla välillä 3-50';
        }
        return $errors;
    }

    public function validate_password() {
        $errors = array();

        if (!self::string_not_null_or_empty($this->password)) {
            $errors[] = 'Salasana ei saa olla tyhjä';
        }

        if (!self::string_is_proper_length($this->password, 5, 30)) {
            $errors[] = 'Salasanan pituuden tulee olla välillä 5-30';
        }
        return $errors;
    }

    public function validate_usertype() {
        $errors = array();
        $usertype = $this->type;
        if (!($usertype == 'normal' || $usertype == 'admin' || $usertype == 'recorder')) {
            $errors[] = 'Tuntematon käyttäjätyyppi';
        }
        return $errors;
    }

}
