<?php
// Función para encolar la hoja de estilos
function negocios_enqueue_styles()
{
    // Enqueue jQuery
    wp_enqueue_script('jquery');
    
    wp_enqueue_style('negocios-styles', plugin_dir_url(__FILE__) . 'negocios-styles.css');
}
add_action('wp_enqueue_scripts', 'negocios_enqueue_styles');

// Función para agregar el código JavaScript directamente en el footer
function negocios_add_footer_scripts()
{
    echo '<script type="text/javascript">
    // jQuery script to toggle review details visibility
    jQuery(document).ready(function($){
        // Event listener for click on elements with class "schedule-toggle"
        $(".schedule-toggle").click(function() {
            var nextElement = $(this).find(".hidden-hours");
            if (nextElement.length) {
                nextElement.toggle();
                $(this).find(".chevron").toggleClass("active");
                if ($(this).find(".chevron").hasClass("active")) {
                    $(this).find(".chevron").html("&#8722;"); // Chevron up
                } else {
                    $(this).find(".chevron").html("&#65122;"); // Chevron down
                }
            }
        });
    
        // Close hidden hours when clicking outside of schedule-toggle or hidden-hours
        $(document).click(function(event) {
            $(".schedule-toggle").each(function() {
                var isClickInsideScheduleToggle = $(this).is(event.target) || $(this).has(event.target).length > 0;
                var isClickInsideHiddenHours = $(this).find(".hidden-hours").is(event.target) || $(this).find(".hidden-hours").has(event.target).length > 0;
                if (!isClickInsideScheduleToggle && !isClickInsideHiddenHours) {
                    $(this).find(".hidden-hours").hide();
                    $(this).find(".chevron").removeClass("active").html("&#65122;"); // Chevron down
                }
            });
        });

        $(".reviews-title").click(function(){
            $(this).toggleClass("active");
            if ($(this).hasClass("active")) {
                $(this).find(".chevron").html("&#8722;");
            } else {
                $(this).find(".chevron").html("&#43;");
            }
            $(this).next(".review-details").slideToggle();
        });
    });
    </script>';
}
add_action('wp_footer', 'negocios_add_footer_scripts');

