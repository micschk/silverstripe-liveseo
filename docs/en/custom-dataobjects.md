# Using custom DataObjects
If you have created a custom data object that you want to make SEO friendly (for examnple Products in
a commerce system) then you can by following these steps:

## Ensure you have the correct fields
In order for this module works correctly, your object will need the following DB fields:

* Title
* Content
* URLSegment
* MetaDescription


## Add a getSiteConfig method to your dataobject.
The SEO module needs to access the SiteConfig associated with the current object, so you need to ensure you add the
following to your object class (this is taken directly from SiteTree.

```php

class YourSEODataObject extends DataObject
{

    ...
    
    /**
	 * Stub method to get the site config, unless the current class can provide an alternate.
	 *
	 * @return SiteConfig
	 */
	public function getSiteConfig() {

		if($this->hasMethod('alternateSiteConfig')) {
			$altConfig = $this->alternateSiteConfig();
			if($altConfig) return $altConfig;
		}

		return SiteConfig::current_site_config();
	}
    
    ...
}
```

## Add SEO SeoSiteTreeExtension as an extension to your DataObject
We now need to extend your current object to utilise the SeoSiteTreeExtension, you can do this in two ways:

### Add extension directly to your object:

```php

class YourSEODataObject extends DataObject
{

    ...
    
    private static $extensions = array(
        "SeoSiteTreeExtension"
    );
    
    ...
}
```

### Add extension via config.yml

```
YourSEODataObject:
  extensions:
    - SeoSiteTreeExtension
```

## Re-build database and flush
Finally run a dev/build?flush=1 and then ensure you flush the admin interface (with ?flush=1).