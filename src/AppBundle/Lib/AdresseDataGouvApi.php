<?php
namespace AppBundle\Lib;

class AdresseDataGouvApi {
	const SEARCH_URI = 'https://api-adresse.data.gouv.fr/search/';
	const REVERSE_URI = 'https://api-adresse.data.gouv.fr/reverse/';
	const SEARCH_PARAM = '?q=_query_';
	const REVERSE_PARAM = '?lon=_lon_&lat=_lat_';
	
	public static function getSearchUri($q = null) {
		return ($q)? str_replace('_query_', $q, SEARCH_URI.SEARCH_PARAM) : SEARCH_URI.SEARCH_PARAM;
	}
	
	public function getAddrByCoordinates($coordinates) {
		$coordinates = explode(',', $coordinates);
		if (count($coordinates) != 2) {
			return null;
		}
		$url = str_replace('_lon_', $coordinates[0], str_replace('_lat_', $coordinates[1], self::REVERSE_URI.self::REVERSE_PARAM));
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_COOKIESESSION, true);
		$result = json_decode(curl_exec($curl), true);
		curl_close($curl);
		
		if (count($result['features']) < 1) {
			return null;
		}
		
		return $result['features'][0]['properties']['label'];
	}
}