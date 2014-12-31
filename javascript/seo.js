
(function($) {

	$.entwine('ss', function($){

		$('input[name="MetaTitle"], textarea[name="MetaDescription"], input[name="SEOPageSubject"]').entwine({
			// Constructor: onmatch
			onmatch: function(){
				set_preview_google_search_result();
				calc_score_n_tips();
			},
			onunmatch: function(){},
			onkeyup : function() {
				set_preview_google_search_result();
				calc_score_n_tips();
			},
			// extra: for live update on selecting a suggestion
			onchange : function() {
				set_preview_google_search_result();
				calc_score_n_tips();
			},
		});

	});
	
	// when switching tabs from editing content to SEO tab, update;
	$('#tab-Root_SEO').click(function(){
		set_preview_google_search_result();
		calc_score_n_tips();
	});
	
	function calc_score_n_tips() {

		// calculate score & set tips;
		var score = calc_score();

		// visualise with star rating;
		set_score(score);

	}

	function set_score( score ){
		// score between 0-100;
		for(var i=1; i<=5; i++){
			var roundedscore = Math.round(score/10)*10;
			// for each star, set the score;
			if(roundedscore >= i*20){
				$('#fivestar-widget .star:eq('+(i-1)+')').removeClass('half on').addClass('on');
			} else if(roundedscore >= i*20-10){
				$('#fivestar-widget .star:eq('+(i-1)+')').addClass('half on');
			} else {
				$('#fivestar-widget .star:eq('+(i-1)+')').removeClass('half on');
			}
		}
		// save into form for saving into database
		$('input[name="SEOPageScore"]').val(score);
	}

	function set_preview_google_search_result() {

		var page_url_basehref = $('input[name="URLSegment"]').attr('data-prefix'),
			page_url_segment = $('input[name="URLSegment"]').val(),
			page_title       = ($('#Form_EditForm_MetaTitle').val() || $('#Form_EditForm_Title').val()),
			page_menutitle  = $('#Form_EditForm_MenuTitle').val(),
			page_content     = $('textarea#Form_EditForm_Content').val(),
			page_metadata_title = $('#Form_EditForm_MetaTitle').val(),
			page_metadata_description = $('#Form_EditForm_MetaDescription').val(),
			siteconfig_title = $('#ss_siteconfig_title').html();

			// build google search preview
			var google_search_title = $TitleTemplate;
			var google_search_url = page_url_basehref + page_url_segment;
			var google_search_description = page_metadata_description;

			var search_result_html = '';
			search_result_html += '<h3>' + google_search_title + '</h3>';
			search_result_html += '<div class="google_search_url">' + google_search_url + '</div>';
			search_result_html += '<p>' + google_search_description + '</p>';

			$('#google_search_snippet').html(search_result_html);
	}
	
	// prototype to check if a string contains multiple words (in no particular order)
	String.prototype.containsallwords = function(word_str) {
		// Todo: split into synonyms on ',' and allow any, eg 'dog feed, pet food, animal snack'
		var word_arr = word_str.split(" ");
		for (var i = 0; i < word_arr.length; i++) {
			if (this.indexOf(word_arr[i]) == -1) {
				return false;
			}
		}
		return true;
	};

	function calc_score(){

		// get references to all relevant form fields;
		var SEOPageSubject = $('input[name="SEOPageSubject"]').val().toLowerCase();
		var PageTitle = $('input[name="Title"]').val().toLowerCase();
		var EditorContent = (tinyMCE.getInstanceById("Form_EditForm_Content")!==undefined? // already initiated, else take field value (upon first load)
				tinyMCE.getInstanceById("Form_EditForm_Content").getContent() :
						$('textarea[name="Content"]').val());
				//console.log(EditorContent);
		var FirstParagraph = $(EditorContent).filter('p').first().text().toLowerCase();
		var PageContent = $(EditorContent).text().toLowerCase();
		var PageURL = $('input[name="URLSegment"]').attr('data-prefix').toLowerCase();
		var PageMetaTitle = $('input[name="MetaTitle"]').val().toLowerCase();
		var PageMetaDescription = $('textarea[name="MetaDescription"]').val().toLowerCase();

		var score = 0;

		// check for page subject (& cancel all if we don't have one);
		if($.trim(SEOPageSubject)==''){ 
			// hide simple_pagesubject_test & simple_pagesubject_test_title
			$('#simple_pagesubject_test, #simple_pagesubject_test_title').hide();
			return 0; 
		} else { 
			score += 10;
			// hide pagesubject tip
			$('#pagesubject_defined').hide();
			// show simplepagesubjecttests
			$('#simple_pagesubject_test, #simple_pagesubject_test_title').show();
		}
		
		// TODO: hide simple_pagesubject_tests at some point... (?)
		//$('#simple_pagesubject_test, #simple_pagesubject_test_title').hide();

		// check pagesubject in title
		if(PageTitle.containsallwords(SEOPageSubject)){
			score += 10;
			$('.subjtest_pagetitle .subjtest').hide().filter('.subjtest_yes').show();
			$('#pagesubject_in_title').hide();
		} else {
			$('.subjtest_pagetitle .subjtest').hide().filter('.subjtest_no').show();
			$('#pagesubject_in_title').show();
		}

		// check pagesubject in first paragraph
		if(FirstParagraph.containsallwords(SEOPageSubject)){
			score += 10;
			$('.subjtest_firstpar .subjtest').hide().filter('.subjtest_yes').show();
			$('#pagesubject_in_firstparagraph').hide();
		} else {
			$('.subjtest_firstpar .subjtest').hide().filter('.subjtest_no').show();
			$('#pagesubject_in_firstparagraph').show();
		}

		// check pagesubject in content (just an extra check, score calculated in first paragraph)
		if(PageContent.containsallwords(SEOPageSubject)){
			$('.subjtest_pagecontent .subjtest').hide().filter('.subjtest_yes').show();
		} else {
			$('.subjtest_pagecontent .subjtest').hide().filter('.subjtest_no').show();
		}

		// check pagesubject in url
		if(PageURL.containsallwords(SEOPageSubject)){
			score += 10;
			$('.subjtest_pageurl .subjtest').hide().filter('.subjtest_yes').show();
			$('#pagesubject_in_url').hide();
		} else {
			$('.subjtest_pageurl .subjtest').hide().filter('.subjtest_no').show();
			$('#pagesubject_in_url').show();
		}

		// check pagesubject in metatitle
		if(PageMetaTitle.containsallwords(SEOPageSubject)){
			score += 5;
			$('.subjtest_metatitle .subjtest').hide().filter('.subjtest_yes').show();
			$('#pagesubject_in_metatitle').hide();
		} else {
			$('.subjtest_metatitle .subjtest').hide().filter('.subjtest_no').show();
			$('#pagesubject_in_metatitle').show();
		}

		// check pagesubject in metadescription
		if(PageMetaDescription.containsallwords(SEOPageSubject)){
			score += 5;
			$('.subjtest_metadescr .subjtest').hide().filter('.subjtest_yes').show();
			$('#pagesubject_in_metadescription').hide();
		} else {
			$('.subjtest_metadescr .subjtest').hide().filter('.subjtest_no').show();
			$('#pagesubject_in_metadescription').show();
		}

		// check number of words of content
		if(PageContent.split(' ').length>=300){
			score += 10;
			$('#numwords_content_ok').hide();
		} else {
			$('#numwords_content_ok').show();
		}

		// check pagetitle length
		if(PageTitle.length>=35){
			score += 10;
			$('#pagetitle_length_ok').hide();
		} else {
			$('#pagetitle_length_ok').show();
		}

		// check subtitles
		if(EditorContent.search('<h')>=0){
			score += 10;
			$('#content_has_subtitles').hide();
		} else {
			$('#content_has_subtitles').show();
		}

		// check links
		if(EditorContent.search('<a')>=0){
			score += 10;
			$('#content_has_links').hide();
		} else {
			$('#content_has_links').show();
		}

		// check images
		if(EditorContent.search('<img')>=0){
			score += 10;
			$('#page_has_images').hide(); // hide "page doesn't have images"
		} else {
			$('#page_has_images').show(); // show "page doesn't have images"
		}
		
		// show info about images: x images miss alt or title
		var missingaltsortitles = 0;
		$(EditorContent).find('img').each(function(){ // count missing titles or alts
			if( $.trim($(this).attr('alt')).length<5 || $.trim($(this).attr('title')).length<5 ) { missingaltsortitles++; }
		});
		if(missingaltsortitles){
			$('#page_images_alttitle').show().find('span').text(missingaltsortitles);
		} else { // or hide altogether
			$('#page_images_alttitle').hide();
		}
		// show info about images: x images miss keyword in alt AND title
		var missingaltandtitlekeywords = 0;
		$(EditorContent).find('img').each(function(){ // count missing titles or alts (title & alt concatenated)
			if( ( $(this).attr('alt')+" "+$(this).attr('title') ).toLowerCase().search(SEOPageSubject) == -1 ) { 
				missingaltandtitlekeywords++; }
		});
		if(missingaltandtitlekeywords){
			$('#page_images_keywordalttitle').show().find('span').text(missingaltandtitlekeywords);
		} else { // or hide altogether
			$('#page_images_keywordalttitle').hide();
		}
		
		
		// show/hide h4.seo_score if no tips shown...
		// custom filter because :visible will return false if parent is hidden (which happens a lot because of tabs)
		if($("#seo_score_tips li").filter(function() { return $(this).css('display') != 'none'; }).length){
			$("h4.seo_score").show();
		} else {
			$("h4.seo_score").hide();
		}
		
		return score;
		
	}
	
})(jQuery);

