<?php

namespace Liquipedia\LiquiFlow;

use Hooks;
use Html;
use Linker;
use Title;
use Sanitizer;
use Xml;

/**
 * QuickTemplate class for LiquiFlow skin
 * @ingroup Skins
 */
class Template extends \BaseTemplate {

	private $icons = [
		// Wiki actions
		'subject' => [
			'subject-0' => 'far fa-fw fa-file-alt',
			'subject-2' => 'far fa-fw fa-user',
			'subject-6' => 'far fa-fw fa-file-image',
			'subject-8' => 'far fa-fw fa-file-code',
			'subject-10' => 'far fa-fw fa-file-code',
			'subject-12' => 'far fa-fw fa-question-circle',
			'subject-14' => 'far fa-fw fa-folder',
		],
		'subjectdefault' => 'far fa-fw fa-file-alt',
		'main' => 'far fa-fw fa-file-alt',
		'view' => 'far fa-fw fa-file-alt',
		'talk' => 'far fa-fw fa-comments-alt',
		'history' => 'far fa-fw fa-history',
		'edit' => 'far fa-fw fa-pencil-alt',
		'watch' => 'far fa-fw fa-bookmark',
		'unwatch' => 'fas fa-fw fa-bookmark',
		'delete' => 'far fa-fw fa-trash-alt',
		'undelete' => 'far fa-fw fa-undo',
		'move' => 'fas fa-fw fa-exchange-alt',
		'protect' => 'far fa-fw fa-lock-open-alt',
		'unprotect' => 'far fa-fw fa-lock-alt',
		'purge' => 'far fa-fw fa-redo-alt',
		'addsection' => 'fas fa-fw fa-plus',
		'stability' => 'far fa-fw fa-check-circle',
		'viewsource' => 'far fa-fw fa-pencil-alt',
		'current' => 'far fa-fw fa-circle-notch fa-spinner-7s',
		'default' => 'far fa-fw fa-pause-circle',
		'view-foreign' => 'far fa-fw fa-hdd',
		// Tools
		't-recent-changes' => 'far fa-fw fa-clock',
		't-pending-changes' => 'far fa-fw fa-circle-notch',
		't-unreviewed-pages' => 'far fa-fw fa-circle',
		't-random-page' => 'fas fa-fw fa-random',
		't-whatlinkshere' => 'fas fa-fw fa-link',
		't-print' => 'fas fa-fw fa-print',
		't-recentchangeslinked' => 'liquipedia-custom-icon liquipedia-custom-icon-related-changes',
		't-specialpages' => 'fas fa-fw fa-magic',
		't-permalink' => 'far fa-fw fa-dot-circle',
		't-info' => 'fas fa-fw fa-info',
		't-smwbrowselink' => 'fas fa-fw fa-thumbtack',
		't-upload' => 'fas fa-fw fa-upload',
		't-blockip' => 'far fa-fw fa-ban',
		't-log' => 'fas fa-fw fa-book',
		't-contributions' => 'fas fa-fw fa-puzzle-piece',
		't-userrights' => 'far fa-fw fa-list-alt',
		'feed-atom' => 'fas fa-fw fa-rss',
		't-liquipediadblink' => 'fas fa-fw fa-database',
		't-share' => 'fas fa-fw fa-share-alt',
		't-tools' => 'fas fa-fw fa-wrench',
		't-admin-links' => 'fas fa-fw fa-gavel',
		't-menu-user' => 'fas fa-fw fa-user-circle',
		't-other-wikis' => 'fas fa-fw fa-map-signs',
		't-back-to-top' => 'fas fa-fw fa-arrow-up',
		't-mobile-search-button' => 'fas fa-fw fa-arrow-right',
		// Personal
		'pt-user' => 'far fa-fw fa-user',
		'pt-userpage' => 'fas fa-fw fa-home',
		'pt-mytalk' => 'fas fa-fw fa-inbox',
		'pt-preferences' => 'fas fa-fw fa-sliders-v',
		'pt-watchlist' => 'fas fa-fw fa-bookmark',
		'pt-mycontris' => 'fas fa-fw fa-puzzle-piece',
		'pt-createaccount' => 'fas fa-fw fa-user-plus',
		'pt-logout' => 'fas fa-fw fa-sign-out-alt',
		'pt-login' => 'fas fa-fw fa-sign-in-alt',
		'pt-adminlinks' => 'fas fa-fw fa-gavel',
		// Extensions
		'ext-bugtracker' => 'fas fa-fw fa-bug',
		'ext-teamtemplate' => 'fas fa-fw fa-users',
		'ext-streampage' => 'fas fa-fw fa-tv-retro',
		'ext-networknotice' => 'far fa-fw fa-exclamation-triangle',
		// Other icons
		'top-menu-trending' => 'far fa-fw fa-chart-line',
		'top-menu-tournaments' => 'fas fa-fw fa-trophy',
		'top-menu-contribute' => 'fas fa-fw fa-puzzle-piece',
		'top-menu-actions' => 'fas fa-fw fa-gavel',
		'top-menu-search-button' => 'fas fa-fw fa-search',
		// Share menu
		'share-twitter' => 'fab fa-fw fa-twitter',
		'share-facebook' => 'fab fa-fw fa-facebook-f',
		'share-reddit' => 'fab fa-fw fa-reddit-alien',
		'share-qq' => 'fab fa-fw fa-qq',
		'share-vk' => 'fab fa-fw fa-vk',
		'share-weibo' => 'fab fa-fw fa-weibo',
		'share-whatsapp' => 'fab fa-fw fa-whatsapp',
		// Mobile icons
		'big-bars' => 'fas fa-fw fa-bars fa-2x',
		'big-list' => 'fas fa-fw fa-list fa-2x',
		'big-search' => 'fas fa-fw fa-search fa-2x',
	];

	private function makeFAIcon( $name ) {
		return '<span class="' . $this->icons[ $name ] . '"></span>';
	}

	private $adminDropdown = [
		'about' => [
			[ 'page' => 'Special:Statistics', 'title' => 'statistics', 'id' => 'ad-statistics' ],
			[ 'page' => 'Special:ValidationStatistics', 'title' => 'validationstatistics', 'id' => 'ad-validation-statistics' ],
			[ 'page' => 'Special:Version?detail', 'title' => 'version', 'id' => 'ad-version' ],
			[ 'page' => 'Special:Log', 'title' => 'log', 'id' => 'ad-logs' ],
		],
		'filehist-user' => [
			[ 'page' => 'Special:UserList', 'title' => 'liquiflow-user-list', 'id' => 'ad-user-list' ],
			[ 'page' => 'Special:UserRights', 'title' => 'liquiflow-user-rights', 'id' => 'ad-user-rights' ],
			[ 'page' => 'Special:BlockUser', 'title' => 'liquiflow-block-user', 'id' => 'ad-block-user' ],
		],
		'liquiflow-interface' => [
			[ 'page' => 'MediaWiki:Common.css', 'title' => 'liquiflow-edit-common-css', 'id' => 'ad-edit-css' ],
			[ 'page' => 'MediaWiki:Common.js', 'title' => 'liquiflow-edit-common-js', 'id' => 'ad-edit-js' ],
			[ 'page' => 'MediaWiki:Sidebar', 'title' => 'liquiflow-edit-sidebar', 'id' => 'ad-sidebar' ],
			[ 'page' => 'MediaWiki:Sitenotice', 'title' => 'liquiflow-edit-sitenotice', 'id' => 'ad-sitenotice' ],
		],
		'liquiflow-other' => [
			[ 'page' => 'Special:Import', 'title' => 'liquiflow-import', 'id' => 'ad-import' ],
			[ 'page' => 'Special:Export', 'title' => 'liquiflow-export', 'id' => 'ad-export' ],
		],
	];
	private $installedExtensions = [];

	private function checkInstalledExtensions() {
		if ( $this->getMsg( 'flaggedrevs-desc' )->exists() ) {
			$this->installedExtensions[ 'FlaggedRevs' ] = true;
		} else {
			$this->installedExtensions[ 'FlaggedRevs' ] = false;
		}
	}

