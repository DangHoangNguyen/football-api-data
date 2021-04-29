<?php
/*
    Plugin Name: Football API Data
    Plugin URI: https://fcb88.com/
    Description: Add shortcode to show football data
    Author: fcb88
    Version: 5.2
    Author URI: https://fcb88.com/
*/
defined('ABSPATH') or die('Access denied');
define("SLUG","add-url-btn");

add_action('plugins_loaded', array( 'Api_Plugin', 'init' ) );

register_activation_hook(   __FILE__, array('Api_Plugin', 'on_activation'));
register_deactivation_hook( __FILE__, array('Api_Plugin', 'on_deactivation'));
register_uninstall_hook(    __FILE__, array('Api_Plugin', 'on_uninstall'));

class Api_Plugin
{
    protected static $instance;

    public static function init()
    {
        is_null( self::$instance ) AND self::$instance = new self;
        return self::$instance;
    }

    public function __construct()
    {
        add_action( current_filter(), array( $this, 'load_files' ), 30 );
    }

    public function load_files()
    {
        foreach ( glob( plugin_dir_path( __FILE__ ).'classes/*.php' ) as $file )
        {
            require_once $file;
        }

        $auto_load = new Api_Load(); 
        $api_cron = new Api_System();
    }

    public function on_activation()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
        {
            return;
        }

        foreach ( glob( plugin_dir_path( __FILE__ ).'classes/*.php' ) as $file )
        {
            require_once $file;
        }
        $api_load = new Api_Load();
        $api_load->check_db();
    }

    public function on_deactivation()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
        {
            return;
        }
        $api_load = new Api_Load();
        $api_load->uninstall();
    }

    public function on_uninstall()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
        {
            return;
        }

        if ( __FILE__ != WP_UNINSTALL_PLUGIN )
        {
            return;
        }

        $api_load = new Api_Load();
        $api_load->uninstall();
    }
}
?>