<?php
/*
	Plugin Name: EventCards
	Plugin URI: http://www.enchantedcelebrations.com
	Description: A custom WordPress plugin.
	Author: Edward R. Jenkins
	Version: 1.0
	Author URI: https://edwardrjenkins.com
	Text Domain: eventcard
	Domain Path: /languages
 */

add_action('admin_init', 'eventcard_init' );
add_action('admin_menu', 'eventcard_add_page');
// eventcard init
function eventcard_init(){
	if (delete_transient('eventcard_flush_rules')) {
		 flush_rewrite_rules();
		}
	register_setting( 'eventcard_options', 'eventcard', 'eventcard_validate' );
}
// adds menu page
function eventcard_add_page() {
	add_options_page('Event Cards Options', 'Event Cards Options', 'manage_options', 'eventcard', 'eventcard_do_page');
}
// writes the menu page
function eventcard_do_page() {
		?>
	<div class="wrap">
<table>

<tr valign="top"><td scope="row"><h2>Event Card Creator Steps</h2></td></tr><tr>
<td><p>1. Uploading images</p><p>Images are uploaded to the "ecweddings" bucket in the Enchanted Celebrations S3 account. The images should be the full sized images and all the images should be contained in a folder with a name that contains ONLY LETTERS or numbers, NO SPACES PLEASE and no other characters. You should open the "ecweddings" bucket and Upload the entire folder. The folder should contain all the images, named like this : ABCD-1, ABCD-2, etc. </p>
<p>As the images upload, an automatic operation takes each image and creates a "large" version and a square thumbnail image. You do not have to do anything, this is completely automatic.</p><p>If the uploading to the bucket fails to upload any images, you should see a message telling you which images did not upload. You can just go into your original image folder, find those images and manually add them to the folder you just uploaded. You can do this anytime.</p>
<p>2. To create the new Event Card just open a new card. First, FILL IN The information for the Event Details.You will need the FOLDER NAME you uploaded to the Amazon ecweddings bucket and the ACRONYM (e.g. ABCD) that prefixes all the images. And fill in the Event Card New box with the word "true".</p>
<p>After you enter this information, it is a good idea to save the Event Card draft.</p>
<p>3. Enter the content and add a featured image in the usual way.</p>
<p>Before you begin, you need to have the image numbers which should be in the various sections, such as Top Picks is images 1 to 25, etc. Scroll down the page and enter the starting and ending image numbers -- just the number - of the images which go into the various sections.</p><p>A little bit of MATH should be done ahead of time to DIVIDE the images between, for example Bride and Bride 2, Portraits and Portraits 2, etc. So you might have something like this:</p>
<ul><li>Top Picks: 1 - 25</li>
<li>Bride Preparation: 26 - 150 </li>
<li>Bride Prep 2: 150 - 300</li>
<li>Groom Prep: 301 - 400</li>
<li>Groom 2 : </li>
<li>Ceremony : 401 - 650</li>
<li>Ceremony 2 : 651 - 800</li>
<li>etc...</li></ul>
<p>If there are sections where there are no images, just leave it blank</p>

<h2>If you have images from a second photographer...</h2>
<p>There is a 2nd Amazon S3 bucket, if you have images from a second photographer. The second bucket also handles files names with a space</p>
<p>Upload these images to the bucket "ecsecondshooter" and the large and thumb versions will be created.</p>
<p>For the second photographer's images - it is assumed that you used the same folder name as the first photographer, but just in case you will be requested to enter it a second time.</p>
<p>Enter the image ID's for the 2nd photographer's photos in the boxes for each section.</p>
<p>Once you have all the image numbers in place, Publish the card. If you made a mistake don't worry, just find the section and enter the correct numbers for the images.</p>
<p>The two processes -- upload the images and creating the card are completely separate. You can "create" the card before you upload images and  you can upload as many "folders" of weddings to the ecweddings folder as you like then come back later and do the Event cards</p>
<p>Old Event Cards are still running with the old template -- so if you need to open and edit one of the old ones the card should automatically "know" it's on the old template and in that case, images are just added or removed in the usual way.</p>
</td></tr><tr><td><hr></td></tr></table>

		<h2><?php _e ('Event Cards Options Panel', 'eventcard'); ?></h2>
		<form method="post" action="options.php">
			<?php settings_fields('eventcard_options'); ?>
			<?php $eventcardoptions = get_option('eventcard'); ?>
			<table class="form-table">
				<tr valign="top"><th scope="row"><?php _e ('Main Gallery Layout', 'eventcard'); ?></th>
				<td><input name="eventcard[gridlayout]" type="checkbox" value="1" <?php checked(true, $eventcardoptions['gridlayout']); ?> />
					<p class="description"><?php _e('Check this to use the grid layout.', 'eventcard'); ?></p>
				</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e ('Excerpt Style', 'eventcard'); ?></th>
				<td>
				<?php $eventcard_estyle = $eventcardoptions['estyle']; ?>
				<select name="eventcard[estyle]">
					<option value="excerpt" <?php if ($eventcard_estyle == "excerpt") echo ('selected="selected"'); ?> ><?php _e ('Excerpt', 'eventcard' ); ?></option>
					<option value="full" <?php if ($eventcard_estyle == "full") echo ('selected="selected"'); ?> ><?php _e ('Full', 'eventcard' ); ?></option>
					<option value="none" <?php if ($eventcard_estyle == "none") echo ('selected="selected"'); ?> ><?php _e ('Disabled', 'eventcard' ); ?></option>
				</select>
					<p class="description"><?php _e('Choose your listing excerpt style.', 'eventcard'); ?></p>
				</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e ('EC Gallery Main Page Description', 'eventcard'); ?></th>
				<td>
				<?php 
					$text =  $eventcardoptions['sdesc'];
					wp_editor( $text, 'sdesc', array( 'textarea_name' => 'sdesc', 'media_buttons' => true, 'textarea_rows' => '10' ) ); ?>
					<p class="description"><?php _e('Add a description for the eventcard directory, which will appear below the main directory title.', 'eventcard'); ?></p>
				</td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e ('Listings per page', 'eventcard'); ?></th>
				<td><input type="number" size="80" max="99" name="eventcard[perpage]" value='<?php echo $eventcardoptions['perpage']; ?>' />
					<p class="description"><?php _e('Set the number of listings per page. Leave blank to inherit the Settings->Reading.', 'eventcard'); ?></p>
				</td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e ('eventcard Page Title', 'eventcard'); ?></th>
				<td><input type="text" size="80" name="eventcard[ptitle]" value='<?php echo $eventcardoptions['ptitle']; ?>' />
					<p class="description"><?php _e('Set a custom eventcard page title, if desired. This title will be used as the archive page title and within breadcrumbs. Leave blank to use the default.', 'eventcard'); ?></p>
				</td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e ('eventcard Label', 'eventcard'); ?></th>
				<td><input type="text" size="80" name="eventcard[label]" value='<?php if ( isset ($eventcardoptions['label'] ) ) { echo $eventcardoptions['label']; } ?>' />
					<p class="description"><?php _e('Set a custom label, if desired. This is shown in the admin panel and is used in the <code>title</code> tag. The default label is <code>Staff</code>. Leave blank to use the default.', 'eventcard'); ?></p>
				</td>
				</tr>				
				<tr valign="top"><th scope="row"><?php _e ('eventcard URL slug', 'eventcard'); ?></th>
				<td><input type="text" size="80" name="eventcard[slug]" value='<?php echo $eventcardoptions['slug']; ?>' />
					<p class="description"><?php _e('Set a custom URL slug, if desired.<strong>Notice:</strong> Use lowercase only, and use no spaces, i.e., instead of using <code>Staff Pages</code>, use <code>staff-pages</code>. Leave blank to use the default.', 'eventcard'); ?></p>
				</td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e ('Disable CSS', 'eventcard'); ?></th>
				<td><input name="eventcard[disablecss]" type="checkbox" value="1" <?php checked(true, $eventcardoptions['disablecss']); ?> />
					<p class="description"><?php _e('Check this box to disable all eventcard CSS.', 'eventcard'); ?></p>
				</td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e ('Use custom wrappers', 'eventcard'); ?></th>
				<td><input name="eventcard[customwrapper]" type="checkbox" value="1" <?php checked(true, $eventcardoptions['customwrapper']); ?> />
					<p class="description"><?php _e('Check this box and enter your wrappers below to use custom content wrappers. See the documentation for details. You may need to use custom wrappers if eventcard pages do not flow well with your theme.', 'eventcard'); ?></p>
				</td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e ('Custom start wrapper', 'eventcard'); ?></th>
				<td><input type="text" size="80" name="eventcard[startwrapper]" value='<?php echo $eventcardoptions['startwrapper']; ?>' />
					<p class="description"><?php _e('Enter your custom start wrapper.', 'eventcard'); ?></p>
				</td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e ('Custom end wrapper', 'eventcard'); ?></th>
				<td><input type="text" size="80" name="eventcard[endwrapper]" value='<?php echo $eventcardoptions['endwrapper']; ?>' />
					<p class="description"><?php _e('Enter your custom ending wrapper. This should close out the wrapper started above.', 'eventcard'); ?></p>
				</td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e ('Custom CSS'); ?></th>
				<td>
				<textarea rows="20" class="large-text" type="text" name="eventcard[customcss]" cols="50" rows="10" /><?php echo $eventcardoptions['customcss']; ?></textarea>
				<p class="description"><?php _e('Input your custom style rules here.', 'eventcard'); ?></p>
				</td>
				</tr>
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'eventcard') ?>" />
			</p>
		</form>
	</div>
	<?php	
	}
