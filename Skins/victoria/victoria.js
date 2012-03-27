/* JS specific to Skin:Victoria */

/********************/
/*     Functions    */

function isPrintMode() {
	return mw.util.getParamValue('printable') == 'yes' ? true : false;
}

/* BEGIN content area resizing */
	function contentHeightResize() {	
		if( isPrintMode() ) return;

		// Compatibility map - do not resize in mobile browsers
		var map = {
			'browsers': {
				// Left-to-right languages
				'ltr': {
					'docomo': false,
					'blackberry': false,
					'ipod': false,
					'iphone': false
				},
				// Right-to-left languages
				'rtl': {
					'docomo': false,
					'blackberry': false,
					'ipod': false,
					'iphone': false
				}
			}
		};
		incompatibleOS = [ 'Android', 'webOS' ];
		if ( !$.client.test( map ) || $.inArray( $.client.profile().platform, incompatibleOS ) > -1 ) {
			return true;
		}

		var editorToolbarHeight = $('#editorToolbar').length != 0 ? $('#editorToolbar').outerHeight(true) : 0;
		var scrollPageHeight = $(window).height() - $('#headerWrapper').outerHeight(true) - editorToolbarHeight;
		$('#scrollPage').height( scrollPageHeight ).css( 'overflow-y','scroll' ).css('width', '100%');
	};
/* END content area resizing */

/* BEGIN Font Resizer */
	var fontResizerElement = 'page-wrap';	
	var fontResizerCookieName = 'kz-font-size';

	function fontResize( sizeId ) {
		if( isPrintMode() ) return;
		$('a.font-resizer').removeClass('selected');
		$( '#'+sizeId).addClass('selected');
		switch( sizeId ){
			case 'RegFontBtn':
				$( '#'+fontResizerElement ).css( 'font-size', '100%' );
				break;
			case 'MedFontBtn':
				$( '#'+fontResizerElement ).css( 'font-size', '125%' );
				break;
			case 'BigFontBtn':
				$( '#'+fontResizerElement ).css( 'font-size','150%' );
				break;
		};
	};
	
	function fontResizeBindButtons() {
		if( isPrintMode() ) return;
		$('a.font-resizer').click( function( event ) {
			var sizeId = $(this).attr('id');
			fontResize( sizeId );
			$.cookie( fontResizerCookieName, sizeId, { 'expires': 365, 'path': '/' } );
			contentHeightResize(); /* Adjust content area height, even though it's a slight change */
			event.preventDefault(); //A bit redundant, considering "return false" does this too, but oh well...
			return false;
		});
	};
	
	function fontResizeOnLoad() {
		mw.loader.using( 'jquery.cookie', function() {
			var fontResizerCookie = $.cookie( fontResizerCookieName );
			if ( fontResizerCookie != null ) {
				fontResize( fontResizerCookie );	
			};
		});
	};
/* END Font Resizer */
																					
/* Set page display */
$(document).ready(function() {
	fontResizeOnLoad();
	contentHeightResize();
	fontResizeBindButtons();
	
	$(window).resize( function() {
		contentHeightResize();	/* And make sure it updates when window size changes */
	});
	
	/* Warning of display discrepancies to IE < 8 users */
	if( $.client.profile().name == 'msie' && $.client.profile().version < 8 ) {
	      jsMsg( mw.msg( 'wr-browser-is-ancient' ) );
	};
	
});
