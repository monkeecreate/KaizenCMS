<?php
class makeCalendar
{
	private $_cal = Array();
	private $_time;
	private $_events = Array();
	
	function __construct()
	{
		$this->_time = time();
	}

	## Return ##
	public function cal_days()
	{
		return cal_days_in_month(CAL_GREGORIAN, date("n", $this->_time), date("Y", $this->_time));
	}
	############

	## Public Functions ##
	public function change_time($hour, $minute, $sec, $month, $day, $year)
	{
		$this->_time = mktime($hour, $minute, $sec, $month, $day, $year);
	}
	public function add_event($event)
	{
		$this->_events[] = $event;
	}
	public function build()
	{
		$days_in_month = cal_days_in_month(CAL_GREGORIAN, date("n", $this->_time), date("Y", $this->_time));

		## Empty Start ##
		$start = date("w", mktime(0, 0, 0, date("n", $this->_time), 1, date("Y", $this->_time)));
		$empty_start = 7 - abs($start - 7);
		#################

		## Empty End ##
		$end = date("w", mktime(0, 0, 0, date("n", $this->_time), $days_in_month, date("Y", $this->_time)));
		$empty_end = (7 - $end)-1;
		###############

		## First Array Issued ##
		$empty = 1;
		for($i=0;$i<$empty_start;$i++)
		{
			$this->_cal["empty_start_".$empty] = Array();
			$empty++;
		}

		for($i=1;$i<=$days_in_month;$i++)
		{
			$aDay = Array();

			$aDay["events"] = Array();

			if(date("m/d/y") == date("m/d/y", mktime(0, 0, 0, date("n", $this->_time), $i, date("Y", $this->_time))))
				$aDay["today"] = 1;
			else
				$aDay["today"] = 0;

			$this->_cal[$i] = $aDay;
		}

		$empty = 1;
		for($i=0;$i<$empty_end;$i++)
		{
			$this->_cal["empty_end_".$empty] = Array();
			$empty++;
		}
		########################
		
		## Add Events To Day ##
		foreach($this->_events as $event)
		{
			//Check for actualy in this month
			if(date("n/Y", $this->_time) == date("n/Y", $event["datetime"]))
			{
				$day = date("j", $event["datetime"]);
				$this->_cal[$day]["events"][] = $event;
			}
		}
		#######################
		$cDay = 1;
		$week = 1;
		$aCal = $this->_cal;
		$this->_cal = Array();
		foreach($aCal as $key => $day)
		{
			if($cDay == 8)
			{
				$cDay = 1;
				$week++;
			}
			$this->_cal["cal"][$week][$key] = $day;
			$cDay++;
		}
		
		$this->_cal["curtime"] = $this->_time;
		
		return $this->_cal;
	}
	######################
}