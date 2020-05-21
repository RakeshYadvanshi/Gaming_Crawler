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
            
            $url3='https://fortnitetracker.com/leaderboards/all/Top1?mode=p9'; //Fortnite:

                            //For URL no 3--start
                             $html = file_get_html($url3,false,stream_context_create(array(
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
                            //             <th>Rank</th>
                            //             <th>Wins</th>
                            //             <th>Matches</th>
                            //         </tr>
                            //     </thead>
                            //     <tbody>';                               
                            // $count=1;
                            foreach($html->find('div.trn-table-container table.trn-table tr.trn-table__row.trn-lb-entry') as $row)
                            {
                                $rank = $row->children(0)->text();
                                $name = $row->children(1)->children(0)->text();
                                $link = $row->children(1)->children(0)->getAttribute('href');
                                $wins = $row->children(2)->text();
                                $matches = $row->children(3)->text();

                                // echo "<tr>
                                //         <td>$count</td>
                                //         <td>$name</td>
                                //         <td>$link</td>
                                //         <td>$rank</td>
                                //         <td>$wins</td>
                                //         <td>$matches</td>
                                //     </tr>";
                                // $count++;
                                  $date=date('Y-m-d'); 

                            $sql_check="SELECT name,source,created_date FROM `scrape_data` WHERE name='$name' AND source='$url3' AND created_date='$date'";
                            $results=$Db->getTable($sql_check);

                            if($results->num_rows == 0){
                             $sql = "INSERT INTO `scrape_data` (name,link,rank,wins,matches,source,created_date) 
                            VALUES ('".$name."','".$link."','".$rank."','".$wins."','".$matches."','".$url3."','".$date."')";
                            $results = $Db->getTable($sql);
                            }
                        }
                            // echo'</tbody></table>';

                            //For URL no 3--end
            }
        
        } catch (Exception $e) {
        	echo $e->getMessage(); // display error message
        	exit();
        }
    
    }

start();