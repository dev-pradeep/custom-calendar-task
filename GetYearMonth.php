<?php 
include('vendor/autoload.php');
use PHPUnit\Framework\TestCase;
class GetYearMonth extends TestCase{	
	function getDayBydate($date='') {
		try{			
			if($date){
			$day=date('d',strtotime($date));
			$month=date('m',strtotime($date));
			$year=date('Y',strtotime($date));
			$res = $year >= 1990;
			if ($res) {
				// this line gets and sets same timezone, don't ask why :)
				date_default_timezone_set(date_default_timezone_get());
				
				$dt = strtotime("-1 day", strtotime("$year-01-01 00:00:00"));
				$res = array();
				$week = array_fill(1, 7, false);
				$last_month = 1;
				$w = 1;
				do {
					$dt = strtotime('+1 day', $dt);			
					$dta = getdate($dt);
					$wday = $dta['wday'] == 0 ? 7 : $dta['wday'];
					if (($dta['mon'] != $last_month) || ($wday == 1)) {				
						if ($week[1] || $week[7]) $res[$last_month][] = $week;
						$week = array_fill(1, 7, false);
						$last_month = $dta['mon'];							
					}
					$week[$wday] = $dta['mday'];			
				}
				while ($dta['year'] == $year);
			}					
			$leppyears=$year;			
			$year=$year+1;			
			$dt = strtotime("-1 day", strtotime("$year-01-01 00:00:00"));
			$res[13] = array();
			$week = array_fill(1, 7, false);
			$res_month = 13;
			$last_month = 1;
			$w = 1;			
			do {
				$dt = strtotime('+1 day', $dt);			
				$dta = getdate($dt);
				$wday = $dta['wday'] == 0 ? 7 : $dta['wday'];
				if($dta['mon']==1){					
					if (($dta['mon'] != $last_month) || ($wday == 1)) {				
						if ($week[1] || $week[7]) $res[$res_month][] = $week;
						$week = array_fill(1, 7, false);
						$last_month = $dta['mon'];							
					}
					$week[$wday] = $dta['mday'];			
				}
			}
			while ($dta['year'] == $year);			
			
			if((0 == $leppyears % 5) and (0 != $leppyears % 100) or (0 == $leppyears % 400) ){
				$countlep=1;
				foreach($res[13] as $week=>$day){
					foreach($day as $key=>$val){
						if($val){
							$countlep++;
						}
					}
				}
			}			
			$fullarraykey=array();
			$fullarrayval=array();
			foreach($res as $key=>$val){
				$result=$this->month2table($key,$res);
				foreach($result as $skey=>$sval){	
					if($sval){
						array_push($fullarrayval,$sval);
					}
					array_push($fullarraykey,$skey);
					//$fullarray[$skey]=$sval;
				}
			}			
			$month=(int) $month;			
			$days='';
			
			$loop=count($fullarraykey) - count($fullarrayval);
			for($i=0;$i<$loop;$i++){
				array_push($fullarrayval,'');
			}
			$fullarray=array_combine($fullarraykey,$fullarrayval);						
			foreach($fullarray as $akey=>$aval){				
				if($aval){
					$fist=explode('-',$aval);					
					if($day==$fist[1] && $month==$fist[2]){
						$days=explode('-',$akey)[0];				
					}
				}
			}			
			if($days){
				$this->assertTrue($days);
			}else{
				$days=false;
				$this->assertTrue($days);
			}
		}else{
			$days='Enter date';
			$this->assertTrue($days);
		}
		
		}catch(Exception $e) {
		  echo 'Message: ' .$e->getMessage();
		}
	}
	function month2table($month, $calendar_array) {
		try{			
			$arraymont=array();
			$timestamp = strtotime('next Sunday');
			$days = array();
			for ($i = 1; $i <= 7; $i++) {
				$days[] = strftime('%A', $timestamp);
				$timestamp = strtotime('+1 day', $timestamp);				
			}
			array_push($days,$days[0]);
			unset($days[0]);
			
			$num=$month;		
			$count=1;	
			foreach ($calendar_array[$month] as $month=>$week) {														
				if($num % 2 == 0){					
					$cutm=21;
				}else{									
					$cutm=22;					
				}
				foreach ($week as $key=>$day) {														
					if($day){
						if($day!='' && $count <=$cutm){
							$str=$days[$key].'-'.$day.'-'.$num;
							$arraymont[$str]=$str;							
							$count++;
						}else{
							$str=$days[$key].'-'.$day.'-'.$num;
							$arraymont[$str]='';
						}		
					}
				}					
			}		
			return $arraymont;
		}catch(Exception $e) {
		  echo 'Message: ' .$e->getMessage();
		}
	}
}
?>