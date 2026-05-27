<?php
/**
 * Formulario de suscripción de Jetpack con RGPD
 * Basado en https://artprojectgroup.es/personalizando-el-widget-suscripciones-de-jetpack y http://hookr.io/functions/jetpack_do_subscription_form/
 *
 * @package   Protección de datos - RGPD
 * @author    ABCdatos
 * @license   GPLv2
 * @link      https://taller.abcdatos.net/
 */

defined( 'ABSPATH' ) || die( 'No se permite el acceso.' );

if ( pdrgpd_modulo_jetpack_suscripciones_activo() ) {
	// Se mantienen ambos shortcodes por compatibilidad.
	// El nombre sin guión (pdrgpd_jetpack_suscripcion) es el original.
	// El nombre con guión (pdrgpd-jetpack-suscripcion) sigue la convención del plugin.
	add_shortcode( 'pdrgpd_jetpack_suscripcion', 'pdrgpd_jetpack_do_subscription_form' );
	add_shortcode( 'pdrgpd-jetpack-suscripcion', 'pdrgpd_jetpack_do_subscription_form' );
}

/**
 * Genera el formulario de suscripción de Jetpack adaptado al RGPD.
 *
 * Esta función envuelve el widget de suscripción de Jetpack y modifica su salida
 * para incluir:
 * - Una casilla obligatoria de aceptación de la política de privacidad.
 * - La primera capa de información legal (RGPD).
 *
 * Se utiliza como callback del shortcode `pdrgpd_jetpack_suscripcion`.
 *
 * @param array $instance {
 *     Parámetros de configuración del widget de suscripción.
 *
 *     @type bool $show_subscribers_total Opcional. Indica si se muestra el total de suscriptores.
 *     Otros parámetros son heredados de Jetpack_Subscriptions_Widget::defaults().
 * }
 *
 * @return string HTML del formulario de suscripción modificado con los elementos RGPD añadidos.
 *
 * @since 1.0.0
 */
function pdrgpd_jetpack_do_subscription_form( $instance ) {
	// Datos para la primera capa de la suscripción mediante Jetpack.
	$finalidad      = __( 'Inform you of new posts in the site.', 'proteccion-datos-rgpd' );
	$responsable    = 'Automattic Inc., EEUU';
	$transferencia  = $responsable;
	$url_privacidad = pdrgpd_url_privacidad_jetpack();
	$gestion        = 'https://subscribe.wordpress.com/';

	if ( empty( $instance ) || ! is_array( $instance ) ) {
		$instance = array();
	}
	$instance['show_subscribers_total'] = empty( $instance['show_subscribers_total'] ) ? false : true;

	if ( class_exists( 'Jetpack_Subscriptions_Widget' ) ) {
		$defaults = call_user_func( array( 'Jetpack_Subscriptions_Widget', 'defaults' ) );
	} else {
		return '';
	}

	$instance = shortcode_atts(
		$defaults,
		$instance,
		'jetpack_subscription_form'
	);
	$args     = array(
		'before_widget' => sprintf(
			'<div class="%s">',
			'jetpack_subscription_widget'
		),
	);
	ob_start();
	the_widget( 'Jetpack_Subscriptions_Widget', $instance, $args );
	$output = ob_get_clean();

	// Código a localizar para el remplazo.
	$original = '<p id="subscribe-submit">';
	// Casilla de aceptación de política de privacidad.
	$nuevo = '<p id="subscribe-policy"><input type="checkbox" name="privacidad" value="privacy-key" class="required" required="required" id="privacidad" /> <span>' . __( 'I accept the', 'proteccion-datos-rgpd' ) . ' <a target="blank" href="' . $url_privacidad . '">' . __( 'privacy policy', 'proteccion-datos-rgpd' ) . ' ' . __( 'of', 'proteccion-datos-rgpd' ) . ' ' . $responsable . '</a>.</span></p>' . "\n";
	// Primera capa de deber de información.
	$nuevo .= pdrgpd_deber_informacion_primera_capa( $finalidad, $transferencia, $responsable, $url_privacidad, $gestion );
	$nuevo .= $original;

	$output = str_replace( $original, $nuevo, $output );

	return $output;
}
