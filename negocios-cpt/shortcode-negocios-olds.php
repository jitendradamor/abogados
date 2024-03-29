<?php
// Función para encolar la hoja de estilos
function negocios_enqueue_styles()
{
    wp_enqueue_style('negocios-styles', plugin_dir_url(__FILE__) . 'negocios-styles.css');
}
add_action('wp_enqueue_scripts', 'negocios_enqueue_styles');

// Función para agregar el código JavaScript directamente en el footer
function negocios_add_footer_scripts()
{
    echo '<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".chevron").forEach(function(element) {
            element.addEventListener("click", function() {
                var nextElement = this.nextElementSibling;
                // Loop through siblings until we find the element with the class "hidden-hours"
                while (nextElement && !nextElement.classList.contains("hidden-hours")) {
                    nextElement = nextElement.nextElementSibling;
                }
                if (nextElement) {
                    nextElement.style.display = nextElement.style.display === "none" ? "block" : "none";
                    this.classList.toggle("active");
                    this.innerHTML = this.classList.contains("active") ? "&#8722;" : "&#65122;"; // Chevron up or down
                }
            });
        });
    });
    </script>';
}
add_action('wp_footer', 'negocios_add_footer_scripts');

// Función del shortcode para mostrar el detalle del negocio
function mostrar_detalle_negocio($atts)
{
    $atts = shortcode_atts(array(
        'ciudad' => '',
        'cantidad_negocios' => 10, // Número predeterminado de negocios a mostrar
        'boton_servicios_url' => '',
    ), $atts, 'detalle_negocio');

    $ciudad = $atts['ciudad'];
    $cantidad_negocios = $atts['cantidad_negocios'];

    ob_start();

    // Consulta para obtener negocios por ciudad
    $args = array(
        'post_type' => 'negocios',
        'posts_per_page' => $cantidad_negocios,
        'meta_query' => array(
            array(
                'key' => 'negocios_ciudad',
                'value' => $ciudad,
                'compare' => 'LIKE',
            ),
        ),
    );

    $negocios_query = new WP_Query($args);

    if ($negocios_query->have_posts()) {
        while ($negocios_query->have_posts()) {
            $negocios_query->the_post();
            $negocio_id = get_the_ID();

            // Retrieve logo URL
            $logo_url = get_post_meta($negocio_id, 'negocios_logo', true);

            $direccion = get_post_meta($negocio_id, 'negocios_direccion', true);
            $estrellas = get_post_meta($negocio_id, 'negocios_estrellas', true);
            $reseñas = get_post_meta($negocio_id, 'negocios_reseñas', true);
            $telefono = get_post_meta($negocio_id, 'negocios_telefono', true);
            $sitio_web = get_post_meta($negocio_id, 'negocios_sitio_web', true);
            $mapa = get_post_meta($negocio_id, 'negocios_mapa', true);

            $dias = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

            echo '<div class="negocio-detalle">';
            echo '<div class="columna1">';
            echo '<div class="top-section">';
            // Display logo if URL is available
            if (!empty($logo_url)) {
                echo '<img src="' . esc_url($logo_url) . '" alt="Logo" style="max-width: 200px; height: auto;">';
            }
            echo '<h3>' . get_the_title($negocio_id) . '</h3>';

            // Get the permalink of the current post
            $permalink = get_permalink();
            // Concatenate the permalink with HTML for the button
            echo '<a href="' . $permalink . '"><button type="button">Ver Servicios</button></a>';
            echo '<a href= tel:' . esc_html($telefono) . '><button type="button">Contactar</button></a>';

            echo '<p><span class="icon">&#9733;</span> ' . esc_html($estrellas) . ' rating</p>';
            echo '<p>y ' . esc_html($reseñas) . ' comentarios</p>';

            // echo '<hr>'; // Insertado después de la sección de rating

            // Assuming $post_id is the ID of the custom post
            $terms = get_the_terms($negocio_id, 'categorias_servicios'); // Replace 'categorias_servicios' with the name of your custom taxonomy

            echo '</div>';
            echo '<div class="contacto">';
            $content = get_the_content($negocio_id);
            $trimmed_content = wp_trim_words( $content, 200, '<a href="'. get_permalink() .'">...[ read more ]</a>' ); ?>
            <p><?php echo $trimmed_content; ?></p>

            <div class="address">
                <!--  -->
                <img src="<?php echo plugin_dir_url(__FILE__); ?>img/location_on.svg" alt="Phone Icon">
                <p><?php echo get_post_meta($negocio_id, 'negocios_direccion', true); ?></p>
            </div>
            <ul class="info-contacto">
            <?php
            // Ícono de teléfono con SVG
            echo '<li><img src="' . plugin_dir_url(__FILE__) . 'img/phone-icon.svg" alt="Phone Icon" >' . esc_html($telefono) . '</li>';
            // Ícono de sitio web con SVG
            echo '<li><img src="' . plugin_dir_url(__FILE__) . 'img/link.svg" alt="Web Icon" ><a href="' . esc_url($sitio_web) . '">' . esc_html($sitio_web) . '</a></li>';
            // Ícono de calendario con SVG
            echo ' <li><img src="' . plugin_dir_url(__FILE__) . 'img/calendar-icon.svg" alt="Calendar Icon" >';
            // Horarios de hoy
            $dia_actual = strtolower(date('l'));
            $horario_apertura_hoy = get_post_meta($negocio_id, 'negocios_horario_apertura_' . $dia_actual, true);
            $horario_cierre_hoy = get_post_meta($negocio_id, 'negocios_horario_cierre_' . $dia_actual, true);
            $abierto_24_horas_hoy = get_post_meta($negocio_id, 'negocios_abierto_24_horas_' . $dia_actual, true);
            if ($abierto_24_horas_hoy) {
                echo 'Open 24 hours';
            } elseif ($horario_apertura_hoy && $horario_cierre_hoy) {
                echo ucfirst($dia_actual) . ': ' . esc_html($horario_apertura_hoy) . ' - ' . esc_html($horario_cierre_hoy);
            } else {
                echo 'Closed';
            }
            echo '<span class="chevron">&#65122;</span>';
            echo '<div class="hidden-hours" style="display:none;">';
            foreach ($dias as $dia) {
                $horario_apertura = get_post_meta($negocio_id, 'negocios_horario_apertura_' . strtolower($dia), true);
                $horario_cierre = get_post_meta($negocio_id, 'negocios_horario_cierre_' . strtolower($dia), true);
                $abierto_24_horas = get_post_meta($negocio_id, 'negocios_abierto_24_horas_' . strtolower($dia), true);
                echo '<p>';
                if ($abierto_24_horas) {
                    echo ucfirst($dia) . ': Open 24 hours';
                } elseif ($horario_apertura && $horario_cierre) {
                    echo ucfirst($dia) . ': ' . esc_html($horario_apertura) . ' - ' . esc_html($horario_cierre);
                } else {
                    echo ucfirst($dia) . ': Closed';
                }
                echo '</p>';
            }
            echo '</div></li>'; // Cierre de .hidden-hours
            echo '</ul>'; // Cierre de .info-contacto
            echo '</div>'; // Cierre de .info-contacto
            // echo '<button onclick="location.href=\'tel:' . esc_attr($telefono) . '\'">Call</button>';
            if (!empty($atts['boton_servicios_url'])) {
                echo '<button onclick="window.location.href=\'' . esc_url($atts['boton_servicios_url']) . '\'">Services</button>';
            }
            echo '</div>'; // Cierre de .columna1

            echo '<div class="columna2">';
            echo $mapa; // Asegúrate de que este contenido sea seguro antes de imprimirlo directamente


            echo '</div>'; // Cierre de .columna2

            echo '<div class="columna3">';
            ?>
            <ul class="e-address-contacto">
            <?php
            // Ícono de teléfono con SVG
           // echo '<li><a href= tel:' . esc_html($telefono) . '><img src="' . plugin_dir_url(__FILE__) . 'img/phone-icon.svg" alt="Phone Icon" > Llamar</a></li>';
            // Ícono de sitio web con SVG
           // echo ' <li><a href="mailto:yahoo@gmail.com"><img src="' . plugin_dir_url(__FILE__) . 'img/calendar-icon.svg" alt="Calendar Icon" > Email</a></li>';
            // Ícono de calendario con SVG
           // echo '<li><a href="' . esc_url($sitio_web) . '"><img src="' . plugin_dir_url(__FILE__) . 'img/web-icon.svg" alt="Web Icon" >Sitio Web</a></li></ul>';

            // Get the reviews
            $reviews = get_post_meta($negocio_id, 'negocios_reviews', true);

            if (!empty($reviews)) {
                echo '<div class="reviews-row">';
                echo '<div class="reviews-title"><h2>' . __('Opiniones Reales', 'mi-plugin-negocios') . '*</h2></div>';

                echo '<div class="review-details">';
                foreach ($reviews as $review) {
                    echo '<div class="review">';
                    echo '<h5>' . esc_html($review['text']) . '</h5>';
                    echo '<p>' . esc_html($review['description']) . '</p>';
                    // Check if URL is provided and not empty
                    if (!empty($review['url'])) {
                        echo '<div class="link"><a target="_blank" href="' . esc_url($review['url']) . '">' . __('Leer mas en Google', 'mi-plugin-negocios') . '</a></div>';
                    }
                    echo '</div>';
                }
                echo '</div>'; // End .reviews
                echo '</div>'; // End .reviews
            } else {
                echo '<p>No reviews available.</p>';
            }

            echo '<div style="clear: both;"></div>';
            echo '</div>'; // Cierre de .negocio-detalle
        }
    } else {
        echo '<p>No comapny found in ' . esc_html($ciudad) . '.</p>';
    }

    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('detalle_negocio', 'mostrar_detalle_negocio');


// Start the Shortcode For the Buisness State
function mostrar_detalles_negocio($atts)
{
    $atts = shortcode_atts(array(
        'estado' => 'estado',
        'cantidad_negocios' => 10,
        'boton_servicios_url' => '',
    ), $atts, 'detalle_negocios');

    $estado = $atts['estado'];
    $cantidad_negocios = $atts['cantidad_negocios'];

    ob_start();

    // Consulta para obtener negocios por estado
    $args = array(
        'post_type' => 'negocios',
        'posts_per_page' => $cantidad_negocios,
        'meta_query' => array(
            array(
                'key' => 'negocios_estado',
                'value' => $estado,
                'compare' => 'LIKE',
            ),
        ),
    );

    $negocios_querys = new WP_Query($args);


    // echo "<pre>";
    // print_r($negocios_querys);
    // echo "</pre>";

    if ($negocios_querys->have_posts()) {
        while ($negocios_querys->have_posts()) {
            $negocios_querys->the_post();
            $negocio_id = get_the_ID();

            // Retrieve other business details
            $city = get_post_meta($negocio_id, 'negocios_ciudad', true); // Assuming 'negocios_ciudad' is the meta key for city
            $state = get_post_meta($negocio_id, 'negocios_estado', true); // Assuming 'negocios_estado' is the meta key for state
            // print_r($negocios_querys);

            // echo do_shortcode('[advertisement_terms]');

            // Output HTML for business details
            echo '<p>' . esc_html($city) . ', ' . esc_html($state) . ' <a href="' . esc_url(get_permalink()) . '">View Details</a></p>';
        }
    } else {
        echo '<p>No businesses found in ' . esc_html($estado) . '.</p>';
    }

    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('detalle_negocios', 'mostrar_detalles_negocio');


// Shortcode for displaying HTML Ads Block
function custom_abogados_widget_shortcode()
{
	ob_start();
	dynamic_sidebar('custom-html-ads-block'); // Display sidebar widget content
	return ob_get_clean();
}
// Register shortcode
add_shortcode('custom_abogados_widget', 'custom_abogados_widget_shortcode');
