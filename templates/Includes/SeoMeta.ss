<!-- Optimized with the Silverstripe SEO module $SEO.Version (score $SEOPageScore) -->
<link rel="canonical" href="$AbsoluteLink" />
<% if $SEOGplusAuthorlink %><link rel="author" href="$SEOGplusAuthorlink"/><% end_if %>
<% if $SEOGplusPublisherlink %><link rel="publisher" href="$SEOGplusPublisherlink"/><% end_if %>
<meta property="og:locale" content="$Locale"/>
<%--<!-- TODO: loop over alt languages with <meta property="og:locale:alternate" content="fr_FR" /> -->--%>
<meta property="og:type" content="article"/>
<meta property="og:title" content="<% if MetaTitle %>$MetaTitle<% else %>$Title<% end_if %>"/>
<meta property="og:description" content="<% if $SEOFBdescription %>$SEOFBdescription<% else %>$MetaDescription<% end_if %>"/>
<meta property="og:url" content="$AbsoluteLink"/>
<meta property="og:site_name" content="$SiteConfig.Title - $SiteConfig.Tagline"/>
<% if $SEOFBPublisherlink %><meta property="article:publisher" content="$SEOFBPublisherlink" /><% end_if %>
<% if $SEOFBAuthorlink %><meta property="article:author" content="$SEOFBAuthorlink" /><% end_if %>
<meta property="article:published_time" content="$Created.Rfc3339" />
<meta property="article:modified_time" content="$LastEdited.Rfc3339" />
<% if $SEOMetaRobotsSettings %><meta name="robots" content="$SEOMetaRobotsSettings" /><% end_if %>
<!-- / Silverstripe SEO module. -->