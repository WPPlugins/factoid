<?php
	$fa = new WP_octo_factoid(); 
	$fa->register_plugin_styles();
	wp_enqueue_script('jquery');
	
	// In this version of Factoid, help is provided in English only.
	// In this version of Factoid, the factoids are provided only in English!
	// Future versions may provide for localisation.
?>


<h2>Factoid settings</h2>
<h4>v
<?php 
	$full = intval($fa->version/100);
	echo sprintf("%d.%02d",$full,$fa->version - ($full * 100)); 
?>
</h4>

<div class="octo_outer" >

	<p>
		There are no real settings for the Factoid plug-in overall. Settings for the widget are handled through the 
		Widget page (Appearance->Widgets), which are explained below, and settings for the shortcode are handled 
		through shortcode options which are all explained below. Instructions for using the new "user custom"
		style are also included below. The actual individual factoids are retrieved over
		the internet from our own online database of Factoids, which means that the Factoid plugin will only work
		when your visitor has a functioning internet connection - which they must have anyway if they're visiting 
		your site, right? (This is really only included in case you're testing this plugin in an offline environment,
		in which case the plugin will not be able to retrieve any Factoids.)
	</p>
	<p>
		If you have any ideas for improvements, or encounter any problems, please use our 
		<a href="http://www.october.com.au/contact.htm">contact form</a>.
	</p>

	<div class="octo_settingstable">	
		<h3>
			Choosing the "User custom" CSS style
		</h3>
		<p>
		In earlier versions, if you wanted to create your own styling (to suit your site's theme), then you had to
		edit the Factoid CSS file. That means that any upgrades to the Factoid plug-in would wipe out your unique
		styling. In this version, a slightly different approach has been taken. We've created the "User custom"
		style (see below for directions on how to choose that), which means you can now have the CSS in any other
		CSS file your website imports, so you don't have to edit the Factoid CSS any more. 
		Here's how to use it:
		</p>
		<p>
		1. Select a CSS file - either your theme's default CSS file, or one you've created and imported.<br />
		2. Add the code below to that CSS file.<br />
		3. Add CSS values to create a look that matches your site's theme. (If you don't add the values your 
		Factoid will be displayed with no styling at all.)<br />
		4. Select the 'User Custom' style using either the widget or shortcode instructions below.<br />
		5. Enjoy.<br />
		</p>
		<p>
			<pre>
	.factoid_container.factoid_usercustom {
		/**	outer container **/
	}

	.factoid_title_container.factoid_usercustom {
		/** container for the title on the first line **/
	}

	.factoid_content_container.factoid_usercustom {
		/** container for the content, the main body **/
	}

	.factoid_footer_container.factoid_usercustom {
		/** outer container for the footer section **/
	}

	.factoid_footer_innerleft.factoid_usercustom {
		/** container for the inner left of the footer **/
	}

	.factoid_footer_innerright.factoid_usercustom {
		/** container for the inner right of the footer **/
	}			
			</pre>
		</p>
	</div>
	<div class="octo_settingstable">	
		<h3>
			Widget settings
		</h3>
		<p>
			The Factoid widget is implemented and set through the Widgets Settings page (Appearance->Widgets). You can
			include as many Factoid widgets on the one page as you like - Wordpress will treat them all individually, 
			which means you can have multiple Factoid widgets, each with a different type of Factoid appearing!
		</p>
		<p>
			<strong>Theme title</strong><br />
			Many Wordpress themes allow for a themed title for a widget that appears on the page just before the widget.
			If you want to have such a title, enter it here. Leaving this blank will usually mean your theme will not
			include anything prior to the widget itself. Experiment with this to see if you want whatever it is that
			your theme includes here.
		</p>
		<p>
			<strong>Widget title</strong><br />
			The widget itself can have a separate title displayed within the widget. If you don't include a theme
			title, it's probably a good idea to include a Widget title just to describe the content. If you have multiple 
			Factoid widgets, this is a good way to visually differentiate between them, especially assuming they
			will each display a different type of factoid.
		</p>
		<p>
			<strong>Factoid type</strong><br />
			This is where you choose what sort of Factoid you'd like to display.In this version of Factoid you can
			choose from the following Factoid types:<br />
			1. Factoids - short trivia<br />
			2. Quotes<br />
			Use the drop down list to select which Factoid type you'd like to have displayed in this Widget.
		</p>
		<p>
			<strong>Factoid category</strong><br />
			The Factoid cateogry options shown will depend on the Factoid type you have chosen, as each type has 
			different sub-categories. Once you've decided on a Factoid type, you can then choose the category within
			that type that you'd like to have displayed within this Widget. A category only exists within a type.
			In this version of Factoid you can choose
			from the following categories for the given types:<br /><br />
			For "1. Factoids - short trivia" you can choose from ...<br />
			0. All factoid (shows a factoid from any of the following categories)<br />
			1. General (general subjects)<br />
			2. Showbiz (Factoids specific to movies, television, music, etc)<br />
			3. Travel (Factoids specific to a geographic location)<br />
			4. History (Factoids related to history)<br /><br />
			For "2. Quotes" you can choose quotes that are related to ... <br />
			0. All quotes (quotes could be from any of the following categories)<br />
			1. Inspirational (inspiring and motivating)<br />
			2. Showbiz (Quotes from movie, music or entertainment people)<br />
			3. Humourous (funny quotes)<br />
			4. General (Otherwise interesting quotes)<br />
		</p>
		<p>
			<strong>Display style</strong><br />
			Factoid provides a limited number of display styles (themes), but these are planned to increase in future versions.
			Use this drop-down list to select a display style. If you know your CSS stuff, you can choose the "User Custom" style
			and create your own styling. See above for instructions.
		</p>
		<p>
			<strong>Only show Safe for Work content</strong><br />
			There are a few Factoids that may offend some people. Use this checkbox to filter out any risque or borderline
			Factoids and limit your Factoids purely to family-friendly, safe-for-work items.
		</p>
	</div>

	<div class="octo_settingstable">	
		<h3>
			Shortcode options
		</h3>
		<p>
			Factoids can be included in WordPress pages and posts using the Factoid shortcode. You can
			include as many Factoids on the one page as you like - Wordpress will treat them all individually, 
			which means you can have multiple Factoids, each with a different type of Factoid appearing! Each
			'option=value' pair within the shortcode has a default that will be used if the option is omitted.
			These are each explained below. The most basic shortcode for Factoid is:<br /><br />
			<code>[Factoid]</code><br />
		</p>
		<p>
			<strong>type</strong><br />
			Factoids are divided into types. Each type has a number. Use the number here for the factoid type
			you'd like displayed. See the Factoid Types in the widget section above for a full list. Using
			a number not from the list will give an error message. (Default = 1)<br /><br />
			<code>[Factoid type="1"]</code> Displays a short trivia factoid<br />
			<code>[Factoid type="2"]</code> Displays a quote factoid<br />
		</p>
		<p>
			<strong>category</strong><br />
			Factoids are divided into types, and types are divided into categories. Each category has a number
			that means something different for each type. Use the number here for the category you'd like displayed
			within the factoid type you've selected. See the Factoid Categories in the widget section above for a 
			full list of categories for each type. Using a number not from the list will give an error message. 
			(Default = 0)<br /><br />
			<code>[Factoid type="1" category="0"]</code> Displays factoids from all categories within the short 
			trivia factoid type (same as not including a category at all)<br />
			<code>[Factoid type="2" category="2"]</code> Displays a showbiz quote factoid<br />
		</p>
		<p>
			<strong>title</strong><br />
			Displays a title in the title area of the factoid. (Default = "Factoid!")<br /><br />
			<code>[Factoid type="2" category="2" title="Showbiz quote of the moment:"]</code> Displays a showbiz 
			quote factoid with a unique title<br />
		</p>
		<p>
			<strong>width</strong><br />
			Determines the width of the Factoid container, relative to its parent container. For those who know
			CSS, this is added as an inline style in the Factoid container. That means that any valid CSS width
			value can be used here. (Default = "95%)"<br /><br />
			<code>[Factoid type="1" width="50%"]</code> Displays a short trivia factoid, half the width of the parent container<br />
		</p>
		<p>
			<strong>sfw</strong><br />
			Selects whether or not the displayed Factoid is "safe for work". There are a few Factoids that may 
			offend some people. Use this option to filter out any risque or borderline
			Factoids and limit your Factoids purely to family-friendly, safe-for-work items.
			This is a binary value, where 1 is true (sfw is on), and 0 is false (sfw is off). (Default = 1)<br /><br />
			<code>[Factoid type="1" sfw="0"]</code> Displays a short trivia factoid, and turns 'safe for work' off<br />
		</p>
		<p>
			<strong>style</strong><br />
			Determines the Factoid style to use to display this factoid. There are currently only a limited number
			of styles (themes). In this option, use the exact CSS name of the style you want. In this version those
			styles are:<br />
			simple (the default)<br />
			simpledark<br />
			simpleblue<br />
			simplewarm<br />
			simplevintage<br />
			simpleusercustom<br />
			<code>[Factoid type="1" style="simpledark"]</code> Displays a short trivia factoid, using the simpledark style<br />
		</p>
	</div>

</div> 

