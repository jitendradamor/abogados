<?php
/*
Plugin Name: Negocios CPT Mejorado
Description: Añade un Custom Post Type para Negocios con campos personalizados, incluyendo un mapa que acepta HTML, horarios de apertura detallados y un selector de estados de EE. UU.
Version: 1.0
Author: SPZT
*/

// Enqueue custom admin CSS for the "Negocios CPT Mejorado" plugin
function enqueue_plugin_admin_styles() {
    // Check if we are on the edit page of the custom post type 'negocios'
    global $pagenow;
    $current_screen = get_current_screen();
    if ($pagenow === 'post.php' && $current_screen->post_type === 'negocios') {
        // Enqueue your custom CSS file
        wp_enqueue_style('negocios-cpt-admin-styles', plugins_url('/css/admin-styles.css', __FILE__));
    }
}
add_action('admin_enqueue_scripts', 'enqueue_plugin_admin_styles');

// Registrar el Custom Post Type 'Negocios'
function registrar_cpt_negocios() {
    $labels = array(
        'name'                  => _x('Negocios', 'Post Type General Name', 'mi-plugin-negocios'),
        'singular_name'         => _x('Negocio', 'Post Type Singular Name', 'mi-plugin-negocios'),
        // Otros labels...
    );
    $args = array(
        'label'                 => __('Negocio', 'mi-plugin-negocios'),
        'description'           => __('Post Type para Negocios', 'mi-plugin-negocios'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'author'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true, // Para que sea compatible con Gutenberg.
        'rewrite'               => array('slug' => 'company'), // Cambio aquí para la URL
    );
    register_post_type('negocios', $args);
}
add_action('init', 'registrar_cpt_negocios');

// Registrar taxonomía para Categorías de Servicios
function registrar_taxonomia_categorias_servicios() {
    $labels = array(
        'name'              => _x('Categorías de Servicios', 'taxonomy general name'),
        'singular_name'     => _x('Categoría de Servicio', 'taxonomy singular name'),
        // Otros labels...
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true, // Para que sea compatible con Gutenberg.
    );
    register_taxonomy('categorias_servicios', array('negocios'), $args);
}
add_action('init', 'registrar_taxonomia_categorias_servicios');

/*
* Sorting for negocios_estado
*/
// Add column and label for 'negocios_estado'
function negocios_custom_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') {
            $new_columns['negocios_estado'] = __('Estado', 'mi-plugin-negocios');
            $new_columns['negocios_ciudad'] = __('Ciudad', 'mi-plugin-negocios');
        }
    }
    return $new_columns;
}
add_filter('manage_negocios_posts_columns', 'negocios_custom_columns');

// Display value for 'negocios_estado' column
function negocios_custom_column_content($column_name, $post_id) {
    if ($column_name == 'negocios_estado') {
        $estado = get_post_meta($post_id, 'negocios_estado', true);
        echo $estado; // Output the value of 'negocios_estado'
    }
}
add_action('manage_negocios_posts_custom_column', 'negocios_custom_column_content', 10, 2);

// Make the 'negocios_estado' column sortable
function negocios_sortable_columns($columns) {
    $columns['negocios_estado'] = 'negocios_estado';
    $columns['negocios_ciudad'] = 'negocios_ciudad';
    return $columns;
}
add_filter('manage_edit-negocios_sortable_columns', 'negocios_sortable_columns');

// Handle sorting query for 'negocios_estado' column
function negocios_custom_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    if ($query->get('post_type') === 'negocios' && $query->get('orderby') === 'negocios_estado') {
        $query->set('meta_key', 'negocios_estado');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'negocios_custom_orderby');

/*
* End - Sorting for negocios_estado
*/

/*
* Sorting for negocios_ciudad
*/
// Add column and label for 'negocios_ciudad'
function negocios_columns($columns) {
    $columns['negocios_ciudad'] = __('Ciudad', 'mi-plugin-negocios');
    return $columns;
}
add_filter('manage_negocios_posts_columns', 'negocios_columns');

