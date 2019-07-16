<?php  

if( ! function_exists( 'get_fa' ) ) {

	function get_fa( $format = false, $post_id = null ) {
		if ( ! $post_id ) {
			global $post;
			if ( ! is_object( $post ) ) {
				return;
			}
			$post_id = $post->ID;
		}
		$icon = get_post_meta( $post_id, 'fa_field_icon', true );
		if ( ! $icon ) {
			return;
		}
		if ( $format ) {
			if(strpos($icon, '-o') !==false){
				$icon="fa ".$icon;
			 }else if(strpos($icon, 'fas')!==false || strpos($icon, 'fab') !==false) {
                  $icon=$icon;
             }else{
				$icon="fa ".$icon;
			 }
			$output = '<i class="' . $icon . '"></i>';
		} else {
			$output = $icon;
		}
		return $output;
	}

}

if( ! function_exists( 'the_fa' ) ) {

	function the_fa( $format = false, $post_id = null ) {
		echo get_fa( $format, $post_id );
	}

}
?>