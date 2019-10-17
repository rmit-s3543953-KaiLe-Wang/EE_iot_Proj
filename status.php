<?php
session_start();
date_default_timezone_set('Australia/Sydney');
?>
<?php
	  echo "<div class='graph_box'>";
	  echo "<h1>Log status</h1>";
	  // check if there is a request, if so respond with corresponding message.
	  // then, save request to file, by reading the old file, then concatenate new request.
	  $req_dump;
	  if(isset($_REQUEST["Humidity"])){
		  if ($_REQUEST["Humidity"]=="Humidity Warning"){
		  echo "received Humidity status: ";
		  echo $_REQUEST["Humidity"];
		  $fp=fopen("gs://eeet2371-iot-proj.appspot.com/request.log",'r');
		  
		  while(!feof($fp)){
			$req_dump=$req_dump.fgets($fp);
	  }
	  fclose($fp);
		  $req_dump =$req_dump. '['.date("r").']'. print_r($_REQUEST["Humidity"], TRUE)."\n";
			$fp = fopen("gs://eeet2371-iot-proj.appspot.com/request.log", 'w');
			fwrite($fp,$req_dump);
			fclose($fp);
		  }
		  else{
			  echo "invalid request";
		  }
	  }
	  else if(isset($_REQUEST["Temperature"])){
		  if ($_REQUEST["Temperature"]=="Temperature Warning")
		  {
		  echo "received Temperature status: ";
		  echo $_REQUEST["Temperature"];
		  $fp=fopen("gs://eeet2371-iot-proj.appspot.com/request.log",'r');
		  while(!feof($fp)){
			$req_dump=$req_dump.fgets($fp);
	  }
	  fclose($fp);
		  $req_dump =$req_dump.'['.date("r").']'. print_r($_REQUEST["Temperature"], TRUE)."\n";
			$fp = fopen("gs://eeet2371-iot-proj.appspot.com/request.log", 'w');
			fwrite($fp,$req_dump);
			fclose($fp);
		  }
		  else{
			  echo "invalid request";
		  }
	  }
	  else if (sizeOf($_REQUEST)>1&&!isset($_REQUEST["clear"])&&!isset($_REQUEST["action"]))
		  echo "invalid request";
	  else{
	  $fp=fopen("gs://eeet2371-iot-proj.appspot.com/request.log",'r') or die ("no log has been found!");
	  while(!feof($fp)){
	  echo fgets($fp). "<br>";
	  }
	  }
	  echo "</div>";
	  echo "<br>";
	  echo "<form action='index.php' method ='GET' style= 'display: inline-block; margin:auto;'>";
		echo '<input type="hidden" name ="clear"'. "value = 'clear' />";
        echo "<button type = 'submit' value=''>clear log</button></form>";
		
		echo "<form action='index.php' method ='GET' style= 'display: inline-block; margin:auto;'>";
		echo '<input type="hidden" name ="action"'. "value = 'action' />";
        echo "<button type = 'submit' value=''>Take Action!</button></form>";
		if (isset($_GET["clear"])){
			$fp = fopen("gs://eeet2371-iot-proj.appspot.com/request.log", 'w');
			fwrite($fp,"");
			fclose($fp);
			header("Location: index.php");
			exit();
		}
?>