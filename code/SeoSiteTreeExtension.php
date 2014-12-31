<?php

class SeoSiteTreeExtension extends SiteTreeExtension {
	
	/**
	 * Specify page types that will not include the SEO tab
	 *
	 * @config
	 * @var array
	 */
	private static $excluded_page_types = array(
		'ErrorPage',
		'RedirectorPage',
		'VirtualPage'
	);

	private static $db = array(
		'MetaTitle' => 'Varchar(255)',
		'SEOPageSubject' => 'Varchar(255)',
		'SEOPageScore' => 'Int',
		
		'MetaRobotsNoIndex' => "Boolean",
		'MetaRobotsNoFollow' => "Boolean",
		'MetaRobotsNoCache' => "Boolean",
		'MetaRobotsNoSnippet' => "Boolean",

		'SEOFBdescription' => 'Text',
		'SEOFBPublisherlink' => 'Varchar(255)',
		'SEOFBAuthorlink' => 'Varchar(255)',
		'SEOGplusAuthorlink' => 'Varchar(255)',
		'SEOGplusPublisherlink' => 'Varchar(255)',
	);

//	public function updateFieldLabels(&$labels) {
//		$labels['MetaTitle'] = _t('SiteTree.METATITLE', "Title");
//	}
	
	/**
	 * updateCMSFields.
 	 * Update Silverstripe CMS Fields for SEO Module
 	 *  	 
	 * @param FieldList
	 * @return none
	 */
	public function updateCMSFields(FieldList $fields) {
		
		// exclude SEO tab from some pages
		if (in_array($this->owner->getClassName(), 
				Config::inst()->get("SeoSiteTreeExtension", "excluded_page_types"))) {
			return;
		}

		Requirements::css(SEO_DIR.'/css/seo.css');
		//Requirements::javascript(SEO_DIR.'/javascript/seo.js');
		
		// Get title template
		$sc = SiteConfig::current_site_config();
		if($sc->SEOTitleTemplate){
			$TitleTemplate = $sc->SEOTitleTemplate;
		} else {
			$TitleTemplate = "page_title + ' &raquo; ' + siteconfig_title";
		}
		
		$jsvars = array(
			"TitleTemplate" => $TitleTemplate,
		);
		Requirements::javascriptTemplate(SEO_DIR.'/javascript/seo.js', $jsvars);

		// better do this below in some init method? :
//		$this->getSEOScoreCalculation(); 
//		$this->setSEOScoreTipsUL();

		// lets create a new tab on top
		$fields->addFieldsToTab('Root.SEO', array(
			LiteralField::create('googlesearchsnippetintro', 
					'<h3>' . _t('SEO.SEOGoogleSearchPreviewTitle', 'Preview google search') . '</h3>'),
			LiteralField::create('googlesearchsnippet', 
					'<div id="google_search_snippet"></div>'),
			LiteralField::create('siteconfigtitle', 
					'<div id="ss_siteconfig_title">' . $this->owner->getSiteConfig()->Title . '</div>'),
		));

		// move Metadata field from Root.Main to SEO tab for visualising direct impact on search result
		$fields->removeByName('Metadata');
		
		// Create SEO tabs
		$fields->addFieldToTab("Root.SEO", new TabSet('Options'));
		$fields->findOrMakeTab('Root.SEO.Options.HelpAndSEOScore',_t('SEO.SEOScoreAndTips', 'SEO Score and Tips'));
		$fields->findOrMakeTab('Root.SEO.Options.Meta',_t('SEO.SEOMetaData', 'Metadata'));
		$fields->findOrMakeTab('Root.SEO.Options.Social',_t('SEO.Social', 'Social'));
		$fields->findOrMakeTab('Root.SEO.Options.Advanced',_t('SEO.Advanced', 'Advanced'));
		
		// ADD metadata fields
		$fields->addFieldsToTab('Root.SEO.Options.Meta', array(
			// METATITLE (re-add)
			TextField::create("MetaTitle", 
				_t('SEO.SEOMetaTitle', 'Meta title')
			)->setRightTitle(
				_t('SEO.SEOMetaTitleHelp', 
					'Name of the page, search engines use this as title of search results. If unset, the page title will be used.')
			),
			// METADESCR
			TextareaField::create("MetaDescription", $this->owner->fieldLabel('MetaDescription'))
				->setRightTitle(
					_t( 'SiteTree.METADESCHELP', 
						"Search engines use this content for displaying search results (although it will not influence their ranking).")
				)->addExtraClass('help'),
			// EXTRAMETA
			TextareaField::create("ExtraMeta",$this->owner->fieldLabel('ExtraMeta'))
				->setRightTitle(
					_t('SiteTree.METAEXTRAHELP', 
						"HTML tags for additional meta information. For example &lt;meta name=\"customName\" content=\"your custom content here\" /&gt;")
				)->addExtraClass('help')
			)
		);
		
		// ADD SEOTIPS fiels
//		$template = new SSViewer('Footer');
//		$seotips = $template->process($this->owner->customise(new ArrayData(array(
//			'ShowError' => true,
//		))));
		//Debug::dump( SSViewer::get_theme_folder() );
//		$pagehtml = $this->owner->renderwith(array('Footer'));
		
		$fields->addFieldsToTab('Root.SEO.Options.HelpAndSEOScore', array(
//			LiteralField::create('TitleHTML', '<h4>'.$fulltitle.'</h4>'),
			LiteralField::create('ScoreTitle', '<h4 class="seo_score">' . _t('SEO.SEOScore', 'SEO Score') . '</h4>'),
			LiteralField::create('Score', $this->getHTMLStars()),
			HiddenField::create('SEOPageScore', $this->owner->SEOPageScore),
			LiteralField::create('ScoreClear', '<div class="score_clear"></div>'),
			GoogleSuggestField::create("SEOPageSubject", 
					_t('SEO.SEOPageSubjectTitle', 'Subject of this page (required to view this page SEO score)'))
						
			)
		);   
		
		// ADD PageSubjectchecks if subject = defined
//		if (!empty($this->SEOPageSubject)) {
			$fields->addFieldsToTab('Root.SEO.Options.HelpAndSEOScore', array(
				LiteralField::create('SimplePageSubjectCheckValues', $this->getHTMLSimplePageSubjectTest())      
				)
			);
//		}
		
		// Add SEO score tips if below 10 -> add always, show/hide from js.
		//if ($this->seo_score < 10) {
			$fields->addFieldsToTab('Root.SEO.Options.HelpAndSEOScore', array(
				LiteralField::create('ScoreTipsTitle', 
						'<h4 class="seo_score">' . _t('SEO.SEOScoreTips', 'SEO Score Tips') . '</h4>'),
				LiteralField::create('ScoreTips', $this->getSEOScoreTipsUL())     
				)
			);    
		//} 
		
		$fields->addFieldsToTab('Root.SEO.Options.Social', array(
			// Facebook/social stuff
			TextField::create("SEOFBdescription", _t('SEO.SEOFBdescription', 'Facebook description'))
				->setRightTitle( _t('SEO.SEOFBdescriptionHelp', 'Wanneer je niet de metabeschrijving wil gebruiken voor het delen van berichten op Facebook, maar een andere omschrijving wil, schrijf het dan hier.')),
			// FB
			TextField::create("SEOFBAuthorlink", _t('SEO.SEOFBAuthorlink', 'Facebook author'))
				->setRightTitle( _t('SEO.SEOFBAuthorlinkHelp', 'Author Facebook PROFILE URL (incl. http://)')),
			TextField::create("SEOFBPublisherlink", _t('SEO.SEOFBPublisherlink', 'Facebook publisher'))
				->setRightTitle( _t('SEO.SEOFBPublisherlinkHelp', 'Publisher Facebook PAGE URL (incl. http://)')),
			// Gplus
			TextField::create("SEOGplusAuthorlink", _t('SEO.SEOGplusAuthorlink', 'Google+ author'))
				->setRightTitle( _t('SEO.SEOGplusAuthorlinkHelp', 'Author Google+ PROFILE URL (incl. http://)')),
			TextField::create("SEOGplusPublisherlink", _t('SEO.SEOGplusPublisherlink', 'Google+ publisher'))
				->setRightTitle( _t('SEO.SEOGplusPublisherlinkHelp', 'Publisher Google+ PAGE URL (incl. http://)'))
		));
		
		$fields->addFieldsToTab('Root.SEO.Options.Advanced', array(
			HeaderField::create('RobotsTitle', _t('SEO.SEORobotSettings','Page settings for search engines'), 4),
			CheckboxField::create('MetaRobotsNoIndex',_t('SEO.MetaRobotsNoIndex','Prevent indexing this page')),
			CheckboxField::create('MetaRobotsNoFollow',_t('SEO.MetaRobotsNoFollow','Prevent following any links from this page')),
			CheckboxField::create('MetaRobotsNoCache',_t('SEO.MetaRobotsNoCache','Prevent caching a version of this page')),
			CheckboxField::create('MetaRobotsNoSnippet', 
					_t('SEO.MetaRobotsNoSnippet','Prevent showing a snippet of this page in the search results (also prevents caching)')),
		));
		
		
	}
	
