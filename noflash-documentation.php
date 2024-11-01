<?php
/*
+-------------------------------------------------------------------+
|																								|
|	WordPress Plugin: NoFlash Slidehow 1.3							|
|	Nikos M.										|
|																								|
|	File Written By:																		|
|	- Nikos M.																|
|	- http://nikos-web-development.netai.net																|
|																								|
|	File Information:																		|
|	- Documentation															|
|	- wp-content/plugins/wp-noflash/noflash-documentation.php	|
|																								|
+-------------------------------------------------------------------+
*/
if (!defined('ABSPATH')) die('SECURITY');
if (!current_user_can( 'manage_noflash' )) die('SECURITY');
?>
<p>
<h1>NoFlash Slideshow WordPress (3.0 up to 3.4)</h1>
<br />based on noflash.jquery plugin by <a href="http://nikos-web-development.netai.net">Nikos.M</a>
<br />Author: <a href="http://nikos-web-development.netai.net">Nikos.M</a>
<br />Plugin URL (download & demo/manual): <a href="http://nikos-web-development.netai.net/blog/downloads">NoFlash Slideshow Plugin for WordPress</a>
<br /><br />The slideshow contains more than 100 transition effects (including variations) which is powered by 20KB of minified javascript. The plugin is also optimized as regards to the handling of database queries and performance.
<br /><br />
<h2>Documentation</h2><br />
<br /><h3>Quick Overview</h3>
The plugin is based on the noflash jquery plugin by same author. The interface is intuitive and self-explanatory for the most part.
<ol>
<li>After installation, you create a slideshow by clicking on the New Slideshow Menu Item</li>
<li>You fill in the parameters for the slideshow</li>
<li>You can use the slideshow from anywhere within your wordpress site either by calling specifically the <code>&lt;?php noflash_slideshow(id_of_slideshow); ?&gt;</code> function from within your theme, or by
calling the shortcode <code>[noflash id=id_of_slideshow]</code> from within a post or page
<br />Alternatively you can insert slideshows to posts/pages directly from tinyMCE editor, using the noflash button (new in 1.2)!</li>
<li>You can edit the slideshow by clicking the Manage Slideshows menu and selecting the specific slideshow for editing</li>
<li>You can unistall the NoFlash Plugin by clicking the Uninstall menu, this completely uninstalls the plugin</li>
</ol>
<br /><h3>Parameters</h3>
<br /><h6>Width and Height:</h6> define the width and height (in pixels) of the slideshow. These should be equal to the dimensions of your images. Note that the slideshow
has advanced transition effects that work best for images of the same dimensions, else the results might be unpredictable.<br />
<h6>Slideshow name:</h6> A name for the specific slideshow for easy recognition<br />
<h6>Posts ids:</h6> The ids of posts (not pages) separated by commas (no space) to define as targets for the slideshow. If used the post titles and excerpts are used as titles and captions for the images of the slideshow. If fewer posts than images are defined then the rest of images will be displayed with the titles and captions defined for them.
If fewer images than posts are defined, then only those posts for which there are images will displayed with the order given.
<span style="font-style:italic">If you want to use both posts together with custom items, just use 0 as a placeholder for the post id</span>
<br /><h6>Random Order:</h6> whether the order of the images will be randomized when slideshow is displayed.
<br /><h6>Back Color:</h6> The background color of the slideshow (if blank  '#000' will be used)
<br /><h6>Excerpt Length:</h6> If posts are defined, this defines the number of words to use for the excerpts (if zero the default length is used as defined by wordpress or your theme)
<br /><h6>Global Delay/Global Duration:</h6> Global values for delay and duration in seconds for the transitions if not defined for each specific transition. Delay means the length of time during which an image stays after the transition and until the next transition. Duration means the length of time for the transition effect.
<br /><h6>Order:</h6> The order of the specified image in the slideshow. Also the mapping of the images to post ids (if specified),even if random ordering is checked. Note <strong>Order</strong> is applicable only to images, post ids are ordered as given. 
<br /><h6>Image URL:</h6> The url of image to be inserted to the slideshow. Here you can use the %HOME% tag in the url to define urls that are easilly transported (for example when there are multiple copies of the wordpress site like a development copy and a live copy). The %HOME% tag always points to the wordpress home directory for the specific installation. This way the same slideshow configuration works for both your test site and your live site only you will have to upload the images. Example <code>%HOME%/wp-content/image1.jpg</code>
<br />(ver 1.3) You can also upload and use images from your media library using the Set/Upload Image Button.<br />
<br /><h6>Alt/Title:</h6> The alt and title text for the specific image (if posts are defined this will be replaced by the posts title)
<br /><h6>Caption:</h6> The caption for the specific image, html is allowed (if posts are defined the post's excerpt will be used as caption)
<br /><h6>Fx:</h6> The specific transition effect for this image see below for effect parameters
<br /><h6>Remove Image Link:</h6> Removes the specific image from the slideshow.
<br /><h6>Add New image Link:</h6> Adds another image to the slideshow
<br /><br />
<h3>Styling the slideshow</h3>
<br />The css file for the slideshow is located at <code>WP_PLUGINS_DIR/wp-noflash/css/noflash.css</code>. You can change it to meet your needs. Also you can provide specific stylesheets for each specific slideshow you create by creating a style sheet with the name <code>noflash-id_of_slideshow.css</code> (replace id_of_slideshow with the id value of the specific slideshow) at the same folder. 
<br /><br />
<h3>Effect parameters (from the documentation of noflash.jquery plugin)</h3>
<h5>Transitions (ie the specific effect to apply):</h5>
<ul>
<li>random (a combination of the rest transitions)</li>
<li>rotate-tiles (NEW! v.1.4+)</li>

<li>rotate-tiles-reverse (NEW! v.1.4+)</li>
<li>flip-tiles-horizontal (NEW! v.1.3+)</li>
<li>flip-tiles-vertical (NEW! v.1.3+)</li>
<li>iris</li>
<li>iris-reverse</li>
<li>fade-tiles</li>
<li>fade-grow-tiles</li>
<li>fade-shrink-tiles</li>
<li>shrink-tiles</li>

<li>grow-tiles</li>
<li>shrink-tiles-horizontal</li>
<li>grow-tiles-horizontal</li>
<li>grow-tiles-vertical</li>
<li>grow-fade-tiles-horizontal</li>
<li>grow-fade-tiles-vertical</li>
<li>shrink-tiles-vertical</li>
<li>move-tiles-vertical-down</li>
<li>move-tiles-vertical-up</li>
<li>move-tiles-vertical-up-down</li>
<li>move-tiles-horizontal-right</li>
<li>move-tiles-horizontal-left</li>
<li>move-tiles-horizontal-left-right</li>
<li>move-fade-tiles-vertical-down</li>
<li>move-fade-tiles-vertical-up</li>
<li>move-fade-tiles-vertical-up-down</li>
<li>move-fade-tiles-horizontal-right</li>
<li>move-fade-tiles-horizontal-left</li>
<li>move-fade-tiles-horizontal-left-right</li>
<li>fly-top-left</li>
<li>fly-bottom-left</li>
<li>fly-top-right</li>
<li>fly-bottom-right</li>
<li>fly-left</li>
<li>fly-right</li>
<li>fly-top</li>

<li>fly-bottom</li>
<li>pan-top-left</li>
<li>pan-top-right</li>
<li>pan-bottom-right</li>
<li>pan-bottom-left</li>
<li>pan-left</li>
<li>pan-right</li>
<li>pan-top</li>
<li>pan-bottom</li>

</ul>
<h5>Orderings (ie the order of the tiles in the transition animation):</h5>
<ul>
<li>checkerboard</li>
<li>diagonal-top-left</li>
<li>diagonal-top-right</li>
<li>diagonal-bottom-left</li>
<li>diagonal-bottom-right</li>
<li>rows</li>
<li>rows-reverse</li>

<li>columns</li>
<li>columns-reverse</li>
<li>rows-first</li>
<li>rows-first-reverse</li>
<li>columns-first</li>
<li>columns-first-reverse</li>
<li>spiral-top-left</li>
<li>spiral-top-right</li>
<li>spiral-bottom-left</li>

<li>spiral-bottom-right</li>
<li>spiral-top-left-reverse</li>
<li>spiral-top-right-reverse</li>
<li>spiral-bottom-left-reverse</li>
<li>spiral-bottom-right-reverse</li>
<li>random</li>
<li>up-down</li>
<li>up-down-reverse</li>
<li>left-right</li>

<li>left-right-reverse</li>
</ul>
<h5>Easing</h5>
<p>
i use a jquery easings plugin (jquery.animation.easing.js)<br />
however u can use your own easings, just include the js file and use the name of the easing function in the easing parameter<br />
note: some default transitions use the easing functions from jquery.animation.easing.js so include it in ur scripts</p>
<h5>Duration</h5>
<p>
The duration of the transition in seconds</p>

<h5>Delay</h5>
<p>
The duration of the image after the transition until the next transition in seconds</p>
<h5>Rows</h5>
<p>
The number of rows to slice image for transition<br />
note: some transitions use default rows regardless of parameter</p>
<h5>Columns</h5>
<p>
The number of columns to slice image for transition<br />

note: some transitions use default columns regardless of parameter</p>
<h5>Overlap</h5>
<p>
The percentage of overlap between each slice during animation<br />
0: next slice starts after previous finishes(no overlap), 1: all start simultaneously (full overlap)</p>
<h4>Javascript-only parameters</h4>
<p>
Previous parameters can be applied either through html or through javascript<br />
the next set of parameters are applied only as plugin parameters through javascript</p>
<h5>Caption</h5>

<p>
Boolean, wether to show captions or not, default true</p>
<h5>Controls</h5>
<p>
Boolean, wether to show controls or not, default true</p>
<h5>randomOrder</h5>
<p>
Boolean, wether to randomize order of images, default false</p>
<h5>Preload</h5>
<p>
Boolean, wether to preload images, default true</p>

<h5>preloaderClass</h5>
<p>
String, css class of div that contains the preloader (look at noflash.css)</p>
<h5>captionClass</h5>
<p>
String, css class to apply to caption holder</p>
<h5>controlsClass</h5>
<p>
String, css class to apply to controls holder</p>
<h5>backColor</h5>

<p>
String, color code for background color of slideshow</p>
<h5>width,height</h5>
<p>
The width and height of the images in pixels, numbers</p>
<h5>imgs</h5>
<p>
Array of additional images with the transitions etc to add to the slideshow, the parameters are the same as the html parameters<br />
(ie transition, easing, ordering, rows,etc) additional parameters are<br />
src: the url of the image,<br />

caption: the caption text/html for image,<br />
alt: the alt attribute for the image</p>
<h5>Note:</h5>
<p>
If no transition, rows etc parameters are provided for each image then the javascript options parameters act as default<br />
Also if multiple elements match the selector only the first will be the slideshow (the code would be too messy if multiple elements were matched)<br />
If u want to have multiple copies just run the plugin once for each matched element<br />
</p>