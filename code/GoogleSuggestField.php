<?php
/**
 * Field which gets suggestions from google search
 */
class GoogleSuggestField extends FormField
{
    
    public function Field($properties = array())
    {
        Requirements::customScript(<<<JS

 			(function($) {
				var edit_form_id = "Form_EditForm";
				var alt_edit_form_id = "Form_ItemEditForm";

				$.entwine('ss', function($){

					$('.cms-edit-form input#Form_EditForm_{$this->getName()}').entwine({
						// Constructor: onmatch
						onmatch : function() {
							if (!$("#" + edit_form_id ).length) {
								edit_form_id = alt_edit_form_id;
							}

							console.log("#" + edit_form_id + "_{$this->getName()}");

							$( "#" + edit_form_id + "_{$this->getName()}" ).autocomplete({
								source: function( request, response ) {
									$.ajax({
									  url: "//suggestqueries.google.com/complete/search",
									  dataType: "jsonp",
									  data: {
										  client: 'firefox',
									    q: request.term
									  },
									  success: function( data ) {
									    response( data[1] );
									  }
									});
								},
								minLength: 3
							});
	
						},
					});
				});

			})(jQuery);
JS
);

        $this->addExtraClass('text');

        return parent::Field($properties);
    }
}
