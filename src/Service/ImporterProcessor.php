<?php

namespace App\Service;

class ImporterProcessor
{
	public function process($data) {

		// If data size is less than 100 entries, return false
		if (count($data) < 100) {
			return false;
		}

		$return = array();

		foreach ($data as $d) {

			// If at least 1 entry is not an AU national, return false
			if ($d['nat'] != "AU") {
				return false;
			}

			if (!array_key_exists('login', $d)) {
				return false;
			}

			if (!array_key_exists('name', $d)) {
				return false;
			}

			if (!array_key_exists('location', $d)) {
				return false;
			}

			$login = array(
				'uuid' => $d['login']['uuid'] ? $d['login']['uuid'] : "",
				'username' => $d['login']['username'] ? $d['login']['username'] : "",
				'password' => $d['login']['password'] ? $d['login']['password'] : "",
				'md5' => $d['login']['md5'] ? $d['login']['md5'] : ""
			);

			$tmp = array(
				'first_name' => $d['name']['first'] ? $d['name']['first'] : "",
				'last_name' => $d['name']['last'] ? $d['name']['last'] : "",
				'email' => $d['email'] ? $d['email'] : "",
				'gender' => $d['gender'] ? $d['gender'] : "",
				'country' => $d['location']['country'] ? $d['location']['country'] : "",
				'city' => $d['location']['city'] ? $d['location']['city'] : "",
				'phone' => $d['phone'] ? $d['phone'] : "",
				'mobile' => $d['cell'] ? $d['cell'] : "",
				'login' => $login
			);

			array_push($return, $tmp);
		}

		return $return;
	}
}

?>