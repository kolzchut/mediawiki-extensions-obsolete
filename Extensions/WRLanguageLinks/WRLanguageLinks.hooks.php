<?php

class WRLanguageLinksHooks {
	
	public static function register( &$parser ) {
		$parser->setHook( 'languagelinks', array( 'WRLanguageLinks', 'renderMarker' ) );
		$parser->setFunctionHook( 'haslanguagelinks', array( 'WRLanguageLinks', 'renderHasLinksMarker' ) );
		return true;
	}
	
	/**
	 * @param $parser Parser
	 * @param $alt string
	 * @return String
	 */
	public static function render( &$parser, &$text ) {
		// Create LangBox
		$wrLanguageLinks = new WRLanguageLinks( $parser );
		
		// Return output
		$wrLanguageLinks->render( $text );
		
		return true;
	}
}