	public function MetaTags( & $tags ){
		
		$extraMeta = $this->owner->renderWith('SeoMeta');
		$tags .= $extraMeta;
		
		// TODO: move these extra HTTP headers to controller & use Silverstripe request object?
		// eg: $this->owner->request->addHeader('X-test','value');
		header( 'Link: <'.$this->owner->AbsoluteLink().'>; rel="canonical"' );
		if($seorobotsdirective = $this->SEOMetaRobotsSettings()){
			header( 'X-Robots-Tag: '.$seorobotsdirective );
		}
		
	}
	
	public function SEOMetaRobotsSettings(){
		
		$robots = array();
		if( !$this->owner->MetaRobotsNoIndex && !$this->owner->MetaRobotsNoFollow 
				&& !$this->owner->MetaRobotsNoCache && !$this->owner->MetaRobotsNoSnippet ) { 
			return false; 
		} // else return correct meta robots settings;
		$this->owner->MetaRobotsNoIndex ? $robots[] = 'noindex' : $robots[] = 'index';
		$this->owner->MetaRobotsNoFollow ? $robots[] = 'nofollow' : $robots[] = 'follow';
		if( $this->owner->MetaRobotsNoCache ){ $robots[] = 'noarchive, nocache'; }
		if( $this->owner->MetaRobotsNoSnippet ){ $robots[] = 'nosnippet'; }
		
		return implode(', ', $robots);
		
	}
	
