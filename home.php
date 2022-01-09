<?php
// Define array with values
$stat_translate = array("pas_att" => "PassingAttempts","pas_cmp" => "PassingCompletions","pas_tds" => "PassingTouchdowns","pas_yds" => "PassingYards","rus_att" => "RushingAttempts","rus_tds" => "RushingTouchdowns","rus_yds" => "RushingYards","rec_rec" => "Receptions","rec_tds" => "ReceivingTouchdowns","rec_yds" => "ReceivingYards");
$score_guide = array("pas_yds"=>0.04,"pas_tds"=>4.00,"rus_yds"=>0.10,"rus_tds"=>6.00,"rec_rec"=>1.00,"rec_yds"=>0.10,"rec_tds"=>6.00);

// Read the JSON file
$json = file_get_contents('files/projections.json');

// Decode the JSON file
$json_data = json_decode($json,true);

// Define some variables
$gameIds = $eventArray = array();
$x = $y = 0;

// Create a for loop for fetching game data
for($i=0;$i<count($json_data);$i++){
    // Check if duplicate game Id
    if(!in_array($json_data[$i]['GameID'],$gameIds)){
            // Add new gameID in array
            array_push($gameIds,$json_data[$i]['GameID']);
            $eventArray['Events'][$x]['GameID'] = $json_data[$i]['GameID'];
            $eventArray['Events'][$x]['DateTime'] = $json_data[$i]['DateTime'];
            $teamArray = array();
            // Create a for loop for fetching team data
            for($j=0; $j<count($json_data);$j++){
                // Check Main game id is same as looping array game id 
                if($eventArray['Events'][$x]['GameID'] == $json_data[$j]['GameID']){
                    // Check if duplicate Team Id
                    if(!in_array($json_data[$j]['TeamID'],$teamArray)){
                            $z=0; // define variable for players array
                            // Add new TeamID in array
                            array_push($teamArray,$json_data[$j]['TeamID']);
                            $eventArray['Events'][$x]['Teams'][$y]['TeamID'] = $json_data[$j]['TeamID'];
                            $eventArray['Events'][$x]['Teams'][$y]['Team'] = $json_data[$j]['Team'];
                            $eventArray['Events'][$x]['Teams'][$y]['Team_Abbr'] = $json_data[$j]['Team_Abbr'];
                            $playerArray = array();
                            // Create a for loop for fetching Player data
                            for($k=0; $k<count($json_data);$k++){
                                // Check Main game id is same as looping array game id And Check Main Team id is same as looping array Team id 
                                if(($eventArray['Events'][$x]['GameID'] == $json_data[$k]['GameID']) && ($eventArray['Events'][$x]['Teams'][$y]['TeamID'] == $json_data[$k]['TeamID'])){
                                        $eventArray['Events'][$x]['Teams'][$y]['Players'][$z]['PlayerID'] = $json_data[$k]['PlayerID'];
                                        $eventArray['Events'][$x]['Teams'][$y]['Players'][$z]['Player'] = $json_data[$k]['Player'];
                                        $eventArray['Events'][$x]['Teams'][$y]['Players'][$z]['Position'] = $json_data[$k]['Position'];
                                        
                                        // Count Fantasy point by sum of all projections value multiply with score guide values
                                        $eventArray['Events'][$x]['Teams'][$y]['Players'][$z]['FantasyPoints'] = round(($json_data[$k]['pas_att']+$json_data[$k]['pas_cmp']+$json_data[$k]['rus_att']+($json_data[$k]['pas_tds'] * $score_guide['pas_tds'])+($json_data[$k]['pas_yds'] * $score_guide['pas_yds'])+($json_data[$k]['rus_tds'] * $score_guide['rus_tds'])
                                        +($json_data[$k]['rus_yds'] * $score_guide['rus_yds'])+($json_data[$k]['rec_rec'] * $score_guide['rec_rec'])+($json_data[$k]['rec_tds'] * $score_guide['rec_tds'])+($json_data[$k]['rec_yds'] * $score_guide['rec_yds'])),2);

                                        // Projection value multiply with score guide values
                                        $eventArray['Events'][$x]['Teams'][$y]['Players'][$z]['Projections'][$stat_translate['pas_att']] = round($json_data[$k]['pas_att'],2);
                                        $eventArray['Events'][$x]['Teams'][$y]['Players'][$z]['Projections'][$stat_translate['pas_cmp']] = round($json_data[$k]['pas_cmp'],2);
                                        $eventArray['Events'][$x]['Teams'][$y]['Players'][$z]['Projections'][$stat_translate['pas_tds']] = round($json_data[$k]['pas_tds'] * $score_guide['pas_tds'],2);
                                        $eventArray['Events'][$x]['Teams'][$y]['Players'][$z]['Projections'][$stat_translate['pas_yds']] = round($json_data[$k]['pas_yds'] * $score_guide['pas_yds'],2);
                                        $eventArray['Events'][$x]['Teams'][$y]['Players'][$z]['Projections'][$stat_translate['rus_att']] = round($json_data[$k]['rus_att'],2);
                                        $eventArray['Events'][$x]['Teams'][$y]['Players'][$z]['Projections'][$stat_translate['rus_tds']] = round($json_data[$k]['rus_tds'] * $score_guide['rus_tds'],2);
                                        $eventArray['Events'][$x]['Teams'][$y]['Players'][$z]['Projections'][$stat_translate['rus_yds']] = round($json_data[$k]['rus_yds'] * $score_guide['rus_yds'],2);
                                        $eventArray['Events'][$x]['Teams'][$y]['Players'][$z]['Projections'][$stat_translate['rec_rec']] = round($json_data[$k]['rec_rec'] * $score_guide['rec_rec'],2);
                                        $eventArray['Events'][$x]['Teams'][$y]['Players'][$z]['Projections'][$stat_translate['rec_tds']] = round($json_data[$k]['rec_tds'] * $score_guide['rec_tds'],2);
                                        $eventArray['Events'][$x]['Teams'][$y]['Players'][$z]['Projections'][$stat_translate['rec_yds']] = round($json_data[$k]['rec_yds'] * $score_guide['rec_yds'],2);
                                        
                                        $z++; // increment this variable for Player array
                                }
                            }
                            // Start Sorting the players array by the fantasypoints decending
                            $price = array_column($eventArray['Events'][$x]['Teams'][$y]['Players'], 'FantasyPoints');
                            array_multisort($price, SORT_DESC, $eventArray['Events'][$x]['Teams'][$y]['Players']);
                            // End Sorting the players array by the fantasypoints decending
                            $y++; // increment this variable for Team array
                    }
                }
            }
            
            $x++; // increment this variable for Game array
    }
}

// // Display data
// echo "<pre>";
// print_r($eventArray);
// echo "</pre>";
// die();

// Create Json heirarchy data
$result_json_data = json_encode($eventArray);

// Create filename
$filename = 'formated_projections' . date( 'Y-m-dhis' );

// Force download .json file with JSON in it
header("Content-type: application/vnd.ms-excel");
header("Content-Type: application/force-download");
header("Content-Type: application/download");
header("Content-disposition: " . $filename . ".json");
header("Content-disposition: filename=" . $filename . ".json");

print $result_json_data;
exit;

?>