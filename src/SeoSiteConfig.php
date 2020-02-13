<?php

namespace Restruct\Silverstripe\LiveSEO;

use SilverStripe\View\ArrayData;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\TextareaField;
use Wilr\GoogleSitemaps\GoogleSitemap;
use SilverStripe\Core\Manifest\ModuleLoader;

/**
 * SeoSiteConfig
 * adds site-wide settings for SEO
 */
class SeoSiteConfig extends DataExtension {

	private static $db = array(
        'SEOTitleTemplate' => 'Varchar(255)',
		'GoogleWebmasterMetaTag' => 'Varchar(512)'
	);  

	/**
	 * updateCMSFields.
 	 * Update Silverstripe CMS Fields for SEO Module
 	 *
	 * @param FieldList
	 * @return none
	 */
	public function updateCMSFields(FieldList $fields) {
		$manifest = ModuleLoader::inst()->getManifest();
		$GSMactive = false;
		$GSMping = false;
		$RedirActive = false;

		// check for Google Sitemaps module & notification;
		if ($manifest->moduleExists('wilr/silverstripe-googlesitemaps')) {
			$GSMactive = Config::inst()->get(GoogleSitemap::class, 'enabled');
			$GSMping = Config::inst()->get(GoogleSitemap::class, 'google_notification_enabled');
		}

		// check if Redirectmanager and CMS are installed
		if ($manifest->moduleExists('silverstripe/cms') && $manifest->moduleExists('silverstripe/redirectedurls')) {
			$RedirActive = true;
		}

		$fields->addFieldToTab(
			"Root.SEO",
			LiteralField::create(
				'SEOtips',
				$this->owner->customise(
					ArrayData::create(
						array(
							'GSMactive' => $GSMactive,
							'GSMping' => $GSMping,
							'RedirActive' => $RedirActive
						)
					)
				)->renderWith('AdminSiteConfigSeoTips')
			)
		);

        $fields->addFieldToTab(
			"Root.SEO",
            TextareaField::create("GoogleWebmasterMetaTag", _t('SEO.SEOGoogleWebmasterMetaTag', 'Google webmaster meta tag'))
            ->setRightTitle(
                _t(
                    'SEO.SEOGoogleWebmasterMetaTagRightTitle',
                    "Full Google webmaster meta tag For example &lt;meta name=\"google-site-verification\" content=\"hjhjhJHG12736JHGdfsdf\" /&gt;"
                )
            )
		);

        $fields->addFieldToTab(
			"Root.SEO",
			$seotitlefield = TextField::create('SEOTitleTemplate')
                ->setRightTitle(
					"For SEO preview (valid js expression, available vars: page_title, page_menutitle, '.
					'page_metadata_title), eg:<br /> page_title + ' &raquo; ' +
					siteconfig_title [OR] (page_metadata_title ? page_metadata_title : page_title)"
				)
		);

        // set default/initial value
        if (!$this->owner->SEOTitleTemplate) {
            $seotitlefield->setValue("page_title + ' &raquo; ' + siteconfig_title");
		}
    }
}
