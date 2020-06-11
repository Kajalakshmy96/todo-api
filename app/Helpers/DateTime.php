<?php
class ApplicationDateTime{
	const _FRIENDLY_DISPLAY_DATETIME_FORMAT = 'l, d-m-Y H:i';
	const _DATABSE_PERSIST_DATETIME_FORMAT = 'Y-m-j H:i:s';
    const _ISO_8601_DATETIME_FORMAT = 'Y-m-d\TH:i:sP';

    const _CONST_DEFAULT_TIMEZONE = 'Asia/Colombo';
    const _CONST_SERVER_TIMEZONE = 'Asia/Colombo';
    const _CONST_SERVER_DATEFORMAT = 'Y-m-d H:i:s';

	static function now($str_user_timezone = ApplicationDateTime::_CONST_DEFAULT_TIMEZONE,
		   $str_server_timezone = ApplicationDateTime::_CONST_SERVER_TIMEZONE,
		   $str_server_dateformat = ApplicationDateTime::_CONST_SERVER_DATEFORMAT) {

	  // set timezone to user timezone
	  date_default_timezone_set($str_user_timezone);

	  $date = new DateTime('now');
	  $date->setTimezone(new DateTimeZone($str_server_timezone));
	  $str_server_now = $date->format($str_server_dateformat);

	  // return timezone to server default
	  date_default_timezone_set($str_server_timezone);

	  return $str_server_now;
	}
	static function changeDate($modifier,$str_user_timezone =  ApplicationDateTime::_CONST_DEFAULT_TIMEZONE,
		   $str_server_timezone =  ApplicationDateTime::_CONST_SERVER_TIMEZONE,
		   $str_server_dateformat =  ApplicationDateTime::_CONST_SERVER_DATEFORMAT) {

	  // set timezone to user timezone
	  date_default_timezone_set($str_user_timezone);

	  $date = new DateTime('now');
	  date_modify($date,$modifier);
	  $date->setTimezone(new DateTimeZone($str_server_timezone));
	  $str_server_now = $date->format($str_server_dateformat);

	  // return timezone to server default
	  date_default_timezone_set($str_server_timezone);

	  return $str_server_now;
	}
	static function changeDateForDateTime($str_datetime, $modifier,$str_user_timezone =  ApplicationDateTime::_CONST_DEFAULT_TIMEZONE,
			$str_server_timezone =  ApplicationDateTime::_CONST_SERVER_TIMEZONE,
			$str_server_dateformat =  ApplicationDateTime::_CONST_SERVER_DATEFORMAT) {

		// set timezone to user timezone
		date_default_timezone_set($str_user_timezone);

		$date = new DateTime($str_datetime);
		date_modify($date,$modifier);
		$date->setTimezone(new DateTimeZone($str_server_timezone));
		$str_server_now = $date->format($str_server_dateformat);

		// return timezone to server default
		date_default_timezone_set($str_server_timezone);

		return $str_server_now;
	}
	static function changeTimeStampForDate($str_datetime, $str_original_timezone =  ApplicationDateTime::_CONST_DEFAULT_TIMEZONE,
			$str_change_timezone =  ApplicationDateTime::_CONST_DEFAULT_TIMEZONE,
			$str_server_timezone =  ApplicationDateTime::_CONST_SERVER_TIMEZONE,
			$str_server_dateformat =  ApplicationDateTime::_CONST_SERVER_DATEFORMAT) {

		// set timezone to user timezone
		date_default_timezone_set($str_original_timezone);

		$date = new DateTime($str_datetime);
		$date->setTimezone(new DateTimeZone($str_change_timezone));
		$str_server_now = $date->format($str_server_dateformat);

		// return timezone to server default
		date_default_timezone_set($str_server_timezone);

		return $str_server_now;
	}
	static function isBefore($firstDate,$checkDate){
		if(!($firstDate instanceOf DateTime)) {
			$firstDate = new DateTime($firstDate);
		}
		if(!($checkDate instanceOf DateTime)) {
			$checkDate = new DateTime($checkDate);
		}
		return $firstDate>$checkDate;
	}
	static function isBetween($startDate, $endDate, $checkDate)
	{
		if(!($startDate instanceOf DateTime)) {
			$startDate = new DateTime($startDate);
		}
		if(!($endDate instanceOf DateTime)) {
			$endDate = new DateTime($endDate);
		}
		if(!($checkDate instanceOf DateTime)) {
			$checkDate = new DateTime($checkDate);
		}
		return (($checkDate >= $startDate) && ($checkDate <= $endDate));
	}
	static function changeToDateTime($str_datetime) {
		return new DateTime($str_datetime);
	}

	static function parseDateTime($datetime,$datetime_format= ApplicationDateTime::_DATABSE_PERSIST_DATETIME_FORMAT) {
		if(!($datetime instanceOf DateTime)) {
			$datetime = new DateTime($datetime);
		}
		return $datetime->format($datetime_format);
	}

	static function createUnixTimestampFromDBDateTime($str_datetime, $datetime_format= ApplicationDateTime::_DATABSE_PERSIST_DATETIME_FORMAT) {
	  $dateInfo = date_parse_from_format($datetime_format, $str_datetime);
	  $unixTimestamp = mktime(
				$dateInfo['hour'], $dateInfo['minute'], $dateInfo['second'],
				$dateInfo['month'], $dateInfo['day'], $dateInfo['year'],
				$dateInfo['is_dst']
	  );
	  return $unixTimestamp;
	}

	static function parseUnixTimestampToStr($unixTimestamp,$datetime_format=ApplicationDateTime::_DATABSE_PERSIST_DATETIME_FORMAT) {
		return date($datetime_format,$unixTimestamp);
	}

	static function getFriendlyTimeAgoDisplay($datetime,$nowDateTime) {
		$friendlyDisplay = "";
		if(!($datetime instanceOf DateTime)) {
			$datetime = new DateTime($datetime);
		}
		if(!($nowDateTime instanceOf DateTime)) {
			$nowDateTime = new DateTime($nowDateTime);
		}
		$since_start = $datetime->diff($nowDateTime);
		if($since_start->days>0)  return $since_start->days." days ago";
		if($since_start->h>0) return $since_start->h." hours ago";
		if($since_start->i>0) return $since_start->i." minutes ago";
		if($since_start->s>0) return $since_start->s." seconds ago";
		if($datetime->getTimestamp()==$nowDateTime->getTimestamp()) return " just now";
    }

	static function getTimeAgo($datetime,$nowDateTime) {
		if(!($datetime instanceOf DateTime)) {
			$datetime = new DateTime($datetime);
		}
		if(!($nowDateTime instanceOf DateTime)) {
			$nowDateTime = new DateTime($nowDateTime);
		}
		$since_start = $datetime->diff($nowDateTime);
		return $since_start;
	}

	static function getFriendlyTimeAfterDisplay($datetime,$nowDateTime) {
		$friendlyDisplay = "";
		if(!($datetime instanceOf DateTime)) {
			$datetime = new DateTime($datetime);
		}
		if(!($nowDateTime instanceOf DateTime)) {
			$nowDateTime = new DateTime($nowDateTime);
		}
		$since_start = $nowDateTime->diff($datetime);
		if($since_start->days>0)  return $since_start->days." days";
		if($since_start->h>0) return $since_start->h." hours";
		if($since_start->i>0) return $since_start->i." minutes";
		if($since_start->s>0) return $since_start->s." seconds";
		if($datetime->getTimestamp()==$nowDateTime->getTimestamp()) return " just now";
	}
}

?>
