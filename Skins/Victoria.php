<?php
/**
 * victoria - fork of Vector for Kol-Zchut (codename WikiRights)
 *
 * @todo document
 * @file
 * @ingroup Skins
 */

 /**
  * Being a fork of Vector, Victoria is available under the same license, GNU General Public License, version 2 or later 
  * (see http://www.fsf.org/licensing/licenses/gpl.html). Images specific to this skin (under the newimages directory) 
  * are licensed CC-BY-SA, version 2.5 or later, ported or not, with attribution to "Kol-Zchut Ltd. (www.kolzchut.org.il)". 
  * We'd very much appreciate an attribution for the skin itself, but that's up to you :-)
  */

if( !defined( 'MEDIAWIKI' ) ) {
	die( -1 );
}


/* WR: Do not show credit list for this skin */
global $wgMaxCredits;
$wgMaxCredits = 0;

/**
 * SkinTemplate class for victoria skin
 * @ingroup Skins
 */
class SkinVictoria extends SkinTemplate {

	/* Functions */
	var $skinname = 'victoria', $stylename = 'victoria',
		$template = 'VictoriaTemplate', $useHeadElement = true;

	/**
	 * Initializes output page and sets up skin-specific parameters
	 * @param $out OutputPage object to initialize
	 */
	public function initPage( OutputPage $out ) {
		global $wgLocalStylePath, $wgRequest;
		
		parent::initPage( $out );

		// Append CSS which includes IE only behavior fixes for hover support -
		// this is better than including this in a CSS file since it doesn't
		// wait for the CSS file to load before fetching the HTC file.
		$min = $wgRequest->getFuzzyBool( 'debug' ) ? '' : '.min';
		$out->addHeadItem( 'csshover',
			'<!--[if lt IE 7]><style type="text/css">body{behavior:url("' .
				htmlspecialchars( $wgLocalStylePath ) .
				"/{$this->stylename}/csshover{$min}.htc\")}</style><![endif]-->"
		);
		//$out->addModuleScripts( 'skins.victoria' );
		$out->addModules( 'skins.victoria' );
		
	}
	
	/**
	 * Load skin and user CSS files in the correct order
	 * fixes bug 22916
	 * @param $out OutputPage object
	 */
	function setupSkinUserCss( OutputPage $out ){
		parent::setupSkinUserCss( $out );
		
		/*
		global $wgResourceModules;
		$wgResourceModules += array(
			'skins.victoria' => array(
				'styles' => array( 'victoria/screen.css'),
				'remoteBasePath' => $GLOBALS['wgStylePath'],
				'localBasePath' => $GLOBALS['wgStyleDirectory']
			),
		);*/
		$out->addModuleStyles( 'skins.victoria' );
	}
	