// sanitize and validate input
function eventcard_validate($input) {
	// Our first value is either 0 or 1
	if ( ! isset( $input['disablecss'] ) )
	$input['disablecss'] = null;
	if ( ! isset( $input['label'] ) )
	$input['label'] = 'Event Cards';
	if ( ! isset ($input['customwrapper']) )
	$input['customwrapper'] = null;
	if ( ! isset ($input['gridlayout']) )
	$input['gridlayout'] = null;
	if ( ! isset ($input['estyle']) )
	$input['estyle'] = 'excerpt';
	// Say our second option must be safe text with no HTML tags
	$input['perpage'] =  esc_html( $input['perpage'] );
	//if ( ! isset ($input['slug'] ) ) {
	$input['slug'] = sanitize_title_with_dashes ($input['slug']);
	$input['ptitle'] = ucfirst ($input['ptitle']);
	$input['label'] = ucfirst ($input['label']);
	$input['sdesc'] = stripslashes ( $_POST['sdesc'] );
	//} else {
	//$input['slug'] = 'staff';
	//}
	$input['startwrapper'] =  wp_kses_post( $input['startwrapper'] );
	$input['endwrapper'] =  wp_kses_post ( $input['endwrapper'] );
	$input['customcss'] = wp_kses_post ( $input['customcss'] );
	// in case of slug change
	set_transient('eventcard_flush_rules', true);
		return $input;
}

// sets up the taxonomy
function eventcard_taxonomy () {
	$eventcardoptions = get_option ('eventcard');
	$eventcardslug = $eventcardoptions['slug'];
	if ( $eventcardslug == '' ) {
		$eventcardslug = 'event-cards';
	}
	$taxslug = $eventcardslug . '/type';
	register_taxonomy(
		'type',
		'eventcard',
		array(
			'hierarchical' => true,
			'label' => __( 'Types' ),
			'rewrite' => array( 'slug' => $taxslug, 'hierarchical' => true),
			'query_var'    => 'type',
			'public' => true,
			'show_admin_column' => true,
		)
	);
	}
add_action ('init', 'eventcard_taxonomy');

	function create_cpt_eventcard() {
		$eventcardoptions = get_option ('eventcard');
		$eventcardslug = $eventcardoptions['slug'];
		$rewrite = array(
		'slug'                => $eventcardslug,
		'with_front'          => true,
		'pages'               => true,
		'feeds'               => true,
		);
		register_post_type('eventcard', array(
			'labels' => array(
			'name' => 'EventCards',
			'taxonomies' => array ('type', 'category' ),
			'singular_name' => __('EventCard'),
			'add_new_item' => __('Add New EventCard'),
			'edit_item' => 'Edit EventCard',
			'new_item' => 'New EventCard',
			'all_items' => 'All EventCards',
			'view_item' => 'View EventCard',
			'search_items' => 'Search EventCards',
			'not_found' => 'No EventCards Found',
			'not_found_in_trash' => 'No EventCards in Trash',
			),
			'public' => true,
			'has_archive' => true,
			'show_in_menu' => true,
			'menu_order' => '4',
			'rewrite' => $rewrite,
			'menu_icon' => 'dashicons-camera',
			'supports' => array(
				'title',
				'editor',
				'revisions',
				'custom-fields',
				'thumbnail',
				'excerpt',
				'page-attributes'
				)
				));
				}
add_action ('init', 'create_cpt_eventcard' );

// adds meta box to member post types

add_action('add_meta_boxes', 'eventcard_meta_box', 0);

add_action('save_post_eventcard', 'eventcard_save_postdata');
// Adds a box to the main column on the Post and Page edit screens
function eventcard_meta_box() {
	$post_types = array(
		'eventcard',
	);
	foreach ($post_types as $post_type) {
					add_meta_box('eventcard_details', __('Event Details', 'eventcard'), 'eventcard_info_box', $post_type, 'side', 'high', $post_types);
			}
	foreach ($post_types as $post_type) {
					add_meta_box('eventcard_content', __('Event Content', 'eventcard'), 'eventcard_content_box', $post_type, 'normal', 'high', $post_types);
			}
		}
// prints the eventcard post type boxes
function eventcard_info_box($post) {
				// Use nonce for verification
				wp_nonce_field(plugin_basename(__FILE__), 'cpt_noncename');
				// Use get_post_meta to retrieve an existing value from the database and use the value for the form
				$value = get_post_meta($post->ID, 'eventcard_bride_fname', true);
				echo '<label for="eventcard_bride_fname"><strong>Bride\'s First Name</strong></label>';
				echo '<input id="eventcard_bride_fname" name="eventcard_bride_fname" size="28" value="' . esc_attr($value) . '"><br>';
				$value = get_post_meta($post->ID, 'eventcard_groom_fname', true);
				echo '<label for="eventcard_groom_fname"><strong>Groom\'s First Name</strong></label>';
				echo '<input id="eventcard_groom_fname" name="eventcard_groom_fname" size="28" value="' . esc_attr($value) . '"><br>';
				$value = get_post_meta($post->ID, 'eventcard_wedding_date', true);
				echo '<label for="eventcard_wedding_date"><strong>Wedding Date</strong></label>';
				echo '<input type="date" id="eventcard_wedding_date" name="eventcard_wedding_date" size="28" value="' . esc_attr($value) . '"><br>';
				$value = get_post_meta($post->ID, 'eventcard_reception_venue', true);
				echo '<label for="eventcard_reception_venue"><strong>Reception Venue</strong></label>';
				echo '<input id="eventcard_reception_venue" name="eventcard_reception_venue" size="28" value="' . esc_attr($value) . '"><br>';
				$value = get_post_meta($post->ID, 'eventcard_new', true);
				echo '<label for="eventcard_new"><strong>Event Card New? (true or false)</strong></label>';
				echo '<input id="eventcard_new" name="eventcard_new" size="5" value="' . esc_attr($value) . '"><br>';




$u_time = get_the_time('U'); $u_modified_time = get_the_modified_time('U'); 
if ($u_modified_time < 1453578496)
 { echo "This EC uses the old version<br> ";

}
else { echo "This EC should be using the new version<br> ";
add_post_meta($post->ID, 'eventcard_new', 'true');
}

if (get_post_meta($post->ID, 'eventcard_new', true) == "true"){
				 $value = get_post_meta($post->ID, 'eventcard_prefix', true);


                        echo '<label for="eventcard_prefix"><strong>Prefix - like ABCD, just enter letters</strong></label>';
				echo '<input id="eventcard_prefix" name="eventcard_prefix" size="10" value="' . esc_attr($value) . '"><br>';


                          $value = get_post_meta($post->ID, 'eventcard_folder_name', true);
                echo '<label for="eventcard_folder_name"><strong>Event card folder name</strong></label>';
				echo '<input id="eventcard_folder_name" name="eventcard_folder_name" size="20" value="' . esc_attr($value) . '"><br>'; 



//second photographer
			echo '<br>If there are images from a second photographer, uploaded separately:<br>';
			$value = get_post_meta($post->ID, 'eventcard_ssprefix', true);

                        echo '<label for="eventcard_ssprefix"><strong>Prefix - 2nd Photog, like SS-ABCD</strong></label>';
			echo '<input style="background-color:maize" id="eventcard_ssprefix" name="eventcard_ssprefix" size="10" value="' . esc_attr($value) . '"><br>';


                          $value = get_post_meta($post->ID, 'eventcard_ssfolder_name', true);
                echo '<label for="eventcard_ssfolder_name"><strong>2nd folder name</strong></label>';
		echo '<input style="background-color:maize" id="eventcard_ssfolder_name" name="eventcard_ssfolder_name" size="20" value="' . esc_attr($value) . '"<br>'; 






                     
		}
}
// prints the eventcard content boxes


function ecgen($file,$acronym,$imgstart,$imgend) {
if ($imgstart < 1 ) {
	return;
} else {

$shard = Array('brides','photos','funphotos');

$y = 0;

for ($x = $imgstart; $x <= $imgend; $x++) {
if ($y > 2)
{$y = 0;}

$ssrc = 'http://' . $shard[$y] .'.enchantedcelebrations.com/'. $file . '/';
$large = $ssrc .'large/' . $acronym . '-';
$thumb = $ssrc .'thumb/' . $acronym . '-';

$topcode .= '<a class="event-card-img fbx-link fbx-instance" href="' .$large . $x .'.jpg"><img class="alignnone size-thumbnail" data-src="' .$thumb . $x .'.jpg" alt="' .$acronym . $x .'" width="150" height="150" src="' .$thumb . $x .'.jpg"></a>';
$y = $y + 1;
}

//echo $topcode;
return $topcode;

}
}

function ecgenn($file,$acronym,$imgstart,$imgend) {

if ($imgstart <  1 ) {
	return;
} else {

$shard = Array('brides','photos','funphotos');

$y = 0;

for ($x = $imgstart; $x <= $imgend; $x++) {
if ($y > 2)
{$y = 0;}

$ssrc = 'http://' . $shard[$y] .'.enchantedcelebrations.com/'. $file . '/';
$large = $ssrc .'large/' . $acronym . '-';
$thumb = $ssrc .'thumb/' . $acronym . '-';

$topcode .='<a href="' .$large . $x .'.jpg">
<img class="alignnone size-thumbnail" alt="' .$acronym . $x .'" width="150" height="150" src="' .$thumb . $x .'.jpg"></a>';
$y = $y + 1;
}

//echo $topcode;
return $topcode;


}
}

