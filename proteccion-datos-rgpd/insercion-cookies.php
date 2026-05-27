<?php
/**
 * Gestiona el banner de cookies
 *
 * @package   Protección de datos - RGPD
 * @author    ABCdatos
 * @license   GPLv2
 * @link      https://taller.abcdatos.net/
 */

defined( 'ABSPATH' ) || die( 'No se permite el acceso.' );

// gtag (Google Global Site Tag).
if ( '' !== pdrgpd_conf_google_analytics_id() ) {
	add_action( 'wp_head', 'pdrgpd_inserta_gtag' );
	/**
	 * Inserta el snippet inline de Google Analytics en el head.
	 *
	 * @return void
	 */
	function pdrgpd_inserta_gtag() {
		$pdrgpd_google_analytics_id = pdrgpd_conf_google_analytics_id();
		// Debe ir en el head, antes de cualquier llamada a comandos gtag.
		?>

<!-- Global site tag (gtag.js) - Google Analytics -->
<?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript -- Este snippet de tercero debe salir inline en wp_head para mantener su orden y configuración. ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $pdrgpd_google_analytics_id ); ?>"></script>
<?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript -- Este snippet de tercero debe salir inline en wp_head para mantener su orden y configuración. ?>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){window.dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '<?php echo esc_attr( $pdrgpd_google_analytics_id ); ?>');
</script>

		<?php
	}
}


// Facebook Pixel.
if ( '' !== pdrgpd_conf_facebook_pixel_id() ) {
	add_action( 'wp_head', 'pdrgpd_inserta_fb_pixel' );
	/**
	 * Inserta el snippet inline de Facebook Pixel en el head.
	 *
	 * @return void
	 */
	function pdrgpd_inserta_fb_pixel() {
		$pdrgpd_facebook_pixel_id = pdrgpd_conf_facebook_pixel_id();
		// Debe ir en el head.
		?>

<!-- Facebook Pixel Code -->
<?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript -- Este snippet de tercero debe salir inline en wp_head para mantener su orden y configuración. ?>
<script>
	!function(f,b,e,v,n,t,s)
	{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
	n.callMethod.apply(n,arguments):n.queue.push(arguments)};
	if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
	n.queue=[];t=b.createElement(e);t.async=!0;
	t.src=v;s=b.getElementsByTagName(e)[0];
	s.parentNode.insertBefore(t,s)}(window, document,'script',
	'https://connect.facebook.net/en_US/fbevents.js');
	fbq('init', '<?php echo esc_attr( $pdrgpd_facebook_pixel_id ); ?>');
	fbq('track', 'PageView');
</script>
<noscript>
	<img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<?php echo esc_attr( $pdrgpd_facebook_pixel_id ); ?>&ev=PageView&noscript=1"/>
</noscript>
<!-- End Facebook Pixel Code -->

		<?php
	}
}


/*
 * Valores cookies
 */

/**
 * Indica si se aceptaron las cookies de estadisticas.
 *
 * @return bool Verdadero si la cookie esta activa.
 */
function pdrgpd_cookie_estadisticas() {
	if ( ! isset( $_COOKIE['pdrgpd_estadisticas'] ) ) {
		return false;
	}

	return '1' === $_COOKIE['pdrgpd_estadisticas'] || 'true' === $_COOKIE['pdrgpd_estadisticas'];
}

/*
 * Valores configurados
 */

/**
 * Devuelve el identificador configurado de Google Analytics.
 *
 * @return string ID configurado o cadena vacia.
 */
function pdrgpd_conf_google_analytics_id() {
	return esc_html( get_option( 'pdrgpd_google_analytics_id', '' ) );
}

/**
 * Devuelve el identificador configurado de Facebook Pixel.
 *
 * @return string ID configurado o cadena vacia.
 */
function pdrgpd_conf_facebook_pixel_id() {
	return esc_html( get_option( 'pdrgpd_facebook_pixel_id', '' ) );
}
