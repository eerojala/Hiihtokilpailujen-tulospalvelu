<?php

class ResultsController extends BaseController {
    
    public static function competition_results($competition_id) {
        $results = Results::get_competition_results($competition_id);
        ResultsController::index_view($results, Competition::find($competition_id), true); 
    }
    
    private static function index_view($results, $header_object, $boolean) {
        View::make('results/index.html', array(
            'header_object' => $header_object, 'results' => $results,
            'competition_results' => $boolean
        ));
    }
    
    public static function competitor_results($competitor_id) {
        $results = Results::get_competitor_results($competitor_id);
        ResultsController::index_view($results, Competitor::find($competitor_id), false);
    }
}

