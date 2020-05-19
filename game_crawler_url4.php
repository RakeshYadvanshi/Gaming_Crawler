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
            
            $url4='https://tabstats.com/apex/leaderboards'; //Apex Legends: 

                            //For URL no 4--start
                             $html = file_get_html($url4,false,stream_context_create(array(
                                'http'=>array(
                                  'method'=>"GET",
                                  'header'=>"Accept-language: en\r\n" .
                                            "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad 
                                )
                              )));
                            
                            echo '<table>
                                <thead>
                                    <tr>
                                        <th>No#</th>
                                        <th>Name</th>
                                        <th>Link</th>
                                        <th>Level</th>
                                        <th>Elo</th>
                                        <th>Score</th>
                                    </tr>
                                </thead>
                                <tbody>';                               
                            $count=1;
                            foreach($html->find('div.oneplayer') as $row)
                            {
                                echo $children = $row->children(1);
                                
                                echo $link = $row->getAttribute('mgo');         
                                // $rank = $row->children(0)->text();
                                // $name = $row->children(1)->children(0)->text();
                                // $link = $row->children(1)->children(0)->getAttribute('href');
                                // $wins = $row->children(2)->text();
                                // $matches = $row->children(3)->text();

                                echo "<tr>
                                        <td>$count</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>";
                                $count++;
                            }
                            echo'</tbody>
                            </table>';

                            //For URL no 4--end
                           
            }
        
        } catch (Exception $e) {
        	echo $e->getMessage(); // display error message
        	exit();
        }
    
    }

start();