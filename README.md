# Live SEO optimizations for Silverstripe

This module was largely rewritten from hubertusanton/silverstripe-seo to provide real-time feedback & SEO tips to CMS editors. Where the original module handles this in php (on page save), this module's feedback logic was written in javascript, hence 'Live SEO'. For the time being we have decided to let both modules co-exist, as they both do the job just fine, and this allows developers to pick the version that suits them best (php or javascript).

- Real-time in-CMS SEO page analysis with tips & score (moved to js, no need to save/refresh)
- Configurable Title template for search snippet visualisation (from within siteconfig)
- Multiple keyword support in no particular order (eg "dogs drinking beer" = "drinking my beer while watching the dog")
- Checks & suggests installs of other modules that are good for SEO (GoogleSitemap)
- Auto-set GoogleSitemap::google_notification_enabled(true) if available
- Added support for Facebook & Google+ author markup
- Added support for in-page meta robots settings
- Added some additional tests & tips from Yoast's WP SEO plugin
- Largely based on Bart's/30's Silverstripe SEO plugin (basically half of this plugin)
- Re-adds the 'MetaTitle' field that was removed in SilverStripe 3.1 (thanks to Loz Calver)

## Maintainer Contacts

* Bart van Irsel (Nickname: hubertusanton) [Dertig Media](http://www.30.nl)
* Michael van Schaik (Nickname: micschk) [Restruct](http://restruct.nl)
* Morven Lewis-Everley (Nickname: mo) [ilateral](http://ilateralweb.co.uk)

## Installation

Simply clone or download this repository, copy it into your SilverStripe installation folder, then run `dev/build?flush=all`.

### Composer

```
composer require: "micschk/silverstripe-liveseo": "dev-master"
```

## Requirements

* SilverStripe 3.*

## Documantation

[View detailed documentation](docs/en/index.md)

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