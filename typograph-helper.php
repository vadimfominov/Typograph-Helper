<?php
/*
Plugin Name: Typograph Helper
Plugin URI: https://github.com/vadimfominov/Typograph-Helper
Description: Автоматическая типографская обработка текста с добавлением неразрывных пробелов после предлогов
Version: 1.0.0
Author: Vadim Fominov
Author URI: https://t.me/vadimfominov
License: GPL2
Text Domain: typograph-helper
*/


// Регистрация настроек
function typography_plugin_settings() {
	register_setting('typography_options_group', 'typography_prepositions');
	register_setting('typography_options_group', 'typography_elements');
}
add_action('admin_init', 'typography_plugin_settings');

// Создание страницы настроек
function typography_plugin_menu() {
	add_options_page(
		'Настройки типографики', 
		'Типографика', 
		'manage_options', 
		'typography-settings', 
		'typography_settings_page'
	);
}
add_action('admin_menu', 'typography_plugin_menu');

// Страница настроек
function typography_settings_page() {
	?>
	<div class="wrap">
		<h1>Настройки типографики</h1>
		<form method="post" action="options.php">
			<?php
			settings_fields('typography_options_group');
			do_settings_sections('typography-settings');
			?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Предлоги</th>
					<td>
						<textarea 
							name="typography_prepositions" 
							rows="5" 
							cols="50"
						><?php 
							echo esc_textarea(get_option('typography_prepositions', 
							'и,а,в,на,с,к,по,во,о,об,у,от,до,из,за,для,под,про,над,без,через,при,перед')); 
						?></textarea>
						<p class="description">Введите предлоги через запятую</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">HTML-элементы</th>
					<td>
						<input 
							type="text" 
							name="typography_elements" 
							value="<?php 
								echo esc_attr(get_option('typography_elements', 'h2,h4,p,span')); 
							?>" 
							size="50"
						/>
						<p class="description">Введите HTML-теги через запятую (например: h1,h2,p,div)</p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

// Добавление скрипта
function add_typography_script() {
	// Получаем предлоги из настроек
	$prepositions = get_option('typography_prepositions', 
		'и,а,в,на,с,к,по,во,о,об,у,от,до,из,за,для,под,про,над,без,через,при,перед');
	
	// Получаем элементы из настроек
	$elements = get_option('typography_elements', 'h2,h4,p,span');
	
	// Подготавливаем скрипт с динамическими предлогами и элементами
	wp_enqueue_script(
		'typography-script', 
		plugin_dir_url(__FILE__) . 'js/typography.js', 
		array(), 
		'1.2', 
		true
	);
	
	// Передаем предлоги и элементы в скрипт
	wp_localize_script('typography-script', 'typographySettings', array(
		'prepositions' => explode(',', $prepositions),
		'elements' => explode(',', $elements)
	));
}
add_action('wp_enqueue_scripts', 'add_typography_script');

// Добавление ссылки на настройки рядом с кнопкой "Деактивировать"
function typography_plugin_action_links($links) {
	$settings_link = '<a href="' . 
		admin_url('options-general.php?page=typography-settings') . 
		'">Настройки</a>';
	array_unshift($links, $settings_link);
	return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'typography_plugin_action_links');