<?php

  class BaseModel{
    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null){
      // Käydään assosiaatiolistan avaimet läpi
      foreach($attributes as $attribute => $value){
        // Jos avaimen niminen attribuutti on olemassa...
        if(property_exists($this, $attribute)){
          // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
          $this->{$attribute} = $value;
        }
      }
    }

    public function errors(){
      // Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
      $errors = array();

      foreach($this->validators as $validator){
        // Kutsu validointimetodia tässä ja lisää sen palauttamat virheet errors-taulukkoon
          $validatorErrors = $this->{$validator}();
          $errors = array_merge($errors, $validatorErrors);
      }

      return $errors;
    }
    
    public static function get_every_id($table_name) {
        $query = DB::connection()->prepare('SELECT id FROM ' . $table_name);
        $query->execute();
        $rows = $query->fetchAll();
        $ids = array();
        foreach ($rows as $row) {
            $ids[] = $row['id'];
        }
        return $ids;
    }
    
//    public static function validate_string($string, $minLength, $maxLength) {
//        $errors = array();
//        if ($string == '' || $string == null) {
//            $errors[] = 'Merkkijono ei saa olla tyhjä!';
//        }
//        
//        if (strlen($string) > $maxLength || strlen($string < $minLength)) {
//            $errors[] = 'Merkkijonon tulee olla vähintään ' + $minLength +
//                    ' merkkiä pitkä ja enintään ' + $maxLength + ' merkkiä pitkä';
//        }
//        return $errors;
//    }
    
    public static function string_not_null_or_empty($string) {
        if ($string == '' || $string == null) {
            return false;
        }
        return true;
    }
    
    public static function string_is_proper_length($string, $min, $max) {
        if (strlen($string) < $min || strlen($string) > $max) {
            return false;
        }
        return true;
    }
    
    public static function date_is_proper_format($date) {
        $dt = DateTime::createFromFormat("d.m.Y", $date);
        return $dt !== false && !array_sum($dt->getLastErrors());
        // stackoverflow copypaste:
        // $dt !== false ensures that the date can be parsed with the specified
        // fortmat and the array_sum trick is a terse way of ensuring that PHP 
        // did not do "month shifting" (e.g. consider that January 32 is February 1).
    }
    
    public static function dateTime_is_proper_format($dateTime) {
        $dt = DateTime::createFromFormat("d.m.Y G:i", $dateTime);
        return $dt !== false && !array_sum($dt->getLastErrors());
    }
    
    public static function interval_is_proper_format($interval) {
        return preg_match("/^(([0-9][0-9])(:[0-5][0-9]){2}.([0-9]){3})$/", $interval);
    }
  }
