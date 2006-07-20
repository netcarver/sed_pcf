<?php

$plugin['name'] = 'sed_packed_custom_fields';
$plugin['version'] = '0.1';
$plugin['author'] = 'Stephen Dickinson';
$plugin['author_uri'] = 'txp-plugins.netcarving.com';
$plugin['description'] = 'Allows packing of multiple values into one custom field.';

$plugin['type'] = 1; // 0 = regular plugin; public only, 1 = admin plugin; public + admin, 2 = library

@include_once('zem_tpl.php');

if (0) {
?>
<!-- CSS 
# --- BEGIN PLUGIN CSS ---
<style type="text/css">
div#sed_help td { vertical-align:top; }
div#sed_help code { font-weight:bold; font: 105%/130% "Courier New", courier, monospace; background-color: #FFFFCC;}
div#sed_help code.sed_code_tag { font-weight:normal; border:1px dotted #999; background-color: #f0e68c; display:block; margin:10px 10px 20px; padding:10px; }
div#sed_help a:link, div#sed_help a:visited { color: blue; text-decoration: none; border-bottom: 1px solid blue; padding-bottom:1px;}
div#sed_help a:hover, div#sed_help a:active { color: blue; text-decoration: none; border-bottom: 2px solid blue; padding-bottom:1px;}
div#sed_help h1 { color: #369; font: 20px Georgia, sans-serif; margin: 0; text-align: center; }
div#sed_help h2 { border-bottom: 1px solid black; padding:10px 0 0; color: #369; font: 17px Georgia, sans-serif; }
div#sed_help h3 { color: #693; font: bold 12px Arial, sans-serif; letter-spacing: 1px; margin: 10px 0 0;text-transform: uppercase;}
</style>
# --- END PLUGIN CSS ---
-- CSS -->
<!-- HELP
# --- BEGIN PLUGIN HELP ---
<div id="sed_help">

h1(#manual). PCF(Packed Custom Fields)

sed_pcf plugin, v0.1 (June 8th, 2006)

This little plugin allows you to use an article's custom fields to store multiple, named, values. The packing must follow "a specific format":#format. The tag list includes tags to test for a value in the field and to access a value from a field.

h2(#tags). Tag Directory

|_. Tag    |_. Description  |
| "<code>sed_pcf_get_value</code>":#get | Returns the named value from the named section of the named custom field. |
| "<code>sed_pcf_if_value</code>":#if | Tests for the existence of a named value in a named section of a cusom field. |
| "<code>sed_pcf_email</code>":#email | Uses the values in a named section of a custom field to call the TXP email tag. |

h2(#get). The @sed_pcf_get_value@ tag

This tag can take the following attributes&#8230;

|_. Attribute    |_. Default Value |_. Status   |_. Description |
| 'custom'       | ''          | Needed   | Name of the custom field to access. |
| 'section'      | ''          | Needed   | Name of the section within the field to access. |
| 'variable'     | ''          | Needed   | Name of the variable within the section. |
| 'default'      | NULL        | Optional | The value to return if the access fails. |

*Emphasized items* have been added or changed since the last release.
-Struck out items- have been removed since the last release.

h2(#if). The @sed_pcf_if_value@ tag

The attributes are as above EXCEPT that there is no default value used. If the value is present then the contents of the contained tag will be evaluated. If there is an @else@ section then that will be evaluated if the value is not present.

h2(#email). The @sed_pcf_email@ tag

|_. Attribute    |_. Default Value |_. Status   |_. Description |
| 'custom'       | ''          | Needed   | Name of the custom field to access. |
| 'section'      | 'email'     | Optional | Name of the section within the field to grab the values to pass to the TXP @email@ tag. |
| 'default'      | NULL        | Optional | The value to return if the access fails. |

Allows you to setup email links on a per-article basis. You need to setup the custom field you are going to stash the email values in. Then add this to the article body or article form @<txp:sed_pcf_email custom='my_field_name'/>@. Replace the 'my_field_name' as needed.

Next, in the custom field itself, make sure you have @email(email='a@b.c';linktext='foo';title='bar')@. Replacing the values as you need.

h2(#format). Field Format

Some examples of valid fields...
* section1(a='1';b='2')|section2(c='Hello!';d='';e='Goodbye!')
* copyright(start='1945';owner='Ritchard the great')|email(email='john@nowhere.splat';linktext='link text';title='Click here to send a message')

Sections are separated with a '|', the variable list for a section is within brakets and the variables are separated with ';'. Each variable forms a name='value' pair.

h2. Version History.

v0.1 Implemented the following features&#8230;

* Basic retrieve and test of packed values from a custom field.
* Tag to build a TXP email link from a section of a custom field.

</div>
# --- END PLUGIN HELP ---
-- HELP -->
<?php
}

# --- BEGIN PLUGIN CODE ---

// ================== IMMEDIATE CODE & DECLARATIONS FOLLOW ===================
//
//	This plugin requires the services of my plugin library to allow it to pull apart packed variables from the custom fields.
//
@require_plugin('sed_plugin_library');

// ================== CLIENT-SIDE TAGS FOLLOW ===================

function sed_pcf_get_value( $atts ) {
	//
	//	Returns the value of the named variable in the named section of the named custom field (if any) else returns the default value (NULL).
	//
	global $thisarticle;

	extract(lAtts(array(
		'custom'	=> '',
		'section'	=> '', 
		'variable'  => '',
		'default'	=> NULL,
	),$atts));	

	$result = $default;
	$vars = @$thisarticle[$custom];
	if( !empty( $vars ) and !empty($section) and !empty($variable) ) {
		$vars = _extract_packed_variable_section( $section , $vars );
		if( is_array( $vars ) ) {
			$result = @$vars[$variable];
			}
		}
	return $result;
	}

function sed_pcf_if_value( $atts , $thing='' ) {
	//
	//	Tests to see if there is a value to the named variable in the named section of the named custom field.
	//
	$val = sed_pcf_get_value( $atts );
	return parse(EvalElse($thing, (isset($val) and !empty($val)) ));
	}

function sed_pcf_email( $atts ) {
	//
	//	This extracts values from a named section of a custom field any outputs an email link.
	//
	global $thisarticle;

	extract(lAtts(array(
		'custom'	=> '',
		'section'	=> 'email', 
		'default'	=> '',
	),$atts));	

	$result = $default;
	$vars = @$thisarticle[$custom];
	if( !empty( $vars ) and !empty($section) ) {
		$vars = _extract_packed_variable_section( $section , $vars );
		if( is_array( $vars ) ) {
			$result = email ( $vars );
			}
		}
	return $result;
	}

# --- END PLUGIN CODE ---
?>
