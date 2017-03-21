<?php
/*
Plugin Name: On site seo
Plugin URI: http://haidersiddiq.info/wp-content/uploads/2017/03/onsiteseo.zip
Description: You can add meta keywords into your wordpress page
Version: 1.0
Author: Bilas Siddiq
Author URI: http://haidersiddiq.info
License: GPL2
*/

class OnsiteSeo
{
    private static $PLUGIN_URL;
    public function OnsiteSeo()
    {
        $this->PLUGIN_URL = plugin_dir_path( __FILE__ );
        add_action('wp_head', array(&$this, 'AddMetaTags'));
        add_action('admin_menu',array(&$this,'SalsabeelKeywordsMenu'));
        add_action('wp_ajax_add_keywords_into_page',array(&$this,'AddKeywordsIntoPage'));
        add_action('wp_ajax_get_keywords_by_page_slug',array(&$this,'GetKeywordsByPageSlug'));
    }
    public function AddMetaTags()
    {
        global $post;
        $metakeywords = get_post_meta($post->ID, $post->post_name, true);
        echo '<meta name="keywords" content="' . $metakeywords . '" />' . "\n";
    }
    
    public function SalsabeelKeywordsMenu()
    {
        add_menu_page( 'SEO Keywords', 'SEO Keywords', 'manage_options', 'add_keywords',array(&$this,'AddKeywordsPage'));
    }
    
    public function AddKeywordsPage()
    {
        include_once($this->PLUGIN_URL."/template/insert_keywords.php");
    }
    
    public function AddKeywordsIntoPage()
    {
        $page_slug = $_POST['page_slug'];
        $page_title = $_POST['page_title'];
        $keywords = $_POST['keywords'];
        $page_id     = &$this->get_id_by_slug($page_slug);
        $_POST['page_id'] = $page_id;
        update_post_meta($page_id, $page_slug, $keywords);
        echo json_encode($_POST);
        exit();
    }
    
    public function get_id_by_slug($page_slug) 
    {
        $page = get_page_by_path($page_slug);
        if ($page) {
            return $page->ID;
        } else {
            return null;
        }
    }

    public function GetKeywordsByPageSlug()
    {
        $page_slug = $_POST['page_slug'];
        $page_id = &$this->get_id_by_slug($page_slug);
        $keywords = get_post_meta($page_id, $page_slug, true);
        echo json_encode($keywords);
        exit();
    }
}//End of class OnsiteSeo;

$wpOnsiteSeo = new OnsiteSeo();
?>