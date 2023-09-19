<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class CTL_free_migrations
{

    /**
     * Constructor.
     */
    public function __construct()
    {        
        // migrate old version stories       
        add_action('admin_init',array($this,'ctl_postmeta_migration'));//Post meta migration > 3.5.3
        add_action('admin_init',array($this,'ctl_settings_migration'));
    }


    // migrate stories from cool timeline free version
    function ctl_postmeta_migration(){
        
        // migration done
        if(get_option('ctl-postmeta-migration')){
            return;
        }
        //Direct new version
        if(version_compare(get_option('cool-free-timeline-v'),'2.1', '>') && !(get_option('cool-timelne-v'))){		
			return;
		}
                     
        $args = array( 
            'post_type'   => 'cool_timeline',
             'post_status'=>array('publish','future'),
             'numberposts' => -1 );
           $posts = get_posts( $args );

        //Story Type
        $story_type_key = array(          
            'ctl_story_date',       
        ); 
        //Story Media
        $story_media_key = array(    
            'img_cont_size',
        );
        $story_icon_key = array(
            'fa_field_icon', 
        );   
       
        if(isset($posts)&& is_array($posts) && !empty($posts))
        {
           foreach ( $posts as $post )
            {   
                 
                foreach ( $story_icon_key as $item )
                { 
                    $item_value =  get_post_meta($post->ID,$item,true);
                    $array_icon_type[$item] = $item_value;
                    $array_icon_type['story_icon_type'] = 'fontawesome';  
                }

                foreach ( $story_type_key as $item )
                { 
                    $item_value =  get_post_meta($post->ID,$item,true);
                    $array_story_type[$item] = $item_value;
                    $array_story_type['story_based_on'] = 'default';                    
                }
                
                foreach ( $story_media_key as $item )
                { 
                    $item_value =  get_post_meta($post->ID,$item,true);                  
                    $array_story_media[$item] = $item_value;
                    $array_story_media['story_format'] = 'default';     
                }   
              
                update_post_meta($post->ID, 'story_type', $array_story_type);
                update_post_meta($post->ID, 'story_media',$array_story_media);
                update_post_meta($post->ID, 'story_icon',$array_icon_type);
                update_option("ctl-postmeta-migration","done"); 
            }
        }

    } 

    
    function ctl_settings_migration()
	{
		if(!get_option('cool_timeline_options')){
			return;
		}  

		$old_settings =	get_option('cool_timeline_options');
       
	    $new_settings = $this->ctl_save_settings($old_settings, array('face' => 'font-family','size'=>'font-size','weight'=>'font-weight','src'=>'url'));
	 
		update_option('cool_timeline_settings', $new_settings);
		update_option('ctl_settings_migration_status','done');
		delete_option('cool_timeline_options');
	}  

    function ctl_recursive_change_key($arr, $set) {
		if (is_array($arr) && is_array($set)) {
			$newArr = array();
			foreach ($arr as $k => $v) {
				$key = array_key_exists( $k, $set) ? $set[$k] : $k;
				$newArr[$key] = is_array($v) ? $this->ctl_recursive_change_key($v, $set) : $v;
                if($key == 'font-size'){
					$newArr[$key] = str_replace("px","",$v);
				}	
			}
		     
			return $newArr;
		}
		return $arr;	
	}

    function ctl_save_settings($arr, $set) {
		if (is_array($arr) && is_array($set)) {
			$newArr = array();
            $timeline_header = array(); $story_date_settings = array(); $story_content_settings = array();
          
            $timeline_header_key = array('title_text','user_avatar');     
            
            $story_date_settings_key = array('year_label_visibility');
            $story_content_settings_key = array('content_length','display_readmore');          
            $arr = $this->ctl_recursive_change_key($arr, $set);
            foreach($arr as $key=>$value){
                if(in_array($key,$timeline_header_key)){
                    if($key == 'user_avatar'){  
                        if(!empty($value)){
                            $value = $this->ctl_recursive_change_key($value, array('src'=>'url'));
                            $thumbnail_img = wp_get_attachment_image_src($value['id'],'thumbnail');
                            $value += array('thumbnail' =>$thumbnail_img[0],'width'=>'843','height'=>'450');                       
                            $timeline_header += array($key =>$value);
                        }  
                    }else{
                        $timeline_header += array($key =>$value);
                    }   
                }
                elseif(in_array($key,$story_date_settings_key)){ 
                    $story_date_settings += array($key =>$value);   
                } 
                elseif(in_array($key,$story_content_settings_key)){  
                    $story_content_settings += array($key =>$value);  
                }elseif($key == 'main_title_typo'){
                    $title_alignment = isset($arr['title_alignment'])?$arr['title_alignment']:'center';
                    $value +=array('text-align' =>$title_alignment,'type' =>'google'); 
                    $newArr['main_title_typo'] = $value;
                }elseif($key == 'post_title_text_style'){
					$newArr['post_title_typo']['text-transform'] = $value;
				}elseif($key == 'background'){				    
					if(isset($value['enabled'])){					
						$newArr['timeline_background']='1';						
						$newArr['timeline_bg_color']=$value['bg_color'];						
					}
					else{						
						$newArr['timeline_background'] ='0';
					}						
				}elseif($key == 'post_title_typo'){
                    $value +=array('type' =>'google'); 
					$newArr['ctl_date_typo']['font-family']  = $value['font-family'];
					$newArr['ctl_date_typo']['font-weight']  = $value['font-weight'];
					$newArr['ctl_date_typo']['font-size']  = '21';
                    $newArr['ctl_date_typo']['type'] = 'google';
                    $newArr['post_title_typo'] = $value;
				}elseif($key == 'post_content_typo'){
                    $value +=array('type' =>'google');
                    $newArr['post_content_typo'] = $value;
                }
                else{
                    $newArr[$key] = $value;
                }
                
            }
            
            $newArr['timeline_header'] = $timeline_header; 
            $newArr['story_date_settings'] = $story_date_settings; 
            $newArr['story_content_settings'] = $story_content_settings;
			return $newArr;
		}
		return $arr;	
	}
 
	

}
new CTL_free_migrations();