	/**
	 * Builds a structured array of links used for tabs and menus
	 * @return array
	 * @private
	 */
	function buildNavigationUrls() {
		global $wgContLang, $wgLang, $wgOut, $wgUser, $wgRequest, $wgArticle;
		global $wgDisableLangConversion, $wgVectorUseIconWatch;

		wfProfileIn( __METHOD__ );

		$links = array(
			'namespaces' => array(),
			'views' => array(),
			'actions' => array(),
			'variants' => array()
		);

		// Detects parameters
		$action = $wgRequest->getVal( 'action', 'view' );
		$section = $wgRequest->getVal( 'section' );

		$userCanRead = $this->mTitle->userCanRead();

		// Checks if page is some kind of content
		if( $this->iscontent ) {
			// Gets page objects for the related namespaces
			$subjectPage = $this->mTitle->getSubjectPage();
			$talkPage = $this->mTitle->getTalkPage();

			// Determines if this is a talk page
			$isTalk = $this->mTitle->isTalkPage();

			// Generates XML IDs from namespace names
			$subjectId = $this->mTitle->getNamespaceKey( '' );

			if ( $subjectId == 'main' ) {
				$talkId = 'talk';
			} else {
				$talkId = "{$subjectId}_talk";
			}

			// Adds namespace links
			$links['namespaces'][$subjectId] = $this->tabAction(
				$subjectPage, 'nstab-' . $subjectId, !$isTalk, '', $userCanRead
			);
			$links['namespaces'][$subjectId]['context'] = 'subject';
			$links['namespaces'][$talkId] = $this->tabAction(
				$talkPage, 'talk', $isTalk, '', $userCanRead
			);
			$links['namespaces'][$talkId]['context'] = 'talk';

			// Adds view view link
			if ( $this->mTitle->exists() && $userCanRead ) {
				$links['views']['view'] = $this->tabAction(
					$isTalk ? $talkPage : $subjectPage,
						'vector-view-view', ( $action == 'view' || $action == 'purge' ), '', true
				);
			}

			wfProfileIn( __METHOD__ . '-edit' );

			// Checks if user can...
			if (
				// read and edit the current page
				$userCanRead && $this->mTitle->quickUserCan( 'edit' ) &&
				(
					// if it exists
					$this->mTitle->exists() ||
					// or they can create one here
					$this->mTitle->quickUserCan( 'create' )
				)
			) {
				// Builds CSS class for talk page links
				$isTalkClass = $isTalk ? ' istalk' : '';

				// Determines if we're in edit mode
				$selected = (
					( $action == 'edit' || $action == 'submit' ) &&
					( $section != 'new' )
				);
				$links['views']['edit'] = array(
					'class' => ( $selected ? 'selected' : '' ) . $isTalkClass,
					'text' => $this->mTitle->exists()
						? wfMsg( 'vector-view-edit' )
						: wfMsg( 'vector-view-create' ),
					'href' =>
						$this->mTitle->getLocalURL( $this->editUrlOptions() )
				);
				// Checks if this is a current rev of talk page and we should show a new
				// section link
				if ( ( $isTalk && $wgArticle && $wgArticle->isCurrent() ) || ( $wgOut->showNewSectionLink() ) ) {
					// Checks if we should ever show a new section link
					if ( !$wgOut->forceHideNewSectionLink() ) {
						// Adds new section link
						//$links['actions']['addsection']
						$links['views']['addsection'] = array(
							'class' => 'collapsible ' . ( $section == 'new' ? 'selected' : false ),
							'text' => wfMsg( 'vector-action-addsection' ),
							'href' => $this->mTitle->getLocalURL(
								'action=edit&section=new'
							)
						);
					}
				}
			// Checks if the page has some kind of viewable content
			} elseif ( $this->mTitle->hasSourceText() && $userCanRead ) {
				// Adds view source view link
				$links['views']['viewsource'] = array(
					'class' => ( $action == 'edit' ) ? 'selected' : false,
					'text' => wfMsg( 'vector-view-viewsource' ),
					'href' => '',	//[WR:old:]$this->mTitle->getLocalURL( $this->editUrlOptions() )
				);
			}
			wfProfileOut( __METHOD__ . '-edit' );

			wfProfileIn( __METHOD__ . '-live' );

			// Checks if the page exists
			if ( $this->mTitle->exists() && $userCanRead ) {
				// Adds history view link
				$links['views']['history'] = array(
					'class' => 'collapsible ' . ( ( $action == 'history' ) ? 'selected' : false ),
					'text' => wfMsg( 'vector-view-history' ),
					'href' => $this->mTitle->getLocalURL( 'action=history' ),
					'rel' => 'archives',
				);

				if( $wgUser->isAllowed( 'delete' ) ) {
					$links['actions']['delete'] = array(
						'class' => ( $action == 'delete' ) ? 'selected' : false,
						'text' => wfMsg( 'vector-action-delete' ),
						'href' => $this->mTitle->getLocalURL( 'action=delete' )
					);
				}
				if ( $this->mTitle->quickUserCan( 'move' ) ) {
					$moveTitle = SpecialPage::getTitleFor(
						'Movepage', $this->thispage
					);
					$links['actions']['move'] = array(
						'class' => $this->mTitle->isSpecial( 'Movepage' ) ?
										'selected' : false,
						'text' => wfMsg( 'vector-action-move' ),
						'href' => $moveTitle->getLocalURL()
					);
				}

				if (
					$this->mTitle->getNamespace() !== NS_MEDIAWIKI &&
					$wgUser->isAllowed( 'protect' )
				) {
					if ( !$this->mTitle->isProtected() ) {
						$links['actions']['protect'] = array(
							'class' => ( $action == 'protect' ) ?
											'selected' : false,
							'text' => wfMsg( 'vector-action-protect' ),
							'href' =>
								$this->mTitle->getLocalURL( 'action=protect' )
						);

					} else {
						$links['actions']['unprotect'] = array(
							'class' => ( $action == 'unprotect' ) ?
											'selected' : false,
							'text' => wfMsg( 'vector-action-unprotect' ),
							'href' =>
								$this->mTitle->getLocalURL( 'action=unprotect' )
						);
					}
				}
			} else {
				// article doesn't exist or is deleted
				if (
					$wgUser->isAllowed( 'deletedhistory' ) &&
					$wgUser->isAllowed( 'undelete' )
				) {
					$n = $this->mTitle->isDeleted();
					if( $n ) {
						$undelTitle = SpecialPage::getTitleFor( 'Undelete' );
						$links['actions']['undelete'] = array(
							'class' => false,
							'text' => wfMsgExt(
								'vector-action-undelete',
								array( 'parsemag' ),
								$wgLang->formatNum( $n )
							),
							'href' => $undelTitle->getLocalURL(
								'target=' . urlencode( $this->thispage )
							)
						);
					}
				}

				if (
					$this->mTitle->getNamespace() !== NS_MEDIAWIKI &&
					$wgUser->isAllowed( 'protect' )
				) {
					if ( !$this->mTitle->getRestrictions( 'create' ) ) {
						$links['actions']['protect'] = array(
							'class' => ( $action == 'protect' ) ?
											'selected' : false,
							'text' => wfMsg( 'vector-action-protect' ),
							'href' =>
								$this->mTitle->getLocalURL( 'action=protect' )
						);

					} else {
						$links['actions']['unprotect'] = array(
							'class' => ( $action == 'unprotect' ) ?
											'selected' : false,
							'text' => wfMsg( 'vector-action-unprotect' ),
							'href' =>
								$this->mTitle->getLocalURL( 'action=unprotect' )
						);
					}
				}
			}
			wfProfileOut( __METHOD__ . '-live' );
			/**
			 * The following actions use messages which, if made particular to
			 * the Vector skin, would break the Ajax code which makes this
			 * action happen entirely inline. Skin::makeGlobalVariablesScript
			 * defines a set of messages in a javascript object - and these
			 * messages are assumed to be global for all skins. Without making
			 * a change to that procedure these messages will have to remain as
			 * the global versions.
			 */
			 
			$wgVectorUseIconWatch = true;  /*WR hack: instead of putting this in settings*/
			// Checks if the user is logged in
			if ( $this->loggedin ) {
				if ( $wgVectorUseIconWatch ) {
					$class = 'icon';
					$place = 'views';
				} else {
					$class = '';
					/*WR: ugly hack*/ /*$place = 'actions';*/
					$place = 'views';
				}
				$mode = $this->mTitle->userIsWatching() ? 'unwatch' : 'watch';
				$links[$place][$mode] = array(
					'class' => $class . ( ( $action == 'watch' || $action == 'unwatch' ) ? ' selected' : false ),
					'text' => wfMsg( $mode ), // uses 'watch' or 'unwatch' message
					'href' => $this->mTitle->getLocalURL( 'action=' . $mode )
				);
			}
			// This is instead of SkinTemplateTabs - which uses a flat array
			wfRunHooks( 'SkinTemplateNavigation', array( &$this, &$links ) );

		// If it's not content, it's got to be a special page
		} else {
			$links['namespaces']['special'] = array(
				'class' => 'selected',
				'text' => wfMsg( 'nstab-special' ),
				'href' => $wgRequest->getRequestURL()
			);
			// Equiv to SkinTemplateBuildContentActionUrlsAfterSpecialPage
			wfRunHooks( 'SkinTemplateNavigation::SpecialPage', array( &$this, &$links ) );
		}

		// Gets list of language variants
		$variants = $wgContLang->getVariants();
		// Checks that language conversion is enabled and variants exist
		if( !$wgDisableLangConversion && count( $variants ) > 1 ) {
			// Gets preferred variant
			$preferred = $wgContLang->getPreferredVariant();
			// Loops over each variant
			foreach( $variants as $code ) {
				// Gets variant name from language code
				$varname = $wgContLang->getVariantname( $code );
				// Checks if the variant is marked as disabled
				if( $varname == 'disable' ) {
					// Skips this variant
					continue;
				}
				// Appends variant link
				$links['variants'][] = array(
					'class' => ( $code == $preferred ) ? 'selected' : false,
					'text' => $varname,
					'href' => $this->mTitle->getLocalURL( '', $code )
				);
			}
		}

		// Equiv to SkinTemplateContentActions
		wfRunHooks( 'SkinTemplateNavigation::Universal', array( &$this,  &$links ) );

		wfProfileOut( __METHOD__ );

		return $links;
	}	

