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
            

            $url2='https://pubgstats.com/?sort=squad-kd'; //PUBG: 

            //For URL no 2--start
                            $html = file_get_html($url2,false,stream_context_create(array(
                                'http'=>array(
                                  'method'=>"GET",
                                  'header'=>"Accept-language: en\r\n" .
                                            "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad 
                                )
                              )));
                            
                            // echo '<table>
                            //     <thead>
                            //         <tr>
                            //             <th>No#</th>
                            //             <th>Name</th>
                            //             <th>Link</th>
                            //             <th>K/D</th>
                            //             <th>WR</th>
                            //             <th>Kills</th>
                            //             <th>Wins</th>
                            //             <th>Matches</th>
                            //             <th>Score</th>
                            //         </tr>
                            //     </thead>
                            //     <tbody>';                               
                            // $count=1;
                            foreach($html->find('div.table-responsive table.table tr.player-row') as $row)
                            {
                                $link = $row->children(0)->children(1)->getAttribute('href');
                                $name = $row->children(0)->children(1)->text();
                                $kd = $row->children(1)->text();
                                $wr = $row->children(2)->children(0)->text();
                                $kills = $row->children(3)->text();
                                $wins = $row->children(4)->text();
                                $matches = $row->children(5)->text();
                                $score = $row->children(6)->text();

                                // echo "<tr>
                                //     <td>$count</td>
                                //     <td>$name</td>
                                //     <td>$link</td>
                                //     <td>$kd</td>
                                //     <td>$wr</td>
                                //     <td>$kills</td>
                                //     <td>$wins</td>
                                //     <td>$matches</td>
                                //     <td>$score</td>
                                // </tr>";
                                // $count++;
                                $date=date('Y-m-d'); 

							$sql_check="SELECT name,source,created_date FROM `scrape_data` WHERE name='$name' AND source='$url2' AND created_date='$date'";
							$results=$Db->getTable($sql_check);

							if(isset($results->num_rows) && $results->num_rows == 0){
	                                $sql = "INSERT INTO `scrape_data` (name,link,kd,winrate,kills,wins,matches,score,source,created_date) 
	                            VALUES ('".$name."','".$link."','".$kd."','".$wr."','".$kills."','".$wins."','".$matches."','".$score."','".$url2."','".$date."')";

	                            $results = $Db->getTable($sql);
                   			}
                            }
                            // echo'</tbody></table>';

                            //For URL no 2--end
            }
        
        } catch (Exception $e) {
        	echo $e->getMessage(); // display error message
        	exit();
        }
    
    }

start();