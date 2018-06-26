
<?php
	try{
		include('GetYearMonth.php');
		$obj=new GetYearMonth();						
		//$date='2018-10-21'	;
		$date=(isset($_POST['date']) ? $_POST['date'] : '') ;		
		if($date){
			var_dump($obj->getDayBydate($date));
		}
	}catch(Exception $e) {
	  echo 'Message: ' .$e->getMessage();
	}
?>