// Display value for 'negocios_ciudad' column
function negocios_column_content($column_name, $post_id) {
    if ($column_name == 'negocios_ciudad') {
        $ciudad = get_post_meta($post_id, 'negocios_ciudad', true);
        echo $ciudad; // Output the value of 'negocios_ciudad'
    }
}
add_action('manage_negocios_posts_custom_column', 'negocios_column_content', 10, 2);

// Make the 'negocios_ciudad' column sortable
function negocios_sortable($columns) {
    $columns['negocios_ciudad'] = 'negocios_ciudad';
    return $columns;
}
add_filter('manage_edit-negocios_sortable_columns', 'negocios_sortable');

// Handle sorting query for 'negocios_ciudad' column
function negocios_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    if ($query->get('post_type') === 'negocios' && $query->get('orderby') === 'negocios_ciudad') {
        $query->set('meta_key', 'negocios_ciudad');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'negocios_orderby');
/*
* End - Sorting for negocios_ciudad
*/
/**
 * Function to retrieve all unique meta values for a given meta key.
 *
 * @param string $meta_key The meta key for which to retrieve unique values.
 * @return array Array of unique meta values.
 */
function get_unique_meta_values($meta_key) {
    global $wpdb;

    // Query to retrieve unique meta values
    $query = $wpdb->prepare("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = %s", $meta_key);
    $results = $wpdb->get_results($query, ARRAY_A);

    // Extract and filter unique meta values
    $meta_values = array();
    foreach ($results as $result) {
        $meta_values[] = $result['meta_value'];
    }

    return $meta_values;
}
/*
* Filter for negocios_estado
*/
// Add filter dropdowns for 'negocios_estado' and 'negocios_ciudad'
function add_negocios_filters() {
    global $typenow;
    if ($typenow === 'negocios') {
        // Estados de EE. UU.
        $estados = array(
            'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California',
            'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'FL' => 'Florida', 'GA' => 'Georgia',
            'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa',
            'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
            'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri',
            'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey',
            'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio',
            'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
            'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont',
            'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming'
        );
        // Dropdown for 'negocios_estado'
        echo '<select name="filter_by_estado">';
        echo '<option value="">' . __('All State', 'mi-plugin-negocios') . '</option>';
        foreach ($estados as $abreviatura => $nombre) {
            // echo '<option value="' . $abreviatura . '">' . $nombre . '</option>';
            echo '<option value="' . $abreviatura . '" ' . (isset($_GET['filter_by_estado']) && $_GET['filter_by_estado'] === $abreviatura ? ' selected' : '') . '>' . $nombre . '</option>';

        }
        echo '</select>';

        // Get the selected city value from the URL
        $selected_slug = isset($_GET['filter_by_city']) ? $_GET['filter_by_city'] : '';

       // Define your custom meta key and options
        $meta_key = 'negocios_ciudad';
        $plugins = get_unique_meta_values($meta_key); // Get unique meta values for the specified meta key
        // Update the query arguments based on the selected value
        $args = array(
            'post_type'      => 'negocios',
            'posts_per_page' => -1,
            'meta_key'       => $meta_key,
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'meta_query'     => array(
                array(
                    'key'     => $meta_key,
                    'compare' => 'EXISTS',
                ),
            ),
        );
        // Update the meta query if a specific value is selected
        if ($selected_slug != '' && in_array($selected_slug, $plugins)) {
            $args['meta_query'][] = array(
                'key'     => $meta_key,
                'value'   => $selected_slug,
                'compare' => '=',
            );
        }
        // Retrieve posts based on the updated arguments
        $queries = get_posts($args);
        // Output the meta values
        $meta_values = array_map(function($post) use ($meta_key) {
            return get_post_meta($post->ID, $meta_key, true);
        }, $queries);
        // Check if an option has been selected
        if( isset( $_GET['filter_by_city'] ) ) {
            $current_plugin = $_GET['filter_by_city']; // Check if option has been selected
        } ?>
        <!--  Dropdown for 'negocios_ciudad' -->
        <select name="filter_by_city" id="filter_by_city">
            <option value="all" <?php selected('all', $selected_slug); ?>><?php _e('All City', 'mi-plugin-negocios'); ?></option>
            <?php foreach ($plugins as $value) { ?>
                <option value="<?php echo esc_attr($value); ?>" <?php selected($value, $selected_slug); ?>><?php echo esc_attr($value); ?></option>
            <?php } ?>
        </select><?php

        // categorias_servicios taxonomy dropdown
        $categorias_servicios = get_terms(array(
            'taxonomy' => 'categorias_servicios', // Replace 'estado_taxonomy' with your actual taxonomy name
            'hide_empty' => false,
        ));
        if ($categorias_servicios) {
            echo '<select name="filter_by_categorias_servicios">';
            echo '<option value="">' . __('All Services', 'mi-plugin-negocios') . '</option>';
            foreach ($categorias_servicios as $term) {
                echo '<option value="' . $term->slug . '" ' . (isset($_GET['filter_by_categorias_servicios']) && $_GET['filter_by_categorias_servicios'] === $term->slug ? ' selected' : '') . '>' . $term->name . '</option>';
            }
            echo '</select>';
        }

        // Author dropdown
        $authors = get_users(array('orderby' => 'display_name'));
        echo '<select name="filter_by_author">';
        echo '<option value="">' . __('Author', 'mi-plugin-negocios') . '</option>';
        foreach ($authors as $author) {
            echo '<option value="' . $author->ID . '" ' . selected($_GET['filter_by_author'], $author->ID, false) . '>' . $author->display_name . '</option>';
        }
        echo '</select>';
    }
}
add_action('restrict_manage_posts', 'add_negocios_filters');
/*
* End - Filter for negocios_estado
*/