// Función del shortcode para mostrar el detalle del negocio
function mostrar_detalle_negocio_ciudad($atts)
{
    $atts = shortcode_atts(
        array(
            'city' => '',
            'cantidad_negocios' => 10, // Número predeterminado de negocios a mostrar
            'page_url' => '',
            'category' => ''
        ), $atts, 'detalle_negocio');

    $ciudad = $atts['city'];
    $page_url = $atts['page_url'];
    $cantidad_negocios = $atts['cantidad_negocios'];

    // echo "id".$cantidad_negocios;
    $category = $atts['category'];

    ob_start();

    // Consulta para obtener negocios por ciudad
    $args = array(
        'post_type' => 'negocios',
        'posts_per_page' => $cantidad_negocios,
    );

    // Only add meta_query if ciudad has a valid value
    if (!empty($ciudad)) {
        $args['meta_query'] = array(
            array(
                'key' => 'negocios_ciudad',
                'value' => $ciudad, // Ensure this has a value
                'compare' => '=', // Match this meta key's value
            ),
        );
    }
    // Initialize tax_query
    $args['tax_query'] = array('relation' => 'OR'); // Use 'OR' to combine queries

    //    Conditionally add more terms if $category is set and not empty
        if(isset($category) && $category != '') {
            $args['tax_query'][] = array(
                'relation' => 'OR',
                'taxonomy' => 'categorias_servicios', // Same taxonomy
                'field' => 'slug', // Field to match
                'terms' => $category, // Use the category variable
            );
    }


    $negocios_query = new WP_Query($args);
    $plugin_dir_url = plugin_dir_url(__FILE__);
    if(isset($category) && $category != '') {?>
        <div class="card-main">
            <div class="card-inner">
                <?php
                $counter = 0; // Counter to track loop iterations
                    while ($negocios_query->have_posts()) {
                        $negocios_query->the_post();
                        $negocio_id = get_the_ID();
                        $permalink = get_permalink();
                        //  $logo_url = get_post_meta(post_id: $negocio_id, 'negocios_logo', true); // Retrieve logo URL
                        $logo_url = get_post_meta($negocio_id, 'negocios_logo', true); // Retrieve logo URL

                        $direccion = get_post_meta($negocio_id, 'negocios_direccion', true);
                        $estrellas = get_post_meta($negocio_id, 'negocios_estrellas', true);
                        $reseñas = get_post_meta($negocio_id, 'negocios_reseñas', true);
                        $telefono = get_post_meta($negocio_id, 'negocios_telefono', true);
                        $sitio_web = get_post_meta($negocio_id, 'negocios_sitio_web', true);
                        $mapa = get_post_meta($negocio_id, 'negocios_mapa', true);
                        $negocios_ciudad = get_post_meta($negocio_id, 'negocios_ciudad', true);

                        $dias = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        $counter++; // Increment counter after each iteration
                    ?>
                        <div class="card">
                                <div class="heading">
                                        <?php
                                        // Display logo if URL is available
                                        if (!empty($logo_url)) { ?>
                                            <img src="<?php echo esc_url($logo_url); ?>" alt="Logo" width="54" height="54"><?php
                                        } ?>
                                        <h3><a href="<?php echo $permalink; ?>"><?php echo get_the_title($negocio_id); ?></a></h3>
                                </div>
                                <p><?php echo !empty($direccion) ? $direccion : ''; ?></p>
                                <div class="rating">
                                    <p><?php echo $negocios_ciudad; ?>, <?php echo $negocios_ciudad; ?></p><?php
                                    // Check if $estrellas is not empty before displaying
                                    if (!empty($estrellas)) { ?>
                                        <p><?php echo esc_html($estrellas); ?> <span class="icon">&#9733;</span></p><?php
                                    } ?>
                                </div>
                                <div class="btn-row">
                                    <a class="dark-btn" href="<?php echo $permalink; ?>#contactar">Contactar</a>
                                    <a class="light-btn" href="<?php echo $permalink; ?>">Ver Servicios</a>
                                </div>
                        </div>
                <?php } ?>
            </div>
            <?php if($page_url) { ?>
                <div class="card-bottom">
                    <a href="#">Ver más abogados de Carros</a>
                </div>
            <?php } ?>
        </div>
    <?php
    }else {
        if ($negocios_query->have_posts()) {
            $counter = 0; // Counter to track loop iterations
            while ($negocios_query->have_posts()) {

                $negocios_query->the_post();
                $negocio_id = get_the_ID();
                $permalink = get_permalink();
                $logo_url = get_post_meta($negocio_id, 'negocios_logo', true); // Retrieve logo URL

                $direccion = get_post_meta($negocio_id, 'negocios_direccion', true);
                $estrellas = get_post_meta($negocio_id, 'negocios_estrellas', true);
                $reseñas = get_post_meta($negocio_id, 'negocios_reseñas', true);
                $telefono = get_post_meta($negocio_id, 'negocios_telefono', true);
                $sitio_web = get_post_meta($negocio_id, 'negocios_sitio_web', true);
                $mapa = get_post_meta($negocio_id, 'negocios_mapa', true);

                $dias = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                $counter++; // Increment counter after each iteration
                ?>
                <div class="negocios-ciudad">
                    <div class="col1">
                        <div class="inner-row">
                            <div class="col1-left">
                                <!-- Display logo if URL is available -->
                                <?php if (!empty($logo_url)) {
                                    echo '<img src="' . esc_url($logo_url) . '" alt="Logo" width="56" height="56">';
                                } ?>
                            </div>
                            <div class="col1-right">
                                <h3><a href="<?php echo $permalink; ?>"><?php echo get_the_title($negocio_id); ?></a></h3>
                                <div class="address">
                                    <img width="13" height="16" src="<?php echo $plugin_dir_url; ?>img/location_on.svg" alt="Phone Icon">
                                    <p><?php echo get_post_meta($negocio_id, 'negocios_direccion', true); ?></p>
                                </div>
                                <p class="rating"><span class="icon">&#9733;</span><?php echo esc_html($estrellas); ?> rating <span>y <?php echo esc_html($reseñas); ?> comentarios</span></p>
                            </div>
                        </div>
                        <div class="col1-bottom">
                            <ul class="info-contacto">                            
                                <!-- Ícono de teléfono con SVG -->
                                <li><img width="20" height="20" src="<?php echo $plugin_dir_url; ?>img/phone-icon.svg" alt="Phone Icon" ><a href="tel:<?php echo esc_html($telefono); ?>"><?php echo esc_html($telefono); ?></a></li>

                                <!-- Ícono de sitio web con SVG -->
                                <li><img width="20" height="20" src="<?php echo $plugin_dir_url; ?>img/link.svg" alt="Web Icon" ><a target="_blank" href="<?php echo esc_url($sitio_web);?>"><?php echo esc_html($sitio_web); ?></a></li>

                                <!-- Ícono de calendario con SVG -->
                                <li class="schedule-toggle"><img width="20" height="20" src="<?php echo $plugin_dir_url; ?>img/calendar-icon.svg" alt="Calendar Icon">

                                    <!-- Horarios de hoy -->
                                    <?php
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
                                    } ?>
                                    <span class="chevron">&#65122;</span>
                                    <div class="hidden-hours" style="display:none;">
                                        <?php foreach ($dias as $dia) {
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
                                        } ?>
                                    </div>
                                </li> <!-- Cierre de .hidden-hours -->
                            </ul> <!-- Cierre de .info-contacto -->
                            <?php
                            $content = get_the_content($negocio_id);
                            $trimmed_content = wp_trim_words( $content, 35, '... <a href="'. get_permalink() .'">Ver más</a>' ); ?>
                            <p class="post-content"><?php echo $trimmed_content; ?></p>
                        </div>
                        <?php
                            // Get the reviews
                            $reviews = get_post_meta($negocio_id, 'negocios_reviews', true);

                            if (!empty($reviews)) { ?>
                                <div class="reviews-row">
                                    <div class="reviews-title">
                                        <h2><span class="chevron">+</span><span class="text"><?php echo __('Testimonios Reales desde Google Maps', 'mi-plugin-negocios'); ?></span><img width="16" height="13" src="<?php echo $plugin_dir_url; ?>img/campaign.svg" alt="Campaign Icon"></h2>
                                    </div>

                                    <div class="review-details" style="display:none;">
                                        <?php foreach ($reviews as $review) { ?>
                                            <div class="review">
                                                <h5><?php echo esc_html($review['text']); ?></h5>
                                                <p><?php echo esc_html($review['description']); ?></p>
                                                <div class="link">
                                                    <a target="_blank" href="<?php echo $permalink; ?>#opiniones"><?php echo __('Leer mas en Google', 'mi-plugin-negocios');?></a>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div><!-- End .reviews -->
                            <?php } ?>
                    </div>
                    <div class="col2">
                        <?php echo $mapa; // Asegúrate de que este contenido sea seguro antes de imprimirlo directamente ?>
                        <div class="btn-row">
                            <a class="light-btn" href="<?php echo $permalink; ?>">Ver Servicios</a>
                            <a class="dark-btn" href="<?php echo $permalink; ?>#contactar">Contactar</a>
                        </div>
                    </div>
                </div><!-- End .negocios-ciudad -->
                <?php 
                    if ($counter == 2) { // Check if it's the second iteration
                        if ( is_active_sidebar( 'custom-html-ads-block' ) ) : ?>
                            <?php dynamic_sidebar( 'custom-html-ads-block' ); ?>
                        <?php endif;
                    }
                ?>
            <?php }
            // Check if the counter is greater than 2 and if the custom HTML ads block widget area is active
            if ($counter > 2 && is_active_sidebar('custom-html-ads-block')) {
                dynamic_sidebar('custom-html-ads-block');
            }
            wp_reset_postdata();
        } else {
            echo '<p>No businesses found in ' . esc_html($ciudad) . '.</p>';
        }
    }
    return ob_get_clean();
}

