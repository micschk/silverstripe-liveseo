<?php

/**
 * Optionally provide extra information for the SEO plugin to use to calculate a score from JS
 */
interface SeoInformationProvider
{
    /**
     * Provide a list of images.  Currently only the number of images is used
     * @return DataList Images, either objects, or URLs
     */
    public function getImagesForSeo();

    /**
     * Provide a list of links, e.g. from a related links relation.
     * Note currently the number of items only is used
     * @return {DataList} List of links
     */
    public function getLinksForSeo();
}