/*
* Filtrado por Servicios
*/

// Filter posts based on taxonomy dropdowns for 'categorias_servicios'
function filter_negocios_by_taxonomy($query) {
    global $pagenow;
    $type = 'negocios';
    if ($pagenow === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === $type) {
        // Filter by 'negocios_estado' taxonomy
        if (isset($_GET['filter_by_categorias_servicios']) && $_GET['filter_by_categorias_servicios'] !== '') {
            $query->query_vars['tax_query'][] = array(
                'taxonomy' => 'categorias_servicios', // Replace 'categorias_servicios' with your actual taxonomy name
                'field' => 'slug',
                'terms' => $_GET['filter_by_categorias_servicios'],
            );
        }

        // Filter by 'negocios_estado' meta key
        if (isset($_GET['filter_by_estado'])) {
            $query->query_vars['meta_key'] = 'negocios_estado';
            $query->query_vars['meta_value'] = $_GET['filter_by_estado'];
        }

        // Filter by author
        if (isset($_GET['filter_by_author']) && $_GET['filter_by_author'] !== '') {
            $query->set('author', $_GET['filter_by_author']);
        }

        // Filter by city
        if(isset( $_GET['filter_by_city'] ) && $_GET['filter_by_city'] !='all'){
            // If conditions are met, modify the query variables to filter by city
            // Set the meta key to 'negocios_ciudad'
            $query->query_vars['meta_key'] = 'negocios_ciudad';
            // Set the meta value to the selected city
            $query->query_vars['meta_value'] = $_GET['filter_by_city'];
            // Set the meta compare to '=' to match exact values
            $query->query_vars['meta_compare'] = '=';
        }
    }
}
add_action('parse_query', 'filter_negocios_by_taxonomy');
/*
* End - Filtrado por Servicios
*/

