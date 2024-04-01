<?php
get_header();
$current_post_id = get_the_ID();
$logo_url = get_post_meta($current_post_id, 'negocios_logo', true);
$direccion = get_post_meta($current_post_id, 'negocios_direccion', true);
$estrellas = get_post_meta($current_post_id, 'negocios_estrellas', true);
$negocios_ciudad = get_post_meta($current_post_id, 'negocios_ciudad', true);
$reseñas = get_post_meta($current_post_id, 'negocios_reseñas', true);
$telefono = get_post_meta($current_post_id, 'negocios_telefono', true);
$sitio_web = get_post_meta($current_post_id, 'negocios_sitio_web', true);
$mapa = get_post_meta($current_post_id, 'negocios_mapa', true);
$ver_todas_opiniones = get_post_meta($current_post_id, 'ver_todas_opiniones', true);
$reviews = get_post_meta($current_post_id, 'negocios_reviews', true);
$terms = get_the_terms($current_post_id, 'categorias_servicios'); // Replace 'categorias_servicios' with the name of your custom taxonomy

$dias = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
?>
<!-- Retrieve logo URL -->
<div class="is-layout-constrained">
    <div class="ab-wrapper">
        <div class="negocio-detalle">
            <div class="columna1">
                <div class="top-section">
                    <?php
                    // Display logo if URL is available
                    if (!empty($logo_url)) { ?>
                        <img src="<?php echo esc_url($logo_url); ?>" alt="Logo" style="max-width: 200px; height: auto;">
                    <?php } ?>
                    <!-- Post title -->
                    <h3><?php echo get_the_title($current_post_id); ?></h3>
                    <p><?php echo esc_html($estrellas); ?><span class="icon">&#9733;</span></p>

                    <!-- Category Terms -->
                    <?php if ($terms && !is_wp_error($terms)) { ?>
                        <div class="category">
                            <p>Especializados en Accidentes de:</p>
                            <ul>
                                <?php // Output the name of the category 
                                    foreach ($terms as $term) { ?>
                                    <li><?php echo $term->name; ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
                <div class="contacto">
                    <?php echo get_the_content($current_post_id); ?>
                    <h4>Contactar</h4>
                    <div class="address">
                        <img src="<?php echo plugin_dir_url(__FILE__); ?>img/location_on.svg" alt="Phone Icon">
                        <p><b>Ubicación :</b> <?php echo ($direccion) ? $direccion : ''; ?></p>
                    </div>
                    <ul class="info-contacto">
                         <!-- Ícono de teléfono con SVG -->
                        <li>
                            <img src="<?php echo plugin_dir_url(__FILE__); ?>img/phone-icon.svg" alt="Phone Icon" ><?php echo esc_html($telefono); ?>
                        </li>
                        <!-- Ícono de sitio web con SVG -->
                        <li>
                            <img src="<?php echo plugin_dir_url(__FILE__); ?>img/link.svg" alt="Web Icon" >
                            <a href="<?php echo esc_url($sitio_web); ?>"><?php echo esc_html($sitio_web); ?></a>
                        </li>
                        <!-- Ícono de calendario con SVG -->
                        <li>
                            <img src="<?php echo plugin_dir_url(__FILE__);?>img/calendar-icon.svg" alt="Calendar Icon">
                            <?php
                                // Horarios de hoy
                                $dia_actual = strtolower(date('l'));
                                $horario_apertura_hoy = get_post_meta($current_post_id, 'negocios_horario_apertura_' . $dia_actual, true);
                                $horario_cierre_hoy = get_post_meta($current_post_id, 'negocios_horario_cierre_' . $dia_actual, true);
                                $abierto_24_horas_hoy = get_post_meta($current_post_id, 'negocios_abierto_24_horas_' . $dia_actual, true);
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
                                    $horario_apertura = get_post_meta($current_post_id, 'negocios_horario_apertura_' . strtolower($dia), true);
                                    $horario_cierre = get_post_meta($current_post_id, 'negocios_horario_cierre_' . strtolower($dia), true);
                                    $abierto_24_horas = get_post_meta($current_post_id, 'negocios_abierto_24_horas_' . strtolower($dia), true); ?>

                                    <p>
                                        <?php
                                            if ($abierto_24_horas) {
                                                echo ucfirst($dia) . ': Open 24 hours';
                                            } elseif ($horario_apertura && $horario_cierre) {
                                                echo ucfirst($dia) . ': ' . esc_html($horario_apertura) . ' - ' . esc_html($horario_cierre);
                                            } else {
                                                echo ucfirst($dia) . ': Closed';
                                            } 
                                        ?>
                                    </p>
                                <?php } ?>
                            </div>
                        </li> <!-- Cierre de .hidden-hours -->
                    </ul> <!-- Cierre de .info-contacto -->
                </div> <!-- ierre de .info-contacto -->
                <?php
                if (!empty($atts['boton_servicios_url'])) {
                    echo '<button onclick="window.location.href=\'' . esc_url($atts['boton_servicios_url']) . '\'">Services</button>';
                } ?>
            </div> <!-- Cierre de .columna1 -->
            <div class="columna2">
                <?php echo $mapa; // Asegúrate de que este contenido sea seguro antes de imprimirlo directamente ?>
            </div> <!-- Cierre de .columna2 -->
            <div class="columna3">
                <ul class="e-address-contacto">
                    
                    <!-- Ícono de teléfono con SVG -->
                    <li><a href= tel:<?php echo esc_html($telefono); ?>><img src="<?php echo plugin_dir_url(__FILE__); ?>img/phone-icon.svg" alt="Phone Icon" > Llamar</a></li>
                    <!-- Ícono de sitio web con SVG -->
                    <li><a href="mailto:yahoo@gmail.com"><img src="<?php echo plugin_dir_url(__FILE__); ?>img/calendar-icon.svg" alt="Calendar Icon" > Email</a></li>
                    <!-- Ícono de calendario con SVG -->
                    <li><a target="_blank" href="<?php echo esc_url($sitio_web); ?>"><img src="<?php echo plugin_dir_url(__FILE__); ?>img/web-icon.svg" alt="Web Icon" >Sitio Web</a></li>
                </ul>
                <?php
                if (!empty($reviews)) { ?>
                    <div class="reviews-row">
                        <div class="reviews-title">
                            <h2><?php echo __('Opiniones Reales', 'mi-plugin-negocios'); ?>*</h2>
                        </div>

                        <div class="review-details">
                            <?php foreach ($reviews as $review) { ?>
                                <div class="review">
                                    <h5><?php echo esc_html($review['text']); ?></h5>
                                    <p><?php echo esc_html($review['description']); ?></p>
                                    <!-- Check if URL is provided and not empty -->
                                    <?php if (!empty($review['url'])) { ?>
                                        <div class="link"><a target="_blank" href="<?php echo esc_url($review['url']); ?>"><?php echo  __('Leer mas en Google', 'mi-plugin-negocios'); ?></a></div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div> <!-- End .reviews  -->
                <?php } else { ?>
                    <p>No reviews available.</p>
                <?php }

                // Display the value if it exists
                if (!empty($ver_todas_opiniones)) { ?>
                    <div class="opiniones-row">
                        <a target="_blank" href="<?php echo esc_url($ver_todas_opiniones); ?>"><?php echo  __('Ver todas las opiniones en Google', 'mi-plugin-negocios'); ?></a>
                        <p>*Las opinione son de usuarios reales que han dejado su reseña acerca del servicio en la pagina de empresa de este servicio, no generamos ningun comentario aleatorio sino que son tomadas de Google My Business para mejorar la experiencia en la toma de decisión del servicio que necesites</p>
                    </div>
                <?php } ?>
            </div> <!-- Cierre de .columna3 -->

            <div style="clear: both;"></div>
        </div> <!--  Cierre de .negocio-detalle  -->
    </div>
        
    <?php // Query posts based on specified parameters
    $args = array(
        'post_type' => 'negocios',
        'posts_per_page' => 3,
        'post__not_in' => array($current_post_id),
        'meta_query'     => array(
            array(
                'key'     => 'negocios_ciudad',
                'value'   => $negocios_ciudad,
                'compare' => 'IN', // Match any value in the array
            ),
        ),
    );

    $negocios_queries = get_posts($args);
    // Check if there are any posts found
    if($negocios_queries){ ?>
        <div class="abogados-sec">
            <h4>Otros Abogados de Accidentes en <?php echo $negocios_ciudad; ?></h4>
            <div class="abogados-cards"> <?php
                foreach($negocios_queries as $negocios_query){
                    // Retrieve post meta data for the current post
                    $negocio_id = $negocios_query->ID;
                    $negocio_url = get_the_permalink($negocio_id);
                    $logo_url = get_post_meta($negocio_id, 'negocios_logo', true);
                    $negocios_direccion = get_post_meta($negocio_id, 'negocios_direccion', true);
                    $negocios_estado = get_post_meta($negocio_id, 'negocios_estado', true);
                    $negocios_ciudad = get_post_meta($negocio_id, 'negocios_ciudad', true);
                    $estrellas = get_post_meta($negocio_id, 'negocios_estrellas', true);
                    $reseñas = get_post_meta($negocio_id, 'negocios_reseñas', true);
                    $telefono = get_post_meta($negocio_id, 'negocios_telefono', true);
                    $sitio_web = get_post_meta($negocio_id, 'negocios_sitio_web', true);
                    $mapa = get_post_meta($negocio_id, 'negocios_mapa', true); ?>
                    <div class="card">
                        <div class="heading"><?php
                            // Display logo if URL is available
                            if (!empty($logo_url)) { ?>
                                <img src="<?php echo esc_url($logo_url); ?>" alt="Logo" width="54" height="54"><?php
                            } ?>
                            <h5><?php echo $negocios_query->post_title; ?></h5>
                        </div>
                        <p><?php echo !empty($negocios_direccion) ? $negocios_direccion : ''; ?></p>
                        <div class="rating">
                            <p><?php echo $negocios_ciudad; ?>, <?php echo $negocios_estado; ?></p><?php
                            // Check if $estrellas is not empty before displaying
                            if (!empty($estrellas)) { ?>
                                <p><?php echo esc_html($estrellas); ?> <span class="icon">&#9733;</span></p><?php
                            } ?>
                        </div>
                        <div class="btns">
                            <a href="<?php echo !empty($telefono) ? 'tel:' . $telefono : '#'; ?>">Contactar</a>
                            <a href="<?php echo $negocio_url; ?>">Ver Servicios</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php  } ?>
</div>
<?php get_footer(); ?>