	/* WR: add extra footer link for policy */
	function policyLink() {
		return parent::footerLink( 'policy', 'policypage' );
	}
	
	/* WR: add extra footer link for contact page */
	function contactLink() {
		return parent::footerLink( 'contact', 'contactpage' );
	}

}

/**
 * QuickTemplate class for Victoria skin
 * @ingroup Skins
 */
class VictoriaTemplate extends QuickTemplate {

	/* Members */

	/**
	 * @var Cached skin object
	 */
	var $skin;

	/* Functions */

	/**
	 * Outputs the entire contents of the XHTML page
	 */
	public function execute() {
		global $wgRequest, $wgLang;
		global $wgArticle; /* WR: required for last-update. DS 16-04-2010 */
		global $wgOut, $wgUser; /* WR: required to check if logged-in for user guide link */
		
		$this->skin = $this->data['skin'];
		$action = $wgRequest->getText( 'action' );
	
		/* WR: add history link to credits msg */
		if ( $this->skin->iscontent && $wgArticle ) {
			$this->data['lastmod'] .=
				'&nbsp;<a title="' . wfMsg( 'tooltip-ca-history' ) . '"' . 
				'alt="' . wfMsg( 'vector-view-history' ) . '"' .
				'href="' . $this->skin->mTitle->getLocalUrl( 'action=history' )  . '">' .
				wfMsg('wr-history') . '</a>';
		}

		// Build additional attributes for navigation urls
		$nav = $this->skin->buildNavigationUrls();
		foreach ( $nav as $section => $links ) {
			foreach ( $links as $key => $link ) {
				$xmlID = $key;
				if ( isset( $link['context'] ) && $link['context'] == 'subject' ) {
					$xmlID = 'ca-nstab-' . $xmlID;
				} else if ( isset( $link['context'] ) && $link['context'] == 'talk' ) {
					$xmlID = 'ca-talk';
				} else {
					$xmlID = 'ca-' . $xmlID;
				}
				$nav[$section][$key]['attributes'] =
					' id="' . Sanitizer::escapeId( $xmlID ) . '"';
				if ( $nav[$section][$key]['class'] ) {
					$nav[$section][$key]['attributes'] .=
						' class="' . htmlspecialchars( $link['class'] ) . '"';
					unset( $nav[$section][$key]['class'] );
				}
				// We don't want to give the watch tab an accesskey if the page
				// is being edited, because that conflicts with the accesskey on
				// the watch checkbox.  We also don't want to give the edit tab
				// an accesskey, because that's fairly superfluous and conflicts
				// with an accesskey (Ctrl-E) often used for editing in Safari.
				if (
					in_array( $action, array( 'edit', 'submit' ) ) &&
					in_array( $key, array( 'edit', 'watch', 'unwatch' ) )
				) {
					$nav[$section][$key]['key'] =
						$this->skin->tooltip( $xmlID );
				} else {
					$nav[$section][$key]['key'] =
						$this->skin->tooltipAndAccesskey( $xmlID );
				}
			}
		}
		$this->data['namespace_urls'] = $nav['namespaces'];
		$this->data['view_urls'] = $nav['views'];
		$this->data['action_urls'] = $nav['actions'];
		$this->data['variant_urls'] = $nav['variants'];
		// Build additional attributes for personal_urls
		foreach ( $this->data['personal_urls'] as $key => $item) {
			$this->data['personal_urls'][$key]['attributes'] =
				' id="' . Sanitizer::escapeId( "pt-$key" ) . '"';
			if ( isset( $item['active'] ) && $item['active'] ) {
				$this->data['personal_urls'][$key]['attributes'] .=
					' class="active"';
			}
			$this->data['personal_urls'][$key]['key'] =
				$this->skin->tooltipAndAccesskey('pt-'.$key);
		}
		
		$title = $wgOut->getTitle();
		$pageurl = $title->getLocalURL();
		$helppageUrlDetails = $this->skin->makeUrlDetails ( wfMsgForContent( 
			( $this->skin->loggedin && $wgUser->isAllowed('edit') )  ? 'wr-editor-helppage' : 'wr-helppage' ) );
		$helppageLink = array(
			'text' => wfMsg( 'wr-help' ),
			'href' => $helppageUrlDetails['href'],
			'class' => $helppageUrlDetails['exists'] ? false : 'new',
			'active' => ( $helppageUrlDetails['href'] == $pageurl )
		);
		
		$this->data['personal_urls'] = array_merge( $this->data['personal_urls'], array('help'=>$helppageLink) ); 				
		 
		// Generate additional footer links
		$footerlinks = $this->data["footerlinks"];
		// WR: reveresed order of info, places. Also moved about & contact to be 1st in "places"
		$footerlinks['places'] = array(
				'about',
				'contact',
				'policy', /*WR: add an extra footer link: policy. Also see the actual link generation below*/
				'privacy',
				'disclaimer',
			);
		$footerlinks['info'] = array(
				'lastmod',
				//'viewcount',
				//'numberofwatchingusers',
				'credits',
				'copyright',
				'tagline',
			);
		
		/* /WR footerlinks override */
		/*WR: add an extra footer links: policy, contact */
		$this->set( 'policy', $this->skin->policyLink() );	
		$this->set( 'contact', $this->skin->contactLink() );			
		
		// Reduce footer links down to only those which are being used
		$validFooterLinks = array();
		foreach( $footerlinks as $category => $links ) {
			$validFooterLinks[$category] = array();
			foreach( $links as $link ) {
				if( isset( $this->data[$link] ) && $this->data[$link] ) {
					$validFooterLinks[$category][] = $link;
				}
			}
		}
				
		// Generate additional footer icons
		$footericons = $this->data["footericons"];
		// Unset any icons which don't have an image
		foreach ( $footericons as $footerIconsKey => &$footerIconsBlock ) {
			foreach ( $footerIconsBlock as $footerIconKey => $footerIcon ) {
				if ( !is_string($footerIcon) && !isset($footerIcon["src"]) ) {
					unset($footerIconsBlock[$footerIconKey]);
				}
			}
		}
		// Redo removal of any empty blocks
		foreach ( $footericons as $footerIconsKey => &$footerIconsBlock ) {
			if ( count($footerIconsBlock) <= 0 ) {
				unset($footericons[$footerIconsKey]);
			}
		}		
		
		// Output HTML Page
		$this->html( 'headelement' );
?>

<div id="page-wrap">

	<div id="headerWrapper">
	<div id="header">
		<div id="banner" class="noprint">
			<div class="pretty-banner-bg"></div>
			<div id="logoWrapper">
				<div id="logo">
					<?php 
						global $wgLogo, $wgStylePath, $wgLanguageCode;
						$wgLogo = "$wgStylePath/victoria/newimages/logo.png";
					?>

					<a class="logo1" style="background-image: url(<?php $this->text( 'logopath' ) ?>);" href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>" <?php echo $this->skin->tooltipAndAccesskey( 'p-logo' ) ?>></a>
					<div class="visualClear"></div>
				</div>
				<div id="slogan">			
				<?php 
					$sloganpage = Title::newFromText( wfMsg( 'aboutpage' ) );
					$sloganlink = $sloganpage->getLinkUrl();
				?>
					<a href="<?php echo htmlspecialchars( $sloganlink ) ?>" target="_blank" title="<?php echo wfMsgHtml( 'aboutsite' ) ?>">
						<span class="slogan-text"><?php echo wfMsg( 'wr-slogan' ) ?></span> 
						<span id="slogan-about">(<span id="slogan-about-inner"><?php echo wfMsg( 'wr-slogan-about' ) ?></span>)</span>
					</a>
				</div>
			</div>
			<div id="top-menu" class="newinlinelist">
				<ul>
					<?php
					if( $this->skin->loggedin ) {
						$item = $this->data['personal_urls']['userpage']; ?>
						<li class="first-child" <?php echo $item['attributes'] ?>><a href="<?php echo htmlspecialchars($item['href']) ?>"<?php echo $item['key'] ?><?php if(!empty($item['class'])): ?> class="<?php echo htmlspecialchars($item['class']) ?>"<?php endif; ?>><?php echo htmlspecialchars($item['text']) ?></a></li>
						<?php $item = $this->data['personal_urls']['watchlist']; ?>
						<li <?php echo $item['attributes'] ?>><a href="<?php echo htmlspecialchars($item['href']) ?>"<?php echo $item['key'] ?><?php if(!empty($item['class'])): ?> class="<?php echo htmlspecialchars($item['class']) ?>"<?php endif; ?>><?php echo htmlspecialchars($item['text']) ?></a></li>
						<?php $item = $this->data['personal_urls']['logout']; ?>
						<li <?php echo $item['attributes'] ?>><a href="<?php echo htmlspecialchars($item['href']) ?>"<?php echo $item['key'] ?><?php if(!empty($item['class'])): ?> class="<?php echo htmlspecialchars($item['class']) ?>"<?php endif; ?>><?php echo htmlspecialchars($item['text']) ?></a></li>
					<?php 
					} else {
						$item = $this->data['personal_urls']['login']; ?>
						<li  class="first-child" <?php echo $item['attributes'] ?>><a href="<?php echo htmlspecialchars($item['href']) ?>"<?php echo $item['key'] ?><?php if(!empty($item['class'])): ?> class="<?php echo htmlspecialchars($item['class']) ?>"<?php endif; ?>><?php echo htmlspecialchars($item['text']) ?></a></li>							
					<?php 	
					}			
					?>
				</ul>
			</div>
			<!-- /about -->
			<!-- controls -->
			<div id="top-controls" class="newinlinelist">
				<ul>
					<li class="searchForm first-child">
						<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
							<input type="hidden" name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>
							<div id="simpleSearch">
								<?php if ( $this->data['rtl'] ): ?>
								<button id="searchButton" type='submit' name='button' class='ui-state-default ui-corner-all' <?php echo $this->skin->tooltipAndAccesskey( 'search-fulltext' ); ?>><?php $this->msg( 'searchbutton' ) ?><img src="<?php echo $this->skin->getSkinStylePath('newimages/search-rtl.png'); ?>" alt="<?php $this->msg( 'searchbutton' ) ?>" /></button>
								<?php endif; ?>
								<input id="searchInput" name="search" type="text" <?php echo $this->skin->tooltipAndAccesskey( 'search' ); ?> <?php if( isset( $this->data['search'] ) ): ?> value="<?php $this->text( 'search' ) ?>"<?php endif; ?> />
								<?php if ( !$this->data['rtl'] ): ?>
								<button id="searchButton" type='submit' name='button' class='ui-state-default ui-corner-all' <?php echo $this->skin->tooltipAndAccesskey( 'search-fulltext' ); ?>><?php $this->msg( 'searchbutton' ) ?><img src="<?php echo $this->skin->getSkinStylePath('newimages/search-ltr.png'); ?>" alt="<?php $this->msg( 'searchbutton' ) ?>" /></button>
								<?php endif; ?>
							</div>
						</form>
					</li>
					<li class="fontButtons">
						<a id="RegFontBtn" class="font-resizer fontBtn selected" href="#" title="<?php echo wfMsgHtml( 'wr-font-resizer-reg-tooltip' ) ?>"><span><?php echo wfMsgHtml( 'wr-font-resizer-btn-text' ) ?></span></a>	
						<a id="MedFontBtn" class="font-resizer fontBtn" href="#" title="<?php echo wfMsgHtml( 'wr-font-resizer-med-tooltip' ) ?>"><span><?php echo wfMsgHtml( 'wr-font-resizer-btn-text' ) ?></span></a>
						<a id="BigFontBtn" class="font-resizer fontBtn" href="#" title="<?php echo wfMsgHtml( 'wr-font-resizer-big-tooltip' ) ?>"><span><?php echo wfMsgHtml( 'wr-font-resizer-btn-text' ) ?></span></a>
					</li>
					<li class="navButtons">
					<a id="homeBtn" class="<?php echo $this->skin->mTitle->getText()==wfMsgForContent('mainpage')?'selected':'' ?>"  href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>" <?php echo $this->skin->tooltipAndAccesskey( 'p-logo' ) ?>></a>
					</li>
				</ul>
			</div>
			<!-- /controls -->
		</div>
		<!-- /banner -->
		<!-- title-row -->
		<div id="firstHeadingRow">
			<div id="firstHeadingBtns" class="newinlinelist noprint">
				<ul>
					<li>
						<?php
							global $wgTitle;
							$pageTitle = $wgTitle;
							$pageTitleEncoded = rawurlencode( str_replace(' ', '_', $pageTitle) );
							$pageUrl = $this->data['serverurl'] . str_replace( '$1', $pageTitleEncoded, $this->data['articlepath'] ); 										
						?>
						<span class="facebookLikeBtn"><iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo $pageUrl ?>&layout=button_count&amp;show_faces=false&amp;width=91&amp;action=recommend&amp;colorscheme=light&amp;height=20" scrolling="no" frameborder="0" allowTransparency="true"></iframe></span>
					</li>
					<?php
						$item = $this->data['view_urls']; 
						$item = isset( $item['watch'] ) ? $item['watch'] : $item['unwatch'];
						if( isset( $item ) ): ?>
							<li<?php echo $item['attributes'] ?>><span><a href="<?php echo htmlspecialchars( $item['href'] ) ?>" <?php echo $item['key'] ?>><?php echo (array_key_exists('img',$item) ?  '<img src="'.$item['img'].'" alt="'.$item['text'].'" />' : htmlspecialchars( $item['text'] ) ) ?></a></span></li>
						<?php endif; ?>
				</ul>
			</div>
			<!-- firstHeading -->
			<h1 id="firstHeading" class="firstHeading"><?php $this->html( 'title' ) ?></h1>
			<!-- /firstHeading -->
		</div>
		<!-- /firstHeadingRow -->
	</div>
	<!-- /header -->
	<div id="mw-js-message" class="js-messagebox" style="display: none;"></div>
	</div>
	<!-- /headerWrapper -->

<div id="scrollPage">
	<div id="content" class="pageSection">                        
		<a id="top"></a>
		<?php if ( $this->data['sitenotice'] ): ?>
			<!-- sitenotice -->
			<div id="siteNotice"><?php $this->html( 'sitenotice' ) ?></div>
			<!-- /sitenotice -->
		<?php endif; ?>
		<div id="bodyContent">
			<!-- tagline -->
			<div id="siteSub"><?php $this->msg( 'tagline' ) ?></div>
			<!-- /tagline -->
			<?php if ( $this->data['subtitle'] ): ?>
			<!-- subtitle -->
			<div id="contentSub"<?php $this->html('userlangattributes') ?>><?php $this->html( 'subtitle' ) ?></div>
			<!-- /subtitle -->
			<?php endif; ?>
			<?php if ( $this->data['undelete'] ): ?>
			<!-- undelete -->
			<div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
			<!-- /undelete -->
			<?php endif; ?>
			<?php if($this->data['newtalk'] ): ?>
			<!-- newtalk -->
			<div class="usermessage"><?php $this->html( 'newtalk' ) ?></div>
			<!-- /newtalk -->
			<?php endif; ?>
			<?php if ( $this->data['showjumplinks'] ): ?>
			<!-- jumpto -->
			<!--<div id="jump-to-nav">
				<?php $this->msg( 'jumpto' ) ?> <a href="#mw-head"><?php $this->msg( 'jumptonavigation' ) ?></a>,
				<a href="#p-search"><?php $this->msg( 'jumptosearch' ) ?></a>
			</div>-->
			<!-- /jumpto -->
			<?php endif; ?>
			<!-- bodytext -->
				<?php $this->html( 'bodytext' ) ?>
			<!-- /bodytext -->
			<?php if ( $this->data['catlinks'] ): ?>
				<!-- catlinks -->
				<?php $this->html( 'catlinks' ); ?>
				<!-- /catlinks -->
			<?php endif; ?>
			<?php if ( $this->data['dataAfterContent'] ): ?>
				<!-- dataAfterContent -->
				<?php $this->html( 'dataAfterContent' ); ?>
				<!-- /dataAfterContent -->
			<?php endif; ?>
			<!-- WR: disclaimer! -->
				<?php $msgName = 'wr-additional-disclaimers'; $msgOut = wfMsg( $msgName );
				if ( !wfEmptyMsg( $msgName, $msgOut ) ): ?>
					<div class="noprint" id="wr-footer-additional-disclaimers"><?php echo wfMsgWikiHtml( $msgName ); ?></div>
				<?php endif; ?>
				<div id="wr-footer-disclaimer"><?php echo wfMsgWikiHtml( 'wr-disclaimer-short' ) ?></div>
			<!-- /wr-disclaimer -->
			<div class="visualClear"></div>
		</div>
		<!-- /bodyContent -->
	</div>
	<!-- /content -->
		
	<div class="visualClear"></div>
	<?php if( $this->skin->loggedin && $wgUser->isAllowed( 'edit' ) ): ?>
	<div id="page-links" class="pageSection noprint">
		<ul class="wr-footer-tools">
			<?php $this->renderToolbox() ?>		
		</ul>
	</div>
	<?php endif; ?>
	
	<?php if( $this->skin->loggedin ): ?>
	<div id="user-links" class="pageSection noprint">
		<ul class="wr-footer-tools">
			<?php $urls = $this->data['personal_urls']; unset( $urls['logout'] ); ?>
			<?php foreach($urls as $item): ?>
				<li <?php echo $item['attributes'] ?>><a href="<?php echo htmlspecialchars($item['href']) ?>"<?php echo $item['key'] ?><?php if(!empty($item['class'])): ?> class="<?php echo htmlspecialchars($item['class']) ?>"<?php endif; ?>><?php echo htmlspecialchars($item['text']) ?></a></li>
			<?php endforeach; ?>		
		</ul>
	</div>
	<?php endif; ?>
	
	<!-- footer -->
	<div id="footer" class="pageSection" <?php $this->html('userlangattributes') ?>>
		<?php foreach( $validFooterLinks as $category => $links ): ?>
			<?php if ( count( $links ) > 0 ): ?>
			<ul id="footer-<?php echo $category ?>" <?php echo $category=='places'?'class="noprint"':''?> >
				<?php foreach( $links as $link ): ?>
					<?php if( isset( $this->data[$link] ) && $this->data[$link] ): ?>
					<li id="footer-<?php echo $category ?>-<?php echo $link ?>"><?php $this->html( $link ) ?></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		<?php endforeach; ?>
<?php			if ( count( $footericons ) > 0 ): ?>
			<ul id="footer-icons" class="noprint">
<?php			foreach ( $footericons as $blockName => $footerIcons ): ?>
				<li id="footer-<?php echo htmlspecialchars($blockName); ?>ico">
<?php				foreach ( $footerIcons as $icon ): ?>
					<?php echo $this->skin->makeFooterIcon( $icon ); ?>

<?php				endforeach; ?>
				</li>
<?php			endforeach; ?>
			</ul>
		<?php endif; ?>
		<div style="clear:both"></div>
	</div>
	<!-- /footer -->
</div><!-- /scrollPage -->
	

	<!-- editorToolbar -->
	<?php if( $this->skin->loggedin && $wgUser->isAllowed( 'edit' ) ): ?>
	<div id="editorToolbar" class="noprint">
		<?php 
			$view_urls = $this->data['view_urls'];
			foreach( $view_urls as $key => $link ):		#WR: do not show history & watch on Editor Toolbar
				if( in_array( $key, array( 'history', 'watch', 'unwatch' ) ) ) unset( $view_urls[$key] );	
				endforeach; 
		?>
		<ul class="wr-footer wr-footer-tools">
			<!--<?php foreach ($this->data['namespace_urls'] as $link ): ?>
				<li <?php echo $link['attributes'] ?>><span><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] ) ?></a></span></li>
			<?php endforeach; ?>-->
			<?php foreach ( $view_urls as $link ): ?>
				<li<?php echo $link['attributes'] ?>><span><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php echo (array_key_exists('img',$link) ?  '<img src="'.$link['img'].'" alt="'.$link['text'].'" />' : htmlspecialchars( $link['text'] ) ) ?></a></span></li>
			<?php endforeach; ?>
			<?php foreach ( $this->data['action_urls'] as $link ): ?>
				<li<?php echo $link['attributes'] ?>><span><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php echo (array_key_exists('img',$link) ?  '<img src="'.$link['img'].'" alt="'.$link['text'].'" />' : htmlspecialchars( $link['text'] ) ) ?></a></span></li>
			<?php endforeach; ?>
			<?php $special = 'upload'; if( $this->data['nav_urls'][$special] ): ?>
				<li id="t-<?php echo $special ?>"><a href="<?php echo htmlspecialchars( $this->data['nav_urls'][$special]['href'] ) ?>"<?php echo $this->skin->tooltipAndAccesskey( 't-' . $special ) ?>><?php $this->msg( $special ) ?></a></li>
			<?php endif; ?>
			<?php $item = $this->data['personal_urls']['help']; ?>
			<li <?php echo $item['attributes'] ?>><a href="<?php echo htmlspecialchars($item['href']) ?>"<?php echo $item['key'] ?><?php if(!empty($item['class'])): ?> class="<?php echo htmlspecialchars($item['class']) ?>"<?php endif; ?>><?php echo htmlspecialchars($item['text']) ?></a></li>	
			<!-- wr-last-update (Last Update Date,WikiRights addition 27/10/2011. -->
			<?php if ( $this->skin->iscontent && $wgArticle ): ?>
				<li id="wr-last-update">
					<span><a title="<?php echo wfMsg( 'tooltip-ca-history' ) ?>"
						alt="<?php echo wfMsg( 'vector-view-history' ) ?>"
						href="<?php echo $this->skin->mTitle->getLocalUrl( 'action=history' ) ?>">
						<?php echo $this->skin->lastModified() ?>
					</a></span>
				</li>
			<?php endif; ?>
			<!-- /wr-last-update -->
		</ul>
	</div>
	<?php endif; ?>
	<!-- /editorToolbar -->
</div>
<!-- /page-wrap -->

	<div class="visualClear"></div>
	<?php $this->html( 'bottomscripts' ); /* JS call to runBodyOnloadHook */ ?>
	<!-- fixalpha -->
	<script type="<?php $this->text('jsmimetype') ?>"> if ( window.isMSIE55 ) fixalpha(); </script>
	<!-- /fixalpha -->
	<?php $this->html( 'reporttime' ) ?>
	<?php if ( $this->data['debug'] ): ?>
	<!-- Debug output: <?php $this->text( 'debug' ); ?> -->
	<?php endif; ?>
</body>

</html>

<?php 		
	}

	/**
	 * WR: Render the toolbox. This piece of code used to be in renderPortals,
	 * but moved for Victor (older skin), where it was used more than once,
	 * which is actually a bad idea considering there are IDs in here...
	 * Anyway, this is how things are... 
	 */
	private function renderToolbox() {
		?>
		<?php if( $this->data['notspecialpage'] ): ?>
			<li id="t-whatlinkshere"><a href="<?php echo htmlspecialchars( $this->data['nav_urls']['whatlinkshere']['href'] ) ?>"<?php echo $this->skin->tooltipAndAccesskey( 't-whatlinkshere' ) ?>><?php $this->msg( 'whatlinkshere' ) ?></a></li>
			<?php if( $this->data['nav_urls']['recentchangeslinked'] ): ?>
			<li id="t-recentchangeslinked"><a href="<?php echo htmlspecialchars( $this->data['nav_urls']['recentchangeslinked']['href'] ) ?>"<?php echo $this->skin->tooltipAndAccesskey( 't-recentchangeslinked' ) ?>><?php $this->msg( 'recentchangeslinked-toolbox' ) ?></a></li>
			<?php endif; ?>
		<?php endif; ?>
		<?php if( isset( $this->data['nav_urls']['trackbacklink'] ) ): ?>
		<li id="t-trackbacklink"><a href="<?php echo htmlspecialchars( $this->data['nav_urls']['trackbacklink']['href'] ) ?>"<?php echo $this->skin->tooltipAndAccesskey( 't-trackbacklink' ) ?>><?php $this->msg( 'trackbacklink' ) ?></a></li>
		<?php endif; ?>
		<?php if( $this->data['feeds']): ?>
		<li id="feedlinks">
			<?php foreach( $this->data['feeds'] as $key => $feed ): ?>
			<a id="<?php echo Sanitizer::escapeId( "feed-$key" ) ?>" href="<?php echo htmlspecialchars( $feed['href'] ) ?>" rel="alternate" type="application/<?php echo $key ?>+xml" class="feedlink"<?php echo $this->skin->tooltipAndAccesskey( 'feed-' . $key ) ?>><?php echo htmlspecialchars( $feed['text'] ) ?></a>
			<?php endforeach; ?>
		</li>
		<?php endif; ?>
		<?php foreach( array( 'contributions', 'log', 'blockip', 'emailuser', 'upload', 'specialpages' ) as $special ): ?>
			<?php if( $this->data['nav_urls'][$special]): ?>
			<li id="t-<?php echo $special ?>"><a href="<?php echo htmlspecialchars( $this->data['nav_urls'][$special]['href'] ) ?>"<?php echo $this->skin->tooltipAndAccesskey( 't-' . $special ) ?>><?php $this->msg( $special ) ?></a></li>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php if( !empty( $this->data['nav_urls']['print']['href'] ) ): ?>
		<li id="t-print"><a href="<?php echo htmlspecialchars( $this->data['nav_urls']['print']['href'] ) ?>" rel="alternate"<?php echo $this->skin->tooltipAndAccesskey( 't-print' ) ?>><?php $this->msg( 'printableversion' ) ?></a></li>
		<?php endif; ?>
		<?php if (  !empty(  $this->data['nav_urls']['permalink']['href'] ) ): ?>
		<li id="t-permalink"><a href="<?php echo htmlspecialchars( $this->data['nav_urls']['permalink']['href'] ) ?>"<?php echo $this->skin->tooltipAndAccesskey( 't-permalink' ) ?>><?php $this->msg( 'permalink' ) ?></a></li>
		<?php elseif ( $this->data['nav_urls']['permalink']['href'] === '' ): ?>
		<li id="t-ispermalink"<?php echo $this->skin->tooltip( 't-ispermalink' ) ?>><?php $this->msg( 'permalink' ) ?></li>
		<?php endif; ?>
		<?php wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this ) ); ?>

<?php 	
	}
}



