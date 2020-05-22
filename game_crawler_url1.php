<?php
/*
Author: Rakesh
*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
            
            $url1='https://masteroverwatch.com/leaderboards/pc/global/category/averagescore'; //  Overwatch : 
            
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
         					// $count=1;
							foreach($html->find('div.data-table.widget-table .table-body .table-row') as $row)
							{
								$link = $row->children(0)->getAttribute('href');
								$name = $row->children(1)->children(1)->children(1)->children(0)->innertext();
                                $name=trim($name);
								$rating = $row->children(1)->children(1)->children(1)->children(1)->children(0)->innertext();
								$score = $row->children(1)->children(2)->children(0)->text();
								$winrate = $row->children(1)->children(3)->children(0)->children(1)->text();
								$kd_ratio = $row->children(1)->children(4)->children(0)->children(0)->text();
								$time_played_hour = $row->children(1)->children(5)->children(0)->text();
								$time_played_mins = $row->children(1)->children(5)->children(1)->text();
								$time_played=$time_played_hour.'-'.$time_played_mins;
								// echo "<tr>
								// 	<td>$count</td>
								// 	<td>$name</td>
								// 	<td>$link</td>
								// 	<td>$rating</td>
								// 	<td>$score</td>
								// 	<td>$winrate</td>
								// 	<td>$kd_ratio</td>
								// 	<td>$time_played</td>
								// </tr>";
								// $count++;
                        	$date=date('Y-m-d'); 
							$sql_check="SELECT name,source,created_date FROM `scrape_data` WHERE name='$name' AND source='$url1' AND created_date='$date'";
							$results=$Db->getTable($sql_check);
							
							if(isset($results->num_rows) && $results->num_rows == 0){
								$sql = "INSERT INTO `scrape_data` (name,link,rating,score,winrate,kd,time_played,source,created_date) 
                            VALUES ('".$name."','".$link."','".$rating."','".$score."','".$winrate."','".$kd_ratio."','".$time_played."','".$url1."','".$date."')";

                            	$results = $Db->getTable($sql);	
							}
                            
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