//2nd photographer functions

function ecgen2($file,$acronym,$imgstart,$imgend) {
if ($imgstart < 1 ) {
	return;
} else {

$shard = Array('bridal','photo','weddings');
// for testing
//$shard = Array('brides','photos','funphotos');

$y = 0;

for ($x = $imgstart; $x <= $imgend; $x++) {
if ($y > 2)
{$y = 0;}

$ssrc = 'http://' . $shard[$y] .'.enchantedcelebrations.com/'. $file . '/';
$large = $ssrc .'large/' . $acronym . '-';
$thumb = $ssrc .'thumb/' . $acronym . '-';

$topcode .= '<a class="event-card-img fbx-link fbx-instance" href="' .$large . $x .'.jpg"><img class="alignnone size-thumbnail" data-src="' .$thumb . $x .'.jpg" alt="' .$acronym . $x .'" width="150" height="150" src="' .$thumb . $x .'.jpg"></a>';
$y = $y + 1;
}

//echo $topcode;
return $topcode;

}
}

function ecgenn2($file,$acronym,$imgstart,$imgend) {

if ($imgstart <  1 ) {
	return;
} else {

$shard = Array('bridal','photo','weddings');
// for testing
//$shard = Array('brides','photos','funphotos');


$y = 0;

for ($x = $imgstart; $x <= $imgend; $x++) {
if ($y > 2)
{$y = 0;}

$ssrc = 'http://' . $shard[$y] .'.enchantedcelebrations.com/'. $file . '/';
$large = $ssrc .'large/' . $acronym . '-';
$thumb = $ssrc .'thumb/' . $acronym . '-';

$topcode .='<a href="' .$large . $x .'.jpg">
<img class="alignnone size-thumbnail" alt="' .$acronym . $x .'" width="150" height="150" src="' .$thumb . $x .'.jpg"></a>';
$y = $y + 1;
}

//echo $topcode;
return $topcode;


}
}
//

