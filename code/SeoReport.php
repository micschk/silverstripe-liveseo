<?php

namespace Restruct\Silverstripe\LiveSEO;

use SS_Report;
use Page;

class SeoReport extends SS_Report
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
