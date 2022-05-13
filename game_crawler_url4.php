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
            
            // $url4='https://tabstats.com/apex/leaderboards'; //Apex Legends: 
            $url4='https://apex.apitab.com/leaderboards/windows/elo?u=21';

                            //For URL no 4--start
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_URL, $url4);
                            $result = curl_exec($ch);
                            curl_close($ch);

                            $obj = json_decode($result);
                            foreach($obj->players as $player){
                                
                                $link='/apex/player/'.$player->player->name.'/'.$player->player->pid;
                                $name=$player->player->name;
                                $level=$player->stats->level;
                                $score=$player->stats->elo;
                                $rank=$player->stats->elorank;
                                $date=date('Y-m-d'); 

                                $sql_check="SELECT name,source,created_date FROM `scrape_data` WHERE name='$name' AND source='$url4' AND created_date='$date'";
                                $results=$Db->getTable($sql_check);

                                if(isset($results->num_rows) && $results->num_rows == 0){
                                 $sql = "INSERT INTO `scrape_data` (name,link,rank,level,score,source,created_date) 
                                VALUES ('".$name."','".$link."','".$rank."','".$level."','".$score."','".$url4."','".$date."')";
                                $results = $Db->getTable($sql);
                                }
                            }
                            //For URL no 4--end
                           
            }
        
        } catch (Exception $e) {
        	echo $e->getMessage(); // display error message
        	exit();
        }
    
    }

start();