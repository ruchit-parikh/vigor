<?php

/**
 * Plugin Name: Validar identidad CF7
 * Plugin URI: https://www.david-sancho.com
 * Description: Poder validar los campos del DNI, NIF, NIE y CIF con el plugin Contact Form 7
 * Version: 1.0
 * Author: David Sancho
 * Author URI: https://www.david-sancho.com
 * License: GPLv2 or later
 * Text Domain: videntidad
 *
 * # LICENSE
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

add_action('admin_notices', 'cf7_requerido_action');

if (!function_exists('cf7_requerido_action')) {
    function cf7_requerido_action() {
        $cf7_validar_messages = "";

        if (file_exists(WP_PLUGIN_DIR . '/contact-form-7/wp-contact-form-7.php')) {
            if (!is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
                $cf7_validar_messages = 'El plugin validación DNI-NIF-NIE-CIF requiere que el plugin Contact Form 7 esté activado';
            }
        } else {
            $cf7_validar_messages = 'El plugin validación DNI-NIF-NIE-CIF requiere el plugin Contact Form 7, <a href="https://wordpress.org/plugins/contact-form-7/">descargalo aquí</a>.';
        }

        if (!empty($cf7_validar_messages)) {
            echo '<div id="message" class="error cf7_validar_messages">';
            echo '<p class="cf7_validar_messages"><b>'. esc_html_e($cf7_validar_messages, 'videntidad').'</b></p>';
            echo '</div>';
        }
    }
}

if (file_exists(WP_PLUGIN_DIR . '/contact-form-7/wp-contact-form-7.php')) {
    if (is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
        if (!function_exists('cf7_validation_callback_action')) {
            // validation_callback_action
            function cf7_validation_callback_action($identidad) {
                $identidad = strtoupper($identidad);

                for ($i = 0; $i < 9; $i ++) {
                    $num_array[$i] = substr($identidad, $i, 1);
                }

                if (!preg_match('/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/', $identidad)) {
                    return 0;
                }

                if (preg_match('/(^[0-9]{8}[A-Z]{1}$)/', $identidad)) {
                    if ($num_array[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($identidad, 0, 8) % 23, 1)) {
                        return 'cf-validate-dni';
                    } else {
                        return -1;
                    }
                }

                $sum_pattern = $num_array[2] + $num_array[4] + $num_array[6];

                for ($i = 1; $i < 8; $i += 2) {
                    $sum_pattern += substr((2 * $num_array[$i]), 0, 1) + substr((2 * $num_array[$i]), 1, 1);
                }

                $n = 10 - substr($sum_pattern, strlen($sum_pattern) - 1, 1);

                if (preg_match('/^[KLM]{1}/', $identidad)) {
                    if ($num_array[8] == chr(64 + $n) || $num_array[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($identidad, 1, 8) % 23, 1)) {
                        return 'cf-validate-nif';
                    } else {
                        return -1;
                    }
                }

                if (preg_match('/^[ABCDEFGHJNPQRSUVW]{1}/', $identidad)) {
                    if ($num_array[8] == chr(64 + $n) || $num_array[8] == substr($n, strlen($n) - 1, 1)) {
                        return 'cf-validate-cif';
                    } else {
                        return -2;
                    }
                }

                if (preg_match('/^[XYZ]{1}/', $identidad)) {
                    if ($num_array[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr(str_replace(array('X','Y','Z'), array('0','1','2'), $identidad), 0, 8) % 23, 1)) {
                        return 'cf-validate-nie';
                    } else {
                        return -3;
                    }
                }

                return 0;
            }
        }

        if (!function_exists('cf7_validacion_nif_cif_nie')) {
            function cf7_validacion_nif_cif_nie($results, $tag) {
                $result_actual = $results['valid'];
                $field_data = sanitize_text_field($_POST[$name]);
                $type = $tag['basetype'];
                $name = $tag['name'];

                if ($type == 'identidad*') {
                    $results->invalidate($tag, wpcf7_get_message('invalid_identidad'));
                }

                $identidad = sanitize_text_field($_POST[$name]);

                if ($identidad != '') {
                    $validated_identidad = cf7_validation_callback_action($identidad);
                    $allowed_types = explode(',', $_POST['__identificacion']);

                    if (!in_array($validated_identidad, $allowed_types, true)) {
                        $message = wpcf7_get_message('invalid_identidad');

                        if (!empty($allowed_types)) {
                            $str = ''; $result = [];

                            foreach ($allowed_types as $type) {
                                $result[] = strtoupper(substr($type, strlen('cf-validate-')));
                            }

                            $str .= implode(', ', $result);

                            $message = str_replace("{{identificador}}", $str, $message);
                        }

                        $results->invalidate($tag, $message);
                    } else {
                        if ($result_actual == false) {
                            $results['valid'] = false;
                        } else {
                            $results['valid'] = true;
                        }
                    }
                }
                //output
                return $results;
            }
        }
        //Add Filter Validación HOOK
        add_filter('wpcf7_validate_identidad', 'cf7_validacion_nif_cif_nie', 10, 2);
        add_filter('wpcf7_validate_identidad*', 'cf7_validacion_nif_cif_nie', 10, 2);
    }

    if (!function_exists('cf7_nif_cif_nie_validacion_message')) {
        function cf7_nif_cif_nie_validacion_message($messages) {
            return array_merge(
                $messages,
                array(
                    'invalid_identidad' => array(
                    'description' => __("Validacion DNI, NIF, NIE o CIF", 'contact-form-7'),
                    'default' => __("Escribe un DNI, NIF, NIE o CIF válido", 'contact-form-7'),
                ),
            )
            );
        }
    }
    /* wpcf7_messages HOOK*/
    add_filter('wpcf7_messages', 'cf7_nif_cif_nie_validacion_message', 10, 1);

    add_action('wpcf7_init', 'custom_add_form_tag_identidad');

    function custom_add_form_tag_identidad() {
        wpcf7_add_form_tag(
            array('identidad', 'identidad*'),
            'custom_identidad_form_tag_handler',
            array(
                'name-attr' => true,
                'selectable-values' => true,
                'multiple-controls-container' => true,
            )
        );
    }

    function custom_identidad_form_tag_handler($tag) {
        $tag = new WPCF7_FormTag($tag);
        $defined_types = ['cf-validate-dni', 'cf-validate-cif', 'cf-validate-nie', 'cf-validate-nif'];

        if (empty($tag->name)) {
            return '';
        }

        $validation_error = wpcf7_get_validation_error($tag->name);

        $atts = array();

        $class = wpcf7_form_controls_class($tag->type);
        $atts['class'] = $tag->get_class_option($class);

        if ($validation_error) {
            $class .= ' wpcf7-not-valid';
        }

        $atts['id'] = $tag->get_id_option();
        $allowed_types = array();

        //push all of custom selected types
        foreach ($tag->values as $key => $value) {
            if (!in_array($value, $defined_types) && $key === 0) 
                continue;
            
            array_push($allowed_types, $value);
        }

        $value = (string) reset($tag->values);

        if ($tag->has_option('placeholder') or $tag->has_option('watermark')) {
            $atts['placeholder'] = $value;
            $value = '';
        }

        $value = $tag->get_default_option($value);

        $value = wpcf7_get_hangover($tag->name, $value);

        $atts['value'] = in_array($value, $defined_types) ? '' : $value;

        $atts['name'] = $tag->name;
        $atts = wpcf7_format_atts($atts);

        $html = sprintf(
            '<span class="wpcf7-form-control-wrap %1$s"><input name="__identificacion" type="hidden" value="' . implode(',', $allowed_types) . '"><input type="text" %2$s />%3$s</span>',
            sanitize_html_class($tag->name),
            $atts,
            $validation_error
        );
        return $html;
    }

    /* Tag generator */
    add_action('wpcf7_admin_init', 'wpcf7_add_tag_generator_identidad', 99, 0);
    function wpcf7_add_tag_generator_identidad() {
        $tag_generator = WPCF7_TagGenerator::get_instance();
        $tag_generator->add('identidad', __('identidad', 'contact-form-7'), 'wpcf7_tag_generator_identidad');
    }

    function wpcf7_tag_generator_identidad($contact_form, $args = '') {
        $args = wp_parse_args($args, array());
        $type = 'identidad';

        $description = __("Generate a form-tag for a identidad input field.", 'contact-form-7'); 
?>
        <div class="control-box">
            <fieldset>
                <legend><?php echo sprintf(esc_html($description)); ?></legend>

                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><?php echo esc_html(__('Field type', 'contact-form-7')); ?></th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text"><?php echo esc_html(__('Field type', 'contact-form-7')); ?></legend>
                                    <label><input type="checkbox" name="required" /> <?php echo esc_html(__('Required field', 'contact-form-7')); ?></label>
                                </fieldset>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"><label for="<?php echo esc_attr($args['content'] . '-name'); ?>"><?php echo esc_html(__('Name', 'contact-form-7')); ?></label></th>
                            <td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr($args['content'] . '-name'); ?>" /></td>
                        </tr>

                        <tr>
                            <th scope="row"><label for="<?php echo esc_attr($args['content'] . '-values'); ?>"><?php echo esc_html(__('Default value', 'contact-form-7')); ?></label></th>
                            <td><input type="text" id="identidad-values" name="values" class="oneline" id="<?php echo esc_attr($args['content'] . '-values'); ?>" /><br />
                            <label><input type="checkbox" name="placeholder" class="option" /> <?php echo esc_html(__('Use this text as the placeholder of the field', 'contact-form-7')); ?></label></td>
                        </tr>

                        <tr>
                            <th scope="row"><label for="<?php echo esc_attr($args['content'] . '-id'); ?>"><?php echo esc_html(__('Id attribute', 'contact-form-7')); ?></label></th>
                            <td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr($args['content'] . '-id'); ?>" /></td>
                        </tr>

                        <tr>
                            <th scope="row"><label for="<?php echo esc_attr($args['content'] . '-class'); ?>"><?php echo esc_html(__('Class attribute', 'contact-form-7')); ?></label></th>
                            <td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr($args['content'] . '-class'); ?>" /></td>
                        </tr>

                        <tr>
                            <th scope="row"><label for="<?php echo esc_attr($args['content'] . '-identificacion'); ?>"><?php echo esc_html(__('Identificacion attribute', 'contact-form-7')); ?></label></th>
                            <td>
                                <select name="identificacion_select" id="identificacion_select" class="identificacion_selectvalue oneline option" id="<?php echo esc_attr($args['content'] . '-identificacion_select'); ?>">
                                    <option value="-1">ALL</option>
                                    <option value="0">Custom</option>
                                </select><br><br>

                                <div id="identificacion" style="display: none;">
                                    <label for="dni" style="margin-right: 10px;">
                                        <input checked type="checkbox" class="dnivalue identificacion" name="identificacion" id="dni" value="cf-validate-dni"> DNI
                                    </label>
                                    <label for="nif" style="margin-right: 10px;">
                                        <input checked type="checkbox" class="nifvalue identificacion" name="identificacion" id="nif" value="cf-validate-nif"> NIF
                                    </label>
                                    <label for="cif" style="margin-right: 10px;">
                                        <input checked type="checkbox" class="cifvalue identificacion" name="identificacion" id="cif" value="cf-validate-cif"> CIF
                                    </label>
                                    <label for="nie" style="margin-right: 10px;">
                                        <input checked type="checkbox" class="nievalue identificacion" name="identificacion" id="nie" value="cf-validate-nie"> NIE
                                    </label>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>
        </div>

        <div class="insert-box">
            <input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

            <div class="submitbox">
                <button id="identidad-btn" class="button button-primary insert-tag"><?php echo esc_attr(__('Insert Tag', 'contact-form-7')); ?></button>
                <input class="button button-primary insert-tag" name="_identidad_btn" type="button" style="display: none;">
            </div>

            <br class="clear" />

            <p class="description mail-tag"><label for="<?php echo esc_attr($args['content'] . '-mailtag'); ?>"><?php echo sprintf(esc_html(__("To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'contact-form-7')), '<strong><span class="mail-tag"></span></strong>'); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr($args['content'] . '-mailtag'); ?>" /></label></p>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function() {
                jQuery(document).on('change', "[data-id='identidad']", function() {
                    updateShortcode();
                });

                jQuery(document).on('change', '#identificacion_select', function() {
                    var $this = jQuery(this);
                    var $options = jQuery('#identificacion');

                    if ($this.val() == -1) {
                        jQuery('.identificacion').prop('checked', true);
                        $options.hide();
                    } else {
                        jQuery('.identificacion').prop('checked', false);
                        $options.show();
                    }
                });

                jQuery(document).on('click', '#identidad-btn', function(e) {
                    if (validateIdentificationForm() === true) {
                        jQuery('input[name="_identidad_btn"]').trigger("click");
                    }
                })
            });

            function updateShortcode() {
                var identificacions = [];
                var $shortcode = jQuery(document).find("[name='identidad']");

                jQuery('.identificacion:checked').each(function(key, item) {
                    var $item = jQuery(item);
                    identificacions.push('"' + $item.val() + '"');
                });

                $shortcode.val($shortcode.val().replace(']', ' ') + identificacions.join(' ') + ']');
            }

            function validateIdentificationForm() {
                var $value = jQuery("#identidad-values");
                var types = ['cf-validate-dni', 'cf-validate-nif', 'cf-validate-cif', 'cf-validate-nie'];
                var $options = jQuery('.identificacion:checked');
                
                $value.closest('td').find('span').remove();
                jQuery('#identificacion span').remove();

                if (types.includes($value.val().toLowerCase())) {
                    $value.closest('td').append('<span style="color: #ff0000; display: block;">El valor no debe ser DNI, NIF, CIF, NIE.</span>');

                    return false;
                }

                if ($options.length <= 0) {
                    jQuery('#identificacion').append('<span style="color: #ff0000; display: block">Se debe seleccionar al menos uno.</span>');

                    return false;
                }

                return true;
            }
        </script>

<?php
    }
}
?>