function eventcard_content_box($post) {
	// Use nonce for verification
	wp_nonce_field(plugin_basename(__FILE__), 'cpt_noncename');
$new = get_post_meta($post->ID, 'eventcard_new', true);
// echo '<label>'.$new.'</label>';

echo '<label for="top_edits"><h2>Top Picks</h2></label>';


if ($new == "true"){

                        $s = get_post_meta($post->ID, 'eventcard_tp_start', true);
    			echo '<label for="eventcard_tp_start"><strong>Top Picks Start (picture number)</strong></label>';
				echo '<input id="eventcard_tp_start" name="eventcard_tp_start" size="5" value="' . esc_attr($s) . '"><br>';
                          $e = get_post_meta($post->ID, 'eventcard_tp_end', true);
    			echo '<label for="eventcard_tp_end"><strong>Top Picks End (picture number)</strong></label>';
				echo '<input id="eventcard_tp_end" name="eventcard_tp_end" size="5" value="' . esc_attr($e) . '"><br>';
                          $x = get_post_meta($post->ID, 'eventcard_tp_exclude', true);
        		// echo '<label for="eventcard_tp_exclude"><strong>Photo numbers to exclude (comma separate list) not working yet</strong></label>';
				// echo '<input id="eventcard_tp_exclude" name="eventcard_tp_exclude" size="30" value="' . esc_attr($x) . '"><br>';
    
	
$f = get_post_meta($post->ID, 'eventcard_folder_name', true);
$a = get_post_meta($post->ID, 'eventcard_prefix', true);

//2nd photographer
echo '<div style="background-color: rgba(213, 33, 33, 0.4);padding:10px">';
			$s2 = get_post_meta($post->ID, 'eventcard_tp_start2', true);
    			echo '<label for="eventcard_tp_start2"><strong>2nd Photog Top Picks Start (picture number)</strong></label>';
				echo '<input id="eventcard_tp_start2" name="eventcard_tp_start2" size="5" value="' . esc_attr($s2) . '"><br>';
                          $e2 = get_post_meta($post->ID, 'eventcard_tp_end2', true);
    			echo '<label for="eventcard_tp_end2"><strong>2nd Top Picks End (picture number)</strong></label>';
				echo '<input id="eventcard_tp_end2" name="eventcard_tp_end2" size="5" value="' . esc_attr($e2) . '"><br>';
                          $x2 = get_post_meta($post->ID, 'eventcard_tp_exclude2', true);
        		// echo '<label for="eventcard_tp_exclude2"><strong>2nd Photo numbers to exclude (comma separate list) not working yet</strong></label>';
				// echo '<input id="eventcard_tp_exclude2" name="eventcard_tp_exclude2" size="30" value="' . esc_attr($x2) . '"><br>';
    echo '</div>';
	
$f2 = get_post_meta($post->ID, 'eventcard_ssfolder_name', true);
$a2 = get_post_meta($post->ID, 'eventcard_ssprefix', true);

//


$value = ecgen($f,$a,$s,$e);
$value .= ecgen2($f2,$a2,$s2,$e2);
//echo $value;
}
else { $value =  get_post_meta($post->ID, 'top_picks', true);
       $value = str_replace('jpg"><img','jpg" class="event-card-img fbx-link fbx-instance"><img',$value);
}
	//echo '<label for="top_edits"><h2>Top Picks</h2></label>';
        wp_editor( $value, 'top_edits', array( 'textarea_name' => 'top_edits', 'media_buttons' => false, 'textarea_rows' => '10', 'drag_drop_upload' => false, 'teeny' => true, 'tinymce' => false) );

echo '<label for="bride_prep"><h2>Bride Preparation</h2></label>';


if ($new == "true"){

                        $s = get_post_meta($post->ID, 'eventcard_bride1_start', true);
        		echo '<label for="eventcard_bride1_start"><strong>Bride Prep Tab 1 Start (picture number)</strong></label>';
				echo '<input id="eventcard_bride1_start" name="eventcard_bride1_start" size="5" value="' . esc_attr($s) . '"><br>';
                          $e = get_post_meta($post->ID, 'eventcard_bride1_end', true);
    			echo '<label for="eventcard_bride1_end"><strong>Bride Prep Tab 1 End (picture number)</strong></label>';
				echo '<input id="eventcard_bride1_end" name="eventcard_bride1_end" size="5" value="' . esc_attr($e) . '"><br>';
                          $x = get_post_meta($post->ID, 'eventcard_bride1_exclude', true);
        		// echo '<label for="eventcard_bride1_exclude"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
				// echo '<input id="eventcard_bride1_exclude" name="eventcard_bride1_exclude" size="30" value="' . esc_attr($x) . '"><br>';

//2nd Photog
echo '<div style="background-color: rgba(213, 33, 33, 0.4);padding:10px">';

			$s2 = get_post_meta($post->ID, 'eventcard_bride1_start2', true);
        		echo '<label for="eventcard_bride1_start2"><strong>2nd Photog Bride Prep Tab 1 Start (picture number)</strong></label>';
				echo '<input id="eventcard_bride1_start2" name="eventcard_bride1_start2" size="5" value="' . esc_attr($s2) . '"><br>';
                          $e2 = get_post_meta($post->ID, 'eventcard_bride1_end', true);
    			echo '<label for="eventcard_bride1_end2"><strong>2nd Bride Prep Tab 1 End (picture number)</strong></label>';
				echo '<input id="eventcard_bride1_end2" name="eventcard_bride1_end2" size="5" value="' . esc_attr($e2) . '"><br>';
                          $x2 = get_post_meta($post->ID, 'eventcard_bride1_exclude2', true);
        		// echo '<label for="eventcard_bride1_exclude2"><strong>2nd Photo numbers to exclude (comma separate list)</strong></label>';
				// echo '<input id="eventcard_bride1_exclude2" name="eventcard_bride1_exclude2" size="30" value="' . esc_attr($x2) . '"><br>';
    

    
    echo '</div>';

 $value = ecgenn($f,$a,$s,$e); 
 $value .= ecgenn2($f2,$a2,$s2,$e2); 

}

else { $value =  get_post_meta($post->ID, 'bride_prep', true);}

	//echo '<label for="bride_prep"><h2>Bride Preparation</h2></label>';

   wp_editor( $value, 'bride_prep', array( 'textarea_name' => 'bride_prep', 'media_buttons' => false, 'textarea_rows' => '10', 'drag_drop_upload' => false, 'teeny' => true, 'tinymce' => false ) );
	
echo '<label for="groom_prep"><h2>Groom Preparation</h2></label>';


if ($new == "true"){

    
 		 	$s = get_post_meta($post->ID, 'eventcard_groom1_start', true);
            		echo '<label for="eventcard_groom1_start"><strong>Groom Prep Tab 1 First Picture (picture number)</strong></label>';
				echo '<input id="eventcard_groom1_start" name="eventcard_groom1_start" size="5" value="' . esc_attr($s) . '"><br>';
                          $e = get_post_meta($post->ID, 'eventcard_groom1_end', true);
    			echo '<label for="eventcard_groom1_end"><strong>Groom Prep Tab 1 Last Picture (picture number)</strong></label>';
				echo '<input id="eventcard_groom1_end" name="eventcard_groom1_end" size="5" value="' . esc_attr($e) . '"><br>';
                          $x = get_post_meta($post->ID, 'eventcard_groom1_exclude', true);
        	//	echo '<label for="eventcard_groom1_exclude"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
			//	echo '<input id="eventcard_groom1_exclude" name="eventcard_groom1_exclude" size="30" value="' . esc_attr($x) . '"><br>';
  // 2nd Photog
echo '<div style="background-color: rgba(213, 33, 33, 0.4);padding:10px">';
 			$s2 = get_post_meta($post->ID, 'eventcard_groom1_start2', true);
            		echo '<label for="eventcard_groom1_start2"><strong>2nd Photog Groom Prep Tab 1 First Picture (picture number)</strong></label>';
				echo '<input id="eventcard_groom1_start2" name="eventcard_groom1_start2" size="5" value="' . esc_attr($s2) . '"><br>';
                          $e2 = get_post_meta($post->ID, 'eventcard_groom1_end2', true);
    			echo '<label for="eventcard_groom1_end2"><strong>2nd Groom Prep Tab 1 Last Picture (picture number)</strong></label>';
				echo '<input id="eventcard_groom1_end2" name="eventcard_groom1_end2" size="5" value="' . esc_attr($e2) . '"><br>';
                          $x2 = get_post_meta($post->ID, 'eventcard_groom1_exclude2', true);
        		// echo '<label for="eventcard_groom1_exclude2"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
				// echo '<input id="eventcard_groom1_exclude2" name="eventcard_groom1_exclude2" size="30" value="' . esc_attr($x2) . '"><br>';
       

     echo '</div>';


$value = ecgenn($f,$a,$s,$e);
$value .= ecgenn2($f2,$a2,$s2,$e2);

 }

else { $value =  get_post_meta($post->ID, 'groom_prep', true); }

 	// echo '<label for="groom_prep"><h2>Groom Preparation</h2></label>';

    wp_editor( $value, 'groom_prep', array( 'textarea_name' => 'groom_prep', 'media_buttons' => false, 'textarea_rows' => '10', 'drag_drop_upload' => false, 'teeny' => true, 'tinymce' => false ) );

echo '<label for="ceremony_session"><h2>Ceremony</h2></label>';
 
if ($new == "true"){
 
 			$s = get_post_meta($post->ID, 'eventcard_ceremony1_start', true);
               		 echo '<label for="eventcard_ceremony1_start"><strong>Ceremony Tab 1 First Picture (picture number)</strong></label>';
				echo '<input id="eventcard_ceremony1_start" name="eventcard_ceremony1_start" size="5" value="' . esc_attr($s) . '"><br>';
                          $e = get_post_meta($post->ID, 'eventcard_ceremony1_end', true);
    			echo '<label for="eventcard_ceremony1_end"><strong>Ceremony Tab 1 Last Picture (picture number)</strong></label>';
				echo '<input id="eventcard_ceremony1_end" name="eventcard_ceremony1_end" size="5" value="' . esc_attr($e) . '"><br>';
                          $x = get_post_meta($post->ID, 'eventcard_ceremony1_exclude', true);
        		//echo '<label for="eventcard_ceremony1_exclude"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
			//	echo '<input id="eventcard_ceremony1_exclude" name="eventcard_ceremony1_exclude" size="30" value="' . esc_attr($x) . '"><br>';
      
echo '<div style="background-color: rgba(213, 33, 33, 0.4);padding:10px">';
			$s2 = get_post_meta($post->ID, 'eventcard_ceremony1_start2', true);
               		 echo '<label for="eventcard_ceremony1_start2"><strong>2nd Photographer Ceremony Tab 1 First Picture</strong></label>';
				echo '<input id="eventcard_ceremony1_start2" name="eventcard_ceremony1_start2" size="5" value="' . esc_attr($s2) . '"><br>';
                          $e2 = get_post_meta($post->ID, 'eventcard_ceremony1_end2', true);
    			echo '<label for="eventcard_ceremony1_end2"><strong>Ceremony Tab 1 Last Picture (picture number)</strong></label>';
				echo '<input id="eventcard_ceremony1_end2" name="eventcard_ceremony1_end2" size="5" value="' . esc_attr($e2) . '"><br>';
                          $x2 = get_post_meta($post->ID, 'eventcard_ceremony1_exclude2', true);
        		//echo '<label for="eventcard_ceremony1_exclude2"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
				//echo '<input id="eventcard_ceremony1_exclude2" name="eventcard_ceremony1_exclude2" size="30" value="' . esc_attr($x2) . '"><br>';
       
echo '</div>';



 $value = ecgenn($f,$a,$s,$e);
$value .= ecgenn2($f2,$a2,$s2,$e2);
 }

else  { $value =  get_post_meta($post->ID, 'ceremony_session', true);}


	// echo '<label for="ceremony_session"><h2>Ceremony</h2></label>';

    wp_editor( $value, 'ceremony_session', array( 'textarea_name' => 'ceremony_session', 'media_buttons' => false, 'textarea_rows' => '10', 'drag_drop_upload' => false, 'teeny' => true, 'tinymce' => false ) );

	echo '<label for="portrait_session"><h2>Portrait Session</h2></label>';

if ($new == "true"){
 			$s = get_post_meta($post->ID, 'eventcard_portrait1_start', true);
                	echo '<label for="eventcard_portrait1_start"><strong>Portrait Session Tab 1 First Picture (picture number)</strong></label>';
    			echo '<input id="eventcard_portrait1_start" name="eventcard_portrait1_start" size="5" value="' . esc_attr($s) . '"><br>';
                          $e = get_post_meta($post->ID, 'eventcard_portrait1_end', true);
    			echo '<label for="eventcard_portrait1_end"><strong>Portrait Session Tab 1 Last Picture (picture number)</strong></label>';
				echo '<input id="eventcard_portrait1_end" name="eventcard_portrait1_end" size="5" value="' . esc_attr($e) . '"><br>';
                          $x = get_post_meta($post->ID, 'eventcard_portrait1_exclude', true);
        		//echo '<label for="eventcard_portrait1_exclude"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
			//	echo '<input id="eventcard_portrait1_exclude" name="eventcard_portrait1_exclude" size="30" value="' . esc_attr($x) . '"><br>';
  //2nd Photog
echo '<div style="background-color: rgba(213, 33, 33, 0.4);padding:10px">';
			$s2 = get_post_meta($post->ID, 'eventcard_portrait1_start2', true);
                	echo '<label for="eventcard_portrait1_start2"><strong>2nd Photog Portrait Session Tab 1 First Picture (picture number)</strong></label>';
    			echo '<input id="eventcard_portrait1_start2" name="eventcard_portrait1_start2" size="5" value="' . esc_attr($s2) . '"><br>';
                          $e2 = get_post_meta($post->ID, 'eventcard_portrait1_end2', true);
    			echo '<label for="eventcard_portrait1_end2"><strong>Portrait Session Tab 1 Last Picture (picture number)</strong></label>';
				echo '<input id="eventcard_portrait1_end2" name="eventcard_portrait1_end2" size="5" value="' . esc_attr($e2) . '"><br>';
                          $x2 = get_post_meta($post->ID, 'eventcard_portrait1_exclude2', true);
        		// echo '<label for="eventcard_portrait1_exclude2"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
				// echo '<input id="eventcard_portrait1_exclude2" name="eventcard_portrait1_exclude2" size="30" value="' . esc_attr($x2) . '"><br>';
   echo '</div>';    
     


$value = ecgenn($f,$a,$s,$e);
$value .= ecgenn2($f2,$a2,$s2,$e2);
 }

else { $value =  get_post_meta($post->ID, 'portrait_session', true);}

//	echo '<label for="portrait_session"><h2>Portrait Session</h2></label>';

    wp_editor( $value, 'portrait_session', array( 'textarea_name' => 'portrait_session', 'media_buttons' => false, 'textarea_rows' => '10', 'drag_drop_upload' => false, 'teeny' => true, 'tinymce' => false ) );


       	echo '<label for="reception_session"><h2>Reception</h2></label>';


if ($new == "true"){

			$s = get_post_meta($post->ID, 'eventcard_reception1_start', true);

             		echo '<label for="eventcard_reception1_start"><strong>Reception Tab 1 First Picture (picture number)</strong></label>';
        		echo '<input id="eventcard_reception1_start" name="eventcard_reception1_start" size="5" value="' . esc_attr($s) . '"><br>';
                          $e = get_post_meta($post->ID, 'eventcard_reception1_end', true);
    			echo '<label for="eventcard_reception1_end"><strong>Reception Tab 1 Last Picture (picture number)</strong></label>';
				echo '<input id="eventcard_reception1_end" name="eventcard_reception1_end" size="5" value="' . esc_attr($e) . '"><br>';
                          $x = get_post_meta($post->ID, 'eventcard_reception1_exclude', true);
        		// echo '<label for="eventcard_reception1_exclude"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
		// echo '<input id="eventcard_reception1_exclude" name="eventcard_reception1_exclude" size="30" value="' . esc_attr($x) . '"><br>';
//2nd Photog 
echo '<div style="background-color: rgba(213, 33, 33, 0.4);padding:10px">'; 

 			$s2 = get_post_meta($post->ID, 'eventcard_reception1_start2', true);

             		echo '<label for="eventcard_reception1_start2"><strong>2nd Photog Reception Tab 1 First Picture (picture number)</strong></label>';
        		echo '<input id="eventcard_reception1_start2" name="eventcard_reception1_start2" size="5" value="' . esc_attr($s2) . '"><br>';
                          $e2 = get_post_meta($post->ID, 'eventcard_reception1_end2', true);
    			echo '<label for="eventcard_reception1_end2"><strong>Reception Tab 1 Last Picture (picture number)</strong></label>';
				echo '<input id="eventcard_reception1_end2" name="eventcard_reception1_end2" size="5" value="' . esc_attr($e2) . '"><br>';
                          $x2 = get_post_meta($post->ID, 'eventcard_reception1_exclude2', true);
        		// echo '<label for="eventcard_reception1_exclude2"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
				// echo '<input id="eventcard_reception1_exclude2" name="eventcard_reception1_exclude2" size="30" value="' . esc_attr($x2) . '"><br>';
       
 echo '</div>';   


$value = ecgenn($f,$a,$s,$e);
$value .= ecgenn2($f2,$a2,$s2,$e2);
 }
 
else { $value =  get_post_meta($post->ID, 'reception_session', true);}

       //	echo '<label for="reception_session"><h2>Reception</h2></label>';
//	wp_editor( $value, 'reception_session', array( 'textarea_name' => 'reception_session', 'media_buttons' => true, 'textarea_rows' => '10', 'drag_drop_upload' => true, 'teeny' => true ) );
    wp_editor( $value, 'reception_session', array( 'textarea_name' => 'reception_session', 'media_buttons' => false, 'textarea_rows' => '10', 'drag_drop_upload' => false, 'teeny' => true, 'tinymce' => false ) );
	echo '<label for="bride_prep2"><h2>More Bride</h2></label>';
if ($new == "true"){

	 		$s = get_post_meta($post->ID, 'eventcard_bride2_start', true);
            			echo '<label for="eventcard_bride2_start"><strong>Bride Prep Tab 2 First Picture (picture number)</strong></label>';
				echo '<input id="eventcard_bride2_start" name="eventcard_bride2_start" size="5" value="' . esc_attr($s) . '"><br>';
                          $e = get_post_meta($post->ID, 'eventcard_bride2_end', true);
    			echo '<label for="eventcard_bride2_end"><strong>Bride Prep Tab 2 Last Picture (picture number)</strong></label>';
				echo '<input id="eventcard_bride2_end" name="eventcard_bride2_end" size="5" value="' . esc_attr($e) . '"><br>';
                          $x = get_post_meta($post->ID, 'eventcard_bride2_exclude', true);
        		// echo '<label for="eventcard_bride2_exclude"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
			//	echo '<input id="eventcard_bride2_exclude" name="eventcard_bride2_exclude" size="30" value="' . esc_attr($x) . '"><br>';

//2nd Photog
echo '<div style="background-color: rgba(213, 33, 33, 0.4);padding:10px">';
 
	 		$s2 = get_post_meta($post->ID, 'eventcard_bride2_start2', true);
            			echo '<label for="eventcard_bride2_start2"><strong>2nd Photographer Bride Prep Tab 2 First Picture (picture number)</strong></label>';
				echo '<input id="eventcard_bride2_start2" name="eventcard_bride2_start2" size="5" value="' . esc_attr($s2) . '"><br>';
                          $e2 = get_post_meta($post->ID, 'eventcard_bride2_end2', true);
    			echo '<label for="eventcard_bride2_end2"><strong>Bride Prep Tab 2 Last Picture (picture number)</strong></label>';
				echo '<input id="eventcard_bride2_end" name="eventcard_bride2_end" size="5" value="' . esc_attr($e2) . '"><br>';
                          $x2 = get_post_meta($post->ID, 'eventcard_bride2_exclude2', true);
        		// echo '<label for="eventcard_bride2_exclude2"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
				// echo '<input id="eventcard_bride2_exclude2" name="eventcard_bride2_exclude2" size="30" value="' . esc_attr($x2) . '"><br>';
 echo '</div>';    
    
$value = ecgenn($f,$a,$s,$e);
$value .= ecgenn2($f2,$a2,$s2,$e2);
 }

else { $value =  get_post_meta($post->ID, 'bride_prep2', true); }

	// echo '<label for="bride_prep2"><h2>More Bride</h2></label>';
	wp_editor( $value, 'bride_prep2', array( 'textarea_name' => 'bride_prep2', 'media_buttons' => true, 'textarea_rows' => '10', 'drag_drop_upload' => true, 'teeny' => true ) );

	echo '<label for="bride_prep2"><h2>Groom Prep 2</h2></label>';

if ($new == "true"){


 			$s = get_post_meta($post->ID, 'eventcard_groom2_start', true);
                	echo '<label for="eventcard_groom2_start"><strong>Groom Prep Tab 2 First Picture (picture number)</strong></label>';
				echo '<input id="eventcard_groom2_start" name="eventcard_groom2_start" size="5" value="' . esc_attr($s) . '"><br>';
                          $e = get_post_meta($post->ID, 'eventcard_groom2_end', true);
    			echo '<label for="eventcard_groom2_end"><strong>Groom Prep Tab 2 Last Picture (picture number)</strong></label>';
				echo '<input id="eventcard_groom2_end" name="eventcard_groom2_end" size="5" value="' . esc_attr($e) . '"><br>';
                          $x = get_post_meta($post->ID, 'eventcard_groom2_exclude', true);
        		// echo '<label for="eventcard_groom2_exclude"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
			//	echo '<input id="eventcard_groom2_exclude" name="eventcard_groom2_exclude" size="30" value="' . esc_attr($x) . '"><br>';
     
// 2nd Photographer
echo '<div style="background-color: rgba(213, 33, 33, 0.4);padding:10px">';

			$s2 = get_post_meta($post->ID, 'eventcard_groom2_start2', true);
                	echo '<label for="eventcard_groom2_start2"><strong>2nd Photographer Groom Prep Tab 2 First Picture (picture number)</strong></label>';
				echo '<input id="eventcard_groom2_start2" name="eventcard_groom2_start2" size="5" value="' . esc_attr($s2) . '"><br>';
                          $e2 = get_post_meta($post->ID, 'eventcard_groom2_end', true);
    			echo '<label for="eventcard_groom2_end2"><strong>Groom Prep Tab 2 Last Picture (picture number)</strong></label>';
				echo '<input id="eventcard_groom2_end2" name="eventcard_groom2_end2" size="5" value="' . esc_attr($e2) . '"><br>';
                          $x2 = get_post_meta($post->ID, 'eventcard_groom2_exclude2', true);
        		// echo '<label for="eventcard_groom2_exclude2"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
				// echo '<input id="eventcard_groom2_exclude2" name="eventcard_groom2_exclude2" size="30" value="' . esc_attr($x2) . '"><br>';
     
echo '</div>';


 
$value = ecgenn($f,$a,$s,$e);
$value .= ecgenn2($f2,$a2,$s2,$e2);
 }

else { $value =  get_post_meta($post->ID, 'groom_prep2', true);}
	//echo '<label for="groom_prep2"><h2>More Groom</h2></label>';
//	wp_editor( $value, 'groom_prep2', array( 'textarea_name' => 'groom_prep2', 'media_buttons' => true, 'textarea_rows' => '10', 'drag_drop_upload' => true, 'teeny' => true ) );
    wp_editor( $value, 'groom_prep2', array( 'textarea_name' => 'groom_prep2',  'media_buttons' => false, 'textarea_rows' => '10', 'drag_drop_upload' => false, 'teeny' => true, 'tinymce' => false ) );

	echo '<label for="bride_prep2"><h2>Portrait 2</h2></label>';
	
if ($new == "true"){
 			$s = get_post_meta($post->ID, 'eventcard_portrait2_start', true);
                	echo '<label for="eventcard_portrait2_start"><strong>Portrait Session Tab 2 First Picture (picture number)</strong></label>';
        		echo '<input id="eventcard_portrait2_start" name="eventcard_portrait2_start" size="5" value="' . esc_attr($s) . '"><br>';
                          $e = get_post_meta($post->ID, 'eventcard_portrait2_end', true);
    			echo '<label for="eventcard_portrait2_end"><strong>Portrait Session Tab 2 Last Picture (picture number)</strong></label>';
				echo '<input id="eventcard_portrait2_end" name="eventcard_portrait2_end" size="5" value="' . esc_attr($e) . '"><br>';
                          $x = get_post_meta($post->ID, 'eventcard_portrait2_exclude', true);
        		// echo '<label for="eventcard_portrait2_exclude"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
				// echo '<input id="eventcard_portrait2_exclude" name="eventcard_portrait2_exclude" size="30" value="' . esc_attr($x) . '"><br>';
       

// 2nd Photographer
echo '<div style="background-color: rgba(213, 33, 33, 0.4);padding:10px">';

			$s2 = get_post_meta($post->ID, 'eventcard_portrait2_start2', true);
                	echo '<label for="eventcard_portrait2_start2"><strong>2nd Photographer Portrait Session Tab 2 First Picture (picture number)</strong></label>';
        		echo '<input id="eventcard_portrait2_start2" name="eventcard_portrait2_start2" size="5" value="' . esc_attr($s2) . '"><br>';
                          $e2 = get_post_meta($post->ID, 'eventcard_portrait2_end', true);
    			echo '<label for="eventcard_portrait2_end2"><strong>Portrait Session Tab 2 Last Picture (picture number)</strong></label>';
				echo '<input id="eventcard_portrait2_end2" name="eventcard_portrait2_end2" size="5" value="' . esc_attr($e2) . '"><br>';
                          $x2 = get_post_meta($post->ID, 'eventcard_portrait2_exclude', true);
        		// echo '<label for="eventcard_portrait2_exclude2"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
			//	echo '<input id="eventcard_portrait2_exclude2" name="eventcard_portrait2_exclude2" size="30" value="' . esc_attr($x2) . '"><br>';
     echo '</div>';  
 
$value = ecgenn($f,$a,$s,$e);
$value .= ecgenn2($f2,$a2,$s2,$e2);
 }

else {
  $value =  get_post_meta($post->ID, 'portrait_session2', true);}
	// echo '<label for="portrait_session2"><h2>More Portraits</h2></label>';

	wp_editor( $value, 'portrait_session2', array( 'textarea_name' => 'portrait_session2',  'media_buttons' => false, 'textarea_rows' => '10', 'drag_drop_upload' => false, 'teeny' => true, 'tinymce' => false ) );

	echo '<label for="bride_prep2"><h2>Ceremony 2</h2></label>';

if ($new == "true"){

 $s = get_post_meta($post->ID, 'eventcard_ceremony2_start', true);
                echo '<label for="eventcard_ceremony2_start"><strong>Ceremony Tab 2 First Picture (picture number)</strong></label>';
    			echo '<input id="eventcard_ceremony2_start" name="eventcard_ceremony2_start" size="5" value="' . esc_attr($s) . '"><br>';
                          $e = get_post_meta($post->ID, 'eventcard_ceremony2_end', true);
    			echo '<label for="eventcard_ceremony2_end"><strong>Ceremony Tab 2 Last Picture (picture number)</strong></label>';
				echo '<input id="eventcard_ceremony2_end" name="eventcard_ceremony2_end" size="5" value="' . esc_attr($e) . '"><br>';
                          $x = get_post_meta($post->ID, 'eventcard_ceremony2_exclude', true);
        		// echo '<label for="eventcard_ceremony2_exclude"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
			//	echo '<input id="eventcard_ceremony2_exclude" name="eventcard_ceremony2_exclude" size="30" value="' . esc_attr($x) . '"><br>';
   
// 2nd Photographer
echo '<div style="background-color: rgba(213, 33, 33, 0.4);padding:10px">';

			$s2 = get_post_meta($post->ID, 'eventcard_ceremony2_start2', true);
                	echo '<label for="eventcard_ceremony2_start2"><strong>2nd Photographer Ceremony Tab 2 First Picture (picture number)</strong></label>';
    			echo '<input id="eventcard_ceremony2_start2" name="eventcard_ceremony2_start2" size="5" value="' . esc_attr($s2) . '"><br>';
                          $e2 = get_post_meta($post->ID, 'eventcard_ceremony2_end2', true);
    			echo '<label for="eventcard_ceremony2_end2"><strong>Ceremony Tab 2 Last Picture (picture number)</strong></label>';
				echo '<input id="eventcard_ceremony2_end2" name="eventcard_ceremony2_end2" size="5" value="' . esc_attr($e2) . '"><br>';
                          $x2 = get_post_meta($post->ID, 'eventcard_ceremony2_exclude2', true);
        		// echo '<label for="eventcard_ceremony2_exclude2"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
			//	echo '<input id="eventcard_ceremony2_exclude2" name="eventcard_ceremony2_exclude2" size="30" value="' . esc_attr($x2) . '"><br>';
   
echo '</div>';


 
$value = ecgenn($f,$a,$s,$e);
$value .= ecgenn2($f2,$a2,$s2,$e2);

 }

else { $value =  get_post_meta($post->ID, 'ceremony_session2', true);}
	// echo '<label for="ceremony_session2"><h2>More Ceremony</h2></label>';

      wp_editor( $value, 'ceremony_session2', array( 'textarea_name' => 'ceremony_session2',  'media_buttons' => false, 'textarea_rows' => '10', 'drag_drop_upload' => false, 'teeny' => true, 'tinymce' => false ) );

	echo '<label for="bride_prep2"><h2>Reception 2</h2></label>';

if ($new == "true"){

   			$s = get_post_meta($post->ID, 'eventcard_reception2_start', true);
                echo '<label for="eventcard_reception2_start"><strong>Reception Tab 2 First Picture (picture number)</strong></label>';
        		echo '<input id="eventcard_reception2_start" name="eventcard_reception2_start" size="5" value="' . esc_attr($s) . '"><br>';
                          $e = get_post_meta($post->ID, 'eventcard_reception2_end', true);
    			echo '<label for="eventcard_reception2_end"><strong>Reception Tab 2 Last Picture (picture number)</strong></label>';
				echo '<input id="eventcard_reception2_end" name="eventcard_reception2_end" size="5" value="' . esc_attr($e) . '"><br>';
                          $x = get_post_meta($post->ID, 'eventcard_reception2_exclude', true);
        		// echo '<label for="eventcard_reception2_exclude"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
			//	echo '<input id="eventcard_reception2_exclude" name="eventcard_reception2_exclude" size="30" value="' . esc_attr($x) . '"><br>';
   
//2nd Photographer

echo '<div style="background-color: rgba(213, 33, 33, 0.4);padding:10px">';

			$s2 = get_post_meta($post->ID, 'eventcard_reception2_start2', true);
                	echo '<label for="eventcard_reception2_start2"><strong>2nd Photographer Reception Tab 2 First Picture (picture number)</strong></label>';
        		echo '<input id="eventcard_reception2_start2" name="eventcard_reception2_start2" size="5" value="' . esc_attr($s2) . '"><br>';
                          $e2 = get_post_meta($post->ID, 'eventcard_reception2_end2', true);
    			echo '<label for="eventcard_reception2_end2"><strong>Reception Tab 2 Last Picture (picture number)</strong></label>';
				echo '<input id="eventcard_reception2_end2" name="eventcard_reception2_end2" size="5" value="' . esc_attr($e2) . '"><br>';
                          $x2 = get_post_meta($post->ID, 'eventcard_reception2_exclude2', true);
        	//	echo '<label for="eventcard_reception2_exclude2"><strong>Photo numbers to exclude (comma separate list)</strong></label>';
				//echo '<input id="eventcard_reception2_exclude2" name="eventcard_reception2_exclude2" size="30" value="' . esc_attr($x2) . '"><br>';
   echo '</div>';

$value = ecgenn($f,$a,$s,$e);
$value .= ecgenn2($f2,$a2,$s2,$e2);

 }

else { $value =  get_post_meta($post->ID, 'reception_session2', true);}
  //   echo '<label for="reception_session2"><h2>More Reception</h2></label>';
    wp_editor( $value, 'reception_session2', array( 'textarea_name' => 'reception_session2', 'media_buttons' => false, 'textarea_rows' => '10', 'drag_drop_upload' => false, 'teeny' => true, 'tinymce' => false ) );

		}



