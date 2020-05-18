<?php
/*
Author: Rakesh
*/
include( 'Scrapper/simple_html_dom.php'); 
include('DatabaseLayer/Conn.php');
include('Model/Option.php');



function addOrUpdateOptions($OptionName, $OptionValue)
{
    $sql = "";
    if(count(getOptions($OptionName))>0){
            $sql = "INSERT INTO wp_options (option_name,option_value,autoload) VALUES ('" . $OptionName . "','" . $OptionValue . "','yes')";
        }
     else {
        $sql = "update  wp_options set option_value='" . $OptionValue . "' where option_name='" . $OptionName . "'";
    }

    $Db = new DbInterface();
    if (strlen($sql)) {
        $result = $Db->getTable($sql);
        if ($result === true) {
            return "saved";
        } else {
            return "error";
        }
    }
}


function CheckOptionsExists($OptionName)
{
    $Id = "0";
    $Db = new DbInterface();
    $result = $Db->getTable("select option_id as Id from wp_options where option_name='" . $OptionName . "'");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $Id = $row["Id"];
        }
    }

    return "";

}

function getOptions($OptionName)
{
    $Db = new DbInterface();
    $options = array();
    $result = $Db->getTable("select * from wp_options where option_name='" . $OptionName . "'");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $b = new Option();
            $b->Id = $row["option_id"];
            $b->OptionName = $row["option_name"];
            $b->OptionValue = $row["option_value"];
            array_push($options, $b);
        }
    }
    return $options;
}

function get_plug_option($ky){
    
    if(!empty(getOptions($ky)))
    {
        return array_values(getOptions($ky))[0]->OptionValue;
    }
    return "";
}

function add_update_plug_option($ky,$value)
{
    addOrUpdateOptions($ky,$value);
}

function getTitleFromMessage($msg){
        substr(str_get_html($msg)->innertext,0,50)
}

function start() {
	
    $key_last_processed="auto_post_feeders_last_processed";
    $key_Freq_Min="auto_post_feeders_freq_minute";

    
    $frequency= get_plug_option($key_Freq_Min);

    if($frequency=="")
    {
        $frequency = 60;
        add_update_plug_option($key_Freq_Min,$frequency);
    }

    try 
    {
        $Db = new DbInterface();
        $lsProcessed=get_plug_option($key_last_processed);
        $downloadFlag=false;
        if($lsProcessed=="")
        {
            $downloadFlag=true;
        }
        else {
            $start_date = new DateTime($lsProcessed);
            $since_start = $start_date->diff(new DateTime(date("Y-m-d h:i:s")));

            $minutes = $since_start->days * 24 * 60;
            $minutes += $since_start->h * 60;
            $minutes += $since_start->i;
            if($minutes>60)
                $downloadFlag=true;
        }
            if($downloadFlag==true)
            {
            $url='https://masteroverwatch.com/leaderboards/pc/global/category/averagescore';

                 $html = file_get_html($url,false,stream_context_create(array(
                                    'http'=>array(
                                      'method'=>"GET",
                                      'header'=>"Accept-language: en\r\n" .
                                                "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad 
                                    )
                                  )));



                            $sql = "INSERT INTO `"."wp_"."posts` (post_date,post_date_gmt,`post_content`, `post_author`, `post_title`, `post_status`, `comment_status`, `post_name`) 
                            VALUES ('".$created_date."','".$created_date."','".$message."','1', '".$title."', 'publish', 'closed', 'facebookpost');";
                           // echo $sql;
                            $results = $Db->getTable($sql);

                            $post_lastId = $Db->GetConnectionObj()->insert_id;
                            $sql = "INSERT INTO `"."wp_"."posts` (post_date,post_date_gmt,`post_content`, `post_author`, `post_title`, `post_status`, `comment_status`, `post_name`,`post_type`,`post_mine_type`,`guid`,post_parent) 
                            VALUES ('".$created_date."','".$created_date."','','1', 'Image', 'publish', 'closed', 'Image','attachment','image/png','".uniqid()."'','".$post_lastId."');";
                           // echo $sql;
                            $results = $Db->getTable($sql);
                            $post_Image_lastId = $Db->GetConnectionObj()->insert_id;
                          
                          
                        
                    add_update_plug_option($key_last_processed, date("Y-m-d h:i:s"));
            }
        
        } catch (FacebookResponseException $e) {
        $postData='error';
        print $e;
        // display error message
        exit();
        } catch (FacebookSDKException $e) {
        $postData='error1';
        // display error message
        exit();
    }
    
    }

start();


