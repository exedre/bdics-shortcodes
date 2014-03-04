<?php
/**
 * Plugin Name: bdics-shortcodes
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: A brief description of the Plugin.
 * Version: 1.0
 * Author: exedre
 * Author URI: http://URI_Of_The_Plugin_Author
 * License: GPL2
 */

function bdics_shortcode( $atts )
{
        // extract attributs
        extract( shortcode_atts( array(
                'field' => "",
		'mode' => "icon",
		'pre' => "",
                'post_id' => false,
        ), $atts ) );
        
        
        // $field is requird
        if( !$field || $field == "" )
        {
                return "";
        }
        
	if ( $field == "jel_tags" ) {
		$post = get_post($post_id);
        	$jt = 'jel_tags';
        	$a = wp_get_object_terms($post->ID, $jt);

		return $post->ID;
        
	}
	else {
	if( get_field( $field, $post_id  ) )
	{
		$value = $pre ;
		$f = get_field_object($field,$post_id);
       		if ( $mode == "icon" )
		{
    			$value .= '<a href="'. $f['value'] . '">'.$f['label'].'</a><br/>   ';
		}
       		if ( $mode == "player" )
		{
			if ( $f['label'] == 'video' ) 
			{
				$value .= do_shortcode('[video width="960" height="720" mp4="'.$f['value'].'"]"][/video]');
			}
			elseif ( $f['label'] == 'audio' ) 
			{
				$value .= do_shortcode('[audio mp3="'.$f['value'].'"]"][/audio]');
			}
		}

       		if ( $mode == "text" )
		{
			 $value .= $f['value'];
		}
		$value .= $post ;
	}
	}
        return $value;
}
add_shortcode( 'bdics', 'bdics_shortcode' );
function bdics_jel( $atts ) {
            extract(shortcode_atts(array(
                'field' => '',
                'pre' => "",
                'post' => "",
                'decode' => '',
		'post_id' => false,
             ), $atts));
	$post = get_post($post_id);
        $jt = 'jel_tags';
        $a = wp_get_object_terms($post->ID, $jt);


	if(!empty($a)){
  		if(!is_wp_error( $a )){
		$d="";
		$e = "";
                foreach($a as $v)
                {
			$name = get_term_link($v->slug, $jt);
			$name = $v->name;
                	$e .="<a href='?jel_tags=".$name."'>".$name."</a>";
                	$e .= ', ';
            		$d = $e;
        	}
		
                if(substr($d,-2)==', ')
                        return substr($d,0,-2);
                else
                        return $d;
	}
	}
	return "-";
}
add_shortcode( 'bdicsjel', 'bdics_jel' );
