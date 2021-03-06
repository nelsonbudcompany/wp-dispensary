<?php
/**
 * The file that defines the metaboxes used by the various custom post types
 *
 * @link       http://www.wpdispensary.com
 * @since      1.0.0
 *
 * @package    WP_Dispensary
 * @subpackage WP_Dispensary/admin/post-types
 */

/**
 * THC% & CBD% metabox
 *
 * Adds the THC% & CBD% metabox to specific custom post types
 *
 * @since    1.3.0
 */
function add_thccbd_metaboxes() {
	$screens = array( 'flowers', 'concentrates' );

	foreach ( $screens as $screen ) {
		add_meta_box(
			'wpdispensary_thccbd',
			__( 'THC% & CBD%', 'wp-dispensary' ),
			'wpdispensary_thccbd',
			$screen,
			'side',
			'default'
		);
	}

}

add_action( 'add_meta_boxes', 'add_thccbd_metaboxes' );

/**
 * Building the metabox
 */
function wpdispensary_thccbd() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="thccbdmeta_noncename" id="thccbdmeta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the thccbd data if its already been entered */
	$thc	= get_post_meta( $post->ID, '_thc', true );
	$cbd	= get_post_meta( $post->ID, '_cbd', true );

	/** Echo out the fields */
	echo '<div class="pricebox">';
	echo '<p>THC %:</p>';
	echo '<input type="number" name="_thc" value="' . $thc  . '" class="widefat" />';
	echo '</div>';
	echo '<div class="pricebox">';
	echo '<p>CBD %:</p>';
	echo '<input type="number" name="_cbd" value="' . $cbd  . '" class="widefat" />';
	echo '</div>';

}

/** Save the Metabox Data */

