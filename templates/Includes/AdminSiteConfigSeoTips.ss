<div class="field">
	<label class="left" for="Form_EditForm_GACode">SEO status</label>
	<div class="middleColumn">

		<ul class="">
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
		</ul>

	</div>
</div>