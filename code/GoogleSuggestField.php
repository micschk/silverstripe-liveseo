<?php
/**
 * Field which gets suggestions from google search
 */
class GoogleSuggestField extends FormField
{
    
    public function Field($properties = array())
    {
        Requirements::javascript(SEO_DIR . "/javascript/googlesuggestfield.js");

        $this->addExtraClass('text');

        return parent::Field($properties);
    }
}
