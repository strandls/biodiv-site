<?php
// $Id: page.tpl.php,v 1.18 2008/01/24 09:42:53 goba Exp $
?>
<!DOCTYPE html>
 <html xmlns:fb="http://www.facebook.com/2008/fbml"
                    xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>"
                    lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
<head>
	<title>Western Ghats Portal - Maps</title>
	<link rel="shortcut icon" href="/sites/all/themes/wg/images/favicon.png" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="/sites/all/themes/wg/css/am.css" />
	<link type="text/css" rel="stylesheet" media="all" href="/sites/all/themes/wg/css/jquery-ui-1.8.13.custom.css"/>
	<link type="text/css" rel="stylesheet" media="all" href="/sites/all/themes/wg/css/styles.css"/>
	<script type="text/javascript" src="/sites/all/themes/wg/scripts/jquery-1.6.1.min.js"></script>
    	<script type="text/javascript" src="/sites/all/themes/wg/scripts/jquery-ui-1.8.13.custom.min.js"></script>
	<script type="text/javascript" src="/sites/all/themes/wg/js/wg.js"></script>
	<?php print $closure ?>
</head>
    
<body id="mapPage">

<div id="header">
<!-- Logo -->
  <div id="logo">
    <a href="<?php print check_url($front_page)?>">
      <img src="<?php print check_url($front_page)?><?php print check_url(path_to_theme())?>/images/map-logo.gif" alt="western ghats" id="wg_logo"/>
    </a>
  </div>
<!-- Logo ends -->

<!-- Main menu -->
			<div id="menus">
				<div id="mainMenu">
					<?php if($main_menu): ?>
					<?php print $main_menu?>
					<?php endif; ?>
				</div>
				<div id="userMenu">
					<ul>
					<?php global $user; ?>
					<?php if($user->uid): ?>
					<li>Welcome <?php echo l($user->name, "user");?></li>
						<?php if($is_admin): ?>
						<li><a href="<?php print check_url($front_page);?>admin">Administration</a></li>
						<?php endif; ?>
					<li class="last"><a href="<?php print check_url($front_page);?>logout">Logout</a></li>
					<?php else: ?>
					<li><a onclick="show_login_dialog();return false;"  href="<?php print check_url($front_page);?>user">Login</a></li>
					<li class="last"><a href="<?php print check_url($front_page);?>user/register">Register</a></li>
					<?php endif; ?>
					</ul>
				</div>
			</div>
			<!-- Main menu ends -->

<div id="top_nav_bar">
<ul>
<li id="maps_nav_link" title="Maps" onclick="location.href='<?php print check_url($front_page)?>map'">Maps</li>
<li id="checklists_nav_link" title="Checklists" onclick="location.href='<?php print check_url($front_page)?>browsechecklists'">Checklists</li>
<li id="collaborate_nav_link" title="Collaborate" onclick="location.href='<?php print check_url($front_page)?>collaborate-wg'">Collaborate</li>
<li id="species_nav_link" title="Species" onclick="location.href='<?php print check_url($front_page)?>biodiv/species/list'">Species</li>
<li id="themes_nav_link" title="Themes" onclick="location.href='<?php print check_url($front_page)?>themepages/list'">Themes</li>
<li id="about_nav_link" title="About" onclick="location.href='<?php print check_url($front_page)?>about/western-ghats'">About</li>
</ul>
</div>


<div id="controls-bar">
<ul>
<li title="Remove all added layers" class="controls_label" onclick="resetMap();">Reset</li>
</ul>
</div>

</div>

<div id="shadow"></div>

<div id="main_wrapper">

<div id="parent_panel">
<div>
<a href="#" id="panel_show_bttn" style="display:none;"></a>
</div>
<div id="panel">
<div id="top_bar">
<ul id="top_bar_links">
<li><a href="#" id="explore_bttn">Explore</a></li>
<li><a href="#" id="search_bttn">Search</a></li>
<!--li><a href="#" id="share_bttn">Share</a></li-->
<li><a href="#" id="selected_layers_bttn">Selected layers</a></li>
<li><a href="#" id="selected_features_bttn">Selected features</a></li>
</ul>


<a href="#" id="panel_hide_bttn"></a>
</div>
<div id="feature_info_panel" class="side_panel" style="overflow:auto; display:none;"></div>
<div id="layers_list_panel" class="side_panel"></div>
<div id="search_panel" class="side_panel" style="display:none;">
<div id="search_box"></div>
<div id="search_results_panel"></div>
</div>
<div id="share_panel" class="side_panel" style="display:none;"></div>
<div id="selected_layers_panel" class="side_panel" style="display:none;"></div>
</div>
</div>
<div id="map"></div>
</div>

<div>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
<script type="text/javascript" src="/sites/all/themes/wg/scripts/OpenLayers-2.10/OpenLayers.js"></script> 
<script type="text/javascript" src="/sites/all/themes/wg/scripts/jquery-1.6.1.min.js"></script> 
<script type="text/javascript" src="/sites/all/themes/wg/scripts/jquery-ui-1.8.13.custom.min.js"></script> 
<script type="text/javascript" src="/sites/all/themes/wg/scripts/cookie-chef.js"></script> 
<script type="text/javascript" src="/sites/all/themes/wg/scripts/am.js"></script> 
<script type="text/javascript" src="/sites/all/themes/wg/scripts/map-search.js"></script> 
<script type="text/javascript" src="/sites/all/themes/wg/scripts/mapapp.js"></script> 
<script id="text/javascript">
$(document).ready(function() {
var mapOptions = {
    popup_enabled: false,
    toolbar_enabled: true,
    baselayers_switcher_enabled: true,
    feature_info_panel_div: 'feature_info_panel'
};


var layerOptions = [
    {
        title:'<?php print $_GET['title']?>',
        layers:'<?php print $_GET['layers']?>',
        styles:'<?php print $_GET['styles']?>',
	cql_filter:'<?php print $_GET['cql_filter']?>',
        opacity:0.7
    }
]


var map = showMap('map', mapOptions, layerOptions);
showLayersExplorer('layers_list_panel', map);

if (layerOptions[0].layers === ''){
    loadLayersFromCookie();
}

addSearchBox('search_box');
addSearchResultsPanel('search_results_panel');

var cache = {},
        lastXhr;
$( "#map_search_text_field" ).autocomplete({
        minLength: 2,
        source: function( request, response ) {
            var term = request.term;
            if ( term in cache ) {
                response( cache[ term ] );
                return;
            }
    
            var q = "wt=json&q=" + term;
            
            $.ajax({
                url: "/wgp_maps/suggest",
                data: q,
                type: 'GET',
                async: false,
                cache: false,
                timeout: 30000,
                dataType: 'text',
                error: function(){
                    return true;
                },
                success: function(data, msg){
                    d = eval('(' + data + ')');
                    response(get_suggestions(d));
                }
            });
        }
});


});
</script>
</div>

<div id="layer_details_dialog" title="Layer Details" style="display:none;"><p>TODO</p></div>

</body>
</html>

<!--
vim: syntax=xml foldmethod=marker
-->
