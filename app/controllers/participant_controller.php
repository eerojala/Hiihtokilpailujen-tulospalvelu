<?php

class ParticipantController extends BaseController {
    public static function index() {
        $participants = Participant::all();
        View::make('participant/index.html', array(
            'header' => 'Kaikkien kilpailujen osallistujat', 'participants' => $participants));
    }
}

