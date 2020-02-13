# Live SEO optimizations for Silverstripe

*NOTE:* This branch is for Silverstripe 4 support, if you are looking for Silvestripe 3, use the "2" branch.

This module was largely rewritten from hubertusanton/silverstripe-seo to provide real-time feedback & SEO tips to CMS editors. Where the original module handles this in php (on page save), this module's feedback logic was written in javascript, hence 'Live SEO'. For the time being we have decided to let both modules co-exist, as they both do the job just fine, and this allows developers to pick the version that suits them best (php or javascript).

## Maintainer Contacts

* Bart van Irsel (Nickname: hubertusanton) [Dertig Media](http://www.30.nl)
* Michael van Schaik (Nickname: micschk) [Restruct](http://restruct.nl)
* Morven Lewis-Everley (Nickname: mo) [ilateral](http://ilateralweb.co.uk)

## Requirements

* SilverStripe 4.*

## Installation

Simply clone or download this repository, copy it into your SilverStripe installation folder, then run `dev/build?flush=all`.

### Composer

```
composer require: "micschk/silverstripe-liveseo": "dev-master"
```

## Documentation

This module helps the administrator of the Silverstripe website in getting good results in search engines.
A rating of the SEO of the current page helps the website editor creating good content around a subject
of the page which can be defined using a google suggest field.

The fields for meta data in pages will be moved to a SEO part by this module.
This is done for giving a realtime preview on the google search result of the page. 

In seo.yml config file you can specify which classes will NOT use the module. 
By default every class extending Page will use the SEO module.
Caution: The new master branch is not compatible with old releases see [this pull request](https://github.com/hubertusanton/silverstripe-seo/pull/10) from [jonom](https://github.com/jonom) (thanks!).
Please use tag 1.1 in old sites with the old config and tag 2.0 for new projects, but updating to 2.0 will also fix google suggest and
has some other fixes.

[View detailed documentation](docs/en/index.md)

## Screenshots

![ScreenShot](1.png)
![ScreenShot](2.png)

## Notes

Template tags:
- $SeoBreadcrumbs -> added microdata for breadcrumbs in SERP

## TODO's for next versions

- [ ] Check img tags for title and alt tags
- [ ] Add support for keyword synonyms
- [x] Option to set social networking title and images for sharing of page on facebook and google plus
- [ ] Create a google webmaster code config 
- [ ] Only check for outgoing links in content ommit links within site
- [ ] Translations to other languages
- [ ] Check for page subject usage in other pages
- [ ] Check how many times the page subject has been used and give feedback to user
- [x] Recalculate SEO Score in realtime with javascript without need to save first
- [x] Put html in cms defined in methods in template files
- [ ] Check extra added db fields/ many_many DataObjects for SEO score and make this configurable
- [ ] Resolve conflicts / update de.yml & es.yml