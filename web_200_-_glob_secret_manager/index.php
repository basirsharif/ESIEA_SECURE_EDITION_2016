<!DOCTYPE html>
<html>
<head>
	<title>Glob-Secrets-Manager</title>
    <link href="./index.css" rel="stylesheet">
</head>
<body>
	<h1>Glob Secrets Manager</h1>
	<span id='lock'>🔒</span>
	<a href='?url=secret'>ENTER</a>	
	<br>
	
	<?php
		# Attack Glob
		$flag = ".ese{f1lter_4ll_th3_th1ngs}";
		if(!file_exists($flag)){
			file_put_contents($flag, "EASTER EGG 1 - Happy Easter");
			chmod($flag, 0777);
		}

		if(isset($_GET['url'])){
			$url = $_GET['url'];
			$it = new DirectoryIterator($url);

			// Little filter
			if($url[0] != '/' and $url[0] != '.' and strpos($url,"..")==0 and strpos($url,'secret/../')==0 and $url!='secret/../'){
				// You have to use secret
				if(strpos($url,"secret") === false){
					echo "<p>Sorry, you must stay in the secret directory <!-- or not ?--></p>";	
				}
				else{
					echo "<pre>Your files are : <br>";
					foreach($it as $f) {
						//Do not display hidden file
						echo $it."<br>";
					}
					echo "</pre>";
				}
			}
			else{
				echo "Attack detected";
			}
		}
	?>
</body>
</html>