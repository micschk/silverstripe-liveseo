<div class="field">
	<label class="left">SEO status</label>
	<div class="middleColumn">

		<ul class="SEOadvice">
			<% if not $GSMactive %>
			<li><span class="seo_score_img bad"></span> De Google Sitemap Module is niet geïnstalleerd
			<% else %>
			<li><span class="seo_score_img good"></span> Google Sitemap is actief
			<% end_if %>

			<% if not $GSMping %>
			<li><span class="seo_score_img bad"></span> Google wordt niet ingelicht wanneer nieuwe pagina's gepubliceerd worden (configureer google_notification_enabled)</td>
			</li>
			<% else %>
			<li><span class="seo_score_img good"></span> Google wordt ingelicht wanneer nieuwe pagina's worden gepubliceerd</li>
			<% end_if %>
			
			<% if not $RedirActive %>
			<li>$RedirMactive<span class="seo_score_img poor"></span> Installeer eventueel de <a href="https://github.com/silverstripe-labs/silverstripe-redirectedurls" target="_blank">Redirected URLs module</a> om handmatig redirects in te kunnen stellen. <br />Het CMS verwijst zelf al pagina's door waarvan de URL is aangepast. <br />Met deze module kunnen ook oude URLs of verkorte URLs worden doorverwezen. </td>
			</li>
			<% else %>
			<li><span class="seo_score_img good"></span> Redirected URLs module is actief. <br />Hiermee kunnen handmatig redirects worden ingesteld. Het CMS verwijst zelf al pagina's door waarvan de URL is aangepast. <br />Met deze module kunnen ook oude URLs of verkorte URLs worden doorverwezen.</li>
			<% end_if %>
		</ul>

	</div>
</div>