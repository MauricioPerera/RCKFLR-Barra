<?php
/*
Plugin Name: Barra Superior Personalizada
Plugin URI: https://rckflr.party/
Description: Este plugin agrega una barra superior personalizada a tu sitio de WordPress. La barra puede ser personalizada con contenido propio, fechas de inicio y fin para su visualización, y opciones para mostrar un botón con una URL personalizada. También permite personalizar los colores de la barra y del botón, y seleccionar en qué dispositivos se mostrará la barra (todos, solo escritorio, solo móvil).
Version: 1.1
Author: Mauricio Perera
Author URI: https://www.linkedin.com/in/mauricioperera/
Donate link: https://www.buymeacoffee.com/rckflr
License: GPL2
*/

wp_enqueue_script('flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr');
wp_enqueue_style('flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');

add_action('wp_footer', 'rckflr_add_tool_bar');

function rckflr_add_tool_bar()
{
    $start_date = get_option('bar_start_date');
    $end_date = get_option('bar_end_date');
    $current_date = date('Y-m-d');
    $display_device = get_option('display_device', 'all');

    $is_mobile = wp_is_mobile();

    if (($display_device == 'all') ||
        ($display_device == 'mobile' && $is_mobile) ||
        ($display_device == 'desktop' && !$is_mobile)
    ) {
        if ($current_date >= $start_date && $current_date <= $end_date) {
            $content = get_option('bar_content');
            $button = get_option('bar_button');
            $button_label = get_option('bar_button_label');
            $button_url = get_option('bar_button_url');
            $button_target = get_option('bar_button_target') ? '_blank' : '_self';

            echo '<section class="custom-top-bar">';
            echo $content;

            if ($button) {
                echo ' <a href="' . $button_url . '" class="btn" target="' . $button_target . '">' . $button_label . '</a>';
            }

            echo '</section>';
        }
    }
}

function rckflr_top_bar_styles()
{
    $bar_bg_color = get_option('bar_bg_color', 'black');
    $bar_text_color = get_option('bar_text_color', 'white');
    $button_bg_color = get_option('button_bg_color', 'transparent');
    $button_text_color = get_option('button_text_color', 'white');
    $button_border_color = get_option('button_border_color', 'grey');
?>
<style>
.custom-top-bar {
    position: absolute;
    width: 100%;
    top: 0;
    z-index: 9999;
    padding: 10px;
    background-color: <?php echo $bar_bg_color;
    ?>;
    color: <?php echo $bar_text_color;
    ?>;
    text-align: center;
}

.custom-top-bar .btn {
    display: inline-block;
    border: 1px solid <?php echo $button_border_color;
    ?>;
    color: <?php echo $button_text_color;
    ?>;
    background-color: <?php echo $button_bg_color;
    ?>;
    padding: 5px 10px;
    font-size: 14px;
    margin-left: 10px;
}

.custom-top-bar .btn:hover {
    background-color: #333;
}

body {
    margin-top: 30px;
}

@media (max-width: 768px) {
    body {
        margin-top: 50px;
    }
}
</style>
<?php
}

add_action('wp_head', 'rckflr_top_bar_styles');

add_action('admin_menu', 'rckflr_top_bar_menu');

function rckflr_top_bar_menu()
{
    add_options_page(
        'Configuración de la Barra Superior',
        'Barra Superior',
        'manage_options',
        'custom-top-bar',
        'rckflr_top_bar_options_page'
    );
}

