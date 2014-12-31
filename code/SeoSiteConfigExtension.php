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
		
		$template = new SSViewer('AdminSiteConfigSeoTips');

		$seotips = $template->process($this->owner->customise(new ArrayData(array(
			'ShowError' => true,
			'GSMactive' => $GSMactive,
			'GSMping' => $GSMping,
//				'Pages' => new ArrayList(array_reverse($pages))
		))));

		$fields->addFieldToTab("Root.Main", LiteralField::create('SEOtips', $seotips));
		
		$fields->addFieldToTab("Root.Main", $seotitlefield = TextField::create('SEOTitleTemplate')
				->SetRightTitle("For Google snippet preview, valid js expression eg:<br /> page_title + ' &raquo; ' + siteconfig_title <br />OR: (page_metadata_title ? page_metadata_title : page_title + ' &raquo; ' + siteconfig_title)<br /> (available vars: page_title, page_menutitle, page_metadata_title)"));
		
		// set default/initial value
		if(!$this->owner->SEOTitleTemplate){ 
			$seotitlefield->setValue("page_title + ' &raquo; ' + siteconfig_title");
		}
		
    }
}