<?php
namespace AppBundle\Lib;

class AdresseDataGouvApi {
	const SEARCH_URI = 'https://api-adresse.data.gouv.fr/search/';
	const REVERSE_URI = 'https://api-adresse.data.gouv.fr/reverse/';
	const SEARCH_PARAM = '?q=_query_';
	
	public static function getSearchUri($q = null) {
		return ($q)? str_replace('_query_', $q, SEARCH_URI.SEARCH_PARAM) : SEARCH_URI.SEARCH_PARAM;
	}
}