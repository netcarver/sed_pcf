<?php
/*$HeadURL$*/

$plugin['revision'] = '$LastChangedRevision$';

$revision = @$plugin['revision'];
if( !empty( $revision ) )
	{
	$parts = explode( ' ' , trim( $revision , '$' ) );
	$revision = $parts[1];
	if( !empty( $revision ) )
		$revision = '.' . $revision;
	}


$plugin['name'] = 'sed_packed_custom_fields';
$plugin['version'] = '0.3' . $revision;
$plugin['author'] = 'Netcarver';
$plugin['author_uri'] = 'http://txp-plugins.netcarving.com';
$plugin['description'] = 'Allows packing of multiple values into one custom field.';
$plugin['type'] = 1;

@include_once('../zem_tpl.php');

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

sed_pcf plugin, v0.2 (June 11th, 2006)

This little plugin allows you to use an article's custom fields to store multiple, named, values. The packing must follow "a specific format":#format. The tag list includes tags to test for a value in the field and to access a value from a field.

h2(#tags). Tag Directory

|_. Tag    |_. Description  |
| "sed_pcf_get_value":#get | Returns the named value from the named section of the named custom field. |
| "sed_pcf_if_value":#if | Tests for the existence of a named value in a named section of a custom field. |
| "sed_pcf_if_field_section":#ifsection | Tests for the existence of a named section of a custom field. |
| "sed_pcf_email":#email | Uses the values in a named section of a custom field to call the TXP email tag. |
| "sed_pcf_image":#image | Uses the values in a named section of a custom field to call the TXP image tag. |
| "sed_pcf_thumbnail":#thumb | Uses the values in a named section of a custom field to call the TXP thumbnail tag. |
| "sed_pcf_for_each_value":#foreach | Uses the values in a named section of a custom field to call the TXP thumbnail tag. |

h2(#get). The @sed_pcf_get_value@ tag

This tag can take the following attributes&#8230;

|_. Attribute    |_. Default Value |_. Status   |_. Description |
| 'custom'       | ''          | Needed   | Name of the custom field to access. |
| *'section'*    | *''*        | *Needed* | Name of the section within the field to access.<br/>*If you are using the simple packing format then set this to 'none'.* |
| 'variable'     | ''          | Needed   | Name of the variable within the section. |
| 'default'      | NULL        | Optional | The value to return if the access fails. |

*Emphasized items* have been added or changed since the last release.
-Struck out items- have been removed since the last release.

h2(#if). The @sed_pcf_if_value@ tag

|_. Attribute    |_. Default Value |_. Status   |_. Description |
| 'custom'       | ''          | Needed   | Name of the custom field to access. |
| *'section'*    | *''*        | *Needed* | Name of the section within the field to access.<br/>*If you are using the simple packing format then set this to 'none'.* |
| 'variable'     | ''          | Needed   | Name of the variable within the section. |
| *'val'*        | *NULL*     | *Optional* | *If specified, this value will be checked against the named variable from the named section of the named custom field.* |

If the value is present and equal to a suppled @val@ then the contents of the contained tag will be evaluated. If there is an @else@ section then that will be evaluated if the value is not present or if it is present and fails to match the @val@ attribute.

h2(#ifsection). The @sed_pcf_if_field_section@ tag

|_. Attribute    |_. Default Value |_. Status   |_. Description |
| 'custom'       | ''          | Needed   | Name of the custom field to access. |
| 'section'      | ''          | Needed   | Name of the section of the packed field to test for. |

If the named section exists within the named custom field then the enclosed part of the tag is parsed.

h2(#email). The @sed_pcf_email@ tag

|_. Attribute    |_. Default Value |_. Status   |_. Description |
| 'custom'       | ''          | Needed   | Name of the custom field to access. |
| *'section'*    | *'email'*   | *Optional* | Name of the section within the field to grab the values to pass to the TXP @email@ tag.<br/>*If you are using the simple packing format then set this to 'none'.* |
| 'default'      | NULL        | Optional | The value to return if the access fails. |
| 'parse'        | false       | Optional | Set this to true to force a the contents of the packed field to be parsed before being used to call the TXP email function. This means that it is possible to use *attributeless* tags (such as @<txp:s/>@ as arguments to the email call. |

Allows you to setup email links on a per-article basis. You need to setup the custom field you are going to stash the email values in. Then add this to the article body or article form @<txp:sed_pcf_email custom='my_field_name'/>@. Replace the 'my_field_name' as needed.

Next, in the custom field itself, make sure you have @email(email='a@b.c';linktext='foo';title='bar')@. Replacing the values as you need.

h2(#image). The @sed_pcf_image@ tag

|_. Attribute    |_. Default Value |_. Status   |_. Description |
| 'custom'       | ''          | Needed   | Name of the custom field to access. |
| *'section'*    | *'image'*   | *Optional* | Name of the section within the field to grab the values to pass to the TXP @image@ tag.<br/>*If you are using the simple packing format then set this to 'none'.* |
| 'default'      | NULL        | Optional | The value to return if the access fails. |

Allows you to display an image based upon the contents of a section of a packed custom field. If you want to use a second layer of tags then that is possible providing they are attributeless tags such as @<txp:author/>@ or @<txp:s/>@.

h2(#thumb). The @sed_pcf_thumbnail@ tag

|_. Attribute    |_. Default Value |_. Status   |_. Description |
| 'custom'       | ''          | Needed   | Name of the custom field to access. |
| *'section'*    | *'thumbnail'*   | *Optional* | Name of the section within the field to grab the values to pass to the TXP @thumbnail@ tag.<br/>*If you are using the simple packing format then set this to 'none'.* |
| 'default'      | NULL        | Optional | The value to return if the access fails. |

As for images, so with thumbnails!

h2(#foreach). The @sed_pcf_for_each_value@ tag

This can be used as an enclosing tag, or with a form, or as a single tag. It was inspired by the *wet_foreach_image* plugin and works in a very similar manner.

|_. Attribute    |_. Default Value |_. Status   |_. Description |
| 'name'         | 'custom1'       | Needed   | Name of the custom field to access. |
| 'debug'        | 0               | Optional | Set to 1 to output extra debug info |
| 'form'         | ''              | Optional | Form to use to format output |
| 'label'        | ''              | Optional | Label text for the output |
| 'labeltag'     | ''              | Optional | tag for the label |
| 'wraptag'      | 'ul'            | Optional | Tag to wrap the whole output in -- you might want to set this to '' if using a form. |
| 'break'        | 'li'            | Optional | Tag to wrap each individual value's output in -- again, you may want to set this to '' if your form wraps the output. |
| 'class'        | 'custom1'       | Optional | Class to apply to the wrapped output |

This tag can be used in three ways...

# As a single tag with default layout... @<txp:sed_pcf_for_each_value />@
# As a single tag using a form for layout... @<txp:sed_pcf_for_each_value form="your_form" />@
# As an enclosing tag... @<txp:sed_pcf_for_each_value>... your stuff here...<txp:sed_pcf_for_each_value />@

The enclosed section, default or supplied form will get parsed once per value in the custom field's list.

On each parse, the string @'{value}'@ will be replaced by the corresponding value from the custom field.

The default layout, used in the absence of a form or contained section, is '{value}'.

h2(#format). Field Formats

There are three formats you can use in your custom fields.

h3(#foreachformat). ForEach Format.

This format can only be used by the @sed_pcf_for_each_value()@ tag. It's a simple comma seperated list of values...

* a,b,c,d,g


h3(#simple). Simple Format

This is a list of name='value' pairs, separated with ';' characters. Some examples of valid formats would be...

* a='1'
* a='1';b='2'
* name='Steve';website='http://txp-plugins.netcarving.com'

If you want to use the simple format then set the section name to 'none' when you call the tags.

h3(#complete). Normal Format

This allows you to pack mutliple sets of the simple-format lists into one field. Each list is called a section and sections can be named, and separated with the '|' character.

Some examples of valid fields...
* section1(a='1';b='2')|section2(c='Hello!';d='';e='Goodbye!')
* copyright(start='1945';owner='Ritchard the great')|email(email='john@nowhere.splat';linktext='link text';title='Click here to send a message')



h2. Version History.

v0.3 Implemented the following features&#8230;

* Added the @sed_pcf_for_each_value()@ tag.

v0.2 Implemented the following features&#8230;

* Added the simple field format
* Added value checking to the @sed_pcf_if_value@ tag
* Added the @sed_pcf_if_field_section@ tag
* Added tags to display thumbnails and images from packed custom fields
* Added parsing of embedded values to allow the use of attributeless tags as arguments to the image, thumbnail and email tags.

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

// ================== PRIVATE FUNCTIONS FOLLOW ===================

function _sed_parse_section_vars( $vars ) {
	$result = array();

	if( is_array($vars) and count($vars) ) {
		foreach( $vars as $k=>$v )
			$result[$k] = parse($v);
		}

	return $result;
	}

function _sed_pcf_txp_fn($atts) {
	//
	//	Generic callback switch. Takes the array it builds from the named section of a custom field and calls a function with the
	// array as an argument. Useful for calling back into the TXP core.
	//
	global $thisarticle;
	$permitted = array( 'email', 'image', 'thumbnail' );

	extract(lAtts(array(
		'txp_fn'	=> '',
		'custom'	=> '',
		'section'	=> '',
		'parse'		=> true,
		'default'	=> '',
	),$atts));

	if( !empty($txp_fn) and empty($section) )
		$section = $txp_fn;

	$result = $default;
	$vars = @$thisarticle[$custom];
	if	(
		!empty($txp_fn) and
		in_array($txp_fn, $permitted) and
		function_exists($txp_fn) and
		!empty($vars) and
		!empty($section)
		) {
		if( 'none' === $section )
			$vars = sed_lib_extract_name_value_pairs( $vars );
		else
			$vars = sed_lib_extract_packed_variable_section( $section , $vars );
		if( is_array( $vars ) ) {
			if( $parse )
				$vars = _sed_parse_section_vars( $vars );
			$result = @$txp_fn( $vars );
			}
		}
	return $result;
	}

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
		if( 'none' === $section )
			$vars = sed_lib_extract_name_value_pairs( $vars );
		else
			$vars = sed_lib_extract_packed_variable_section( $section , $vars );
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
	extract(lAtts(array(
		'custom'	=> '',
		'section'	=> '',
		'variable'  => '',
		'val' => NULL,
	),$atts));

	$value = sed_pcf_get_value( $atts );

	if( $val !== NULL )
		$cond = (@$value == $val);
	else
		$cond = (isset($value) and !empty($value));

	return parse(EvalElse($thing, $cond));
	}

function sed_pcf_if_field_section( $atts , $thing='' ) {
	//
	//	Tests to see if there is a named section of the named custom field.
	//
	global $thisarticle;

	extract(lAtts(array(
		'custom'	=> '',
		'section'	=> '',
	),$atts));

	$cond = false;
	$vars = @$thisarticle[$custom];
	if( !empty( $vars ) and !empty($section) ) {
		$vars = sed_lib_extract_packed_variable_section( $section , $vars );
		$cond = is_array( $vars );
		}

	return parse(EvalElse($thing, $cond));
	}

function sed_pcf_image( $atts ) {
	$atts['txp_fn'] = 'image';
	return _sed_pcf_txp_fn( $atts );
	}

function sed_pcf_thumbnail( $atts ) {
	$atts['txp_fn'] = 'thumbnail';
	return _sed_pcf_txp_fn( $atts );
	}

function sed_pcf_email( $atts ) {
	$atts['txp_fn'] = 'email';
	return _sed_pcf_txp_fn( $atts );
	}

function sed_pcf_for_each_value( $atts , $thing )
	{
	global $thisarticle;

	assert_article();

	$def_custom_name = 'custom1';
	extract( $merged = lAtts( array(
		'debug'		=> 0,
		'name'	=> $def_custom_name,
		'form'  	=> '',
		'label'  	=> '',
		'labeltag'  => '',
		'wraptag'	=> 'ul',
		'break'		=> 'li',
		'class'		=> '',
		), $atts) );

	if( $debug ) echo dmp( $merged );

	$field = @$thisarticle[$name];
	if( empty( $field ) )	# Nothing to do -- the field is empty.
		{
		if( $debug ) echo "Returning early - nothing to do in CF[$name].";
		return '';
		}

	if( empty( $class ) )
		$class = $name;

	if( !empty( $form ) )		# grab the form (if any)
		$thing = fetch_form($form);

	if( empty( $thing ) )		# if no form, and no enclosed thing, use built-in formula...
		$thing = '{value}';

	$out = array();
	$field = do_list( $field );
	foreach( $field as $value )
		{
		$out[] = parse( str_replace( '{value}' , $value , $thing ) );
		}

	return doLabel($label, $labeltag).doWrap($out, $wraptag, $break, $class);
	}

# --- END PLUGIN CODE ---
?>