// saves the eventcard post type details

function eventcard_save_postdata($post_id) {
				if (!current_user_can('edit_page', $post_id)) {
								return;
						}
				else {
								if (!current_user_can('edit_post', $post_id))
												return;
						}
				if (!isset($_POST['cpt_noncename']) || !wp_verify_nonce($_POST['cpt_noncename'], plugin_basename(__FILE__)))
								return;
				// get ID and save data
				$post_ID      = $_POST['post_ID'];
				//sanitize user input
				$bride = ($_POST['eventcard_bride_fname']);
				//$top = str_replace('<img src', '<img data-src', ($_POST['top_edits'] ) );
				$top = ($_POST['top_edits'] );
				$groom = ($_POST['eventcard_groom_fname']);
				$date = ($_POST['eventcard_wedding_date']);
				$venue = ($_POST['eventcard_reception_venue']);
				$new = ($_POST['eventcard_new']);

                
                $acronym = ($_POST['eventcard_prefix']);
                $folder = ($_POST['eventcard_folder_name']);

		$ssacronym = ($_POST['eventcard_ssprefix']);
                $ssfolder = ($_POST['eventcard_ssfolder_name']);

                $tps = ($_POST['eventcard_tp_start']);
                $tpe = ($_POST['eventcard_tp_end']);
                $tpex = ($_POST['eventcard_tp_exclude']);
                
                $brs = ($_POST['eventcard_bride1_start']);
                $bre = ($_POST['eventcard_bride1_end']);
                $brex = ($_POST['eventcard_bride1_exclude']);
                
                $br2s = ($_POST['eventcard_bride2_start']);
                $br2e = ($_POST['eventcard_bride2_end']);
                $br2ex = ($_POST['eventcard_bride2_exclude']);
                
                $grs = ($_POST['eventcard_groom1_start']);
                $gre = ($_POST['eventcard_groom1_end']);
                $grex = ($_POST['eventcard_groom1_exclude']);
                
                $gr2s = ($_POST['eventcard_groom2_start']);
                $gr2e = ($_POST['eventcard_groom2_end']);
                $gr2ex = ($_POST['eventcard_groom2_exclude']);
                
                $crs = ($_POST['eventcard_ceremony1_start']);
                $cre = ($_POST['eventcard_ceremony1_end']);
                $crex = ($_POST['eventcard_ceremony1_exclude']);
                
                $cr2s = ($_POST['eventcard_ceremony2_start']);
                $cr2e = ($_POST['eventcard_ceremony2_end']);
                $cr2ex  = ($_POST['eventcard_ceremony2_exclude']);
                
                $pss = ($_POST['eventcard_portrait1_start']);
                $pse = ($_POST['eventcard_portrait1_end']);
                $psex = ($_POST['eventcard_portrait1_exclude']);
                
                $ps2s = ($_POST['eventcard_portrait2_start']);
                $ps2e = ($_POST['eventcard_portrait2_end']);
                $ps2ex  = ($_POST['eventcard_portrait2_exclude']);
                
                $res = ($_POST['eventcard_reception1_start']);
                $ree = ($_POST['eventcard_reception1_end']);
                $reex = ($_POST['eventcard_reception1_exclude']);
                
                $re2s = ($_POST['eventcard_reception2_start']);
                $re2e = ($_POST['eventcard_reception2_end']);
                $re2ex = ($_POST['eventcard_reception2_exclude']); 




                $tps2 = ($_POST['eventcard_tp_start2']);
                $tpe2 = ($_POST['eventcard_tp_end2']);
                $tpex2 = ($_POST['eventcard_tp_exclude2']);
                
                $brs2 = ($_POST['eventcard_bride1_start2']);
                $bre2 = ($_POST['eventcard_bride1_end2']);
                $brex2 = ($_POST['eventcard_bride1_exclude2']);
                
                $br2s2 = ($_POST['eventcard_bride2_start2']);
                $br2e2 = ($_POST['eventcard_bride2_end2']);
                $br2ex2 = ($_POST['eventcard_bride2_exclude2']);
                
                $grs2 = ($_POST['eventcard_groom1_start2']);
                $gre2 = ($_POST['eventcard_groom1_end2']);
                $grex2 = ($_POST['eventcard_groom1_exclude2']);
                
                $gr2s2 = ($_POST['eventcard_groom2_start2']);
                $gr2e2 = ($_POST['eventcard_groom2_end2']);
                $gr2ex2 = ($_POST['eventcard_groom2_exclude2']);
                
                $crs2 = ($_POST['eventcard_ceremony1_start2']);
                $cre2 = ($_POST['eventcard_ceremony1_end2']);
                $crex2 = ($_POST['eventcard_ceremony1_exclude2']);
                
                $cr2s2 = ($_POST['eventcard_ceremony2_start2']);
                $cr2e2 = ($_POST['eventcard_ceremony2_end2']);
                $cr2ex2  = ($_POST['eventcard_ceremony2_exclude2']);
                
                $pss2 = ($_POST['eventcard_portrait1_start2']);
                $pse2 = ($_POST['eventcard_portrait1_end2']);
                $psex2 = ($_POST['eventcard_portrait1_exclude2']);
                
                $ps2s2 = ($_POST['eventcard_portrait2_start2']);
                $ps2e2 = ($_POST['eventcard_portrait2_end2']);
                $ps2ex2  = ($_POST['eventcard_portrait2_exclude2']);
                
                $res2 = ($_POST['eventcard_reception1_start2']);
                $ree2 = ($_POST['eventcard_reception1_end2']);
                $reex2 = ($_POST['eventcard_reception1_exclude2']);
                
                $re2s2 = ($_POST['eventcard_reception2_start2']);
                $re2e2 = ($_POST['eventcard_reception2_end2']);
                $re2ex2 = ($_POST['eventcard_reception2_exclude2']); 
                
                
                
                //$top = ($_POST['top_edits'] );
                
                
			$bride_prep = ($_POST['bride_prep']);
			$bride_prep2 = ($_POST['bride_prep2']);
			$groom_prep = ($_POST['groom_prep']);
			$groom_prep2 = ($_POST['groom_prep2']);
			$ceremony = ($_POST['ceremony_session']);
			$ceremony2 = ($_POST['ceremony_session2']);
			$portrait_session = ($_POST['portrait_session']);
			$portrait_session2 = ($_POST['portrait_session2']);
			$reception = ($_POST['reception_session']);
				$reception2 = ($_POST['reception_session2']);
				
				
				
		
				
				update_post_meta($post_ID, 'eventcard_bride_fname', $bride);
				update_post_meta($post_ID, 'eventcard_groom_fname', $groom);
				update_post_meta($post_ID, 'eventcard_wedding_date', $date);
				update_post_meta($post_ID, 'eventcard_reception_venue', $venue);
                update_post_meta($post_ID, 'eventcard_prefix',  $acronym);
                update_post_meta($post_ID, 'eventcard_folder_name',   $folder);
 		update_post_meta($post_ID, 'eventcard_new',   $new);
                 update_post_meta($post_ID, 'eventcard_ssprefix',  $ssacronym);
                update_post_meta($post_ID, 'eventcard_ssfolder_name',   $ssfolder);


				update_post_meta($post_ID, 'top_edits', $top );
                update_post_meta($post_ID, 'eventcard_tp_start', $tps );
                update_post_meta($post_ID, 'eventcard_tp_end', $tpe );
                update_post_meta($post_ID, 'eventcard_tp_exclude', $tpex );
                
				update_post_meta($post_ID, 'bride_prep', $bride_prep);
                update_post_meta($post_ID, 'eventcard_bride1_start', $brs );
                update_post_meta($post_ID, 'eventcard_bride1_end', $bre );
                update_post_meta($post_ID, 'eventcard_bride1_exclude', $brex );
                
				update_post_meta($post_ID, 'bride_prep2', $bride_prep2);
                update_post_meta($post_ID, 'eventcard_bride2_start', $br2s );
                update_post_meta($post_ID, 'eventcard_bride2_end', $br2e );
                update_post_meta($post_ID, 'eventcard_bride2_exclude', $br2ex );
                
				update_post_meta($post_ID, 'groom_prep', $groom_prep);
                update_post_meta($post_ID, 'eventcard_groom1_start', $grs );
                update_post_meta($post_ID, 'eventcard_groom1_end', $gre );
                update_post_meta($post_ID, 'eventcard_groom1_exclude', $grex );
                
				update_post_meta($post_ID, 'groom_prep2', $groom_prep2);
                update_post_meta($post_ID, 'eventcard_groom2_start', $gr2s );
                update_post_meta($post_ID, 'eventcard_groom2_end', $gr2e );
                update_post_meta($post_ID, 'eventcard_groom2_exclude', $g2rex );
                
				update_post_meta($post_ID, 'ceremony_session', $ceremony);
                update_post_meta($post_ID, 'eventcard_ceremony1_start', $crs );
                update_post_meta($post_ID, 'eventcard_ceremony1_end', $cre );
                update_post_meta($post_ID, 'eventcard_ceremony1_exclude', $crex );
                
                
				update_post_meta($post_ID, 'ceremony_session2', $ceremony2);
                update_post_meta($post_ID, 'eventcard_ceremony2_start', $cr2s );
                update_post_meta($post_ID, 'eventcard_ceremony2_end', $cr2e );
                update_post_meta($post_ID, 'eventcard_ceremony2_exclude', $cr2ex );
                
				update_post_meta($post_ID, 'portrait_session', $portrait_session);
                update_post_meta($post_ID, 'eventcard_portrait1_start', $pss );
                update_post_meta($post_ID, 'eventcard_portrait1_end', $pse );
                update_post_meta($post_ID, 'eventcard_portrait1_exclude', $psex );
                
				update_post_meta($post_ID, 'portrait_session2', $portrait_session2);
                 update_post_meta($post_ID, 'eventcard_portrait2_start', $ps2s );
                update_post_meta($post_ID, 'eventcard_portrait2_end', $ps2e );
                update_post_meta($post_ID, 'eventcard_portrait2_exclude', $ps2ex );
                
				update_post_meta($post_ID, 'reception_session', $reception);
                update_post_meta($post_ID, 'eventcard_reception1_start', $res );
                update_post_meta($post_ID, 'eventcard_reception1_end', $ree );
                update_post_meta($post_ID, 'eventcard_reception1_exclude', $reex );
                
				update_post_meta($post_ID, 'reception_session2', $reception2);
                update_post_meta($post_ID, 'eventcard_reception2_start', $re2s );
                update_post_meta($post_ID, 'eventcard_reception2_end', $re2e );
                update_post_meta($post_ID, 'eventcard_reception2_exclude', $re2ex );


//second photographer updates
                update_post_meta($post_ID, 'eventcard_tp_start2', $tps2 );
                update_post_meta($post_ID, 'eventcard_tp_end2', $tpe2 );
                update_post_meta($post_ID, 'eventcard_tp_exclude2', $tpex2 );
                
                update_post_meta($post_ID, 'eventcard_bride1_start2', $brs2 );
                update_post_meta($post_ID, 'eventcard_bride1_end2', $bre2 );
                update_post_meta($post_ID, 'eventcard_bride1_exclude2', $brex2 );
                
                update_post_meta($post_ID, 'eventcard_bride2_start2', $br2s2 );
                update_post_meta($post_ID, 'eventcard_bride2_end2', $br2e2 );
                update_post_meta($post_ID, 'eventcard_bride2_exclude2', $br2ex2 );
                
                update_post_meta($post_ID, 'eventcard_groom1_start2', $grs2 );
                update_post_meta($post_ID, 'eventcard_groom1_end2', $gre2 );
                update_post_meta($post_ID, 'eventcard_groom1_exclude2', $grex2 );
                
		update_post_meta($post_ID, 'eventcard_groom2_start2', $gr2s2 );
                update_post_meta($post_ID, 'eventcard_groom2_end2', $gr2e2 );
                update_post_meta($post_ID, 'eventcard_groom2_exclude2', $g2rex2 );
                
		
                update_post_meta($post_ID, 'eventcard_ceremony1_start2', $crs2 );
                update_post_meta($post_ID, 'eventcard_ceremony1_end2', $cre2 );
                update_post_meta($post_ID, 'eventcard_ceremony1_exclude2', $crex2 );
                
                
                update_post_meta($post_ID, 'eventcard_ceremony2_start2', $cr2s2 );
                update_post_meta($post_ID, 'eventcard_ceremony2_end2', $cr2e2 );
                update_post_meta($post_ID, 'eventcard_ceremony2_exclude2', $cr2ex2 );
                
                update_post_meta($post_ID, 'eventcard_portrait1_start2', $pss2 );
                update_post_meta($post_ID, 'eventcard_portrait1_end2', $pse2 );
                update_post_meta($post_ID, 'eventcard_portrait1_exclude2', $psex2 );
                
                update_post_meta($post_ID, 'eventcard_portrait2_start2', $ps2s2 );
                update_post_meta($post_ID, 'eventcard_portrait2_end2', $ps2e2 );
                update_post_meta($post_ID, 'eventcard_portrait2_exclude2', $ps2ex2 );
                
                update_post_meta($post_ID, 'eventcard_reception1_start2', $res2 );
                update_post_meta($post_ID, 'eventcard_reception1_end2', $ree2 );
                update_post_meta($post_ID, 'eventcard_reception1_exclude2', $reex2 );
                
                update_post_meta($post_ID, 'eventcard_reception2_start2', $re2s2 );
                update_post_meta($post_ID, 'eventcard_reception2_end2', $re2e2 );
                update_post_meta($post_ID, 'eventcard_reception2_exclude2', $re2ex2 );


				
		}
