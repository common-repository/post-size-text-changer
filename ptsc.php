<?php
/*
Plugin Name: Post size text changer
Plugin URI: http://www.sooource.net/post-text-size-changer
Description: This plugin allows your blog to change the font size of entries 'on the fly'.
Version: 1.0
Author: TrueFalse
Author URI: http://www.sooource.net
License: GPLv2 or later
Text Domain: ptsc
Domain Path: /languages
*/

# Загрузка локализаций:
load_plugin_textdomain('ptsc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');

# Хуки, действия, фильтры:
add_action('wp_enqueue_scripts', 'ptsc_enqueue_js');    // - подключение JavaScript к фронтенду.
add_action('admin_init', 'ptsc_options_fields');        // - регистрация полей в БД.
add_action('admin_menu', 'ptsc_admin_menu');            // - добавление пункта меню в админку.
add_filter('the_content', 'ptsc_insert_html');          // - вставка HTML-кода в пост.
register_uninstall_hook(__FILE__, 'ptsc_deinstall');    // - крючок деинсталляции.

# Подключение JavaScript:
function ptsc_enqueue_js() {
  wp_enqueue_script('resize', plugins_url('js/resize.js', __FILE__), array('jquery'));
}

# Вставка ссылок плагина:
function ptsc_insert_html($content) {
  if (is_singular())
    return ptsc_default('ptsc_html_before').
    '<a id="increase-font" href="#">[ A+ ] </a>/<a id="decrease-font" href="#">[ A- ] </a>'.
    ptsc_default('ptsc_html_after'). '<div class="resize">'. $content. '</div>';
  else
    return $content;
}

# Регистрируем новую страницу на вкладке "Параметры".
function ptsc_admin_menu() {
  add_options_page(
    __('Post size text changer', 'ptsc'),
    __('Post size text changer', 'ptsc'),
    'manage_options',
    'post-size-text-changer.php',
    'ptsc_options_page');
}

# Показываем форму:
function ptsc_options_page() {
  echo '<div class="wrap">';
  screen_icon();
  echo '<h2>'. __('Post size text changer', 'ptsc'). '</h2>';
  echo '<form method="post" action="options.php">';
  do_settings_sections('ptsc_page');
  settings_fields('ptsc_fields');
  submit_button();
  echo '</form>';
  echo '</div>';
}

# Регистрируем поля в БД и оформляем их отображение.
function ptsc_options_fields() {
  register_setting('ptsc_fields', 'ptsc_html_before');
  register_setting('ptsc_fields', 'ptsc_html_after');
  add_settings_section('ptsc_section_id', NULL, 'ptsc_section_callback', 'ptsc_page');
  add_settings_field('ptsc_setting-html-before-id', __('Before links', 'ptsc'). ':', 'ptsc_html_before_field_callback', 'ptsc_page', 'ptsc_section_id');
  add_settings_field('ptsc_setting-html-after-id', __('After links', 'ptsc'). ':', 'ptsc_html_after_field_callback', 'ptsc_page', 'ptsc_section_id');
}

# Функции вывода элементов формы на экран:
function ptsc_section_callback() {
  echo '<p>'. __('Please configure HTML-wrapper to change the style of inserting the plugin', 'ptsc'). '.</p>';
}
function ptsc_html_before_field_callback() {
  echo '<input type="text" class="regular-text" value="'. esc_html(stripslashes(ptsc_default('ptsc_html_before'))). '" id="ptsc-html-before-field" name="ptsc_html_before" />';
}
function ptsc_html_after_field_callback() {
  echo '<input type="text" class="regular-text" value="'. esc_html(stripslashes(ptsc_default('ptsc_html_after'))). '" id="ptsc-html-after-field" name="ptsc_html_after" />';
}

# Считывание полей из БД и установка значений по умолчанию:
function ptsc_default($field) {
  $default = get_option($field);
  if ($field == 'ptsc_html_before')
    $default = ( !empty($default) ) ? $default: '<p style="text-align:right">';
  elseif ($field == 'ptsc_html_after')
    $default = ( !empty($default) ) ? $default: '</p>';
  return $default;
}

# Хук деисталляции:
function ptsc_deinstall() {
  delete_option('ptsc_html_before');
  delete_option('ptsc_html_after');
}

?>