	/**
	 * Outputs the entire contents of the (X)HTML page
	 */
	public function execute() {
		// Run checks for installed extensions
		$this->checkInstalledExtensions();

		// Build additional attributes for navigation urls
		$nav = $this->data[ 'content_navigation' ];

		$mode = $this->getSkin()->getUser()->isWatched( $this->getSkin()->getRelevantTitle() ) ? 'unwatch' : 'watch';

		if ( isset( $nav[ 'actions' ][ $mode ] ) ) {
			$nav[ 'views' ][ $mode ] = $nav[ 'actions' ][ $mode ];
			$nav[ 'views' ][ $mode ][ 'class' ] = rtrim( 'icon ' . $nav[ 'views' ][ $mode ][ 'class' ], ' ' );
			$nav[ 'views' ][ $mode ][ 'primary' ] = true;
			unset( $nav[ 'actions' ][ $mode ] );
		}

		$xmlID = '';
		foreach ( $nav as $section => $links ) {
			foreach ( $links as $key => $link ) {
				if ( $section == 'views' && !( isset( $link[ 'primary' ] ) && $link[ 'primary' ] ) ) {
					$link[ 'class' ] = rtrim( 'collapsible ' . $link[ 'class' ], ' ' );
				}

				$xmlID = isset( $link[ 'id' ] ) ? $link[ 'id' ] : 'ca-' . $xmlID;
				$nav[ $section ][ $key ][ 'attributes' ] = ' id="' . Sanitizer::escapeId( $xmlID ) . '"';
				if ( $link[ 'class' ] ) {
					$nav[ $section ][ $key ][ 'attributes' ] .= ' class="' . htmlspecialchars( $link[ 'class' ] ) . '"';
					unset( $nav[ $section ][ $key ][ 'class' ] );
				}
				if ( isset( $link[ 'tooltiponly' ] ) && $link[ 'tooltiponly' ] ) {
					$nav[ $section ][ $key ][ 'key' ] = Linker::tooltip( $xmlID );
				} else {
					$nav[ $section ][ $key ][ 'key' ] = Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( $xmlID ) );
				}
			}
		}
		unset( $nav[ 'views' ][ 'view' ] );
		//$nav['actions']['delete']['text'] = '<span class="fa fa-fw fa-trash-o" title="Page"></span> <span="hidden-sm">Delete</span>';
		$this->data[ 'namespace_urls' ] = $nav[ 'namespaces' ];
		$this->data[ 'view_urls' ] = $nav[ 'views' ];
		$this->data[ 'action_urls' ] = $nav[ 'actions' ];
		$this->data[ 'variant_urls' ] = $nav[ 'variants' ];

		// Reverse horizontally rendered navigation elements
		if ( $this->data[ 'rtl' ] ) {
			$this->data[ 'view_urls' ] = array_reverse( $this->data[ 'view_urls' ] );
			$this->data[ 'namespace_urls' ] = array_reverse( $this->data[ 'namespace_urls' ] );
			$this->data[ 'personal_urls' ] = array_reverse( $this->data[ 'personal_urls' ] );
		}

		$this->html( 'headelement' );

		// extract the standard table of contents from the page html in order to add it to the left sidebar
		preg_match( '/<div class="toctitle"(.*?)><h2>(.*?)<\/h2><\/div>(.*?)<ul>(.*?)<\/ul>(.*?)<\/div>/si', $this->data[ 'bodycontent' ], $match );
		$toc = '';
		if ( isset( $match[ 0 ] ) ) {
			$toc = substr( $match[ 0 ], 0, -6 );
			$toc = str_replace( '<ul>', '<ul class="nav">', $toc );

			// Adjust Data-Targets in menu so scrollspy works properly with special characters
			preg_match_all( '/\<a href=\"([^"]*?)\"\>/', $toc, $toc_matches );
			foreach ( $toc_matches[ 1 ] as $match ) {
				$toc = str_replace( '<a href="' . $match . '"', '<a data-target="' . preg_replace( '/\./', '\\\\.', preg_replace( '/\:/', '\\\\:', $match ) ) . '" href="' . $match . '"', $toc );
			}

			preg_match( "/<div class=\"toclimit-([1-6])\">/si", $this->data[ 'bodycontent' ], $toclimitmatch );
			if ( isset( $toclimitmatch[ 0 ] ) ) {
				$toclimit = $toclimitmatch[ 1 ];
			}

			// Hide standard toc on big screens when the sidebar toc is shown
			$this->data[ 'bodycontent' ] = str_replace( '<div id="toc" class="toc">', '<div id="toc" class="toc hidden-lg hidden-xl">', $this->data[ 'bodycontent' ] );
		}

		$hookValue = '';
		Hooks::run( 'LiquiFlowBodyFirst', array( $this->getSkin(), &$hookValue ) );
		echo $hookValue;

		$liquiFlowAlphawiki = $this->config->get( 'LiquiFlowAlphawiki' );
		?>

		<nav class="navbar navbar-default navbar-fixed-top noprint<?php echo( isset( $liquiFlowAlphawiki ) && $liquiFlowAlphawiki ) ? ' alphawiki' : ''; ?>" id="slide-nav">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12 navbar-main-column">
						<div class="navbar-header">
							<button class="navbar-toggle" id="main-nav-toggler">
								<span class="sr-only">Toggle navigation</span>
								<?php echo $this->makeFAIcon( 'big-bars' ); ?></span>
							</button>

