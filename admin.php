<?php
//change every bn_project to bn_project

$settingsArray = [
	//General options
	'bn_project_section_settings' => [
		'bn_project_warning' => [
			'label' => 'Tekst ostrzeżenia o cenach netto',
			'type' => 'mce',
		],
	],
];

function bn_project_settings_init() {
	global $settingsArray;
	// register a new section in the "bn_project" page
	add_settings_section(
	'bn_project_section_settings',
	__( 'Opcje ogólne', 'bn-netto' ),
	'bn_project_section_settings_cb',
	'bn_project'
	);	
	//register invidual settings based on $settingsArray array 
	foreach ($settingsArray as $setting_cat => $settings) {
		foreach($settings as $setting => $data) {
			register_setting('bn_project', $setting);
			add_settings_field(
				$setting, 
				__( $data['label'], 'bn-netto' ),
				'bn_project_dispatch_options_cb',
				'bn_project',
				$setting_cat, 
				array_merge($data, ['name' => $setting])
			);
		}
	}
}
add_action( 'admin_init', 'bn_project_settings_init' );

function bn_project_section_settings_cb() {};

function bn_project_type_bool_cb($args) {
	$option = get_option( $args['name'] );
	?>
	<select name="<?= $args['name'] ?>">
		<option value="0" <?= $option == "0" ? "selected" : "" ?>>Tak</option>
		<option value="1" <?= $option == "1" ? "selected" : "" ?>>Nie</option>
	</select>
	<?php
}

function bn_project_type_text_cb($args) {
	$option = get_option( $args['name'] );
	?>
	<input type="text" name="<?= $args['name'] ?>" value="<?= $option ?>">
	<?php if(isset($args['description'])) : ?>
		<p class="description"><?= $args['description'] ?></p>
	<?php endif;
}

function bn_project_type_color_cb($args) {
	$option = get_option( $args['name'] );
	?>
	<input type="color" name="<?= $args['name'] ?>" value="<?= $option ?>">
	<?php if(isset($args['description'])) : ?>	
		<p class="description">Należy ustawić opcję nadpisania kolorów powyżej, aby zadziałało.</p>
	<?php endif;
}

function bn_project_type_font_cb($args) {
	global $fonts;

	$option = get_option( $args['name'] );
	?>
	<select name="<?= $args['name'] ?>" id="">
		<?php foreach($fonts as $value => $name) : ?>
			<option value="<?= $value ?>" <?= $value == $option ? "selected" : "" ?>><?= $name ?></option>
		<?php endforeach; ?>
	</select>
	<?php if(isset($args['description'])) : ?>	
		<p class="description">Należy ustawić opcję nadpisania kolorów powyżej, aby zadziałało.</p>
	<?php endif;
}
function bn_project_type_number_cb($args) {
	$option = get_option( $args['name'] );
	?>
	<input type="number" name="<?= $args['name'] ?>" value="<?= $option ?>">
	<?php if(isset($args['description'])) : ?>
		<p class="description"><?= $args['description'] ?></p>
	<?php endif;
}

function bn_project_type_mce_cb($args) {
	$option = get_option( $args['name'] );
	wp_editor($option, $args['name'], [
		'text_area_name'=> $args['name'],//name you want for the textarea
		'tinymce' => true,
		'quicktags' => true,
	]);
}

function bn_project_dispatch_options_cb($args) {
	if ($args['type'] == 'custom') {
		if (function_exists($args['function'])) {
			call_user_func($args['function'], $args);
		}
	} else {
		if (function_exists('bn_project_type_'.$args['type'].'_cb')) {
			call_user_func('bn_project_type_'.$args['type'].'_cb', $args);
		}
	}
}

/**
 * Registers top level menu
 */
function bn_project_options_page() {
    // add top level menu page
    if ( empty ( $GLOBALS['admin_page_hooks']['bn_options'] ) ) {
        add_menu_page(
            'Komunikaty',
            'Komunikaty',
            '',
            'bn_options',
            '',
            plugins_url('brodnet-logo.png', __FILE__ )
        );
    }
	add_submenu_page(
		'bn_options',
		__('Komunikaty', 'bn-netto'),
		__('Komunikaty', 'bn-netto'),
		'manage_options',
		'bn_project_options',
		'bn_project_options_page_html'
	);
}
add_action( 'admin_menu', 'bn_project_options_page' );


/**
* top level menu:
* callback functions
*/
function bn_project_options_page_html() {
	// check user capabilities
	if (! current_user_can('manage_options')) {
		return;
		// add error/update messages
	}
	// check if the user have submitted the settings
	// wordpress will add the "settings-updated" $_GET parameter to the url
	if ( isset( $_GET['settings-updated'] ) ) {
		// add settings saved message with the class of "updated"
		add_settings_error( 'bn_project_messages', 'bn_project_message', __( 'Zapisano ustawienia', 'bn_project' ), 'updated' );
	}

	// show error/update messages
	settings_errors( 'bn_project_messages' );
	?>
	<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form action="options.php" method="post">
	<?php
	// output security fields for the registered setting "bn_project"
	settings_fields( 'bn_project' );
	// output setting sections and their fields
	// (sections are registered for "bn_project", each field is registered to a specific section)
	do_settings_sections( 'bn_project' );
	// output save settings button
	submit_button( 'Zapisz' );
	?>
	</form>
	</div>
	<?php
}