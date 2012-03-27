<?php
/**
* Classes for WRLanguageLinks extension
*
* @file
* @ingroup Extensions
*/

// WRLanguageLinks class
class WRLanguageLinks {
	/* Fields */
	
	private $mParser;
	const markerText = 'x--WRLanguageLinks-marker--x';
	const markerHasLinksText = 'x--WRLanguageLinks-has-links-marker--x';
	
	/* Functions */ 
	
	public function __construct( $parser ) {
		$this->mParser = $parser;
	}
	
	public function renderMarker() {
		return self::markerText;
	}
	
	public function renderHasLinksMarker() {
		return self::markerHasLinksText;
	}
	
	public function render( &$text ) {
		// find markers in $text and replace with actual output
		$text = str_replace( self::markerText, $this->getLanguageLinks(), $text ); // Find markers for language links
		$text = str_replace( self::markerHasLinksText, $this->hasLanguageLinks(), $text);	  // Find markers for hasLanguageLinks	
		return true;
	}
	
	private function hasLanguageLinks() {
		global $wgWRLanguageLinksShowOnly;
		$parserLanguageLinks = $this->mParser->getOutput()->getLanguageLinks();
		$showOnly = explode( ',', $wgWRLanguageLinksShowOnly );
		if( $parserLanguageLinks == null ) {
			return "false"; // no language links at all, valid or otherwise
		} elseif( count( $showOnly ) == 0 ) {
			return "true"; // there are some language links, and all links are considered valid
		} else {
			foreach( $parserLanguageLinks as $l ) {
				$tmp = explode( ':', $l, 2 );
				if( in_array( $tmp[0], $showOnly ) ) return "true";	// there is at least one valid language link
			}
		}
		return "false"; // if we got this far, there are no valid language links
	}
	
	private function getLanguageLinks() {
		global $wgContLang, $wgWRLanguageLinksShowOnly, $wgWRLanguageLinksShowTitles, $wgWRLanguageLinksListType;
		
		$listClass = "wr-languagelinks-list-$wgWRLanguageLinksListType";
		
		$output = null;
		# Language links - ripped from SkinTemplate.php and then mangled badly
		$parserLanguageLinks = $this->mParser->getOutput()->getLanguageLinks();
		$language_urls = array();
		
		if( $wgWRLanguageLinksShowOnly != null ) {
			$showOnly = explode( ',', $wgWRLanguageLinksShowOnly );
		}
		foreach( $parserLanguageLinks as $l ) {
			$tmp = explode( ':', $l, 2 );
			if( count( $showOnly ) == 0 || in_array( $tmp[0], $showOnly ) ) {
				$class = 'wr-languagelinks-' . $tmp[0];
				unset( $tmp );
				$nt = Title::newFromText( $l );
				if ( $nt ) {
					$pagename = $nt->getText();
					$langname = ( $wgContLang->getLanguageName( $nt->getInterwiki() ) != '' ? 
									$wgContLang->getLanguageName( $nt->getInterwiki() ) : $l );
					$language_urls[] = array(
						'href' => $nt->getFullURL(),
						'title' => $wgWRLanguageLinksShowTitles ? $langname : $pagename,
						'text' => $wgWRLanguageLinksShowTitles ? $pagename: $langname,
						'class' => $class,
						'iw'	=> $nt->getInterwiki(),
					);
				}
			}
		}

		if( count( $language_urls ) ) {
			$output = '<div class="wr-languagelinks ' . $listClass . '">' . '<div class="wr-languagelinks-title">';
			
			/* not implemented until upgrading to MW1.18 which includes an option to get translated language names
			if( $wgWRLanguageLinksShowTitles && count( $language_urls == 1 ) ) {
				if( $language_urls[0]['iw'] == 'he' ) {
					$output .= wfMessage( 'wr-article-in-hebrew' )->text();
				} else {
					$output .= wfMessage( 'wr-in-single-language', $wgContLang->getLanguageName( $language_urls[0]['iw'] )  )->text();
				}
			in the meantime:*/
			if( $wgWRLanguageLinksShowTitles && count( $language_urls == 1 ) && $language_urls[0]['iw'] == 'he' ) {
				$output .= wfMessage( 'wr-article-in-hebrew' )->text();
			} else {
				$output .= wfMessage( 'wr-otherlanguages' )->text();
			}
			$output .= ':</div>' . '<ul class="wr-languagelinks-list' . ( count( $language_urls ) == 1 ? ' no-bullets' : '' ) . '">';
			foreach ( $language_urls as $langlink ) {
				$output .= '<li class="'. htmlspecialchars(  $langlink['class'] ) . '"><a href="' . htmlspecialchars( $langlink['href'] ) . '" title="' . htmlspecialchars( $langlink['title'] ) . '">' . $langlink['text'] . '</a></li>';
			}
			$output .= '</ul></div>';
			$this->mParser->getOutput()->addModules( 'ext.WRLanguageLinks' );
		}
		
		return $output;
	}

}

		