function wpdispensary_save_thccbd_meta( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if ( ! wp_verify_nonce( $_POST['thccbdmeta_noncename'], plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */

	$thccbd_meta['_thc']	= $_POST['_thc'];
	$thccbd_meta['_cbd']	= $_POST['_cbd'];

	/** Add values of $thccbd_meta as custom fields */

	foreach ( $thccbd_meta as $key => $value ) { /** Cycle through the $thccbd_meta array! */
		if ( $post->post_type == 'revision' ) { /** Don't store custom data twice */
			return;
		}
		$value = implode( ',', (array) $value ); // If $value is an array, make it a CSV (unlikely)
		if ( get_post_meta( $post->ID, $key, false ) ) { // If the custom field already has a value
			update_post_meta( $post->ID, $key, $value );
		} else { // If the custom field doesn't have a value
			add_post_meta( $post->ID, $key, $value );
		}
		if ( ! $value ) { /** Delete if blank */
			delete_post_meta( $post->ID, $key );
		}
	}

}

add_action( 'save_post', 'wpdispensary_save_thccbd_meta', 1, 2 ); // save the custom fields


/**
 * Prices metabox
 *
 * Adds the Prices metabox to specific custom post types
 *
 * @since    1.0.0
 */

function add_prices_metaboxes() {

	$screens = array( 'flowers', 'concentrates' );

	foreach ( $screens as $screen ) {
		add_meta_box(
			'wpdispensary_prices',
			__( 'Product Prices', 'wp-dispensary' ),
			'wpdispensary_prices',
			$screen,
			'normal',
			'default'
		);
	}

}

add_action( 'add_meta_boxes', 'add_prices_metaboxes' );

function wpdispensary_prices() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="pricesmeta_noncename" id="pricesmeta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the prices data if its already been entered */
	$halfgram	= get_post_meta( $post->ID, '_halfgram', true );
	$gram		= get_post_meta( $post->ID, '_gram', true );
	$eighth		= get_post_meta( $post->ID, '_eighth', true );
	$quarter	= get_post_meta( $post->ID, '_quarter', true );
	$halfounce	= get_post_meta( $post->ID, '_halfounce', true );
	$ounce		= get_post_meta( $post->ID, '_ounce', true );

	/** Echo out the fields */
	echo '<div class="pricebox">';
	echo '<p>1/2 Gram:</p>';
	echo '<input type="number" name="_halfgram" value="' . $halfgram  . '" class="widefat" />';
	echo '</div>';
	echo '<div class="pricebox">';
	echo '<p>Gram:</p>';
	echo '<input type="number" name="_gram" value="' . $gram  . '" class="widefat" />';
	echo '</div>';
	echo '<div class="pricebox">';
	echo '<p>1/8 Ounce:</p>';
	echo '<input type="number" name="_eighth" value="' . $eighth  . '" class="widefat" />';
	echo '</div>';
	echo '<div class="pricebox">';
	echo '<p>1/4 Ounce:</p>';
	echo '<input type="number" name="_quarter" value="' . $quarter  . '" class="widefat" />';
	echo '</div>';
	echo '<div class="pricebox">';
	echo '<p>1/2 Ounce:</p>';
	echo '<input type="number" name="_halfounce" value="' . $halfounce  . '" class="widefat" />';
	echo '</div>';
	echo '<div class="pricebox">';
	echo '<p>Ounce:</p>';
	echo '<input type="number" name="_ounce" value="' . $ounce  . '" class="widefat" />';
	echo '</div>';

}

/** Save the Metabox Data */

function wpdispensary_save_prices_meta( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if ( ! wp_verify_nonce( $_POST['pricesmeta_noncename'], plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */

	$prices_meta['_halfgram']	= $_POST['_halfgram'];
	$prices_meta['_gram']		= $_POST['_gram'];
	$prices_meta['_eighth']		= $_POST['_eighth'];
	$prices_meta['_quarter']	= $_POST['_quarter'];
	$prices_meta['_halfounce']	= $_POST['_halfounce'];
	$prices_meta['_ounce']		= $_POST['_ounce'];

	/** Add values of $prices_meta as custom fields */

	foreach ( $prices_meta as $key => $value ) { /** Cycle through the $prices_meta array! */
		if ( $post->post_type == 'revision' ) { /** Don't store custom data twice */
			return;
		}
		$value = implode( ',', (array) $value ); /** If $value is an array, make it a CSV (unlikely) */
		if ( get_post_meta( $post->ID, $key, false ) ) { /** If the custom field already has a value */
			update_post_meta( $post->ID, $key, $value );
		} else { /** If the custom field doesn't have a value */
			add_post_meta( $post->ID, $key, $value );
		}
		if ( ! $value ) { /** Delete if blank */
			delete_post_meta( $post->ID, $key );
		}
	}

}

add_action( 'save_post', 'wpdispensary_save_prices_meta', 1, 2 ); /** save the custom fields */

/**
 * Pre-Roll Flower Type metabox
 *
 * Adds the Flower Type metabox to all the pre-roll custom post type
 *
 * @since    1.0.0
 */

class WPDispensary_Prerolls {
	var $FOR_POST_TYPE = 'prerolls';
	var $SELECT_POST_TYPE = 'flowers';
	var $SELECT_POST_LABEL = 'Flower';
	var $box_id;
	var $box_label;
	var $field_id;
	var $field_label;
	var $field_name;
	var $meta_key;
	function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}
	function admin_init() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
		$this->meta_key     = "_selected_{$this->SELECT_POST_TYPE}";
		$this->box_id       = "select-{$this->SELECT_POST_TYPE}-metabox";
		$this->field_id     = "selected_{$this->SELECT_POST_TYPE}";
		$this->field_name   = "selected_{$this->SELECT_POST_TYPE}";
		$this->box_label    = __( 'Pre-roll Strain', 'wp-dispensary' );
		$this->field_label  = __( "Choose {$this->SELECT_POST_LABEL}", 'wp-dispensary' );
	}
	function add_meta_boxes() {
		add_meta_box(
			$this->box_id,
			$this->box_label,
			array( $this, 'select_box' ),
			$this->FOR_POST_TYPE,
			'side'
		);
	}
	function select_box( $post ) {
		$selected_post_id = get_post_meta( $post->ID, $this->meta_key, true );
		global $wp_post_types;
		$save_hierarchical = $wp_post_types[ $this->SELECT_POST_TYPE ]->hierarchical;
		$wp_post_types[ $this->SELECT_POST_TYPE ]->hierarchical = true;
		wp_dropdown_pages( array(
			'id' => $this->field_id,
			'name' => $this->field_name,
			'selected' => empty( $selected_post_id ) ? 0 : $selected_post_id,
			'post_type' => $this->SELECT_POST_TYPE,
			'show_option_none' => $this->field_label,
		));
		$wp_post_types[ $this->SELECT_POST_TYPE ]->hierarchical = $save_hierarchical;
	}
	function save_post( $post_id, $post ) {
		if ( $post->post_type == $this->FOR_POST_TYPE && isset( $_POST[ $this->field_name ] ) ) {
			$prerollflower = sanitize_text_field( $_POST['selected_flowers'] );
			update_post_meta( $post_id, $this->meta_key, $prerollflower );
		}
	}
}
new WPDispensary_Prerolls();

/**
 * Grower Flower Type metabox
 *
 * Adds a drop down of all flowers to the Growers menu type
 *
 * @since    1.7.0
 */

class WPDispensary_Growers {
	var $FOR_POST_TYPE = 'growers';
	var $SELECT_POST_TYPE = 'flowers';
	var $SELECT_POST_LABEL = 'Flower';
	var $box_id;
	var $box_label;
	var $field_id;
	var $field_label;
	var $field_name;
	var $meta_key;
	function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}
	function admin_init() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ), 10, 2 );
		$this->meta_key     = "_selected_{$this->SELECT_POST_TYPE}";
		$this->box_id       = "select-{$this->SELECT_POST_TYPE}-metabox";
		$this->field_id     = "selected_{$this->SELECT_POST_TYPE}";
		$this->field_name   = "selected_{$this->SELECT_POST_TYPE}";
		$this->box_label    = __( 'Flower Strain', 'wp-dispensary' );
		$this->field_label  = __( "Choose {$this->SELECT_POST_LABEL}", 'wp-dispensary' );
	}
	function add_meta_boxes() {
		add_meta_box(
			$this->box_id,
			$this->box_label,
			array( $this, 'select_box' ),
			$this->FOR_POST_TYPE,
			'side'
		);
	}
	function select_box( $post ) {
		$selected_post_id = get_post_meta( $post->ID, $this->meta_key, true );
		global $wp_post_types;
		$save_hierarchical = $wp_post_types[ $this->SELECT_POST_TYPE ]->hierarchical;
		$wp_post_types[ $this->SELECT_POST_TYPE ]->hierarchical = true;
		wp_dropdown_pages( array(
			'id' => $this->field_id,
			'name' => $this->field_name,
			'selected' => empty( $selected_post_id ) ? 0 : $selected_post_id,
			'post_type' => $this->SELECT_POST_TYPE,
			'show_option_none' => $this->field_label,
		));
		$wp_post_types[ $this->SELECT_POST_TYPE ]->hierarchical = $save_hierarchical;
	}
	function save_post( $post_id, $post ) {
		if ( $post->post_type == $this->FOR_POST_TYPE && isset( $_POST[ $this->field_name ] ) ) {
			$growerflower = sanitize_text_field( $_POST['selected_flowers'] );
			update_post_meta( $post_id, $this->meta_key, $growerflower );
		}
	}
}
new WPDispensary_Growers();

