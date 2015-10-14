<?php

class Redirect {

    public static function to($path, $message = null) {
        self::do_the_redirection(BASE_PATH . $path, $message);
    }

    private static function do_the_redirection($path, $message = null) {
        // Katsotaan onko $message parametri asetettu
        if (!is_null($message)) {
            // Jos on, lisätään se sessioksi JSON-muodossa
            $_SESSION['flash_message'] = json_encode($message);
        }

        // Ohjataan käyttäjä annettuun polkuun
        header('Location: ' . $path);

        exit();
    }

    public static function previous($message = null) {
        if (!isset($_SERVER['HTTP_REFERER'])) {
           self::to('', array('message' => 'Sinulla ei ole oikeuksia tähän toimintoon'));
        } else {
            self::do_the_redirection($_SERVER['HTTP_REFERER'], $message);
        }
    }

}
