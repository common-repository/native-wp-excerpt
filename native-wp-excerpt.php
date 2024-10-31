<?php
/*
Plugin Name: Native WP Excerpt
Plugin URI:  
Description: With this plugin you can easily configure excerpt and more tag output. You can set excerpt tail, lenght of words, add link to excerpt, change text of excerpt link, rename more tag link text and disable scroll. After activation see new sub-page in Settings menu.
Version:     1.0
Author:      Oleg Komarovskyi
Author URI:  https://profiles.wordpress.org/komarovski
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: native-wp-excerpt
Domain Path: /languages/
*/
defined('ABSPATH') or die('No script kiddies please!');
//Unique prefix = nwpexpcode (Native Word Press Excerpt Plugin Code)
//Localization
function nwpexpcode_init(){load_plugin_textdomain('native-wp-excerpt', false, basename(dirname(__FILE__)).'/languages/');}
add_action('init', 'nwpexpcode_init');
//Global variable
$nwpexpcode_tab = get_option('nwpexpcodesettings');
global $nwpexpcode_tab;
//Excerpt tail and link
function nwpexpcode_tail_and_link(){
	global $nwpexpcode_tab;
	if($nwpexpcode_tab['nwpexpcode_link'] == 'Yes'){
		if(!empty($nwpexpcode_tab['nwpexpcode_link_text'])){
			return $nwpexpcode_tab['nwpexpcode_tail'].'</p><p class="excerpt-more-link"> <a href="'.get_permalink($post->ID).'">'.$nwpexpcode_tab['nwpexpcode_link_text'].'</a>';
		}
		else{
			return $nwpexpcode_tab['nwpexpcode_tail'].'</p><p class="excerpt-more-link"> <a href="'.get_permalink($post->ID).'">'.__('Read More', 'native-wp-excerpt').'</a>';
		}
	}
	elseif($nwpexpcode_tab['nwpexpcode_link'] == 'No'){return $nwpexpcode_tab['nwpexpcode_tail'];}
	else{return '[...]';}
}
add_filter('excerpt_more', 'nwpexpcode_tail_and_link');
//Excerpt lenght
function nwpexpcode_excerpt_lenght($length){
	global $nwpexpcode_tab;
	if($nwpexpcode_tab['nwpexpcode_word_lenght'] > 0){return $nwpexpcode_tab['nwpexpcode_word_lenght'];}
	else{return 35;}
}
add_filter('excerpt_length', 'nwpexpcode_excerpt_lenght', 55);
//More tag
function nwpexpcode_more_tag(){
	global $nwpexpcode_tab;
	if(!empty($nwpexpcode_tab['nwpexpcode_more_tag_text'])){
		$link = ' <a class="tag-more-link" href="'.get_permalink().'#more-'.get_the_id().'">'.$nwpexpcode_tab['nwpexpcode_more_tag_text'].'</a>';
		if($nwpexpcode_tab['nwpexpcode_more_tag_scroll'] == 'Yes'){$link = preg_replace('|#more-[0-9]+|', '', $link); return $link;}
		else{return $link;}
	}
	else{
		$link = ' <a class="tag-more-link" href="'.get_permalink().'#more-'.get_the_id().'">'.__('Read More', 'native-wp-excerpt').'</a>';
		if($nwpexpcode_tab['nwpexpcode_more_tag_scroll'] == 'Yes'){$link = preg_replace('|#more-[0-9]+|', '', $link); return $link;}
		else{return $link;}
	}
}
add_filter('the_content_more_link', 'nwpexpcode_more_tag');
//Admin page
function nwpexpcode_page(){
global $nwpexpcode_tab;
ob_start();?>
<div class="wrap">
<form action="options.php" method="post">
	<?php settings_fields('nwpexpcodegroup');?>
	<h1><?php echo __('Excerpt settings', 'native-wp-excerpt');?></h1>
	<table class="form-table">
		<tbody>
			<tr>
				<th colspan="2"><h2><?php echo __('Excerpt', 'native-wp-excerpt');?></h2><hr></th>
			</tr>
			<tr>
				<th><?php echo __('Excerpt tail', 'native-wp-excerpt');?>:</th>
				<td><input type="text" name="nwpexpcodesettings[nwpexpcode_tail]" placeholder="..." value="<?php echo $nwpexpcode_tab['nwpexpcode_tail'];?>"/></td>
			</tr>
			<tr>
				<th><?php echo __('Make excerpt as link to a post', 'native-wp-excerpt');?>?</th>
				<td>
					<label><input type="radio" name="nwpexpcodesettings[nwpexpcode_link]" value="Yes" <?php if($nwpexpcode_tab['nwpexpcode_link'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes, make excerpt as link to a post', 'native-wp-excerpt');?></label><br>
					<label><input type="radio" name="nwpexpcodesettings[nwpexpcode_link]" value="No" <?php if($nwpexpcode_tab['nwpexpcode_link'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No, leave excerpt as plain text', 'native-wp-excerpt');?></label>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Link text', 'native-wp-excerpt');?>:</th>
				<td><input type="text" name="nwpexpcodesettings[nwpexpcode_link_text]" placeholder="<?php echo __('Read More', 'native-wp-excerpt');?>" value="<?php echo $nwpexpcode_tab['nwpexpcode_link_text'];?>"/></td>
			</tr>
			<tr>
				<th><?php echo __('Length of words in excerpt', 'native-wp-excerpt');?>:</th>
				<td><input type="number" min="0" name="nwpexpcodesettings[nwpexpcode_word_lenght]" placeholder="35" value="<?php echo $nwpexpcode_tab['nwpexpcode_word_lenght'];?>"/></td>
			</tr>
			<tr>
				<th colspan="2"><h2><?php echo __('Read More Tag', 'native-wp-excerpt');?></h2><hr></th>
			</tr>
			<tr>
				<th><?php echo __('Remove scroll from read more link', 'native-wp-excerpt');?>?</th>
				<td>
					<label><input type="radio" name="nwpexpcodesettings[nwpexpcode_more_tag_scroll]" value="Yes" <?php if($nwpexpcode_tab['nwpexpcode_more_tag_scroll'] == 'Yes'){echo 'checked="checked"';}?>/> <?php echo __('Yes', 'native-wp-excerpt');?></label><br>
					<label><input type="radio" name="nwpexpcodesettings[nwpexpcode_more_tag_scroll]" value="No" <?php if($nwpexpcode_tab['nwpexpcode_more_tag_scroll'] == 'No'){echo 'checked="checked"';}?>/> <?php echo __('No', 'native-wp-excerpt');?></label>
				</td>
			</tr>
			<tr>
				<th><?php echo __('Read More Tag text', 'native-wp-excerpt');?>:</th>
				<td><input type="text" name="nwpexpcodesettings[nwpexpcode_more_tag_text]" placeholder="<?php echo __('Read More', 'native-wp-excerpt');?>" value="<?php echo $nwpexpcode_tab['nwpexpcode_more_tag_text'];?>"/></td>
			</tr>
		</tbody>
	</table>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save changes', 'native-wp-excerpt');?>"/></p>
</form>
</div>
<?php
echo ob_get_clean();
}
//Admin menu
function nwpexpcode_tab(){add_options_page(__('Excerpt settings', 'native-wp-excerpt'),__('Native WP Excerpt', 'native-wp-excerpt'),'manage_options','native-wp-excerpt','nwpexpcode_page');}
add_action('admin_menu','nwpexpcode_tab');
//Register settings
function nwpexpcode_settings(){register_setting('nwpexpcodegroup','nwpexpcodesettings');}
add_action('admin_init','nwpexpcode_settings');
?>