/**
 * Prices metabox for the following menu types:
 * Pre-Rolls, Edibles, Growers
 *
 * Adds a price metabox to all of the above custom post types
 *
 * @since    1.0.0
 */

function add_singleprices_metaboxes() {

	$screens = array( 'prerolls', 'edibles', 'growers' );

	foreach ( $screens as $screen ) {
		add_meta_box(
			'wpdispensary_singleprices',
			__( 'Product Price', 'wp-dispensary' ),
			'wpdispensary_singleprices',
			$screen,
			'side',
			'default'
		);
	}

}

add_action( 'add_meta_boxes', 'add_singleprices_metaboxes' );

function wpdispensary_singleprices() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="singlepricesmeta_noncename" id="singlepricesmeta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the prices data if its already been entered */
	$priceeach	= get_post_meta( $post->ID, '_priceeach', true );

	/** Echo out the fields */
	echo '<p>Price per unit:</p>';
	echo '<input type="text" name="_priceeach" value="' . $priceeach  . '" class="widefat" />';

}

/** Save the Metabox Data */

function wpdispensary_save_singleprices_meta( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if ( ! wp_verify_nonce( $_POST['singlepricesmeta_noncename'], plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */

	$prices_meta['_priceeach']	= $_POST['_priceeach'];

	/** Add values of $prices_meta as custom fields */

	foreach ( $prices_meta as $key => $value ) { /** Cycle through the $prices_meta array! */
		if ( $post->post_type == 'revision' ) { /** Don't store custom data twice */
			return;
		}
		$value = implode( ',', (array) $value ); /** If $value is an array, make it a CSV (unlikely) */
		if ( get_post_meta( $post->ID, $key, false ) ) { /** If the custom field already has a value */
			update_post_meta( $post->ID, $key, $value );
		} else { /** If the custom field doesn't have a value */
			add_post_meta( $post->ID, $key, $value );
		}
		if ( ! $value ) { /** Delete if blank */
			delete_post_meta( $post->ID, $key );
		}
	}

}

add_action( 'save_post', 'wpdispensary_save_singleprices_meta', 1, 2 ); /** save the custom fields */


/**
 * Seed Count metabox for the following menu types:
 * Growers
 *
 * Adds a seed count metabox to all of the above custom post types
 *
 * @since    1.7.0
 */

function add_seedcount_metaboxes() {

	$screens = array( 'growers' );

	foreach ( $screens as $screen ) {
		add_meta_box(
			'wpdispensary_seedcount',
			__( 'Seed Count', 'wp-dispensary' ),
			'wpdispensary_seedcount',
			$screen,
			'side',
			'default'
		);
	}

}

add_action( 'add_meta_boxes', 'add_seedcount_metaboxes' );

function wpdispensary_seedcount() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="seedcountmeta_noncename" id="seedcountmeta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the seed count data if its already been entered */
	$seedcount	= get_post_meta( $post->ID, '_seedcount', true );

	/** Echo out the fields */
	echo '<p>Seeds per unit:</p>';
	echo '<input type="text" name="_seedcount" value="' . $seedcount  . '" class="widefat" />';

}

/** Save the Metabox Data */

function wpdispensary_save_seedcount_meta( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if ( ! wp_verify_nonce( $_POST['seedcountmeta_noncename'], plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */

	$seedcount['_seedcount']	= $_POST['_seedcount'];

	/** Add values of $seedcount as custom fields */

	foreach ( $seedcount as $key => $value ) { /** Cycle through the $seedcount array! */
		if ( $post->post_type == 'revision' ) { /** Don't store custom data twice */
			return;
		}
		$value = implode( ',', (array) $value ); /** If $value is an array, make it a CSV (unlikely) */
		if ( get_post_meta( $post->ID, $key, false ) ) { /** If the custom field already has a value */
			update_post_meta( $post->ID, $key, $value );
		} else { /** If the custom field doesn't have a value */
			add_post_meta( $post->ID, $key, $value );
		}
		if ( ! $value ) { /** Delete if blank */
			delete_post_meta( $post->ID, $key );
		}
	}

}

add_action( 'save_post', 'wpdispensary_save_seedcount_meta', 1, 2 ); /** save the custom fields */


/**
 * Clone Count metabox for the following menu types:
 * Growers
 *
 * Adds a clone count metabox to all of the above custom post types
 *
 * @since    1.7.0
 */

function add_clonecount_metaboxes() {

	$screens = array( 'growers' );

	foreach ( $screens as $screen ) {
		add_meta_box(
			'wpdispensary_clonecount',
			__( 'Clone Count', 'wp-dispensary' ),
			'wpdispensary_clonecount',
			$screen,
			'side',
			'default'
		);
	}

}

add_action( 'add_meta_boxes', 'add_clonecount_metaboxes' );

function wpdispensary_clonecount() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="clonecountmeta_noncename" id="clonecountmeta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the clone count data if its already been entered */
	$clonecount	= get_post_meta( $post->ID, '_clonecount', true );

	/** Echo out the fields */
	echo '<p>Clones per unit:</p>';
	echo '<input type="text" name="_clonecount" value="' . $clonecount  . '" class="widefat" />';

}

/** Save the Metabox Data */

function wpdispensary_save_clonecount_meta( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if ( ! wp_verify_nonce( $_POST['clonecountmeta_noncename'], plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */

	$clonecount['_clonecount']	= $_POST['_clonecount'];

	/** Add values of $clonecount as custom fields */

	foreach ( $clonecount as $key => $value ) { /** Cycle through the $clonecount array! */
		if ( $post->post_type == 'revision' ) { /** Don't store custom data twice */
			return;
		}
		$value = implode( ',', (array) $value ); /** If $value is an array, make it a CSV (unlikely) */
		if ( get_post_meta( $post->ID, $key, false ) ) { /** If the custom field already has a value */
			update_post_meta( $post->ID, $key, $value );
		} else { /** If the custom field doesn't have a value */
			add_post_meta( $post->ID, $key, $value );
		}
		if ( ! $value ) { /** Delete if blank */
			delete_post_meta( $post->ID, $key );
		}
	}

}

add_action( 'save_post', 'wpdispensary_save_clonecount_meta', 1, 2 ); /** save the custom fields */


/**
 * Edibles THC content metabox
 *
 * Adds a THC content metabox to the edibles custom post type
 *
 * @since    1.0.0
 */

function add_thc_cbd_mg_metaboxes() {

	$screens = array( 'edibles' );

	foreach ( $screens as $screen ) {
		add_meta_box(
			'wpdispensary_thc_cbd_mg',
			__( 'Serving information', 'wp-dispensary' ),
			'wpdispensary_thc_cbd_mg',
			$screen,
			'side',
			'default'
		);
	}

}

add_action( 'add_meta_boxes', 'add_thc_cbd_mg_metaboxes' );

function wpdispensary_thc_cbd_mg() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="thccbdmgmeta_noncename" id="thccbdmgmeta_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the thc mg data if its already been entered */
	$thcmg			= get_post_meta( $post->ID, '_thcmg', true );
	$cbdmg			= get_post_meta( $post->ID, '_cbdmg', true );
	$thccbdservings	= get_post_meta( $post->ID, '_thccbdservings', true );

	/** Echo out the fields */
	echo '<p>THC mg per serving:</p>';
	echo '<input type="number" name="_thcmg" value="' . $thcmg  . '" class="widefat" />';
	echo '<p>CBD mg per serving:</p>';
	echo '<input type="number" name="_cbdmg" value="' . $cbdmg  . '" class="widefat" />';
	echo '<p>Servings:</p>';
	echo '<input type="number" name="_thccbdservings" value="' . $thccbdservings  . '" class="widefat" />';

}

/** Save the Metabox Data */

function wpdispensary_save_thc_cbd_mg_meta( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if ( ! wp_verify_nonce( $_POST['thccbdmgmeta_noncename'], plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */

	$thc_cbd_mg_meta['_thcmg']			= $_POST['_thcmg'];
	$thc_cbd_mg_meta['_cbdmg']			= $_POST['_cbdmg'];
	$thc_cbd_mg_meta['_thccbdservings']	= $_POST['_thccbdservings'];

	/** Add values of $thccbdmg_meta as custom fields */

	foreach ( $thc_cbd_mg_meta as $key => $value ) { /** Cycle through the $thc_cbd_mg_meta array! */
		if ( $post->post_type == 'revision' ) { /** Don't store custom data twice */
			return;
		}
		$value = implode( ',', (array) $value ); /** If $value is an array, make it a CSV (unlikely) */
		if ( get_post_meta( $post->ID, $key, false ) ) { /** If the custom field already has a value */
			update_post_meta( $post->ID, $key, $value );
		} else { /** If the custom field doesn't have a value */
			add_post_meta( $post->ID, $key, $value );
		}
		if ( ! $value ) { /** Delete if blank */
			delete_post_meta( $post->ID, $key );
		}
	}

}

add_action( 'save_post', 'wpdispensary_save_thc_cbd_mg_meta', 1, 2 ); /** save the custom fields */


/**
 * Topicals THC & CBD content metabox
 *
 * Adds a THC & CBD content metabox to the topicals custom post type
 *
 * @since    1.4.0
 */
function add_thccbdtopical_metaboxes() {

	$screens = array( 'topicals' );

	foreach ( $screens as $screen ) {
		add_meta_box(
			'wpdispensary_thccbdtopical',
			__( 'Product Information', 'wp-dispensary' ),
			'wpdispensary_thccbdtopical',
			$screen,
			'side',
			'default'
		);
	}

}

add_action( 'add_meta_boxes', 'add_thccbdtopical_metaboxes' );

/**
 * Building the metabox
 */
function wpdispensary_thccbdtopical() {
	global $post;

	/** Noncename needed to verify where the data originated */
	echo '<input type="hidden" name="thccbdtopical_noncename" id="thccbdtopical_noncename" value="' .
	wp_create_nonce( plugin_basename( __FILE__ ) ) . '" />';

	/** Get the thc mg data if its already been entered */
	$pricetopicals	= get_post_meta( $post->ID, '_pricetopical', true );
	$thctopicals	= get_post_meta( $post->ID, '_thctopical', true );
	$cbdtopicals	= get_post_meta( $post->ID, '_cbdtopical', true );
	$sizetopicals	= get_post_meta( $post->ID, '_sizetopical', true );

	/** Echo out the fields */
	echo '<p>Price per unit:</p>';
	echo '<input type="text" name="_pricetopical" value="' . $pricetopicals  . '" class="widefat" />';
	echo '<p>Size (oz):</p>';
	echo '<input type="text" name="_sizetopical" value="' . $sizetopicals  . '" class="widefat" />';
	echo '<p>THC mg:</p>';
	echo '<input type="number" name="_thctopical" value="' . $thctopicals  . '" class="widefat" />';
	echo '<p>CBD mg:</p>';
	echo '<input type="number" name="_cbdtopical" value="' . $cbdtopicals  . '" class="widefat" />';

}

/** Save the Metabox Data */

function wpdispensary_save_thccbdtopical_meta( $post_id, $post ) {

	/**
	 * Verify this came from the our screen and with proper authorization,
	 * because save_post can be triggered at other times
	 */
	if ( ! wp_verify_nonce( $_POST['thccbdtopical_noncename'], plugin_basename( __FILE__ ) ) ) {
		return $post->ID;
	}

	/** Is the user allowed to edit the post or page? */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
	}

	/**
	 * OK, we're authenticated: we need to find and save the data
	 * We'll put it into an array to make it easier to loop though.
	 */

	$thcmgtopical_meta['_pricetopical']	= $_POST['_pricetopical'];
	$thcmgtopical_meta['_thctopical']	= $_POST['_thctopical'];
	$thcmgtopical_meta['_cbdtopical']	= $_POST['_cbdtopical'];
	$thcmgtopical_meta['_sizetopical']	= $_POST['_sizetopical'];

	/** Add values of $thcmg_meta as custom fields */

	foreach ( $thcmgtopical_meta as $key => $value ) { /** Cycle through the $thcmg_meta array! */
		if ( $post->post_type == 'revision' ) { /** Don't store custom data twice */
			return;
		}
		$value = implode( ',', (array) $value ); /** If $value is an array, make it a CSV (unlikely) */
		if ( get_post_meta( $post->ID, $key, false ) ) { /** If the custom field already has a value */
			update_post_meta( $post->ID, $key, $value );
		} else { /** If the custom field doesn't have a value */
			add_post_meta( $post->ID, $key, $value );
		}
		if ( ! $value ) { /** Delete if blank */
			delete_post_meta( $post->ID, $key );
		}
	}

}

add_action( 'save_post', 'wpdispensary_save_thccbdtopical_meta', 1, 2 ); /** Save the custom fields */
