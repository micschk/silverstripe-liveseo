<?php

namespace Restruct\Silverstripe\LiveSEO;

use Page;
use SilverStripe\Reports\Report;

class SeoReport extends Report
{
    public function title()
    {
        return 'SEO';
    }

    public function sourceRecords($params = null)
    {
        return Page::get()->sort('SEOPageScore');
    }

    public function columns()
    {
        $fields = array(
            "Title" => array(
            "title" => "Title", // todo: use NestedTitle(2)
            "link" => true,
        ),
        'MetaTitle' => 'Meta Title',
        'SEOPageSubject' => 'SEO Page Subject',
        'SEOPageScore' => 'SEO Score'
        );

        return $fields;
    }
}
