<?php

if(!empty($_POST) && isset($_POST["asd"])){
	$json_data = $_POST["asd"];
	$post_data = json_decode($json_data,true,512);
		// print_r($array);
	$country = $post_data["country"];
	$array = $post_data["venues"];
		// var_dump($country);
	$url = "http://form.timeform.betfair.com";
	require_once("classes/simple_html_dom.php");
	$data = array();
	foreach ($array as $venues_race) {
			// echo $url.$venues_race."\n";
		$final_url = $url.$venues_race;
		$html = @file_get_html($final_url);
		if($html !== false){

			foreach ($html->find("div.courseschedule-submodule") as $schedule) {
				$temp = array();
				foreach ($schedule->find("div.vevent div.module-header h2 abbr.dtstart") as $timing_details) {
					$temp["timing"] = str_replace(array("\n","\r","  "),"" ,$timing_details->plaintext);
				}
				foreach ($schedule->find("div.vevent div.module-header h2 abbr span.locality") as $location) {
					$temp["location"] = str_replace(array("\n","\r","  "),"" ,$location->plaintext);
				}
				foreach ($schedule->find("div.vevent p.race-description") as $race_description) {
					$temp["description"] = str_replace(array("\n","\r","  "),"" ,$race_description->plaintext);
				}
				foreach ($schedule->find("div.vevent p.race-info") as $race_info) {
					$temp["info"] = str_replace(array("\n","\r","  ","&nbsp;"),"" ,$race_info->plaintext);
				}
				foreach ($schedule->find("div.table-container table thead") as $table_head) {
						//$temp["head"] = str_replace(array("\n","\r","  "),"" ,$table_head->plaintext);
					$temp_arr = array();
					foreach ($table_head->find("tr") as $row) {
						$cell = array();
						foreach($row->find("th") as $dat){
							$cell[] = str_replace(array("\n","\r","  "),"" ,$dat->plaintext);
						}
						if(isset($cell) && !empty($cell))
							$temp_arr[] = $cell;
					}
					$temp["head"] = $temp_arr;
				}
				foreach ($schedule->find("div.table-container table tbody") as $table_body) {
					$temp_arr = array();
					foreach ($table_body->find("tr") as $row) {
						$cell = array();
						foreach($row->find("td") as $dat){
							$cell[] = str_replace(array("\n","\r","  "),"" ,$dat->plaintext);
						}
						if(isset($cell) && !empty($cell))
							$temp_arr[] = $cell;
					}
					$temp["body"] = $temp_arr;
				}

				$data[] = $temp;
			}
		}
		else{
			echo "3";
		}
	}
		//print_r($data);
	$filename = trim(str_replace(array(' '),"",$country)).date("Ymd").".csv";
	$filehandle = fopen($filename, "w");
	if($filehandle !== false) {
		foreach ($data as $line) {
			fputcsv($filehandle,array($line["timing"],$line["location"]));
			fputcsv($filehandle,array($line["description"]));
			fputcsv($filehandle,array($line["info"]));
			if(isset($line["head"]))
				fputcsv($filehandle,$line["head"][0]);
			if(isset($line["body"]))
				foreach ($line["body"] as $scraped_data) {
					fputcsv($filehandle,$scraped_data);
				}
			}	
			fclose($filehandle);
			echo "1";
		}
		else {
			echo "2";
		}
	}
	else if(!empty($_POST) && isset($_POST["download"])){
		$post_data = $_POST["download"];
		$array = explode(":",$post_data);
		$parent_array = array();
		foreach ($array as $country) {
			if($country !== "" && !empty($country)){
				$filename = trim(str_replace(array(' '),"",$country)).date("Ymd").".csv";
				$file = fopen($filename,"r");
				while(! feof($file)){
					$parent_array[] = fgetcsv($file);
				}
				fclose($file);
			}
		}

		//var_dump($parent_array);

		$filename = "download".date("Ymd").".csv";
		$filehandle = fopen($filename, "w");
		if($filehandle !== false) {
			foreach ($parent_array as $line) {
				fputcsv($filehandle,$line);
			}
			fclose($filehandle);
		}


		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($filename));
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($filename));
		ob_clean();
		flush();
		readfile($filename);
				
		//unlink($file_name);
		//echo time()-$t1;
		exit();
	}
?>