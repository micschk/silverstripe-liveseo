<?php
 
class SeoSiteConfigExtension extends DataExtension {
     
    private static $db = array(
    );
 
    public function updateCMSFields(FieldList $fields) {
		
		Requirements::css(SEO_DIR.'/css/seo.css');
		
		// check for Google Sitemaps module & notification;
		$SMactive = Config::inst()->get('GoogleSitemaps', 'enabled', Config::INHERITED);
		$SMping = Config::inst()->get('GoogleSitemaps', 'google_notification_enabled');
		if( ! $SMactive || ! $SMping ){
			
			$template = new SSViewer('AdminSiteConfigSeoTips');
		
			$seotips = $template->process($this->owner->customise(new ArrayData(array(
				'ShowError' => true,
//				'Pages' => new ArrayList(array_reverse($pages))
			))));
			
			$fields->addFieldToTab("Root.Main", LiteralField::create('SEOtips', $seotips));
		}
		
    }
}