// sets template override for custom template use
function eventcard_templates( $template ) {
    $post_types = array( 'eventcard' );
    $event_tax = 'type';
    if ( is_post_type_archive( $post_types ) && ! file_exists( get_stylesheet_directory() . '/archive-eventcards.php' ) ) {
        $template = plugin_dir_path (__FILE__) . 'archive-eventcards.php';
    }
    if ( is_singular( $post_types ) && ! file_exists( get_stylesheet_directory() . '/single-eventcard.php' ) ) {
        $template = plugin_dir_path (__FILE__) . 'single-eventcard.php';
    }
    if ( is_tax( $event_tax ) && ! file_exists( get_stylesheet_directory() . '/taxonomy-eventcard-type.php' ) ) {
        $template = plugin_dir_path (__FILE__) . 'taxonomy-eventcard-type.php';
    }
    if ( is_search ($post_types) && ! file_exists( get_stylesheet_directory() . '/eventcard-search.php' ) ) {
        $template = plugin_dir_path (__FILE__) . 'eventcard-search.php';
    }
    return $template;
}
add_filter( 'template_include', 'eventcard_templates' );

// loads the default css
function eventcard_load_css() {
	wp_register_style ('eventcard-style', plugins_url('styles/eventcard-styles.css', __FILE__) );
	wp_register_style ('eventcard-font-awesome', plugins_url('styles/font-awesome.min.css', __FILE__) );
	$eventcardoptions = get_option('eventcard');
	wp_enqueue_style ('eventcard-style');

	wp_register_style ('eventcard-jquery-ui', plugins_url( 'styles/jquery-ui.css', __FILE__ ) );
	wp_enqueue_style ('eventcard-jquery-ui');

	wp_enqueue_style ('eventcard-font-awesome');

	wp_register_script( 'eventcard-masonry', plugins_url('js/masonry.pkgd.min.js', __FILE__ ), array('jquery'), '1.0.0', true );

	wp_register_script( 'eventcard-images-loaded', plugins_url('js/imagesloaded.pkgd.min.js', __FILE__ ), array('jquery'), '1.0.0', true );

	wp_register_script( 'eventcard-masonry-init', plugins_url('js/masonry-init.js', __FILE__ ), array('jquery'), '1.0.0', true );

	wp_register_script( 'eventcard-jquery-cookie', plugins_url('js/jquery.cookie.js', __FILE__ ), array('jquery'), '1.0.0', true );


	//wp_enqueue_script ('eventcard-masonry');

	//wp_enqueue_script ('eventcard-images-loaded');

	//wp_enqueue_script ('eventcard-masonry-init');

	// search autocomplete
	wp_enqueue_script( 'jquery-ui-autocomplete' );
	wp_register_script( 'eventcard-autocomplete', plugins_url('js/eventcard-autocomplete.js', __FILE__ ), array('jquery' ), '1.0.0', true );
	wp_localize_script( 'eventcard-autocomplete', 'EventAutocomplete', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
	wp_enqueue_script('eventcard-autocomplete');
	wp_enqueue_script ('eventcard-jquery-cookie');

	}
add_action ('wp_enqueue_scripts','eventcard_load_css');

// search autocomplete
function eventcard_search_autocomplete() {
		$term = strtolower( $_GET['term'] );
		$suggestions = array();
		
		$loop = new WP_Query( 'post_type=eventcard&s=' . $term );
		
		while( $loop->have_posts() ) {
			$loop->the_post();
			$suggestion = array();
			$suggestion['label'] = get_the_title();
			$suggestion['link'] = get_permalink();
			
			$suggestions[] = $suggestion;
		}
		
		wp_reset_query();
    	
    	
    	$response = json_encode( $suggestions );
    	echo $response;
    	exit();

}

add_action( 'wp_ajax_my_search', 'eventcard_search_autocomplete' );
add_action( 'wp_ajax_nopriv_my_search', 'eventcard_search_autocomplete' );

// loads up any custom css
function eventcard_custom_styles() {
	$eventcardoptions = get_option ('eventcard');
		if ($eventcardoptions['customcss'] != '' ) {
			$eventcardcustomcss = $eventcardoptions['customcss'];
			print ( '<!-- EC Custom CSS --><style>' . $eventcardcustomcss . '</style>');
						}
						}
add_action ('wp_head', 'eventcard_custom_styles' );

// flush rewrite rules on activation and deactivation
function eventcard_activate() {
	//create_cpt_eventcard();
	// flush rewrite rules to prevent 404s
	flush_rewrite_rules();

}
register_activation_hook( __FILE__, 'eventcard_activate' );

function eventcard_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'eventcard_deactivate' );
 