	/**
	 * Return a breadcrumb trail to this page. Excludes "hidden" pages
	 * (with ShowInMenus=0). Adds extra microdata compared to 
	 *
	 * @param int $maxDepth The maximum depth to traverse.
	 * @param boolean $unlinked Do not make page names links
	 * @param string $stopAtPageType ClassName of a page to stop the upwards traversal.
	 * @param boolean $showHidden Include pages marked with the attribute ShowInMenus = 0 
	 * @return string The breadcrumb trail.
	 */
	public function SeoBreadcrumbs($separator = '&raquo;', $addhome = true, $maxDepth = 20, $unlinked = false, $stopAtPageType = false, $showHidden = false) {
		$page = $this->owner;
		$pages = array();
		
		while(
			$page  
 			&& (!$maxDepth || count($pages) < $maxDepth) 
 			&& (!$stopAtPageType || $page->ClassName != $stopAtPageType)
 		) {
			if($showHidden || $page->ShowInMenus || ($page->ID == $this->owner->ID)) { 
				$pages[] = $page;
			}
			
			$page = $page->Parent;
		}
		// add homepage;
		if($addhome){
			$pages[] = $this->owner->getHomepageCurrLang();
		}
		
		$template = new SSViewer('SeoBreadcrumbsTemplate');
		
		return $template->process($this->owner->customise(new ArrayData(array(
			'BreadcrumbSeparator' => $separator,
			'AddHome' => $addhome,
			'Pages' => new ArrayList(array_reverse($pages))
		))));
	}
	
//	public $score_criteria = array(
//		'pagesubject_defined' => false,
//		'pagesubject_in_title' => false,
//		'pagesubject_in_firstparagraph' => false,
//		'pagesubject_in_url' => false,
//		'pagesubject_in_metadescription' => false,
//		'numwords_content_ok' => false,       
//		'pagetitle_length_ok' => false,
//		'content_has_links' => false,
//		'page_has_images' => false,
//		'content_has_subtitles' => false
//	);
//
//	public $seo_score = 0;
//
//	public $seo_score_tips = '';
	
	
	/**
	 * getHTMLStars.
 	 * Get html of stars rating in CMS
 	 * 
	 * @TODO: could be placed in template file.. someday maybe
	 * @param none
	 * @return String $html
	 */
	public function getHTMLStars() {

		$html = '<div id="fivestar-widget">';
		for ($i = 1; $i <= 5; $i++) {
			$html .= '<div class="star"></div>';
		}
		$html .= '</div>';
		return $html;
		
	}
	
