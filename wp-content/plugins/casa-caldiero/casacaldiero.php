<?php
/*
Plugin Name: Casa Caldiero
Description: Functions, cpt and acf per casa caldiero 2017
Author: Mauro Fioravanzi
Version: 1.0
Author URI: http://imeuro.io
*/

require "cpt.php"; // custom post types
require "acf.php"; // custom fields

add_action( 'plugins_loaded', 'load_custom_translations' );
function load_custom_translations() {
    require "translations.php"; // custom translations: after all plugins (polylang, mainly) are loaded!
}

// disable xmlrpc calls
add_filter('xmlrpc_enabled', '__return_false');

add_image_size( 'slider-hp', '1170', '600', array( "center", "center") );
add_image_size( 'main-for-apt', '1170', '400', array( "center", "center") );
add_image_size( 'thumbnail-hp', '640', '240', array( "center", "center") );

function printACFgroup($id,$isachecklist=false) {
	$fields = acf_get_fields($id);
	$class = '';
	if($isachecklist==true) {
		$class = ' checklist';
	}
	if( $fields ) {
		echo '<ul class="acf-fieldgroup col-xs-12 row">';

		foreach( $fields as $field ) {
			$value = get_field( $field['name'] );
			if(!empty($value)||$value!=0) {
				echo '<li class="col-xs-12 col-sm-6">';
				echo '<strong class="acf-field-label'.$class.'">' . pll__($field['label']) . '</strong>';
				echo '<span class="acf-field-value'.$class.'">' .pll__($value) . '</span>';
				echo '</li>';
			}
		}

		echo '</ul>';
	}
}

//Page Slug Body Class
function add_slug_body_class( $classes ) {
global $post;
if ( isset( $post ) ) {
$classes[] = $post->post_type . '-' . $post->post_name;
}
return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );


// obfuscate email addresses
// http://johnhaller.com/useful-stuff/obfuscate-mailto/code-php
function createMailto($strEmail){
  $strNewAddress = '';
  for($intCounter = 0; $intCounter < strlen($strEmail); $intCounter++){
    $strNewAddress .= "&#" . ord(substr($strEmail,$intCounter,1)) . ";";
  }
  $arrEmail = explode("&#64;", $strNewAddress);
  $strTag = "<script language="."Javascript"." type="."text/javascript".">\n";
  $strTag .= "<!--\n";
  $strTag .= "document.write('<a href=\"mai');\n";
  $strTag .= "document.write('lto');\n";
  $strTag .= "document.write(':" . $arrEmail[0] . "');\n";
  $strTag .= "document.write('@');\n";
  $strTag .= "document.write('" . $arrEmail[1] . "\">');\n";
  $strTag .= "document.write('" . $arrEmail[0] . "');\n";
  $strTag .= "document.write('@');\n";
  $strTag .= "document.write('" . $arrEmail[1] . "<\/a>');\n";
  $strTag .= "// -->\n";
  $strTag .= "</script><noscript>" . $arrEmail[0] . " at \n";
  $strTag .= str_replace("&#46;"," dot ",$arrEmail[1]) . "</noscript>";
  return $strTag;
}

?>