// adds link to main archive
function eventcard_admin_menu() {
    global $submenu;
    $url = home_url('/');
    $eventcardoptions = get_option('eventcard');
	if ( $eventcardoptions['slug'] == '' ) {
		$slug = 'event-cards';
	} else {
		$slug = $eventcardoptions['slug'];
	}
	if ( get_option ('permalink_structure') ) {
	$url = $url . $slug;
	} else {
		$url = $url . '?post_type=eventcard';
	}
    $submenu['edit.php?post_type=eventcard'][] = array(__('View Events Page', 'eventcard'), 'manage_options', $url);
}
add_action('admin_menu', 'eventcard_admin_menu');

// allows modification of posts per page without affecting non-eventcard pages

function eventcard_per_page_mod($query) {
	$eventcardoptions = get_option ('eventcard');
	$perpage = $eventcardoptions['perpage'];
	$post_type = 'eventcard';
	$taxonomy = 'type';
		if ( $query->is_main_query() && !is_admin() && is_post_type_archive( $post_type ) ) {
				$query->set( 'posts_per_page', $perpage );
			} elseif ( $query->is_main_query() && !is_admin() && is_tax ($taxonomy) ) {
				$query->set( 'posts_per_page', $perpage );
		}

	}
	$eventcardoptions = get_option ('eventcard');
	$perpage = $eventcardoptions['perpage'];
	if ($perpage != '' ) {
		add_action ('pre_get_posts', 'eventcard_per_page_mod' );
	}

