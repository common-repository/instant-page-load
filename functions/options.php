<?php
/**
 * Usually functions that return settings values
 */

if( ! function_exists( 'aiop_site_url' ) ) :
function aiop_site_url() {
	return get_bloginfo( 'url' );
}
endif;