<?php

function mysql_timestamp()
{
	return date ( 'Y-m-d H:i:s' );
}

function bake_cookie($name, $value)
{
	if (is_array ( $value )) {
		$value = implode ( ',', $value );
	}
	
	set_cookie ( array (
			'name' => $name . '_session',
			'value' => $value,
			'expire' => 0
	) );
}

function burn_cookie($name)
{
	set_cookie ( array (
			'name' => $name . '_session',
			'value' => '',
			'expire' => NULL
	) );
}

function cookie($name, $xss_clean = NULL)
{
	$name = $name . '_session';
	return get_cookie ( $name, $xss_clean );
}

function create_input($object, $name, $label, $id = NULL, $default_value = FALSE, $required = FALSE, $classes = array())
{
	if (! $id) {
		$id = $name;
	}
	$class = '';
	if ($classes) {
		if (is_array ( $classes )) {
			$class = join ( ' ', $classes );
		} else {
			$class = $classes;
		}
	}
	if ($required) {
		$required = 'required';
	}
	$value = '';
	if ($default_value) {
		$value = cookie ( $name );
	}
	return sprintf ( '<label for="%s">%s: </label><input type="text" name="%s" id="%s" value="%s" class="%s" %s/>', $name, $label, $name, $id, get_value ( $object, $name, $value ), $class, $required );
}

function get_current_year()
{
	if (date ( 'm' ) > 6) { // after June
		$year = date ( 'Y' ) + 1;
	} else {
		$year = date ( 'Y' );
	}
	return $year;
}

/*
 * @params $table varchar table name @params $data array consisting of "where"
 * string or array, and "select" comma-delimited string @returns an array of
 * key-value pairs reflecting a Database primary key and human-meaningful string
 */
function get_keyed_pairs($list, $pairs, $initialBlank = NULL, $other = NULL, $alternate = array())
{
	$output = false;
	if ($initialBlank) {
		$output [''] = '';
	}
	if (! empty ( $alternate )) {
		$output [$alternate ['name']] = $alternate ['value'];
	}
	asort($list);
	foreach ( $list as $item ) {
		$key_name = $pairs [0];
		$key_value = $pairs [1];
		$output [urlencode ( $item->$key_name )] = $item->$key_value;
	}
	if ($other) {
		$output ['other'] = 'Other...';
	}
	return $output;
}

function get_value($object, $item, $default = null)
{
	$output = $default;
	
	if ($default) {
		$output = $default;
	}
	if ($object) {
		
		$var_list = get_object_vars ( $object );
		$var_keys = array_keys ( $var_list );
		if (in_array ( $item, $var_keys )) {
			$output = ($object->$item);
		}
	}
	return $output;
}

function get_as_price($int)
{
	$output = sprintf ( '$%s', number_format ( $int, 2 ) );
	return $output;
}

function get_as_time($time)
{
	$output = '';
	if ($time != '0') {
		$output = $time;
		// $output= date('g:i A',strtotime($time));
	}
	return $output;
}

function get_user_name($user)
{
	return sprintf ( '%s %s', $user->first_name, $user->last_name );
}

function format_latin_name($object)
{
	$output [] = ucfirst ( $object->genus );
	
	if ($object->species) {
		$output [] = strtolower ( $object->species );
	}
	return implode ( ' ', $output );
}

function quark_latin_name($genus, $species, $multiple = FALSE)
{
	if ($multiple) {
		if ($species) {
			$output [] = ucfirst ( substr ( $genus, 0, 1 ) ) . '.';
			$output [] = strtolower ( $species );
		} else {
			$output [] = NULL;
		}
	} else {
		if ($species) {
			$output [] = ucfirst ( $genus );
			$output [] = strtolower ( $species );
		} else if (! $species) {
			$output [] = ucfirst ( $genus );
		}
	}
	return implode ( ' ', $output );
}

function format_catalog($order_id, $category)
{
	return sprintf ( '%s%s', ucfirst ( substr ( $category, 0, 1 ) ), $order_id );
}

function abbr_unit($measure)
{
	switch ($measure) {
		case 'Feet' :
			$output = '&#39;';
			break;
		case 'Inches' :
		default :
			$output = '&quot;';
			break;
	}
	return $output;
}

function clean_string($string)
{
	return preg_replace ( '/[^a-zA-Z0-9\"\.\<\>\=]+/', ' ', $string );
}