	/**
	* getHTMLSimplePageSubjectTest.
	* Get html of tips for the Page Subject
	* 
	* @TODO move to template. 
	* @param none
	* @return String $html
	*/
        public function getHTMLSimplePageSubjectTest() {

			$html = '<h4 id="simple_pagesubject_test_title">' 
					. _t('SEO.SEOSubjectCheckIntro', 'Your page subject was found in:'). '</h4>';
			$html .= '<ul id="simple_pagesubject_test">';
			$html .= '<li class="subjtest_pagetitle">' . _t('SEO.SEOSubjectCheckPageTitle', 'Page title:'). ' ';
				$html .= '<span class="subjtest subjtest_yes">' . _t('SEO.SEOYes', 'Yes') . '</span>';
				$html .= '<span class="subjtest subjtest_no">' . _t('SEO.SEONo', 'No') . '</span>';
				$html .= '</li>';
			$html .= '<li class="subjtest_firstpar">' . _t('SEO.SEOSubjectCheckFirstParagraph', 'First paragraph:'). ' ';
				$html .= '<span class="subjtest subjtest_yes">' . _t('SEO.SEOYes', 'Yes') . '</span>';
				$html .= '<span class="subjtest subjtest_no">' . _t('SEO.SEONo', 'No') . '</span>';
				$html .= '</li>';
			$html .= '<li class="subjtest_pagecontent">' . _t('SEO.SEOSubjectCheckPageContent', 'Page content:'). ' ';
				$html .= '<span class="subjtest subjtest_yes">' . _t('SEO.SEOYes', 'Yes') . '</span>';
				$html .= '<span class="subjtest subjtest_no">' . _t('SEO.SEONo', 'No') . '</span>';
				$html .= '</li>';        
			$html .= '<li class="subjtest_pageurl">' . _t('SEO.SEOSubjectCheckPageURL', 'Page URL:'). ' ';
				$html .= '<span class="subjtest subjtest_yes">' . _t('SEO.SEOYes', 'Yes') . '</span>';
				$html .= '<span class="subjtest subjtest_no">' . _t('SEO.SEONo', 'No') . '</span>';
				$html .= '</li>';    
			$html .= '<li class="subjtest_metatitle">' 
					. _t('SEO.SEOSubjectCheckPageMetaTitle', 'Page meta title:'). ' ';
				$html .= '<span class="subjtest subjtest_yes">' . _t('SEO.SEOYes', 'Yes') . '</span>';
				$html .= '<span class="subjtest subjtest_no">' . _t('SEO.SEONo', 'No') . '</span>';
				$html .= '</li>';
			$html .= '<li class="subjtest_metadescr">' 
					. _t('SEO.SEOSubjectCheckPageMetaDescription', 'Page meta description:'). ' ';
				$html .= '<span class="subjtest subjtest_yes">' . _t('SEO.SEOYes', 'Yes') . '</span>';
				$html .= '<span class="subjtest subjtest_no">' . _t('SEO.SEONo', 'No') . '</span>';
				$html .= '</li>';    

			$html .= '</ul>';
			return $html;

	}