function eventcard_toggle_script() {
	//wp_register_script( 'eventcard-toggle', plugins_url('js/toggle-script.js', __FILE__ ), array('jquery'), '1.0.0' );
	//wp_register_script( 'eventcard-clearing', plugins_url('js/eventcard.clearing.js', __FILE__ ), array('jquery'), '1.0.0', true );
	//wp_register_script( 'eventcard-lazy', plugins_url('js/jquery.lazyload.min.js', __FILE__ ), array('jquery'), '1.0.0', true );
	//if ( !isset($_COOKIE['no_wedding'])) {
	//wp_enqueue_script ('eventcard-toggle' );
// }
	wp_register_script( 'eventcard-unveil', plugins_url('js/jquery.unveil.js', __FILE__ ), array('jquery'), '1.0.0' );
	//wp_enqueue_script ('eventcard-clearing');
	//wp_enqueue_script ('eventcard-lazy');
	wp_enqueue_script ('eventcard-unveil');
}

add_action ('wp_enqueue_scripts', 'eventcard_toggle_script', 9999 );

function close_event_modal() {
 echo '<script>jQuery("#myModal").foundation("reveal", "close");
		jQuery.cookie("no_wedding", 1, { expires : 7 });</script>';
 }  
//add_action('gform_after_submission_51', 'close_event_modal', 10, 2);