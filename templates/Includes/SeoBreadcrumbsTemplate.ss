<% if $Pages %>
<nav itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
	<% loop $Pages %>
		<% if not $First %><span itemprop="child" itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><% end_if %>
			<a href="$Link" class="breadcrumb-$Pos $LinkingMode $FirstLast" itemprop="url">
				<span itemprop="title">$MenuTitle.XML</span>
			</a> <% if not $Last %>$Top.BreadcrumbSeparator<% end_if %>
	<% end_loop %>
	<% loop $Pages %>
		<% if not $First %></span><% end_if %>
	<% end_loop %>
</nav>
<% end_if %>