add_shortcode('company_lists_using_city', 'mostrar_detalle_negocio_ciudad');

// Define custom shortcode function

function mostrar_detalles_negocio_estado($atts)
{
    $atts = shortcode_atts(array(
        'state' => '',
        'cantidad_negocios' => -1,
        'category' => '',
    ), $atts, 'detalle_negocios');

    // Map shortcode attribute values to database values
    $state_map = array(
        'FLORIDA' => 'FL', // Map "FLORIDA" to "FL"
        'ALABAMA' => 'AL', // Map "ALABAMA" to "AL"
        'ALASKA' => 'AK', // Map "ALASKA" to "AK"
        'ARIZONA' => 'AZ', // Map "ARIZONA" to "AZ"
        'ARKANSAS' => 'AR', // Map "ARKANSAS" to "AR"
        'CALIFORNIA' => 'CA', // Map "CALIFORNIA" to "CA"
        'COLORADO' => 'CO', // Map "COLORADO" to "CO"
        'CONNECTICUT' => 'CT', // Map "CONNECTICUT" to "CT"
        'DELAWARE' => 'DE', // Map "DELAWARE" to "DE"
        'DISTRICT OF COLUMBIA' => 'DC', // Map "DISTRICT OF COLUMBIA" to "DC"
        'FLORIDA' => 'FL', // Map "FLORIDA" to "FL"
        'GEORGIA' => 'GA', // Map "GEORGIA" to "GA"
        'HAWAII' => 'HI', // Map "HAWAII" to "HI"
        'IDAHO' => 'ID', // Map "IDAHO" to "ID"
        'ILLINOIS' => 'IL', // Map "ILLINOIS" to "IL"
        'INDIANA' => 'IN', // Map "INDIANA" to "IN"
        'IOWA' => 'IA', // Map "IOWA" to "IA"
        'KANSAS' => 'KS', // Map "KANSAS" to "KS"
        'KENTUCKY' => 'KY', // Map "KENTUCKY" to "KY"
        'LOUISIANA' => 'LA', // Map "LOUISIANA" to "LA"
        'MAINE' => 'ME', // Map "MAINE" to "ME"
        'MARYLAND' => 'MD', // Map "MARYLAND" to "MD"
        'MASSACHUSETTS' => 'MA', // Map "MASSACHUSETTS" to "MA"
        'MICHIGAN' => 'MI', // Map "MICHIGAN" to "MI"
        'MINNESOTA' => 'MN', // Map "MINNESOTA" to "MN"
        'MISSISSIPPI' => 'MS', // Map "MISSISSIPPI" to "MS"
        'MISSOURI' => 'MO', // Map "MISSOURI" to "MO"
        'MONTANA' => 'MT', // Map "MONTANA" to "MT"
        'NEBRASKA' => 'NE', // Map "NEBRASKA" to "NE"
        'NEVADA' => 'NV', // Map "NEVADA" to "NV"
        'NEW HAMPSHIRE' => 'NH', // Map "NEW HAMPSHIRE" to "NH"
        'NEW JERSEY' => 'NJ', // Map "NEW JERSEY" to "NJ"
        'NEW MEXICO' => 'NM', // Map "NEW MEXICO" to "NM"
        'NEW YORK' => 'NY', // Map "NEW YORK" to "NY"
        'NORTH CAROLINA' => 'NC', // Map "NORTH CAROLINA" to "NC"
        'NORTH DAKOTA' => 'ND', // Map "NORTH DAKOTA" to "ND"
        'OHIO' => 'OH', // Map "OHIO" to "OH"
        'OKLAHOMA' => 'OK', // Map "OKLAHOMA" to "OK"
        'OREGON' => 'OR', // Map "OREGON" to "OR"
        'PENNSYLVANIA' => 'PA', // Map "PENNSYLVANIA" to "PA"
        'RHODE ISLAND' => 'RI', // Map "RHODE ISLAND" to "RI"
        'SOUTH CAROLINA' => 'SC', // Map "SOUTH CAROLINA" to "SC"
        'SOUTH DAKOTA' => 'SD', // Map "SOUTH DAKOTA" to "SD"
        'TENNESSEE' => 'TN', // Map "TENNESSEE" to "TN"
        'TEXAS' => 'TX', // Map "TEXAS" to "TX"
        'UTAH' => 'UT', // Map "UTAH" to "UT"
        'VERMONT' => 'VT', // Map "VERMONT" to "VT"
        'VIRGINIA' => 'VA', // Map "VIRGINIA" to "VA"
        'WASHINGTON' => 'WA', // Map "WASHINGTON" to "WA"
        'WEST VIRGINIA' => 'WV', // Map "WEST VIRGINIA" to "WV"
        'WISCONSIN' => 'WI', // Map "WISCONSIN" to "WI"
        'WYOMING' => 'WY', // Map "WYOMING" to "WY"
        // Add more mappings as needed
    );

    // Map state codes to full state names
    $state_names = array(
        'FL' => 'Florida',
        'AL' => 'Alabama',
        'AK' => 'Alaska',
        'AZ' => 'Arizona',
        'AR' => 'Arkansas',
        'CA' => 'California',
        'CO' => 'Colorado',
        'CT' => 'Connecticut',
        'DE' => 'Delaware',
        'DC' => 'District of Columbia',
        'FL' => 'Florida',
        'GA' => 'Georgia',
        'HI' => 'Hawaii',
        'ID' => 'Idaho',
        'IL' => 'Illinois',
        'IN' => 'Indiana',
        'IA' => 'Iowa',
        'KS' => 'Kansas',
        'KY' => 'Kentucky',
        'LA' => 'Louisiana',
        'ME' => 'Maine',
        'MD' => 'Maryland',
        'MA' => 'Massachusetts',
        'MI' => 'Michigan',
        'MN' => 'Minnesota',
        'MS' => 'Mississippi',
        'MO' => 'Missouri',
        'MT' => 'Montana',
        'NE' => 'Nebraska',
        'NV' => 'Nevada',
        'NH' => 'New Hampshire',
        'NJ' => 'New Jersey',
        'NM' => 'New Mexico',
        'NY' => 'New York',
        'NC' => 'North Carolina',
        'ND' => 'North Dakota',
        'OH' => 'Ohio',
        'OK' => 'Oklahoma',
        'OR' => 'Oregon',
        'PA' => 'Pennsylvania',
        'RI' => 'Rhode Island',
        'SC' => 'South Carolina',
        'SD' => 'South Dakota',
        'TN' => 'Tennessee',
        'TX' => 'Texas',
        'UT' => 'Utah',
        'VT' => 'Vermont',
        'VA' => 'Virginia',
        'WA' => 'Washington',
        'WV' => 'West Virginia',
        'WI' => 'Wisconsin',
        'WY' => 'Wyoming',
        // Add more state names as needed
    );

    // Get database value from shortcode attribute value
    $state_code = isset( $state_map[ strtoupper( $atts['state'] ) ] ) ? $state_map[ strtoupper( $atts['state'] ) ] : '';
    $cantidad_negocios = $atts['cantidad_negocios'];
    $category = $atts['category'];

    ob_start();

    // Consulta para obtener negocios por estado
    $args = array(
        'post_type' => 'negocios',
        'posts_per_page' => $cantidad_negocios,
        'meta_query' => array(
            array(
                'key' => 'negocios_estado',
                'value' => $state_code,
                'compare' => '=',
            ),
        ),
    );
    if(isset($category) && $category != '') {

        $args['tax_query'] = array(
            'relation'=> 'OR',
            array(
              'taxonomy' => 'categorias_servicios',
              'field' => 'slug',
              'terms' => $category,
            ),
        );
    }

    $negocios_querys = new WP_Query($args);
    $plugin_dir_url = plugin_dir_url(__FILE__);
    if ($negocios_querys->have_posts()) { ?>
        <div class="negocio-estado">
            <?php while ($negocios_querys->have_posts()) {
                $negocios_querys->the_post();
                $negocio_id = get_the_ID();

                // Retrieve other business details
                $city = get_post_meta($negocio_id, 'negocios_ciudad', true); // Assuming 'negocios_ciudad' is the meta key for city
                $state = get_post_meta($negocio_id, 'negocios_estado', true); // Assuming 'negocios_estado' is the meta key for state
                $full_state_name = isset( $state_names[ $state_code ] ) ? $state_names[ $state_code ] : '';
                ?>
                <!-- Output HTML for business details -->
                <div class="estado-row">
                    <h5 class="city">
                        <?php echo esc_html($city); ?>, <?php echo esc_html($state); ?>
                    </h5>
                    <h6 class="state"><?php echo esc_html($full_state_name); ?></h6>                    
                    <a href="<?php echo esc_url(get_permalink()); ?>"><img src="<?php echo $plugin_dir_url; ?>img/arrow_right_alt.svg" alt="arrow_right_alt" width="15" height="11"></a>
                </div>
            <?php } ?>
        </div>
    <?php } else {
        echo '<p>No businesses found in ' . esc_html($atts['estado']) . '.</p>';
    }

    wp_reset_postdata();

    return ob_get_clean();
}

