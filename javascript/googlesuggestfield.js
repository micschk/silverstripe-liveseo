(function($) {
    var edit_form_id = "Form_EditForm";
    var alt_edit_form_id = "Form_ItemEditForm";

    $.entwine('liveseo', function($){
        $('.cms-edit-form input.googlesuggest').entwine({
            // Constructor: onmatch
            onmatch : function() {
                if (!$("#" + edit_form_id ).length) {
                    edit_form_id = alt_edit_form_id;
                }

                var field_id = $(this).attr("ID");

                $( "#"+ field_id ).autocomplete({
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