<?php

class SeoSiteConfigExtension extends DataExtension {

    private static $db = array(
		'SEOTitleTemplate' => 'Varchar(255)'
    );

    public function updateCMSFields(FieldList $fields) {

		Requirements::css(SEO_DIR.'/css/seo.css');

		// check for Google Sitemaps module & notification;
		$GSMactive = Config::inst()->get('GoogleSitemap', 'enabled', Config::INHERITED);
		$GSMping = Config::inst()->get('GoogleSitemap', 'google_notification_enabled', Config::INHERITED);

		// check for Redirectmanager
		$RedirActive = Object::has_extension("ContentController", "RedirectedURLHandler");

		//$template = new SSViewer('AdminSiteConfigSeoTips');
		//$seotips = $template->process($this->owner->customise(new ArrayData(array(
		$seotips = $this->owner->customise(new ArrayData(array(
			//'ShowError' => true,
			'GSMactive' => $GSMactive,
			'GSMping' => $GSMping,
			'RedirActive' => $RedirActive,
//				'Pages' => new ArrayList(array_reverse($pages))
		//))));
			)))->renderWith('AdminSiteConfigSeoTips');

		$fields->addFieldToTab("Root.Main", LiteralField::create('SEOtips', $seotips));

		// SEOTITLE
		// parse out the title tag as used by the theme;
		$loader = SS_TemplateLoader::instance();
		$theme = Config::inst()->get('SSViewer', 'theme');
		$foundpath  = $loader->findTemplates("main/Page", $theme); // TODO: this is a guess...
		$path = $foundpath['main'];
		$templatecode = file_get_contents($path);
		if(strpos($templatecode, '<title>')){
			$templatetag = explode('<title>', $templatecode );
			$templatetag = array_pop( $templatetag );
			$templatetag = explode('</title>', $templatetag );
			$templatetag = array_shift( $templatetag );
		}
//		$template = SSViewer::fromString($titlehtml);
//		$fulltitle = $template->process($this->owner);
		if($templatetag) { $templatetag = "<br />Current template title tag: ".$templatetag;
		} else { $templatetag = ""; }

		$fields->addFieldToTab("Root.Main", $seotitlefield = TextField::create('SEOTitleTemplate')
				->SetRightTitle("For SEO preview (valid js expression, available vars: page_title, page_menutitle, page_metadata_title), eg:<br /> page_title + ' &raquo; ' + siteconfig_title [OR] (page_metadata_title ? page_metadata_title : page_title)".$titlehtml));

		// set default/initial value
		if(!$this->owner->SEOTitleTemplate){
			$seotitlefield->setValue("page_title + ' &raquo; ' + siteconfig_title");
		}
    }
}
