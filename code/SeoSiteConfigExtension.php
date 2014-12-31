<?php
 
class SeoSiteConfigExtension extends DataExtension {
     
    private static $db = array(
    );
 
    public function updateCMSFields(FieldList $fields) {
		
		Requirements::css(SEO_DIR.'/css/seo.css');
		
		// check for Google Sitemaps module & notification;
		$GSMactive = Config::inst()->get('GoogleSitemap', 'enabled', Config::INHERITED);
		$GSMping = Config::inst()->get('GoogleSitemap', 'google_notification_enabled', Config::INHERITED);
		
		$template = new SSViewer('AdminSiteConfigSeoTips');

		$seotips = $template->process($this->owner->customise(new ArrayData(array(
			'ShowError' => true,
			'GSMactive' => $GSMactive,
			'GSMping' => $GSMping,
//				'Pages' => new ArrayList(array_reverse($pages))
		))));

		$fields->addFieldToTab("Root.Main", LiteralField::create('SEOtips', $seotips));
		
    }
}