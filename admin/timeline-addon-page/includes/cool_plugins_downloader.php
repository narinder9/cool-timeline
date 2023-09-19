<?php
/**
 * This class is used to update/install/rollback a plugin. It extends wordpress core class Plugin_Upgrader
 * Due to the use of package & namaspace, class can not be extend requiring file
 * 
 */

 if( !defined( 'ABSPATH' )){
     die( 'WordPress enviornment not found!');
 }

 if( !class_exists( 'Plugin_Upgrader' ) ){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
 }

 class cool_plugins_downloader extends Plugin_Upgrader{

    public function rollback($url = null,$action='install'){

        $this->init();
        $this->upgrade_strings();

        add_filter( 'upgrader_pre_install', array( $this, 'deactivate_plugin_before_upgrade' ), 10, 2 );
        add_filter( 'upgrader_clear_destination', array( $this, 'delete_old_plugin' ), 10, 4 );

        $this->run( array(
            'package'           => $url,
            'destination'       => WP_PLUGIN_DIR,
            'clear_destination' => true,
            'clear_working'     => true,
            'hook_extra'        => array(
                'type'   => 'plugin',
                'action' => $action,
            ),
        ) );

        remove_filter( 'upgrader_pre_install', array( $this, 'deactivate_plugin_before_upgrade' ) );
        remove_filter( 'upgrader_clear_destination', array( $this, 'delete_old_plugin' ) );

        if ( ! $this->result || is_wp_error( $this->result ) ) {
            return $this->result;
        }
        return 'Plugin install successful!';
    }
 }