/**
 * If a decimal value is equal to its integer value, just return the integer without the decimal points.
 * 
 * @param unknown $value        	
 */
function clean_decimal($value)
{
	if (round ( $value ) == $value) {
		$value = round ( $value );
	}
	return $value;
}

function format_dimensions($min = FALSE, $max = FALSE, $unit = 'Inches', $direction = FALSE)
{
	$min = clean_decimal ( $min );
	$max = clean_decimal ( $max );
	$output = '';
	if (! $min && ! $max) {
		$output = '';
	} elseif ($min == $max || ($min && ! $max)) {
		$output = sprintf ( '%s%s', $min, ucfirst ( $unit ) );
	} elseif ($min == $max || ($max && ! $min)) {
		$output = sprintf ( '%s%s', $max, ucfirst ( $unit ) );
	} else {
		$output = sprintf ( '%s~%s%s', $min, $max, ucfirst ( $unit ) );
	}
	
	if ($direction) {
		$output = sprintf ( '%s%s', $output, $direction );
	}
	
	return $output;
}

function format_address($grower)

{
	$street = array ();
	if ($grower->street_address) {
		$street [] = $grower->street_address;
	}
	if ($grower->po_box) {
		$street [] = $grower->po_box;
	}
	if ($grower->city) {
		$locale = sprintf ( '%s, %s %s', $grower->city, $grower->state, $grower->zip );
	} else {
		$locale = '<span class="highlight">NO CITY ENTERED</span>';
	}
	if (empty ( $street )) {
		$street = '<span class="highlight">NO STREET OR PO BOX ENTERED</span>';
	} else {
		$street = implode ( ' ', $street );
	}
	
	if (empty ( $grower->country )) {
		$country = 'USA';
	} else {
		$country = $grower->country;
	}
	
	return array (
			'street' => $street,
			'locale' => $locale,
			'country' => $country
	);
}

/**
 *
 * @param object $order
 *
 * @return string if the difference between two prices is greater than a set
 *         amount, there is a discrepancy.
 *         This is used exclusively to provide a class tag for the orders where
 *         mistakes may have been entered into the system due to a bug in the
 *         user interface.
 */
function has_price_discrepancy(object $order): string {
	$plant_value = round ( $order->flat_size * $order->plant_cost, 2 );
	$output = '';
	if (abs ( $plant_value - $order->flat_cost ) > .15) {
		$output = 'price-discrepancy';
	}
	return $output;
}

function format_email($object, $field): ?string {
	$email = get_value ( $object, $field );
	if (filter_var ( $email, FILTER_VALIDATE_EMAIL )) {
		$output = sprintf ( '<a href="mailto:%s" title="send an email to %s">%s</a>', $email, $email, $email );
	} else {
		$output = $email;
	}
	return $output;
}

/**
 * Create a custom sql sorting string.
 *
 * @param array $values        	
 * @param string $field        	
 * @return string
 */
function get_custom_order($values = array(NULL,'Hostas','Daylilies','Coleus','Basil','Lavender'), $field = 'name')
{
	// @TODO there should be a UI-available tool for global sorting.
	$order [] = 'CASE';
	for($i = 0; $i < count ( $values ); $i ++) {
		$my_value = $values [$i];
		$x = $i + 1;
		if ($my_value == 'NULL' || $my_value == NULL) {
			$order [] = 'WHEN `$field` IS NULL THEN' . $x;
		} else {
			$order [] = 'WHEN `$field`="'  . $my_value .'" THEN "' . $x;
		}
	}
	$order [] = 'END';
	return implode ( ' ', $order );
}

function css_classify($string)
{
	$string = preg_replace ( '~[^A-z\ ]+~', '', $string );
	$string = str_replace ( ' ', '-', $string );
	$string = strtolower ( $string );
	return $string;
}

function needs_bag($order = NULL)
{
	$output = FALSE;
	if ($order) {
		if (is_array ( $order )) {
			$order = $order[0]; //get just the first order. 
		}
		$needs_pot = $order->pot_size;
		switch($needs_pot){
			case strpos($needs_pot, 'bulb'):
			case strpos($needs_pot, 'bag'):
			case strpos($needs_pot, 'bareroot'):
			case strpos($needs_pot, 'pound'):
				$output = TRUE;
				break;
		}
	}
	return $output;
}
