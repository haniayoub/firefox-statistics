<?php
function getDay($day)
{
	switch ($day) {
		case 1:
			return "Sun";
		case 2:
			return "Mon";
		case 3:
			return "Tue";
		case 4:
			return "Wed";
		case 5:
			return "Thu";
		case 6:
			return "Fri";
		case 7:
			return "Sat";
	}
}

function getTimeString($seconds)
{
	return (date('H:i:s', $seconds-date('H', 0)*3600)." Hours:Minutes:Seconds");
}
?>