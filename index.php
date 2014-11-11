<?php
    include "lib/NumberTranslator.php";  
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset = "UTF-8" />
    <title>Test</title>
</head>
<body>
	 <center>
	<?php
	    $translator = NumberTranslator::getNumberTranslator();
	    if(isset($_POST["ru"]))
	    	$translator->setLanguageRU();
	 	if(isset($_POST["ua"]))
	    	$translator->setLanguageUa();
		if(isset($_POST["eng"]))
	    	$translator->setLanguageENG();
	    if(isset($_POST["number"]))
	    	echo $translator->getStringForNumber($_POST["number"]);  
	?>

	<form  method = "post" >
        <div>
            <input type="text" name="number" />
        </div>
		<div>
			<input type = "submit" name="ru" value = "Русский"/>
		</div>
        <div>
        	<input type = "submit" name="ua" value = "Українська"/>
        </div>
        <div>
        	<input type = "submit" name="eng" value = "English"/>
        </div>
	</form>
</center>
</body>
</html>