	/**
	 * setSEOScoreTipsUL.
	 * Set SEO Score tips ul > li for SEO tips literal field, based on score_criteria
	 * 
	 * @param none
	 * @return none, set class string seo_score_tips with tips html
	 */
	public function getSEOScoreTipsUL() {
		
		$seo_score_tips = '<ul id="seo_score_tips">';
		foreach ($this->getSEOScoreTips() as $crit => $tip) {
				$seo_score_tips .= "<li id='$crit'>$tip</li>";
		}
		$seo_score_tips .= '</ul>';
		return $seo_score_tips;

	}
	
	/**
	 * getSEOScoreTips.
 	 * Get array of tips translated in current locale
 	 *  	 
	 * @param none
	 * @return array $score_criteria_tips Associative array with translated tips
	 */
	public function getSEOScoreTips() {
	
		$score_criteria_tips = array(
			'pagesubject_defined' => _t('SEO.SEOScoreTipPageSubjectDefined', 
					'Page subject is not defined for page'),
			'pagesubject_in_title' => _t('SEO.SEOScoreTipPageSubjectInTitle', 
					'Page subject is not in the title of this page'),
			'pagesubject_in_firstparagraph' => _t('SEO.SEOScoreTipPageSubjectInFirstParagraph', 
					'Page subject is not present in the first paragraph of the content of this page'),
			'pagesubject_in_url' => _t('SEO.SEOScoreTipPageSubjectInURL', 
					'Page subject is not present in the URL of this page'),
			'pagesubject_in_metatitle' => _t('SEO.SEOScoreTipPageSubjectInMetaTitle', 
					'Page subject is not in the meta title of this page'),
			'pagesubject_in_metadescription' => _t('SEO.SEOScoreTipPageSubjectInMetaDescription', 
					'Page subject is not present in the meta description of the page'),
			'numwords_content_ok' => _t('SEO.SEOScoreTipNumwordsContentOk', 
					'The content of this page is too short and does not have enough words. 
						Please create content of at least 300 words based on the Page subject.'),      
			'pagetitle_length_ok' => _t('SEO.SEOScoreTipPageTitleLengthOk', 
					'The title of the page is not long enough and should have a length of at least 40 characters.'),
			'content_has_links' => _t('SEO.SEOScoreTipContentHasLinks', 
					'The content of this page does not have any (outgoing) links.'),
			'page_has_images' => _t('SEO.SEOScoreTipPageHasImages', 
					'The content of this page does not have any images.'),
			'page_images_alttitle' => _t('SEO.SEOScoreTipImageAltTitle', 
					'<span id="seoimgtipalt">x</span> images missing ALT text or title'),
			'page_images_keywordalttitle' => _t('SEO.SEOScoreTipImageKeywordInAltTitle', 
				'Page subject missing in the ALT or title of <span id="seoimgtipkeyword">x</span> images on this page'),
			'content_has_subtitles' => _t('SEO.SEOScoreTipContentHasSubtitles', 
					'The content of this page does not have any subtitles'),
		);

		return $score_criteria_tips;
	}
	
	/* some language/locale helpers */
	public function Locale() {
		return i18n::get_locale();
	}

	public function ShortLocale() {
		return i18n::get_lang_from_locale(i18n::get_locale());
	}
	
	public function getHomepageCurrLang() {
		// @TODO: make this translatable compatible;
		//return $this->owner->get_homepage_link_by_locale($this->owner->get_current_locale()); 
		return SiteTree::get_by_link(RootURLController::get_default_homepage_link());
	}

}