							<a class="navbar-brand" href="<?php echo htmlspecialchars( $this->data[ 'nav_urls' ][ 'mainpage' ][ 'href' ] ); ?>"
							   <?php echo Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) ); ?>>
								<div style="display: inline-block;">
									<div style="float: left;"><img id="brand-logo" src="<?php $this->text( 'logopath' ); ?>" alt="Logo"></div>
									<div class="brand-name visible-xs logotype" style="white-space: nowrap; margin-left: 5px; float: left;"><?php echo ( isset( $liquiFlowAlphawiki ) && $liquiFlowAlphawiki ) ? '<small>' . $this->getMsg( 'liquiflow-brand' )->text() . '</small> alpha' : $this->getMsg( 'liquiflow-brand' )->text(); ?></div>
								</div>
							</a>

							<?php if ( strlen( $toc ) > 0 ) { ?>
								<button class="navbar-toggle pull-right" id="toc-toggler">
									<span class="sr-only">Toggle navigation</span>
									<?php echo $this->makeFAIcon( 'big-list' ); ?>
								</button>
							<?php } ?>
							<button id="mobile-search-button" class="navbar-toggle navbar-search-toggle pull-right visible-xs">
								<?php echo $this->makeFAIcon( 'big-search' ); ?>
							</button>

						</div><!-- /.navbar-header -->
						<?php if ( strlen( $toc ) > 0 ) { ?>
							<div id="slide-toc" class="visible-xs">
								<div id="scroll-wrapper-toc" class="<?php
								if ( isset( $toclimit ) ) {
									echo 'toclimit-' . $toclimit;
								}
								?>">
									<div class="nav navbar-nav">
										<?php echo $toc; ?>
									</div>
								</div>
							</div>
						<?php } ?>

						<div id="slidemenu">
							<div id="scroll-wrapper-menu">
								<ul class="nav navbar-nav">
									<?php
									$currentWikiTitle = key( $this->data[ 'sidebar' ] );
									$wikiNavigation = array_shift( $this->data[ 'sidebar' ] );
									?>
									<li class="dropdown dropdown-brand hidden-xs">
										<a id="brand-menu-toggle" class="dropdown-toggle brand-title" data-toggle="dropdown" data-hover="dropdown" href="#">
											<span id="brand-desktop" class="brand-name logotype" style="font-size:18px;"><?php echo ( isset( $liquiFlowAlphawiki ) && $liquiFlowAlphawiki ) ? '<small>' . $this->getMsg( 'liquiflow-brand' )->text() . '</small> alpha' : $this->getMsg( 'liquiflow-brand' )->text(); ?></span> <span class="caret"></span> <br>
											<span class="hidden-xs brand-subtitle">
												<?php echo $currentWikiTitle; ?>
											</span>
										</a>
										<ul id="brand-menu" class="dropdown-menu">
											<?php
											$wikiMenuKey = 'WIKIMENU';
											if ( array_key_exists( $wikiMenuKey, $this->data[ 'sidebar' ] ) ) {
												echo $this->data[ 'sidebar' ][ $wikiMenuKey ];
												unset( $this->data[ 'sidebar' ][ $wikiMenuKey ] );
											} elseif ( count( $wikiNavigation ) > 0 ) {
												if ( is_array( $wikiNavigation ) ) {
													foreach ( $wikiNavigation as $wikiNavigationEntry ) {
														echo '<li><a href="' . $wikiNavigationEntry[ 'href' ] . '">' . $wikiNavigationEntry[ 'text' ] . '</a></li>';
													}
												}
											}
											?>
										</ul>
									</li>

									<?php
									foreach ( $this->data[ 'sidebar' ] as $navHeader => $navEntryArray ) {

										if ( $navHeader == 'WIKIMENU' ) {
											continue;
										} elseif ( $navHeader == 'TRENDING' ) {
											?>
											<li class="dropdown icon-tablet">
												<a id="trending-pages-menu-toggle" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" href="#">
													<div class="visible-xl visible-lg visible-md visible-xs"><?php echo $this->makeFAIcon( 'top-menu-trending' ); ?> <?php echo $this->getMsg( 'liquiflow-menu-trending' )->text(); ?> <span class="caret"></span></div>
													<div class="visible-sm">
														<?php echo $this->makeFAIcon( 'top-menu-trending' ); ?> <span class="caret"></span>
													</div>
												</a>
												<ul id="trending-pages-menu" class="dropdown-menu"></ul>
											</li>
											<?php
										} elseif ( $navHeader == 'TOURNAMENTS' ) {
											?>
											<li class="dropdown hidden-xs icon-tablet">
												<a id="tournaments-menu-toggle" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" href="#">
													<div class="visible-xl visible-lg visible-md visible-xs"><?php echo $this->makeFAIcon( 'top-menu-tournaments' ); ?> <?php echo $this->getMsg( 'liquiflow-menu-tournaments' )->text(); ?> <span class="caret"></span></div>
													<div class="visible-sm">
														<?php echo $this->makeFAIcon( 'top-menu-tournaments' ); ?> <span class="caret"></span>
													</div>
												</a>
												<div id="tournaments-menu" class="dropdown-menu multi-column columns-3">
													<div class="row">
														<?php
														if ( is_array( $navEntryArray ) ) {
															foreach ( $navEntryArray as $subNavHeader => $navSubEntryArray ) {
																?>
																<div class="col-sm-4">
																	<ul id="tournaments-menu-<?php echo str_replace( ' ', '-', strtolower( $subNavHeader ) ); ?>" class="multi-column-dropdown">
																		<li class="dropdown-header"><?php echo $subNavHeader; ?></li>
																		<?php
																		if ( is_array( $navSubEntryArray ) ) {
																			foreach ( $navSubEntryArray as $navEntry ) {
																				echo '<li><a href="' . $navEntry[ 'href' ] . '">' . $navEntry[ 'text' ] . '</a></li>';
																			}
																		}
																		?>
																	</ul>
																</div>
																<?php
															}
														}
														?>
													</div>
													<div class="row">
														<div style="float:right;margin-right:23px"><a href="<?php echo Title::newFromText( 'Tournaments', NS_PROJECT )->getEditURL(); ?>">[<?php echo $this->getMsg( 'liquiflow-menu-edit' )->text(); ?>]</a></div>
													</div>
												</div>
											</li>
											<li class="visible-xs mobile-divider"></li>
											<?php
											if ( is_array( $navEntryArray ) ) {
												foreach ( $navEntryArray as $subNavHeader => $navSubEntryArray ) {
													?>
													<li class="dropdown visible-xs">
														<a class="dropdown-toggle" data-toggle="dropdown" href="#">
															<?php echo $this->makeFAIcon( 'top-menu-tournaments' ); ?> <?php echo $subNavHeader; ?> <span class="caret"></span>
														</a>
														<ul class="dropdown-menu">
															<?php
															if ( is_array( $navSubEntryArray ) ) {
																foreach ( $navSubEntryArray as $navEntry ) {
																	echo '<li><a href="' . $navEntry[ 'href' ] . '">' . $navEntry[ 'text' ] . '</a></li>';
																}
															}
															?>

														</ul>
													</li>
													<?php
												}
											}
										} elseif ( $navHeader == $this->getMsg( 'liquiflow-menu-contribute' )->text() ) {
											?>
											<li class="visible-xs mobile-divider"></li>
											<li class="dropdown icon-tablet">
												<a id="contribute-menu-toggle" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" href="#">
													<div class="visible-xl visible-lg visible-md visible-xs"><?php echo $this->makeFAIcon( 'top-menu-contribute' ); ?> <?php echo $navHeader; ?> <span class="caret"></span></div>
													<div class="visible-sm">
														<?php echo $this->makeFAIcon( 'top-menu-contribute' ); ?> <span class="caret"></span>
													</div>
												</a>
												<ul id="contribute-menu" class="dropdown-menu">
													<?php
													foreach ( $navEntryArray as $navEntry ) {
														echo '<li><a id="contribute-menu-' . str_replace( ' ', '-', strtolower( $navEntry[ 'text' ] ) ) . '" href="' . $navEntry[ 'href' ] . '">' . $navEntry[ 'text' ] . '</a></li>';
													}
													?>
												</ul>
											</li>
											<?php
										} else {
											?>
											<li class="visible-xs mobile-divider"></li>
											<li class="dropdown icon-tablet">
												<a id="<?php echo str_replace( ' ', '-', strtolower( $navHeader ) ); ?>-menu-toggle" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" href="#">
													<?php echo $navHeader; ?> <span class="caret"></span>
												</a>
												<ul id="<?php echo str_replace( ' ', '-', strtolower( $navHeader ) ); ?>-menu" class="dropdown-menu">
													<?php
													foreach ( $navEntryArray as $navEntry ) {
														echo '<li><a href="' . $navEntry[ 'href' ] . '">' . $navEntry[ 'text' ] . '</a></li>';
													}
													?>
												</ul>
											</li>
											<?php
										}
									}
									?>
									<li class="visible-xs mobile-divider"></li>
									<li class="dropdown visible-xs">
										<a class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" href="#">
											<?php echo $this->makeFAIcon( 'top-menu-actions' ); ?> Actions <span class="caret"></span>
										</a>
										<ul class="dropdown-menu">
											<?php $this->renderNavigation( array( 'NAMESPACES', 'VIEWS', 'ACTIONS' ), 'mobile' ); ?>
										</ul>
									</li>
									<li class="dropdown visible-xs">
										<a class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" href="#">
											<?php echo $this->makeFAIcon( 't-share' ); ?> <span class="hidden-sm"><?php echo $this->getMsg( 'liquiflow-share' )->text(); ?></span> <span class="caret"></span>
										</a>
										<?php $this->renderNavigation( 'SHARE' ); ?>
									</li>
									<li class="dropdown visible-xs">
										<a class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" href="#">
											<?php echo $this->makeFAIcon( 't-tools' ); ?> <span class="hidden-sm"><?php echo $this->getMsg( 'toolbox' )->text(); ?></span> <span class="caret"></span>
										</a>
										<?php $this->renderNavigation( 'TOOLBOX', 'mobile' ); ?>
									</li>
									<?php if ( in_array( 'sysop', $this->getSkin()->getUser()->getEffectiveGroups() ) ) : ?>
										<li class="dropdown visible-xs">
											<a class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" href="#">
												<?php echo $this->makeFAIcon( 't-admin-links' ); ?> <span>Admin Links</span> <span class="caret"></span>
											</a>
											<?php $this->renderNavigation( 'ADMIN', 'mobile' ); ?>
										</li>
									<?php endif; ?>
									<?php
									if ( !$this->getSkin()->getUser()->isLoggedIn() ) {
										$personalTools = $this->getPersonalTools();
										if ( isset( $personalTools[ 'createaccount' ] ) ) {
											$personalTools[ 'createaccount' ][ 'class' ] = 'visible-xs';
											$personalTools[ 'createaccount' ][ 'id' ] = $personalTools[ 'createaccount' ][ 'id' ] . '-mobile';
										}
										$personalTools[ 'login' ][ 'class' ] = 'visible-xs';
										$personalTools[ 'login' ][ 'id' ] = $personalTools[ 'login' ][ 'id' ] . '-mobile';
										if ( isset( $personalTools[ 'createaccount' ] ) ) {
											echo $this->makeListItem( 'createaccount', $personalTools[ 'createaccount' ] );
										}
										echo $this->makeListItem( 'login', $personalTools[ 'login' ] );
									} else {
										$personalTools = $this->getPersonalTools();
										?>
										<li class="dropdown visible-xs">
											<a class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" href="#">
												<?php echo $this->makeFAIcon( 't-menu-user' ); ?> <?php echo $personalTools[ "userpage" ][ "links" ][ 0 ][ "text" ]; ?>
												<span class="caret"></span>
											</a>
											<ul class="dropdown-menu">
												<?php
												$personalTools[ "userpage" ][ "links" ][ 0 ][ "text" ] = 'Userpage';
												foreach ( $personalTools as $key => $item ) {
													$item[ 'id' ] = $item[ 'id' ] . '-mobile';
													unset( $item[ 'links' ][ 0 ][ 'single-id' ] );
													echo $this->makeListItem( $key, $item );
												}
												?>
											</ul>
										</li>
										<?php
									}
									?>

									<li class="visible-xs mobile-divider"></li>
									<li class="dropdown visible-xs">
										<a class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" href="#">
											<?php echo $this->makeFAIcon( 't-other-wikis' ); ?> Other Wikis <span class="caret"></span>
										</a>
										<ul class="dropdown-menu">
											<?php
											foreach ( $wikiNavigation as $wikiNavigationEntry ) {
												echo '<li><a href="' . $wikiNavigationEntry[ 'href' ] . '">' . $wikiNavigationEntry[ 'text' ] . '</a></li>';
											}
											?>
										</ul>
									</li>
									<li class="dropdown visible-xs">
										<a href="#top">
											<?php echo $this->makeFAIcon( 't-back-to-top' ); ?> <?php echo $this->getMsg( 'liquiflow-back-to-top' )->text(); ?>
										</a>
									</li>

								</ul><!-- /.navbar-nav -->

								<ul class="nav navbar-nav navbar-right tablet-personal">
									<?php
									if ( !$this->getSkin()->getUser()->isLoggedIn() ) {
										$personalTools = $this->getPersonalTools();
										$personalTools[ 'createaccount' ][ 'links' ][ 0 ][ 'text' ] = '';
										$personalTools[ 'createaccount' ][ 'class' ] = 'hidden-sm hidden-xs';

										$personalTools[ 'login' ][ 'links' ][ 0 ][ 'text' ] = '';
										$personalTools[ 'login' ][ 'class' ] = 'icon-tablet hidden-xs';

										echo $this->makeListItem( 'createaccount', $personalTools[ 'createaccount' ] );
										echo $this->makeListItem( 'login', $personalTools[ 'login' ] );
									}
									?>
								</ul><!-- /.navbar-nav .navbar-right -->
								<ul class="nav navbar-nav navbar-right">
									<?php $this->renderNavigation( 'SEARCH' ); ?>
								</ul><!-- /.navbar-nav .navbar-right -->

							</div>	<!-- /#scroll-wrapper-menu -->
						</div><!-- /#slide-menu -->
					</div><!-- /.col-lg-8 -->
				</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</nav><!-- /.navbar -->

		<nav id="mobile-search-bar" class="navbar visible-xs noprint hidden">
			<form action="<?php $this->text( 'wgScript' ); ?>" id="mobile-search-form" class="navbar-form navbar-left" role="search">
				<div class="input-group">
					<input id="searchInput-mobile" type="search" accesskey="<?php echo $this->getMsg( 'accesskey-search' )->text(); ?>" title="<?php echo $this->getMsg( 'liquiflow-search' )->params( $this->getMsg( 'liquiflow-brand' )->text() . ' ' . $this->config->get( 'LiquiFlowWikiTitle' ) )->text(); ?> [alt-shift-<?php echo $this->getMsg( 'accesskey-search' )->text(); ?>]" placeholder="Search <?php echo $this->getMsg( 'liquiflow-brand' )->text(); ?>" name="search" autocomplete="off" class="form-control">
					<div class="input-group-btn">
						<button class="btn navbar-search-btn searchButton" type="submit" id="searchButton-mobile">
							<?php echo $this->makeFAIcon( 'top-menu-search-button' ); ?><?php echo $this->makeFAIcon( 't-mobile-search-button' ); ?>
						</button>
					</div>
				</div>
			</form>
		</nav><!-- /#mobile-search-bar -->

		<div id="wiki-nav" class="navbar navbar-inverse hidden-xs noprint">
			<div class="container-fluid">
				<div class="row">
					<div id="wiki-nav-main-column" class="col-md-12">
						<div class="wiki-tool-nav">
							<ul class="nav navbar-nav navbar-nav-2">
								<?php $this->renderNavigation( array( 'NAMESPACES', 'VIEWS', 'ACTIONS' ) ); ?>
							</ul>

							<ul class="nav navbar-nav navbar-right navbar-nav-2">
								<li class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" href="#">
										<?php echo $this->makeFAIcon( 't-share' ); ?> <span class="hidden-sm"><?php echo $this->getMsg( 'liquiflow-share' )->text(); ?></span> <span class="caret"></span>
									</a>
									<?php $this->renderNavigation( 'SHARE' ); ?>
								</li>
								<li class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" href="#">
										<?php echo $this->makeFAIcon( 't-tools' ); ?> <span class="hidden-sm"><?php echo $this->getMsg( 'toolbox' )->text(); ?></span> <span class="caret"></span>
									</a>
									<?php $this->renderNavigation( 'TOOLBOX' ); ?>
								</li>
								<?php if ( in_array( 'sysop', $this->getSkin()->getUser()->getEffectiveGroups() ) ) : ?>
									<li class="dropdown">
										<a class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" href="#">
											<?php echo $this->makeFAIcon( 't-admin-links' ); ?> <span class="caret"></span>
										</a>
										<?php $this->renderNavigation( 'ADMIN' ); ?>
									</li>
								<?php endif; ?>
								<?php $this->renderNavigation( 'PERSONAL' ); ?>
							</ul><!-- /.navbar-right -->
						</div><!--  /.wiki-tool-nav -->
					</div><!-- /.col-lg-8 -->
				</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</div><!-- /.navbar-inverse -->

		<div id="wrap">

			<div id="sidebar-toc-column" class="hidden-xs hidden-sm hidden-md">
				<div id="sidebar-toc" class="sidebar-toc bs-docs-sidebar hidden-print hidden-xs hidden-sm affix-top <?php echo ( isset( $toclimit ) ? 'toclimit-' . $toclimit : '' ); ?>" style="" role="complementary">
					<?php
					$hasToc = strlen( $toc ) > 0;
					if ( $hasToc ) {
						echo $toc;
					}
					$hookValue = '';
					Hooks::run( 'LiquiFlowSidebar', array( $this->getSkin(), &$hookValue, $hasToc ) );
					echo $hookValue;
					?>
				</div>
			</div><!-- /#sidebar-toc-colum -->

			<div class="container-fluid main-content">
				<div class="row">

					<div id="main-content-column" class="col-md-12">
						<div id="main-content" class="mw-body">

							<?php if ( $this->data[ 'sitenotice' ] ) : ?>
								<div id="siteNotice">
									<?php $this->html( 'sitenotice' ); ?>
								</div>
								<?php
							endif;

							echo '<div class="networknotice networknotice-red ie-only-do-not-reuse-this-class">Liquipedia will soon drop support for Internet Explorer.</div>';
							echo '<style>@supports(position:sticky){.ie-only-do-not-reuse-this-class{display:none;}}</style>';

							$hookValue = '';
							Hooks::run( 'LiquiFlowTop', array( $this->getSkin(), &$hookValue ) );
							echo $hookValue;
							?>

							<h1 id="firstHeading" class="firstHeading">
								<span dir="auto"><?php $this->html( 'title' ); ?></span>
							</h1>

							<?php $this->html( 'prebodyhtml' ); ?>
							<div id="bodyContent" class="mw-body-content">
								<?php if ( $this->data[ 'isarticle' ] ) : ?>
									<div id="siteSub">
										<?php $this->getMsg( 'tagline' )->text(); ?>
									</div>
								<?php endif; ?>

								<div id="contentSub">
									<?php $this->html( 'subtitle' ); ?>
								</div>

								<?php if ( $this->data[ 'undelete' ] ) : ?>
									<div id="contentSub2">
										<?php $this->html( 'undelete' ); ?>
									</div>
								<?php endif; ?>

								<?php if ( $this->data[ 'newtalk' ] ) : ?>
									<div class="usermessage">
										<?php $this->html( 'newtalk' ); ?>
									</div>
								<?php endif; ?>

								<?php $this->html( 'bodycontent' ); ?>

								<?php if ( $this->data[ 'printfooter' ] ) : ?>
									<div class="printfooter">
										<?php $this->html( 'printfooter' ); ?>
									</div>
								<?php endif; ?>
								<?php
								if ( $this->data[ 'catlinks' ] ) {
									$this->html( 'catlinks' );
								}
								if ( $this->data[ 'dataAfterContent' ] ) {
									$this->html( 'dataAfterContent' );
								}
								?>
								<div class="visualClear"></div>
								<?php $this->html( 'debughtml' ); ?>
							</div>
						</div>
					</div><!-- /#main-content-column -->
				</div><!-- /.row -->
			</div><!-- /.container -->
		</div><!-- /#wrap -->

		<?php
		$footerLinks = $this->getFooterLinks();
		?>
		<div id="footer" class="footer">
			<div class="container-fluid">
				<div class="col-md-12">
					<div class="row">
						<div class="footer-links">
							<div class="col-md-2 hidden-sm hidden-xs">
								<div class="col">
									<a id="footer-logo-big" href="https://liquipedia.net"></a>
								</div>
							</div>

							<div class="col-md-2 col-sm-3 col-xs-6">
								<div class="col">
									<h4><?php echo $this->getMsg( 'liquiflow-our-wikis' )->text(); ?></h4>
									<ul>
										<li><a href="/apexlegends/" target="_blank">Apex Legends</a></li>
										<li><a href="/artifact/" target="_blank">Artifact</a></li>
										<li><a href="/starcraft/" target="_blank">Brood War</a></li>
										<li><a href="/counterstrike/" target="_blank">Counter-Strike</a></li>
										<li><a href="/dota2/" target="_blank">Dota 2</a></li>
										<li><a href="/hearthstone/" target="_blank">Hearthstone</a></li>
										<li><a href="/heroes/" target="_blank">Heroes of the Storm</a></li>
										<li><a href="/leagueoflegends/" target="_blank">League of Legends</a></li>
										<li><a href="/overwatch/" target="_blank">Overwatch</a></li>
										<li><a href="/pubg/" target="_blank">PUBG</a></li>
										<li><a href="/rainbowsix/" target="_blank">Rainbow Six</a></li>
										<li><a href="/rocketleague/" target="_blank">Rocket League</a></li>
										<li><a href="/smash/" target="_blank">Smash</a></li>
										<li><a href="/starcraft2/" target="_blank">StarCraft II</a></li>
										<li><a href="/warcraft/" target="_blank">Warcraft III</a></li>
										<li><a href="/commons/" target="_blank">Commons</a></li>
									</ul>
								</div>
							</div>

							<div class="col-md-2 col-sm-3 col-xs-6">
								<h4><?php echo $this->getMsg( 'liquiflow-alpha-wikis' )->text(); ?></h4>
								<ul>
									<li><a href="/fortnite/" target="_blank">Fortnite</a></li>
									<li><a href="/quake/" target="_blank">Quake</a></li>
									<li><a href="/teamfortress/" target="_blank">Team Fortress</a></li>
									<li><a href="/worldofwarcraft/" target="_blank">World of Warcraft</a></li>
									<li><a href="/fighters/" target="_blank">Fighting Games</a></li>
									<li><a href="/fifa/" target="_blank">FIFA</a></li>
									<li><a href="/clashroyale/" target="_blank">Clash Royale</a></li>
									<li><a href="/arenaofvalor/" target="_blank">Arena of Valor</a></li>
									<li><a href="/paladins/" target="_blank">Paladins</a></li>
								</ul>
							</div>
							<div class="col-md-2 col-sm-3 col-xs-6">
								<h4><?php echo $this->getMsg( 'about' )->text(); ?></h4>
								<ul>
									<?php foreach ( $footerLinks[ 'places' ] as $link ) { ?>
										<li id="footer-places-<?php echo $link; ?>">
											<?php echo $this->html( $link ); ?>
										</li>
									<?php } ?>
								</ul>
								<h4><?php echo $this->getMsg( 'liquiflow-contact-us' )->text(); ?></h4>
								<ul>
									<li><a href="mailto:liquipedia@teamliquid.net"><?php echo $this->getMsg( 'liquiflow-send-an-email' )->text(); ?></a></li>
									<li><a href="https://tl.net/forum/website-feedback/94785-liquipedia-feedback-thread" target="_blank"><?php echo $this->getMsg( 'liquiflow-post-feedback' )->text(); ?></a></li>
									<li><a href="https://liquipedia.net/discord" target="_blank"><?php echo $this->getMsg( 'liquiflow-chat-with-us' )->text(); ?></a></li>
								</ul>
							</div>

							<div class="col-md-2 hidden-sm hidden-xs">
								<h4><?php echo $this->getMsg( 'liquiflow-affiliated-sites' )->text(); ?></h4>
								<ul>
									<li><a href="https://liquipedia.net">Liquipedia Portal</a></li>
									<li><a href="https://tl.net" target="_blank">TL.net</a></li>
									<li><a href="https://www.liquiddota.com" target="_blank">LiquidDota.com</a></li>
									<li><a href="https://www.liquidlegends.net" target="_blank">LiquidLegends.net</a></li>
									<li><a href="https://masterleague.net/" target="_blank">MasterLeague.net</a></li>
									<li><a href="https://qihl.gg/" target="_blank">qihl.gg</a></li>
								</ul>
							</div>

							<div class="col-md-2 col-sm-3 col-xs-12">
								<h4 class="hidden-xs"><?php echo $this->getMsg( 'liquiflow-follow-us' )->text(); ?></h4>
								<ul id="footer-social-media">
									<li>
										<a target="_blank" href="https://twitter.com/LiquipediaNet" class="social-icon twitter-icon">
											<span class="social-link">Twitter</span>
										</a>
									</li>
									<li>
										<a target="_blank" href="https://www.facebook.com/Liquipedia" class="social-icon facebook-icon">
											<span class="social-link">Facebook</span>
										</a>
									</li>
									<li>
										<a target="_blank" href="https://www.youtube.com/user/Liquipedia" class="social-icon youtube-icon">
											<span class="social-link">YouTube</span>
										</a>
									</li>
									<li>
										<a target="_blank" href="https://www.twitch.tv/liquipedia" class="social-icon twitch-icon">
											<span class="social-link">Twitch</span>
										</a>
									</li>
									<li>
										<a target="_blank" href="https://github.com/Liquipedia" class="social-icon github-icon">
											<span class="social-link">GitHub</span>
										</a>
									</li>
								</ul>
							</div>
						</div><!-- ./footer-links -->
					</div><!-- ./row -->
				</div><!-- ./col-lg-8 -->

				<div style="text-align:center;padding-bottom:1em;" class="col-md-12 col-xs-12">
					<ul id="f-list">
						<?php
						if ( isset( $footerLinks[ 'info' ] ) && is_array( $footerLinks[ 'info' ] ) ) {
							foreach ( $footerLinks[ 'info' ] as $link ) {
								?>
								<li id="footer-info-<?php echo $link; ?>">
									<?php echo $this->html( $link ); ?>
								</li>
								<?php
							}
						}
						?>
					</ul><!-- #/f-list -->
				</div><!-- ./col-lg-8 -->
			</div><!-- /.container-fluid -->
		</div><!-- /.footer -->

		<?php $this->printTrail(); ?>

		<?php
		$endCode = '';
		Hooks::run( 'LiquiFlowEndCode', array( __DIR__, $this->getSkin(), &$endCode ) );
		echo $endCode;
		?>
		</body>
		</html>

		<?php
	}

	/**
	 * Render a series of portals
	 *
	 * @param array $portals
	 */
	protected function renderPortals( $portals ) {
		// Force the rendering of the following portals
		if ( !isset( $portals[ 'SEARCH' ] ) ) {
			$portals[ 'SEARCH' ] = true;
		}
		if ( !isset( $portals[ 'TOOLBOX' ] ) ) {
			$portals[ 'TOOLBOX' ] = true;
		}
		if ( !isset( $portals[ 'LANGUAGES' ] ) ) {
			$portals[ 'LANGUAGES' ] = true;
		}
		// Render portals
		foreach ( $portals as $name => $content ) {
			if ( $content === false ) {
				continue;
			}

			switch ( $name ) {
				case 'SEARCH':
					break;
				case 'TOOLBOX':
					$this->renderPortal( 'tb', $this->getToolbox(), 'toolbox', 'SkinTemplateToolboxEnd' );
					break;
				case 'LANGUAGES':
					if ( $this->data[ 'language_urls' ] !== false ) {
						$this->renderPortal( 'lang', $this->data[ 'language_urls' ], 'otherlanguages' );
					}
					break;
				default:
					echo "renderPortal( " . $name . "," . $content . " )";
					print_r( $content );
					$this->renderPortal( $name, $content );
					break;
			}
		}
	}

	/**
	 * @param string $name
	 * @param array $content
	 * @param null|string $msg
	 * @param null|string|array $hook
	 */
	protected function renderPortal( $name, $content, $msg = null, $hook = null ) {
		if ( $msg === null ) {
			$msg = $name;
		}
		$msgObj = $this->getMsg( $msg );
		?>
		<div class="portal" role="navigation" id='<?php echo Sanitizer::escapeId( "p-$name" ); ?>'<?php echo Linker::tooltip( 'p-' . $name ); ?>
		     aria-labelledby='<?php echo Sanitizer::escapeId( "p-$name-label" ); ?>'>
			<h3 <?php echo $this->html( 'userlangattributes' ); ?> id='<?php echo Sanitizer::escapeId( "p-$name-label" ); ?>'>
				<?php echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $msg  ); ?>
			</h3>

			<div class="body">
				<?php
				if ( is_array( $content ) ) {
					?>
					<ul>
						<?php
						foreach ( $content as $key => $val ) {
							?>
							<?php echo $this->makeListItem( $key, $val ); ?>

							<?php
						}
						if ( $hook !== null ) {
							wfRunHooks( $hook, array( &$this, true ) );
						}
						?>
					</ul>
					<?php
				} else {
					echo $content; /* Allow raw HTML block to be defined by extensions */
				}

				$this->renderAfterPortlet( $name );
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render one or more navigations elements by name, automatically reveresed
	 * when UI is in RTL mode
	 *
	 * @param array $elements
	 */
	protected function renderNavigation( $elements, $view = 'desktop' ) {
		// If only one element was given, wrap it in an array, allowing more
		// flexible arguments
		if ( !is_array( $elements ) ) {
			$elements = array( $elements );
			// If there's a series of elements, reverse them when in RTL mode
		} elseif ( $this->data[ 'rtl' ] ) {
			$elements = array_reverse( $elements );
		}
		// Render elements
		foreach ( $elements as $name => $element ) {
			switch ( $element ) {

				case 'NAMESPACES':
					?>
					<?php foreach ( $this->data[ 'namespace_urls' ] as $key => $link ) : ?>
						<?php
						if ( $view == 'mobile' ) {
							$link[ 'attributes' ] = preg_replace( '/id="([^\"]*)"/', 'id="$1-mobile"', $link[ 'attributes' ] );
							$link[ 'key' ] = preg_replace( '/ accesskey="[^\"]*"/', '', $link[ 'key' ] );
						}
						$namespace = $this->getSkin()->getTitle()->getNamespace();
						$namespace -= $namespace % 2;
						?>
						<li<?php echo $link[ 'attributes' ]; ?> data-stuff="context-<?php echo $namespace; ?>">
							<a href="<?php echo htmlspecialchars( $link[ 'href' ] ); ?>"
							   <?php echo $link[ 'key' ] ?>>
								   <?php
								   if ( isset( $this->icons[ $link[ 'context' ] ] ) && $link[ 'context' ] == 'subject' && isset( $this->icons[ $link[ 'context' ] ][ 'subject-' . $namespace ] ) && $this->icons[ $link[ 'context' ] ][ 'subject-' . $namespace ] !== false ) {
									   echo '<span class="' . $this->icons[ 'subject' ][ 'subject-' . $namespace ] . '"></span> <span class="hidden-sm">' . htmlspecialchars( $link[ 'text' ] ) . '</span>';
								   } elseif ( $link[ 'context' ] == 'subject' ) {
									   echo '<span class="' . $this->icons[ 'subjectdefault' ] . '"></span> <span class="hidden-sm">' . htmlspecialchars( $link[ 'text' ] ) . '</span>';
								   } elseif ( isset( $this->icons[ $link[ 'context' ] ] ) && $this->icons[ $link[ 'context' ] ] !== false ) {
									   echo '<span class="' . $this->icons[ $link[ 'context' ] ] . '"></span> <span class="hidden-sm">' . htmlspecialchars( $link[ 'text' ] ) . '</span>';
								   } else {
									   echo htmlspecialchars( $link[ 'text' ] );
								   }
								   ?>

							</a>
						</li>
						<li class="divider-vertical"></li>
					<?php endforeach; ?>

					<?php
					break;
				case 'VIEWS':
					?>
					<?php foreach ( $this->data[ 'view_urls' ] as $key => $link ) : ?>
						<?php
						if ( $view == 'mobile' ) {
							$link[ 'attributes' ] = preg_replace( '/id="([^\"]*)"/', 'id="$1-mobile"', $link[ 'attributes' ] );
							$link[ 'key' ] = preg_replace( '/ accesskey="[^\"]*"/', '', $link[ 'key' ] );
						}
						?>
						<li<?php echo $link[ 'attributes' ]; ?>>
							<a href="<?php echo htmlspecialchars( $link[ 'href' ] ); ?>"
							   <?php echo $link[ 'key' ] ?>>
								   <?php
								   if ( isset( $this->icons[ $key ] ) && $this->icons[ $key ] !== false ) {
									   if ( in_array( 'sysop', $this->getSkin()->getUser()->getEffectiveGroups() )
										   && in_array( $key, [ 'watch', 'unwatch', 'current', 'addsection' ] ) ) {
										   echo '<span class="visible-xs"><span class="' . $this->icons[ $key ] . '"></span> ' .
										   htmlspecialchars( $link[ 'text' ] ) . '</span>';
										   echo '<span class="hidden-xs"><span class="' . $this->icons[ $key ] . '"></span></span>';
									   } elseif ( in_array( 'reviewer', $this->getSkin()->getUser()->getEffectiveGroups() )
										   && in_array( $key, [ 'watch', 'unwatch', 'current' ] ) ) {
										   echo '<span class="visible-xs"><span class="' . $this->icons[ $key ] . '"></span> ' .
										   htmlspecialchars( $link[ 'text' ] ) . '</span>';
										   echo '<span class="hidden-xs"><span class="' . $this->icons[ $key ] . '"></span></span>';
									   } elseif ( in_array( 'editor', $this->getSkin()->getUser()->getEffectiveGroups() )
										   && in_array( $key, [ 'current' ] ) ) {
										   echo '<span class="visible-xs"><span class="' . $this->icons[ $key ] . '"></span> ' .
										   htmlspecialchars( $link[ 'text' ] ) . '</span>';
										   echo '<span class="hidden-xs"><span class="' . $this->icons[ $key ] . '"></span></span>';
									   } elseif ( in_array( $key, [ 'current' ] ) ) {
										   echo '<span class="visible-xs"><span class="' . $this->icons[ $key ] . '"></span> ' .
										   htmlspecialchars( $link[ 'text' ] ) . '</span>';
										   echo '<span class="hidden-xs"><span class="' . $this->icons[ $key ] . '"></span></span>';
									   } else {
										   echo '<span class="' . $this->icons[ $key ] . '"></span> ' .
										   '<span class="hidden-sm">' . htmlspecialchars( $link[ 'text' ] ) . '</span>';
									   }
								   } else {
									   echo htmlspecialchars( $link[ 'text' ] );
								   }
								   ?>

							</a>
						</li>
						<li class="divider-vertical"></li>
					<?php endforeach; ?>

					<?php
					break;
				case 'ACTIONS':
					if ( isset( $this->data[ 'action_urls' ][ 'protect' ] ) ) {
						$this->data[ 'action_urls' ][ 'default' ] = array(
							'class' => '',
							'text' => $this->getMsg( 'liquiflow-stability' ),
							'href' => \SpecialPage::getTitleFor( 'Stabilization' )->getLocalUrl( 'page=' . $this->getSkin()->getTitle() ),
							'id' => 'ca-default',
							'attributes' => ' id="ca-default"',
							'key' => ' title="' . $this->getMsg( 'liquiflow-stability-tooltip' ) . '"'
						);
					}
					if ( isset( $this->data[ 'action_urls' ][ 'purge' ] ) ) {
						$this->data[ 'action_urls' ][ 'purge' ] = array(
							'class' => '',
							'text' => $this->getMsg( 'liquiflow-purge' ),
							'href' => $this->config->get( 'ScriptPath' ) . '/index.php?title=' . $this->getSkin()->getTitle() . '&action=purge',
							'id' => 'ca-purge',
							'attributes' => ' id="ca-purge"',
							'key' => ' title="' . $this->getMsg( 'liquiflow-purge-tooltip' ) . '"'
						);
					}
					foreach ( $this->data[ 'action_urls' ] as $key => $link ) :
						?>
						<?php
						if ( $view == 'mobile' ) {
							$link[ 'attributes' ] = preg_replace( '/id="([^\"]*)"/', 'id="$1-mobile"', $link[ 'attributes' ] );
							$link[ 'key' ] = preg_replace( '/ accesskey="[^\"]*"/', '', $link[ 'key' ] );
						}
						?>
						<li<?php echo $link[ 'attributes' ]; ?>>
							<a href="<?php echo htmlspecialchars( $link[ 'href' ] ); ?>"
							   <?php echo $link[ 'key' ] ?>>
								   <?php
								   if ( isset( $this->icons[ $key ] ) && $this->icons[ $key ] !== false ) {
									   if ( in_array( 'sysop', $this->getSkin()->getUser()->getEffectiveGroups() )
										   && in_array( $key, [ 'purge', 'delete', 'undelete', 'protect', 'unprotect', 'default', 'move' ] ) ) {
										   echo '<span class="visible-xs"><span class="' . $this->icons[ $key ] . '"></span> ' .
										   htmlspecialchars( $link[ 'text' ] ) . '</span>';
										   echo '<span class="hidden-xs"><span class="' . $this->icons[ $key ] . '"></span></span>';
									   } elseif ( in_array( 'reviewer', $this->getSkin()->getUser()->getEffectiveGroups() )
										   && in_array( $key, [ 'purge', 'move' ] ) ) {
										   echo '<span class="visible-xs"><span class="' . $this->icons[ $key ] . '"></span> ' .
										   htmlspecialchars( $link[ 'text' ] ) . '</span>';
										   echo '<span class="hidden-xs"><span class="' . $this->icons[ $key ] . '"></span></span>';
									   } elseif ( in_array( 'editor', $this->getSkin()->getUser()->getEffectiveGroups() )
										   && in_array( $key, [ 'purge' ] ) ) {
										   echo '<span class="visible-xs"><span class="' . $this->icons[ $key ] . '"></span> ' .
										   htmlspecialchars( $link[ 'text' ] ) . '</span>';
										   echo '<span class="hidden-xs"><span class="' . $this->icons[ $key ] . '"></span></span>';
									   } elseif ( in_array( $key, [ 'purge' ] ) ) {
										   echo '<span class="visible-xs"><span class="' . $this->icons[ $key ] . '"></span> ' .
										   htmlspecialchars( $link[ 'text' ] ) . '</span>';
										   echo '<span class="hidden-xs"><span class="' . $this->icons[ $key ] . '"></span></span>';
									   } else {
										   echo '<span class="' . $this->icons[ $key ] . '"></span> ' .
										   '<span class="hidden-sm">' . htmlspecialchars( $link[ 'text' ] ) . '</span>';
									   }
								   } else {
									   echo htmlspecialchars( $link[ 'text' ] );
								   }
								   ?>
							</a>
						</li>
						<li class="divider-vertical"></li>
					<?php endforeach; ?>

					<?php
					break;
				case 'SHARE':
					?>

					<ul class="dropdown-menu liquiflow-menu-share">
						<?php
						$externalLink = $this->getSkin()->getTitle()->getFullURL();
						?>
						<li>
							<a data-type="twitter" onclick="Share.twitter( '<?php echo $externalLink; ?>', '<?php echo trim( strip_tags( $this->data[ 'title' ] ) ); ?>' )">
								<?php echo $this->makeFAIcon( 'share-twitter' ); ?> Twitter
							</a>
						</li>
						<li>
							<a data-type="facebook" onclick="Share.facebook( '<?php echo $externalLink; ?>', '<?php echo trim( strip_tags( $this->data[ 'title' ] ) ); ?>' )">
								<?php echo $this->makeFAIcon( 'share-facebook' ); ?> Facebook
							</a>
						</li>
						<li>
							<a data-type="reddit" onclick="Share.reddit( '<?php echo $externalLink; ?>', '<?php echo trim( strip_tags( $this->data[ 'title' ] ) ); ?>' )">
								<?php echo $this->makeFAIcon( 'share-reddit' ); ?></span> Reddit
							</a>
						</li>
						<li>
							<a data-type="qq" onclick="Share.qq( '<?php echo $externalLink; ?>', '<?php echo trim( strip_tags( $this->data[ 'title' ] ) ); ?>' )">
								<?php echo $this->makeFAIcon( 'share-qq' ); ?> Tencent QQ
							</a>
						</li>
						<li>
							<a data-type="vk" onclick="Share.vk( '<?php echo $externalLink; ?>', '<?php echo trim( strip_tags( $this->data[ 'title' ] ) ); ?>' )">
								<?php echo $this->makeFAIcon( 'share-vk' ); ?> VK
							</a>
						</li>
						<li>
							<a data-type="weibo" onclick="Share.weibo( '<?php echo $externalLink; ?>', '<?php echo trim( strip_tags( $this->data[ 'title' ] ) ); ?>' )">
								<?php echo $this->makeFAIcon( 'share-weibo' ); ?> Weibo
							</a>
						</li>
						<li class="hidden-lg">
							<a data-type="whatsapp" onclick="Share.whatsapp( '<?php echo $externalLink; ?>', '<?php echo trim( strip_tags( $this->data[ 'title' ] ) ); ?>' )">
								<?php echo $this->makeFAIcon( 'share-whatsapp' ); ?> WhatsApp
							</a>
						</li>
					</ul>

					<?php
					break;

				case 'TOOLBOX':
					$toolbox = $this->getToolbox();
					?>
					<ul class="dropdown-menu">
						<li class="dropdown-header"><?php echo $this->getMsg( 'liquiflow-general' )->text(); ?></li>
						<li><a href="<?php echo Title::newFromText( 'RecentChanges', NS_SPECIAL )->getLocalURL(); ?>"<?php echo ( $view == 'mobile' ? '' : ' ' . Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( 'n-recentchanges' ) ) ); ?>><?php echo $this->makeFAIcon( 't-recent-changes' ); ?> <?php echo $this->getMsg( 'recentchanges' )->text(); ?></a></li>
						<?php if ( $this->installedExtensions[ 'FlaggedRevs' ] ) { ?>
							<li><a href="<?php echo Title::newFromText( 'PendingChanges', NS_SPECIAL )->getLocalURL(); ?>"><?php echo $this->makeFAIcon( 't-pending-changes' ); ?> <?php echo $this->getMsg( 'revreview-current' )->text(); ?></a></li>
							<?php if ( in_array( 'editor', $this->getSkin()->getUser()->getEffectiveGroups() ) ) { ?><li><a href="<?php echo Title::newFromText( 'UnreviewedPages', NS_SPECIAL )->getLocalURL(); ?>"><?php echo $this->makeFAIcon( 't-unreviewed-pages' ); ?> <?php echo $this->getMsg( 'unreviewedpages' )->text(); ?></a></li><?php } ?>
						<?php } ?>

						<li><a href="<?php echo Title::newFromText( 'Random', NS_SPECIAL )->getLocalURL(); ?>"<?php echo ( $view == 'mobile' ? '' : ' ' . Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( 'n-randompage' ) ) ); ?>><?php echo $this->makeFAIcon( 't-random-page' ); ?> <?php echo $this->getMsg( 'randompage' )->text(); ?></a></li>

						<?php
						$extensionsMenu = [];
						Hooks::run( 'LPExtensionMenu', [ &$extensionsMenu, $this->getSkin() ] );
						if ( count( $extensionsMenu ) > 0 ) {
							echo '<li class="divider"></li>';
							foreach ( $extensionsMenu as $extensionKey => $extensionSpecialPage ) {
								echo '<li><a href="' . Title::newFromText( $extensionSpecialPage, NS_SPECIAL )->getLocalURL() . '">' . ( isset( $this->icons[ 'ext-' . $extensionKey ] ) ? '<span class="' . $this->icons[ 'ext-' . $extensionKey ] . '"></span> ' : '' ) . $this->getMsg( 'lpextmenu-' . $extensionKey )->text() . '</a></li>';
							}
						}
						?>

						<li class="divider"></li>
						<li class="dropdown-header"><?php echo $this->getMsg( 'liquiflow-tools-specific' )->text(); ?></li>
						<?php
						foreach ( $toolbox as $key => $item ) {
							if ( $view == 'mobile' ) {
								$item[ 'single-id' ] = $item[ 'id' ];
								$item[ 'id' ] = $item[ 'id' ] . "-mobile";
								$item[ 'tooltiponly' ] = true;
							}
							echo $this->makeListItem( $key, $item );
						}
						?>
					</ul>
					<?php
					break;
				case 'PERSONAL':
					if ( $this->getSkin()->getUser()->isLoggedIn() ) {
						$personalTools = $this->getPersonalTools();
						?>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" href="#">
								<?php echo $this->makeFAIcon( 't-menu-user' ); ?> <span class="hidden-sm"><?php echo $personalTools[ "userpage" ][ "links" ][ 0 ][ "text" ]; ?></span>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu multi-level">
								<li class="dropdown-header visible-sm"><?php echo $personalTools[ "userpage" ][ "links" ][ 0 ][ "text" ]; ?></li>
								<?php
								$personalTools[ "userpage" ][ "links" ][ 0 ][ "text" ] = $this->getMsg( 'filehist-user' );
								foreach ( $personalTools as $key => $item ) {
									echo $this->makeListItem( $key, $item );
								}
								?>

							</ul>
						</li>
					<?php } ?>

					<?php
					break;
				case 'ADMIN':
					?>
					<ul class="dropdown-menu">
						<?php
						if ( !$this->installedExtensions[ 'FlaggedRevs' ] ) {
							foreach ( $this->adminDropdown[ 'about' ] as $i => $adminDropDownItem ) {
								if ( $adminDropDownItem[ 'page' ] == 'Special:ValidationStatistics' ) {
									unset( $this->adminDropdown[ 'about' ][ $i ] );
								}
							}
						}
						$this->adminDropdown[ 'about' ] = array_values( $this->adminDropdown[ 'about' ] );
						$adminDropdownLength = sizeof( $this->adminDropdown );
						$count = 0;
						foreach ( $this->adminDropdown as $header => $items ) :
							$count++;
							?>
							<li class="dropdown-header"><?php echo $this->getMsg( $header )->text(); ?></li>
							<?php foreach ( $items as $key => $item ) : ?>
								<li id="<?php echo $item[ 'id' ] . ( ( $view == 'mobile' ) ? '-mobile' : '' ); ?>">
									<a href="<?php echo $this->data[ 'serverurl' ] . str_replace( '$1', $item[ 'page' ], $this->data[ 'articlepath' ] ); ?>">
										<?php echo $this->getMsg( $item[ 'title' ] )->text(); ?>
									</a>
								</li>
							<?php endforeach; ?>
							<?php if ( $count < $adminDropdownLength ) : ?>
								<li class="divider"></li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
					<?php
					break;

				case 'SEARCH':
					?>
					<li class="hidden-xs">
						<form action="<?php echo $this->text( 'wgScript' ); ?>" id="searchform" class="navbar-form" role="search">
							<div class="input-group">
								<input id="searchInput" type="search" accesskey="<?php echo $this->getMsg( 'accesskey-search' )->text(); ?>"
								       title="<?php echo $this->getMsg( 'liquiflow-search' )->params( $this->getMsg( 'liquiflow-brand' )->text() . ' ' . $this->config->get( 'LiquiFlowWikiTitle' ) )->text(); ?> [alt-shift-<?php echo $this->getMsg( 'accesskey-search' )->text(); ?>]" placeholder="<?php echo $this->getMsg( 'liquiflow-search-placeholder' )->text(); ?>"
								       name="search" autocomplete="off">
								<div class="input-group-btn">
									<button class="btn btn-default searchButton" type="submit" id="searchButton">
										<?php echo $this->makeFAIcon( 'top-menu-search-button' ); ?>
									</button>
								</div>
							</div>
						</form>
					</li>
					<?php
					break;
			}
		}
	}

	/**
	 * Makes a link, usually used by makeListItem to generate a link for an item
	 * in a list used in navigation lists, portlets, portals, sidebars, etc...
	 *
	 * @param string $key Usually a key from the list you are generating this
	 * link from.
	 * @param array $item Contains some of a specific set of keys.
	 *
	 * The text of the link will be generated either from the contents of the
	 * "text" key in the $item array, if a "msg" key is present a message by
	 * that name will be used, and if neither of those are set the $key will be
	 * used as a message name.
	 *
	 * If a "href" key is not present makeLink will just output htmlescaped text.
	 * The "href", "id", "class", "rel", and "type" keys are used as attributes
	 * for the link if present.
	 *
	 * If an "id" or "single-id" (if you don't want the actual id to be output
	 * on the link) is present it will be used to generate a tooltip and
	 * accesskey for the link.
	 *
	 * The keys "context" and "primary" are ignored; these keys are used
	 * internally by skins and are not supposed to be included in the HTML
	 * output.
	 *
	 * If you don't want an accesskey, set $item['tooltiponly'] = true;
	 *
	 * @param array $options Can be used to affect the output of a link.
	 * Possible options are:
	 *   - 'text-wrapper' key to specify a list of elements to wrap the text of
	 *   a link in. This should be an array of arrays containing a 'tag' and
	 *   optionally an 'attributes' key. If you only have one element you don't
	 *   need to wrap it in another array. eg: To use <a><span>...</span></a>
	 *   in all links use array( 'text-wrapper' => array( 'tag' => 'span' ) )
	 *   for your options.
	 *   - 'link-class' key can be used to specify additional classes to apply
	 *   to all links.
	 *   - 'link-fallback' can be used to specify a tag to use instead of "<a>"
	 *   if there is no link. eg: If you specify 'link-fallback' => 'span' than
	 *   any non-link will output a "<span>" instead of just text.
	 *
	 * @return string
	 */
	function makeLink( $key, $item, $options = [] ) {
		if ( isset( $item[ 'text' ] ) ) {
			$text = $item[ 'text' ];
		} else {
			$text = $this->getMsg( isset( $item[ 'msg' ] ) ? $item[ 'msg' ] : $key )->text();
		}

		if ( isset( $item[ 'single-id' ] ) && isset( $this->icons[ $item[ 'single-id' ] ] ) ) {
			$html = '<span class="' . $this->icons[ $item[ 'single-id' ] ] . '"></span> ' . htmlspecialchars( $text );
		} elseif ( isset( $item[ 'id' ] ) && isset( $this->icons[ $item[ 'id' ] ] ) ) {
			$html = '<span class="' . $this->icons[ $item[ 'id' ] ] . '"></span> ' . htmlspecialchars( $text );
		} else {
			$html = htmlspecialchars( $text );
		}

		if ( isset( $options[ 'text-wrapper' ] ) ) {
			$wrapper = $options[ 'text-wrapper' ];
			if ( isset( $wrapper[ 'tag' ] ) ) {
				$wrapper = [ $wrapper ];
			}
			while ( count( $wrapper ) > 0 ) {
				$element = array_pop( $wrapper );
				$html = Html::rawElement( $element[ 'tag' ], isset( $element[ 'attributes' ] ) ? $element[ 'attributes' ] : null, $html );
			}
		}

		if ( isset( $item[ 'href' ] ) || isset( $options[ 'link-fallback' ] ) ) {
			$attrs = $item;
			foreach ( [ 'single-id', 'text', 'msg', 'tooltiponly', 'context', 'primary',
			'tooltip-params', 'exists' ] as $k ) {
				unset( $attrs[ $k ] );
			}

			if ( isset( $attrs[ 'data' ] ) ) {
				foreach ( $attrs[ 'data' ] as $key => $value ) {
					$attrs[ 'data-' . $key ] = $value;
				}
				unset( $attrs[ 'data' ] );
			}

			if ( isset( $item[ 'id' ] ) && !isset( $item[ 'single-id' ] ) ) {
				$item[ 'single-id' ] = $item[ 'id' ];
			}

			$tooltipParams = [];
			if ( isset( $item[ 'tooltip-params' ] ) ) {
				$tooltipParams = $item[ 'tooltip-params' ];
			}

			if ( isset( $item[ 'single-id' ] ) ) {
				$tooltipOption = isset( $item[ 'exists' ] ) && $item[ 'exists' ] === false ? 'nonexisting' : null;

				if ( isset( $item[ 'tooltiponly' ] ) && $item[ 'tooltiponly' ] ) {
					$title = Linker::titleAttrib( $item[ 'single-id' ], $tooltipOption, $tooltipParams );
					if ( $title !== false ) {
						$attrs[ 'title' ] = $title;
					}
				} else {
					$tip = Linker::tooltipAndAccesskeyAttribs(
							$item[ 'single-id' ], $tooltipParams, $tooltipOption
					);
					if ( isset( $tip[ 'title' ] ) && $tip[ 'title' ] !== false ) {
						$attrs[ 'title' ] = $tip[ 'title' ];
					}
					if ( isset( $tip[ 'accesskey' ] ) && $tip[ 'accesskey' ] !== false ) {
						$attrs[ 'accesskey' ] = $tip[ 'accesskey' ];
					}
				}
			}
			if ( isset( $options[ 'link-class' ] ) ) {
				if ( isset( $attrs[ 'class' ] ) ) {
					$attrs[ 'class' ] .= " {$options[ 'link-class' ]}";
				} else {
					$attrs[ 'class' ] = $options[ 'link-class' ];
				}
			}
			$html = Html::rawElement( isset( $attrs[ 'href' ] ) ? 'a' : $options[ 'link-fallback' ], $attrs, $html );
		}

		return $html;
	}

}
