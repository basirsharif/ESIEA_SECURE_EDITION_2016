<!DOCTYPE html>
<html>
<head>
	<title>OutSmarter Filter</title>
    <link href="./index.css" rel="stylesheet">
    <meta charset="utf-8">
</head>
<body>
	<h1>OutSmarter Filter</h1>

	<p>Une compagnie de WAF pense avoir créé un filtre qui empêche toute RCE sur le site prouver leur le contraire.</p>
	<p id='hint'>
		Filtre mis en place :
		<ul>
		<li>Texte de moins de 42 caractères</li>
		<li>Banissement des mots clefs : eval shell exec popen php</li>
		<li>Banissement des opérateurs : ? et ; et $</li>
		</ul>
	</p>

	<form action='' method='POST'>
		<textarea id='code' name='code' maxlength="42"><h2>My Title</h2>Write some code here!</textarea>
		<input type=submit id='btn'>
	</form>
	<footer>Copyright Dummy Inc</footer>

	<?php 
		/*
		Flag:
		-----
		Well done, you found the flag : ese{RCE_4ll_th3_dummy_WAFs}
		*/

		if(isset($_POST['code'])){
			# Filters - (+ no rm, I know you are a good guy ^^)
			$detection = 0;	
			# Filter Size
			if(strlen($_POST['code']) > 42){ $detection = 1;}
			# Filter word
			$blacklist_words    = array('eval','exec','shell','popen','rm');
			for($i=0; $i<sizeof($blacklist_words); $i++){
				if(strpos(strtolower($_POST['code']), $blacklist_words[$i]) > 1){
					$detection = 1;
					break;
				}
			}
			# Filter operator
			$blacklist_operator = array('$','?',';','php');
			for($i=0; $i<sizeof($blacklist_operator); $i++){
				if(strpos($_POST['code'], $blacklist_operator[$i]) > 1){
					$detection = 1;
					break;
				}
			}
			# Detect an hack
			if($detection > 0){
				echo "<p id='error'>Tentative de hack détectée !</p>";
				die();
			}
			else{
				# Random id
				$numbers=range(0,9);        //array of digits
			    $letters=range("A","Z");    //array of uppercase characters
			    $characters=array_merge($numbers,$letters);
			    $characters=array_flip($characters);    //switching key-value of array elements
			    $id='';
			    for($i=0;$i<8;$i++){
			        $id .= array_rand($characters);
			    }
				file_put_contents('tmp/.'.$id.'.tmp', $_POST['code']);


				# Include file
				include('tmp/.'.$id.'.tmp');

				# Delete file
				unlink('tmp/.'.$id.'.tmp');
			}
		}
	?>

</body>
</html>