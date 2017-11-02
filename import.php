<?php
/*
Created by     - Nitin Omle ( nitinomle@gmail.com)
Last updated   - 30/10/2017
Short description for script
- This script  fetch symfony repositories list into mysql db

----Steps to excecute this script--------------------
Step1 - Create Database 
CREATE DATABASE gitrepos;

Step2 - Create Table 
CREATE TABLE repos
(
  repo_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100)
);

Step 3 - run script



*/

echo "Script Started at ".date("Y-m-d h:i:sa")."\n";
require_once 'vendor/autoload.php';
$client = new \Github\Client();

// mysql connection

$dbObj = mysql_connect('localhost','root','');

$dbConnectionObj = mysql_select_db('gitrepo',$dbObj);


//fetch symfony repositories

echo "Fetching Repos .....\n";
$repos = $client->api('repo')->find('symfony');

echo "Inserting into mysql ....\n";

//print_r($repos);
//die;

$done=0;
$total = count($repos['repositories']);
foreach($repos['repositories'] as $key=>$reposData)
{

  echo $reposData['name']; echo "\n";
  
  $InsertStatement = 'Insert Into repos (name)values("'.$reposData['name'].'")';
  mysql_query($InsertStatement);
  $done++;
  show_status($done,$total,10);
  
}

echo "Script Endted  at ".date("Y-m-d h:i:sa")."\n";






function show_status($done, $total, $size=30) {

    static $start_time;

    // if we go over our bound, just ignore it
    if($done > $total) return;

    if(empty($start_time)) $start_time=time();
    $now = time();

    $perc=(double)($done/$total);

    $bar=floor($perc*$size);

    $status_bar="\r[";
    $status_bar.=str_repeat("=", $bar);
    if($bar<$size){
        $status_bar.=">";
        $status_bar.=str_repeat(" ", $size-$bar);
    } else {
        $status_bar.="=";
    }

    $disp=number_format($perc*100, 0);

    $status_bar.="] $disp%  $done/$total";

    $rate = ($now-$start_time)/$done;
    $left = $total - $done;
    $eta = round($rate * $left, 2);

    $elapsed = $now - $start_time;

    $status_bar.= " remaining: ".number_format($eta)." sec.  elapsed: ".number_format($elapsed)." sec.";

    echo "$status_bar  ";

    flush();

    // when done, send a newline
    if($done == $total) {
        echo "\n";
    }

}




?>
