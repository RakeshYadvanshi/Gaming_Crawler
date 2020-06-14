<?php
/*
Author: Rakesh
*/

include( 'Scrapper/simple_html_dom.php'); 
include('DatabaseLayer/Conn.php');
include('Model/Option.php');

function start() {
	try 
    {
      $Db = new DbInterface();
        
        $downloadFlag=true;
            if($downloadFlag==true)
            {
            
            $url1='https://overwatchtracker.com/leaderboards/pc/global'; //  Overwatch : 
            
            //For URL no 1--start
            $html = file_get_html($url1,false,stream_context_create(array(
                                'http'=>array(
                                  'method'=>"GET",
                                  'header'=>"Accept-language: en\r\n" .
                                            "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad 
                                )
                              )));
             				
             	// 			echo '<table>
             	// 				<thead>
             	// 					<tr>
             	// 						<th>No#</th>
             	// 						<th>Name</th>
             	// 						<th>Link</th>
             	// 						<th>Rating</th>
             	// 						<th>Score</th>
             	// 						<th>Winrate</th>
             	// 						<th>K/D Ratio</th>
             	// 						<th>Time Played</th>
             	// 					</tr>
             	// 				</thead>
             	// 				<tbody>';             					
         					$count=1;

							foreach($html->find('table.card-table-material tbody tr') as $row)
                            {
                                if($count!=35){ //skip when add found in HTML
                                    $rank= $row->children(0)->innertext();
                                    $link= $row->children(1)->children(1)->getAttribute('href');
                                    $name= $row->children(1)->children(1)->innertext();
                                    $rating= $row->children(2)->children(0)->children(0)->innertext();
                                    $score= $row->children(2)->children(1)->innertext();
                                    $games= $row->children(3)->innertext();
                                        
                                }
                               
                              
                        	$date=date('Y-m-d'); 
							$sql_check="SELECT name,source,created_date FROM `scrape_data` WHERE name='$name' AND source='$url1' AND created_date='$date'";
							$results=$Db->getTable($sql_check);
							
							if(isset($results->num_rows) && $results->num_rows == 0){
								$sql = "INSERT INTO `scrape_data` (name,link,rating,score,rank,games,source,created_date) 
                            VALUES ('".$name."','".$link."','".$rating."','".$score."','".$rank."','".$games."','".$url1."','".$date."')";

                            	$results = $Db->getTable($sql);	
							}
                            $count++;
							}
							// echo'</tbody></table>';
                            //For URL no 1--end
                    
            }
        
        } catch (Exception $e) {
        	echo $e->getMessage(); // display error message
        	exit();
        }
    
    }

start();