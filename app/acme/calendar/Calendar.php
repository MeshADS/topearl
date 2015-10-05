<?php namespace Acme;

/**
* Calendar class
* Creates a calendar in JSON format
*/
class Calendar
{

	private $years = [];
	private $months = [
						"January", "February", "March",
						"April", "May", "June",
						"July", "August", "September",
						"October", "November", "December"
					];


	function __construct()
	{
		# code...
	}

	public function make()
	{
		// Lets get the last year
		$year = date("Y") - 1;
		// Now we will loop through from last year to four years from now
		for ($i=$year; $i < $year+5; $i++) {
			// Populate years object
			$this->years[] = [
				"full" => date("Y", strtotime("Jan ".$i)),
				"short" => date("y", strtotime("Jan ".$i)),
				"isLeap" => (date("L", strtotime("Jan ".$i)) == 1) ? true : false,
				"isCurrent" => (date("Y", strtotime("Jan ".$i)) == date("Y")) ? true : false,
				"months" => $this->makeMonths(date("Y", strtotime("Jan ".$i)))
 			];
		};

		return $this->years;
	}

	protected function makeMonths($year)
	{
		// First create an array to contain the months
		$monthsArr = [];
		// Loop through class months object
		for ($i=0; $i < count($this->months); $i++) { 
			// Add month and data to the months container
			$monthsArr[] = [
						"full" => date("F", strtotime($this->months[$i]." ".$year)),
						"short" => date("M", strtotime($this->months[$i]." ".$year)),
						"num" => date("m", strtotime($this->months[$i]." ".$year)),
						"maxDays" => date("t", strtotime($this->months[$i]." ".$year)),
						"isCurrent" => (date("m", strtotime($this->months[$i]." ".$year)) == date("m")) ? true : false,
						"days" => $this->makeDays($this->months[$i], $year)
					];
		};
		// Return the months array
		return $monthsArr;
	}

	protected function makeDays($month, $year)
	{
		$daysArr = [];
		$lastDay = date("t", strtotime( $month." ".$year));
		for ($i=1; $i <= $lastDay; $i++) { 
			
			$daysArr[] = [
							"full" => date("l", strtotime($i." ".$month." ".$year)),
							"short" => date("D", strtotime($i." ".$month." ".$year)),
							"numWeek" => date("w", strtotime($i." ".$month." ".$year)),
							"numMonth" => date("d", strtotime($i." ".$month." ".$year)),
							"numYear" => date("z", strtotime($i." ".$month." ".$year)) + 1,
							"isCurrent" => (date("d", strtotime($i." ".$month." ".$year)) == date("d")) ? true : false
						];

		}
		// Return the days array
		return $daysArr;
	}
}
	