function rckflr_top_bar_options_page()
{
    if (!current_user_can('manage_options')) {
        wp_die('No tienes suficientes permisos para acceder a esta página.');
    }

    if (isset($_POST['bar_content'])) {
        update_option('bar_content', $_POST['bar_content']);
    }
    if (isset($_POST['bar_start_date'])) {
        update_option('bar_start_date', $_POST['bar_start_date']);
    }
    if (isset($_POST['bar_end_date'])) {
        update_option('bar_end_date', $_POST['bar_end_date']);
    }
    update_option('bar_button', isset($_POST['bar_button']));
    if (isset($_POST['bar_button_label'])) {
        update_option('bar_button_label', $_POST['bar_button_label']);
    }
    if (isset($_POST['bar_button_url'])) {
        update_option('bar_button_url', $_POST['bar_button_url']);
    }
    update_option('bar_button_target', isset($_POST['bar_button_target']));
    if (isset($_POST['bar_bg_color'])) {
        update_option('bar_bg_color', $_POST['bar_bg_color']);
    }
    if (isset($_POST['bar_text_color'])) {
        update_option('bar_text_color', $_POST['bar_text_color']);
    }
    if (isset($_POST['button_bg_color'])) {
        update_option('button_bg_color', $_POST['button_bg_color']);
    }
    if (isset($_POST['button_text_color'])) {
        update_option('button_text_color', $_POST['button_text_color']);
    }
    if (isset($_POST['button_border_color'])) {
        update_option('button_border_color', $_POST['button_border_color']);
    }
    if (isset($_POST['display_device'])) {
        update_option('display_device', $_POST['display_device']);
    }

    $content = get_option('bar_content');
    $start_date = get_option('bar_start_date');
    $end_date = get_option('bar_end_date');
    $button = get_option('bar_button');
    $button_label = get_option('bar_button_label');
    $button_url = get_option('bar_button_url');
    $button_target = get_option('bar_button_target');
    $bar_bg_color = get_option('bar_bg_color', 'black');
    $bar_text_color = get_option('bar_text_color', 'white');
    $button_bg_color = get_option('button_bg_color', 'transparent');
    $button_text_color = get_option('button_text_color', 'white');
    $button_border_color = get_option('button_border_color', 'grey');
    $display_device = get_option('display_device', 'all');

    echo '<div class="wrap">';
    echo '<h2>Configuración de la Barra Superior</h2>';
    echo '<form method="post" action="">';
    echo '<table class="form-table">';
    echo '<tr valign="top"><th scope="row">Contenido</th>';
    echo '<td><input type="text" name="bar_content" value="' . $content . '" /></td></tr>';
    echo '<tr valign="top"><th scope="row">Fecha de inicio</th>';
    echo '<td><input type="text" id="start_date" name="bar_start_date" value="' . $start_date . '" /></td></tr>';
    echo '<tr valign="top"><th scope="row">Fecha de cierre</th>';
    echo '<td><input type="text" id="end_date" name="bar_end_date" value="' . $end_date . '" /></td></tr>';

    echo '<script>
	jQuery(document).ready(function() {
		jQuery("#start_date, #end_date").flatpickr({
			enableTime: true,
			dateFormat: "Y-m-d H:i",
		});
	});
	</script>';
    echo '<tr valign="top"><th scope="row">Mostrar botón</th>';
    echo '<td><input type="checkbox" name="bar_button" ' . ($button ? 'checked' : '') . ' /></td></tr>';
    echo '<tr valign="top"><th scope="row">Etiqueta del botón</th>';
    echo '<td><input type="text" name="bar_button_label" value="' . $button_label . '" /></td></tr>';
    echo '<tr valign="top"><th scope="row">URL del botón</th>';
    echo '<td><input type="text" name="bar_button_url" value="' . $button_url . '" /></td></tr>';
    echo '<tr valign="top"><th scope="row">Abrir en una nueva pestaña</th>';
    echo '<td><input type="checkbox" name="bar_button_target" ' . ($button_target ? 'checked' : '') . ' /></td></tr>';
    echo '<tr valign="top"><th scope="row">Color de fondo de la barra</th>';
    echo '<td><input type="color" name="bar_bg_color" value="' . $bar_bg_color . '" /></td></tr>';
    echo '<tr valign="top"><th scope="row">Color del texto de la barra</th>';
    echo '<td><input type="color" name="bar_text_color" value="' . $bar_text_color . '" /></td></tr>';
    echo '<tr valign="top"><th scope="row">Color de fondo del botón</th>';
    echo '<td><input type="color" name="button_bg_color" value="' . $button_bg_color . '" /></td></tr>';
    echo '<tr valign="top"><th scope="row">Color del texto del botón</th>';
    echo '<td><input type="color" name="button_text_color" value="' . $button_text_color . '" /></td></tr>';
    echo '<tr valign="top"><th scope="row">Color del borde del botón</th>';
    echo '<td><input type="color" name="button_border_color" value="' . $button_border_color . '" /></td></tr>';
    echo '<tr valign="top"><th scope="row">Mostrar en</th>';
    echo '<td><select name="display_device">';
    echo '<option value="all"' . ($display_device == 'all' ? ' selected' : '') . '>Todos los dispositivos</option>';
    echo '<option value="desktop"' . ($display_device == 'desktop' ? ' selected' : '') . '>Solo escritorio</option>';
    echo '<option value="mobile"' . ($display_device == 'mobile' ? ' selected' : '') . '>Solo móvil</option>';
    echo '</select></td></tr>';
    echo '</table>';
    echo '<p class="submit"><input type="submit" class="button-primary" value="Guardar cambios" /></p>';
    echo '</form>';
    echo '</div>';
}
?>