// Añadir meta boxes para campos personalizados en el CPT 'Negocios'
function negocios_add_meta_boxes() {
    add_meta_box(
        'negocios_info_adicional',
        __('Información Adicional del Negocio', 'mi-plugin-negocios'),
        'negocios_meta_box_callback',
        'negocios',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'negocios_add_meta_boxes');

// Callback para mostrar los campos personalizados en el meta box
function negocios_meta_box_callback($post) {
    wp_nonce_field('negocios_save_meta_box_data', 'negocios_meta_box_nonce');

    // Estados de EE. UU.
    $estados = [
        'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California',
        'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'FL' => 'Florida', 'GA' => 'Georgia',
        'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa',
        'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
        'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri',
        'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey',
        'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio',
        'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
        'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont',
        'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming'
    ];

    // Campos personalizados
    $campos = [
        'direccion' => 'Dirección',
        'estado' => 'Estado',
        'ciudad' => 'Ciudad',
        'estrellas' => 'Estrellas',
        'reseñas' => 'Reseñas',
        'telefono' => 'Teléfono',
        'sitio_web' => 'Sitio Web',
        'mapa' => 'Mapa (HTML permitido)'
    ];

    foreach ($campos as $id => $label) {
        $valor = get_post_meta($post->ID, 'negocios_' . $id, true);
        echo '<p><label for="negocios_' . $id . '">' . esc_html($label) . '</label><br>';

        if ($id === 'estado') {
            echo '<select id="negocios_estado" name="negocios_estado">';
            foreach ($estados as $abreviatura => $nombre) {
                echo '<option value="' . esc_attr($abreviatura) . '"' . selected($valor, $abreviatura, false) . '>' . esc_html($nombre) . '</option>';
            }
            echo '</select>';
        } elseif ($id === 'mapa') {
            echo '<textarea id="negocios_' . $id . '" name="negocios_' . $id . '" rows="4" cols="50" class="large-text code">' . esc_textarea($valor) . '</textarea>';
        } else {
            echo '<input type="text" id="negocios_' . $id . '" name="negocios_' . $id . '" value="' . esc_attr($valor) . '" size="25" />';
        }
        echo '</p><hr>';
    }
    // Horarios de apertura
    echo '<p><strong>' . __('Horarios de Apertura', 'mi-plugin-negocios') . '</strong></p>';
    $dias = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    foreach ($dias as $dia) {
        $horario_apertura = get_post_meta($post->ID, 'negocios_horario_apertura_' . strtolower($dia), true);
        $horario_cierre = get_post_meta($post->ID, 'negocios_horario_cierre_' . strtolower($dia), true);
        $abierto_24_horas = get_post_meta($post->ID, 'negocios_abierto_24_horas_' . strtolower($dia), true);

        echo '<p><label for="negocios_horario_apertura_' . strtolower($dia) . '">' . ucfirst($dia) . ':</label><br>';
        echo '<input type="time" id="negocios_horario_apertura_' . strtolower($dia) . '" name="negocios_horario_apertura_' . strtolower($dia) . '" value="' . esc_attr($horario_apertura) . '">';
        echo ' - ';
        echo '<input type="time" id="negocios_horario_cierre_' . strtolower($dia) . '" name="negocios_horario_cierre_' . strtolower($dia) . '" value="' . esc_attr($horario_cierre) . '">';
        // Checkbox para abierto 24 horas
        echo '<input type="checkbox" id="negocios_abierto_24_horas_' . strtolower($dia) . '" name="negocios_abierto_24_horas_' . strtolower($dia) . '" value="1"' . checked($abierto_24_horas, '1', false) . '>';
        echo '<label for="negocios_abierto_24_horas_' . strtolower($dia) . '">' . __('Abierto 24 horas', 'mi-plugin-negocios') . '</label>';
        echo '</p><hr>';
    }

    // Mostrar el campo del logo primero
    $logo_valor = get_post_meta($post->ID, 'negocios_logo', true);
    echo '<div class="logo-row">';
        echo '<p><strong>' . __('Logo', 'mi-plugin-negocios') . '</strong></p>';
        echo '<input type="hidden" id="negocios_logo" name="negocios_logo" value="' . esc_attr($logo_valor ) . '" size="25" />';
            echo '<div class="logo-upload">';
                echo '<img id="logo-preview" src="' . esc_attr($logo_valor ) . '" style="max-width: 200px; display: block; margin-top: 10px;" />';
                echo '<input type="button" id="upload_logo_button" class="button-secondary" value="Subir Logo"/>';
            echo '</div>';
        echo '<script>
            jQuery(document).ready(function($){
                $("#upload_logo_button").click(function(e) {
                    e.preventDefault();
                    var image = wp.media({ 
                        title: "Subir Logo",
                        multiple: false
                    }).open()
                    .on("select", function(e){
                        var uploaded_image = image.state().get("selection").first();
                        var image_url = uploaded_image.toJSON().url;
                        $("#negocios_logo").val(image_url);
                        $("#logo-preview").attr("src", image_url);
                    });
                });
            });
        </script>';
    echo '</><hr>';

    // Reviews repeater field
    echo '<h4>' . __('Reseñas', 'mi-plugin-negocios') . '</h4>';
    $reviews = get_post_meta($post->ID, 'negocios_reviews', true);
    echo '<div id="reviews-container">';
    if ($reviews && is_array($reviews)) {
        foreach ($reviews as $review) {
            echo '<div class="review">';
                echo '<div class="review-col">';
                    echo '<div class="inner-col"><label>Detalles de la reseña</label><textarea name="negocios_review_description[]" rows="4" cols="100" placeholder="' . __('Detalles de la reseña', 'mi-plugin-negocios') . '">' . esc_textarea($review['description']) . '</textarea></div>';
                    echo '<div class="inner-col"><label>Autor</label><input class="negocios_review_text" type="text" name="negocios_review_text[]" value="' . esc_attr($review['text']) . '" size="50" placeholder="' . __('Autor', 'mi-plugin-negocios') . '" /></div>';

                    echo '<div class="inner-col"><label>Enlace</label><input class="negocios_review_url" type="url" name="negocios_review_url[]" value="' . esc_url($review['url']) . '" size="50" placeholder="' . __('Enlace', 'mi-plugin-negocios') . '" /></div>';
                echo '</div>';
                    echo '<span class="remove-review">Quitar</span>';
            echo '</div>';
        }
    }
    echo '</div>';
    echo '<button class="button button-primary button-large" id="add-review">Agregar una opinión</button>
    <br> <br>';
    
    // Text field after reviews repeater
    $ver_todas_opiniones = get_post_meta($post->ID, 'ver_todas_opiniones', true);
    echo '<div class="opiniones"><label for="ver_todas_opiniones">' . __('Ver todas las opiniones en Google', 'mi-plugin-negocios') . '</label><br>';
    echo '<input type="url" id="ver_todas_opiniones" name="ver_todas_opiniones" value="' . esc_attr($ver_todas_opiniones) . '" placeholder="' . __('Ver todas las opiniones en Google', 'mi-plugin-negocios') . '"></div>';

    echo '<hr>';

    // JavaScript for adding and removing reviews
    echo '<script>
        jQuery(document).ready(function($) {
            $("#add-review").click(function() {
                $("#reviews-container").append(\'<div class="review"><div class="review-col"><div class="inner-col"><label>Detalles de la reseña</label><textarea name="negocios_review_description[]" rows="4" cols="100" placeholder="' . __('Detalles de la reseña', 'mi-plugin-negocios') . '"></textarea></div><div class="inner-col"><label>Autor</label><input type="text" class="negocios_review_text" name="negocios_review_text[]" size="50" placeholder="' . __('Autor', 'mi-plugin-negocios') . '" /></div><div class="inner-col"><label>Enlace</label><input type="text" class="negocios_review_url" name="negocios_review_url[]" size="50" placeholder="' . __('Enlace', 'mi-plugin-negocios') . '" /></div></div><span class="remove-review">Quitar</span></div>\');
            });
            $("#reviews-container").on("click", ".remove-review", function() {
                $(this).parent().remove();
            });
        });
    </script>';
}

// Guardar los datos de los campos personalizados
function negocios_save_meta_box_data($post_id) {
    if (!isset($_POST['negocios_meta_box_nonce']) || !wp_verify_nonce($_POST['negocios_meta_box_nonce'], 'negocios_save_meta_box_data')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (isset($_POST['post_type']) && 'negocios' == $_POST['post_type'] && !current_user_can('edit_post', $post_id)) {
        return;
    }

    $campos = [
        'direccion',
        'estado',
        'ciudad',
        'estrellas',
        'reseñas',
        'telefono',
        'sitio_web',
        'mapa', // Asegúrate de sanitizar correctamente este campo, ya que acepta HTML.
        'logo',
        'ver_todas_opiniones'
    ];

    foreach ($campos as $campo) {
        if (isset($_POST['negocios_' . $campo])) {
            if ($campo === 'mapa' || $campo === 'logo') {
                update_post_meta($post_id, 'negocios_' . $campo, $_POST['negocios_' . $campo]); // Considera sanitizar este campo adecuadamente
            } else {
                update_post_meta($post_id, 'negocios_' . $campo, sanitize_text_field($_POST['negocios_' . $campo]));
            }
        }
    }


    // Guardar horarios de apertura y la opción de abierto 24 horas
    $dias = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    foreach ($dias as $dia) {
        if (isset($_POST['negocios_horario_apertura_' . strtolower($dia)])) {
            update_post_meta($post_id, 'negocios_horario_apertura_' . strtolower($dia), sanitize_text_field($_POST['negocios_horario_apertura_' . strtolower($dia)]));
        }
        if (isset($_POST['negocios_horario_cierre_' . strtolower($dia)])) {
            update_post_meta($post_id, 'negocios_horario_cierre_' . strtolower($dia), sanitize_text_field($_POST['negocios_horario_cierre_' . strtolower($dia)]));
        }
        // Guardar la opción de abierto 24 horas
        $abierto_24_horas = isset($_POST['negocios_abierto_24_horas_' . strtolower($dia)]) ? '1' : '';
        update_post_meta($post_id, 'negocios_abierto_24_horas_' . strtolower($dia), $abierto_24_horas);
    }

    // Guardar reviews
    if (isset($_POST['negocios_review_text']) && isset($_POST['negocios_review_description'])) {
        $reviews = [];
        $text_fields = array_map('sanitize_text_field', $_POST['negocios_review_text']);
        $description_fields = array_map('sanitize_text_field', $_POST['negocios_review_description']);
        $url_fields = array_map('esc_url_raw', $_POST['negocios_review_url']);

        foreach ($text_fields as $index => $text) {
            $reviews[] = [
                'text' => $text,
                'description' => $description_fields[$index],
                'url' => $url_fields[$index],
            ];
        }

        update_post_meta($post_id, 'negocios_reviews', $reviews);
    }

    // Guardar el valor de ver_todas_opiniones
    if (isset($_POST['ver_todas_opiniones'])) {
        update_post_meta($post_id, 'ver_todas_opiniones', sanitize_text_field($_POST['ver_todas_opiniones']));
    }
}
add_action('save_post', 'negocios_save_meta_box_data');

// Incluir el archivo del shortcode
include_once('shortcode-negocios.php');

// Filter to include custom single template
function include_custom_single_template( $template ) {
    if ( is_singular( 'negocios' ) ) {
        $custom_template = plugin_dir_path( __FILE__ ) . 'single-company.php';
        if ( file_exists( $custom_template ) ) {
            return $custom_template;
        }
    }
    return $template;
}
add_filter( 'template_include', 'include_custom_single_template' );


// Register HTML ADS Block Widget Area
function wpb_widgets_init() {
    register_sidebar( array(
        'name'          => 'Advertisement Block',
        'id'            => 'custom-html-ads-block',
        'before_widget' => '<div class="custom-widget-container">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2>',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'wpb_widgets_init' );