add_shortcode('company_lists_using_state', 'mostrar_detalles_negocio_estado');

function custom_abogados_widget_shortcode() {
	ob_start();
	dynamic_sidebar('custom-html-ads-block'); // Display sidebar widget content
	return ob_get_clean();
}
// Register shortcode
add_shortcode('display_advertising_block', 'custom_abogados_widget_shortcode');


// Shortcode function to display businesses by city with various options and details
function shortcode_company($atts) {
    $atts = shortcode_atts(
        array(
            'city' => '',
            'cantidad_negocios' => 10, // Número predeterminado de negocios a mostrar
            'category' => '',
        ), $atts, 'detalle_negocio');

    $ciudad = $atts['city'];
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
                'compare' => '=',
            ),
        ),
    );
    if(isset($category) && $category != '') {

        $args['tax_query'] = array(
            'relation'=> 'OR',
            array(
            'taxonomy' => 'categorias_servicios',
            'field' => 'slug',
            'terms' => $category,
            ),
        );
    }

    $negocios_query = new WP_Query($args);
    $plugin_dir_url = plugin_dir_url(__FILE__);
    if ($negocios_query->have_posts()) {
        $counter = 0; // Counter to track loop iterations
        while ($negocios_query->have_posts()) {

            $negocios_query->the_post();
            $negocio_id = get_the_ID();
            $permalink = get_permalink();
            
            $logo_url = get_post_meta($negocio_id, 'negocios_logo', true); // Retrieve logo URL

            $direccion = get_post_meta($negocio_id, 'negocios_direccion', true);
            $estrellas = get_post_meta($negocio_id, 'negocios_estrellas', true);
            $reseñas = get_post_meta($negocio_id, 'negocios_reseñas', true);
            $telefono = get_post_meta($negocio_id, 'negocios_telefono', true);
            $sitio_web = get_post_meta($negocio_id, 'negocios_sitio_web', true);
            $mapa = get_post_meta($negocio_id, 'negocios_mapa', true);

            $dias = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $counter++; // Increment counter after each iteration
            ?>
            <div class="negocios-ciudad">
                <div class="col1">
                    <div class="inner-row">
                        <div class="col1-left">
                            <!-- Display logo if URL is available -->
                            <?php if (!empty($logo_url)) {
                                echo '<img src="' . esc_url($logo_url) . '" alt="Logo" width="56" height="56">';
                            } ?>
                        </div>
                        <div class="col1-right">
                            <h3><?php echo get_the_title($negocio_id); ?></h3>  
                            <div class="address">
                                <img width="13" height="16" src="<?php echo $plugin_dir_url; ?>img/location_on.svg" alt="Phone Icon">
                                <p><?php echo get_post_meta($negocio_id, 'negocios_direccion', true); ?></p>
                            </div>
                            <p class="rating"><span class="icon">&#9733;</span><?php echo esc_html($estrellas); ?> rating <span>y <?php echo esc_html($reseñas); ?> comentarios</span></p>
                        </div>
                    </div>
                    <div class="col1-bottom">
                        <ul class="info-contacto">                            
                            <!-- Ícono de teléfono con SVG -->
                            <li><img width="20" height="20" src="<?php echo $plugin_dir_url; ?>img/phone-icon.svg" alt="Phone Icon" ><a href="tel:<?php echo esc_html($telefono); ?>"><?php echo esc_html($telefono); ?></a></li>

                            <!-- Ícono de sitio web con SVG -->
                            <li><img width="20" height="20" src="<?php echo $plugin_dir_url; ?>img/link.svg" alt="Web Icon" ><a target="_blank" href="<?php echo esc_url($sitio_web);?>"><?php echo esc_html($sitio_web); ?></a></li>

                            <!-- Ícono de calendario con SVG -->
                            <li class="schedule-toggle"><img width="20" height="20" src="<?php echo $plugin_dir_url; ?>img/calendar-icon.svg" alt="Calendar Icon">

                                <!-- Horarios de hoy -->
                                <?php
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
                                } ?>
                                <span class="chevron">&#65122;</span>
                                <div class="hidden-hours" style="display:none;">
                                    <?php foreach ($dias as $dia) {
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
                                    } ?>
                                </div>
                            </li> <!-- Cierre de .hidden-hours -->
                        </ul> <!-- Cierre de .info-contacto -->
                        <?php
                        $content = get_the_content($negocio_id);
                        $trimmed_content = wp_trim_words( $content, 35, '... <a href="'. get_permalink() .'">Ver más</a>' ); ?>
                        <p class="post-content"><?php echo $trimmed_content; ?></p>
                    </div>
                    <?php
                        // Get the reviews
                        $reviews = get_post_meta($negocio_id, 'negocios_reviews', true);

                        if (!empty($reviews)) { ?>
                            <div class="reviews-row">
                                <div class="reviews-title">
                                    <h2><span class="chevron">+</span><span class="text"><?php echo __('Testimonios Reales desde Google Maps', 'mi-plugin-negocios'); ?></span><img width="16" height="13" src="<?php echo $plugin_dir_url; ?>img/campaign.svg" alt="Campaign Icon"></h2>
                                </div>

                                <div class="review-details" style="display:none;">
                                    <?php foreach ($reviews as $review) { ?>
                                        <div class="review">
                                            <h5><?php echo esc_html($review['text']); ?></h5>
                                            <p><?php echo esc_html($review['description']); ?></p>
                                            <?php // Check if URL is provided and not empty
                                            if (!empty($review['url'])) { ?>
                                                <div class="link">
                                                    <a target="_blank" href="<?php echo esc_url($review['url']);?>"><?php echo __('Leer mas en Google', 'mi-plugin-negocios');?></a>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div><!-- End .reviews -->
                        <?php } ?>
                </div>
                <div class="col2">
                    <?php echo $mapa; // Asegúrate de que este contenido sea seguro antes de imprimirlo directamente ?>
                    <div class="btn-row">
                        <a class="light-btn" href="<?php echo $permalink; ?>">Ver Servicios</a>
                        <a class="dark-btn" href= tel:<?php echo esc_html($telefono); ?>>Contactar</a>
                    </div>
                </div>
            </div><!-- End .negocios-ciudad -->
            <?php 
                if ($counter == 2) { // Check if it's the second iteration
                    if ( is_active_sidebar( 'custom-html-ads-block' ) ) : ?>
                        <?php dynamic_sidebar( 'custom-html-ads-block' ); ?>
                    <?php endif;
                }
            ?>
        <?php }
        // Check if the counter is greater than 2 and if the custom HTML ads block widget area is active
        if ($counter > 2 && is_active_sidebar('custom-html-ads-block')) {
            dynamic_sidebar('custom-html-ads-block');
        }
        wp_reset_postdata();
    } else {
        echo '<p>No businesses found in ' . esc_html($ciudad) . '.</p>';
    }
    return ob_get_clean();
}

add_shortcode('company', 'shortcode_company');