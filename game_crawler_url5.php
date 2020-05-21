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
            
            $url5='https://codstats.net/leaderboards'; //Call Of Duty: 
             
                            //For URL no 5--start
                             $html = file_get_html($url5,false,stream_context_create(array(
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
                            //             <th>Value</th>
                            //             <th>LDL</th>
                            //         </tr>
                            //     </thead>
                            //     <tbody>';                               
                            // $count=1;
                            foreach($html->find('div.main_content div a.white') as $row)
                            {
                                $link = $row->getAttribute('href');
                                $rank = $row->children(0)->children(0)->children(0)->children(0)->text();
                                $name = $row->children(0)->children(0)->children(0)->children(2)->text();
                                $name=trim($name);
                                $value = $row->children(0)->children(0)->children(1)->text();
                                $ldl = $row->children(0)->children(0)->children(2)->children(1)->text();

                                // echo "<tr>
                                //         <td>$count</td>
                                //         <td>$name</td>
                                //         <td>$link</td>
                                //         <td>$rank</td>
                                //         <td>$value</td>
                                //         <td>$ldl</td>
                                //     </tr>";
                                // $count++;
                                $date=date('Y-m-d'); 

                            $sql_check="SELECT name,source,created_date FROM `scrape_data` WHERE name='$name' AND source='$url5' AND created_date='$date'";
                            $results=$Db->getTable($sql_check);

                            if($results->num_rows == 0){
                                 $sql = "INSERT INTO `scrape_data` (name,link,rank,value,ldl,source,created_date) 
                            VALUES ('".$name."','".$link."','".$rank."','".$value."','".$ldl."','".$url5."','".$date."')";
                            $results = $Db->getTable($sql);
                            }
                        }
                            // echo'</tbody></table>';

            }
        
        } catch (Exception $e) {
        	echo $e->getMessage(); // display error message
        	exit();
        }
    
    }

start();