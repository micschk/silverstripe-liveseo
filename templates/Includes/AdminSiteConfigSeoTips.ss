<div class="field">
	<label class="left">SEO status</label>
	<div class="middleColumn">

		<ul class="SEOadvice">
			<li>
				<% if not $GSMactive %>
					<span class="seo_score_img bad"></span>
					<%t SEO.GoogleSitemapModuleInstall "De Google Sitemap Module is niet geïnstalleerd" %>
				<% else %>
					<span class="seo_score_img good"></span>
					<%t SEO.GoogleSitemapModuleActive "Google Sitemap is actief" %>
				<% end_if %>
			</li>

			<li>
				<% if not $GSMping %>
					<span class="seo_score_img bad"></span>
					<%t SEO.GoogleNotificationInactive "Google wordt niet ingelicht wanneer nieuwe pagina's gepubliceerd worden (configureer google_notification_enabled)" %>
				<% else %>
					<span class="seo_score_img good"></span>
					<%t SEO.GoogleNotificationActive "Google wordt ingelicht wanneer nieuwe pagina's worden gepubliceerd" %>
				<% end_if %>
			</li>

			<li>
				<% if not $RedirActive %>
					<span class="seo_score_img poor"></span>
					<%t SEO.RedirectsModuleInactive "Installeer eventueel de redirected URLs module om handmatig redirects in te kunnen stellen." %>
				<% else %>
					<span class="seo_score_img good"></span>
					<%t SEO.RedirectsModuleActive "Redirected URLs module is actief. Hiermee kunnen handmatig redirects worden ingesteld." %>
				<% end_if %>
			</li>
		</ul>

	</div>
</div>