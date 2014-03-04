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
function bdics_get_field( $field, $post_id, $mode ){
	$value = "";
	if ( get_field( $field, $post_id ) ){
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
        return $value;
}
function bdics_page( $atts ) {
	extract(shortcode_atts(array(
                'field' => '',
                'pre' => "",
                'post' => "",
                'decode' => '',
		'dates' => '',
		'times' => '',
		'location' => '',
		'speakers' => '',
		'category' => '',
		'tags' => '',
		'paper' => '',
		'slides' => '',
		'program' => '',
		'audio' => '',
		'audiolink' => '',
		'video' => '',
		'notes' => '',
                'post_id' => false,
             ), $atts));
        $post = get_post($post_id);
	$post_id = $post->ID;
	$r = " <table> <tr> <th>Quando</th> <th>Dove</th> <th>Chi</th> <th>Cosa</th> </tr> ";
	$r .= "<tr><td>" . $dates . "<br/><i>" . $times . "</i></td><td>" . $location .  "</td><td>" . $speakers . "</td><td>" . $category . "</td></tr>";

	$jels = bdics_jel( );
	if ( $jels != "" ) {
		$r .= '<tr><td><b>JEL</b></td><td colspan=3>' . $jels . '</td></tr>';
	}
	if ( $tags != "" ) {
		$r .= "<tr><td><b>Keywords</b></td><td colspan=3>" . $tags . "</td></tr>";
	} 
	$paper = bdics_get_field('paper',$post_id,'icon');
	$slides= bdics_get_field('slides',$post_id,'icon');
	$program = bdics_get_field('program',$post_id,'icon');
	$audiolink = bdics_get_field('audio',$post_id,'icon');
	$audio= bdics_get_field('audio',$post_id,'player');
	$video= bdics_get_field('video',$post_id,'player');

	if ( $paper != "" or $slides != "" or $program != "" or $audiolink != "" ) {
		$r .= "<tr><td><b>Scarica</b></td><td colspan=3>" . $paper . $slides . $program . $audiolink . "</td></tr>";
	}
	if ( $notes != "" ) {
		$r .= '<tr><td colspan="4"> <br style="clear:both" />' . $notes . '</td></tr>';
	}
	if ( $video != "" ) {
		$r .= '<tr><td colspan=4>' . $video . '</td></tr>';
	}
	if ( $audio != "" ) {
		$r .= '<tr><td>Audio</td><td colspan=3>' . $audio . '</td></tr>'; 
	}
	$r .= " </table>";

	return $r;
}
add_shortcode( 'bdicspage', 'bdics_page' );
