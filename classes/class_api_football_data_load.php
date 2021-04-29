<?php
/*
** Class Api Load Function
*/

class Api_Load
{
	public function __construct()
	{
        add_action('admin_init',array($this,'register_style_script'));
        $this->check_db();
        add_action('admin_menu', array($this,'create_menu_list_data'));
        add_shortcode('football', array( $this, 'football'));
        add_action('wp_ajax_api_table', array($this,'api_table_init'));
	}
    
    public function uninstall()
    {
        $api_db ='';
        $api_db = new Api_DB();
        $api_db->uninstall_db();
    }

    public function check_db()
    {
        $api_db = '';
        if ($api_db == null)
        {
            $api_db = new Api_DB();
        }
        $api_db->insert_db();
    }

    public function register_style_script()
    {   
        wp_enqueue_style('style-list-table', plugins_url('css/style.css', __DIR__));
        wp_enqueue_script('script-bootstrap', plugins_url('js/bootstrap.min.js', __DIR__));
    }

    public function football($atts)
    {
        $data_table = '';
        $league_id = '';
        if ($data_table == null)
        {
            $data_table = new Api_Table_Short_Code();
        }
        $league_id = $atts['league_id'];
        $data_table->list_table_data_sc($league_id);
    }

    public function list_data()
    {
        echo '
        <div class="wrap">
            <h1 class="wp-heading-inline">Danh Sách</h1>
                <hr class="wp-header-end">
                <h2 class="screen-reader-text">Lọc danh sách</h2>
                <div id="col">
                    ';
        $api_table = '';
        if ($api_table == null)
        {
            $api_table = new Api_Table();
        }
        $api_table->list_table_data($league_id='') ;
        echo '
            </div>
        </div>';
    }

    public function list_data_england()
    {
        echo '
        <div class="wrap">
            <h1 class="wp-heading-inline">Danh Sách</h1>
                <hr class="wp-header-end">
                <h2 class="screen-reader-text">Lọc danh sách</h2>
                <div id="col">
                    ';
        $api_table= '';
        if ($api_table == null)
        {
            $api_table = new Api_Table();
        }
        $api_table->list_table_data($league_id='148') ;
        echo '
            </div>
        </div>';
    }

    public function list_data_bundesliga()
    {
        echo '
        <div class="wrap">
            <h1 class="wp-heading-inline">Danh Sách</h1>
                <hr class="wp-header-end">
                <h2 class="screen-reader-text">Lọc danh sách</h2>
                <div id="col">
                    ';
        $api_table = '';  
        if ($api_table == null)
        {
            $api_table = new Api_Table();
        }
        $api_table->list_table_data($league_id='195') ;
        echo '
            </div>
        </div>';
    }

    public function list_data_seria_a()
    {
        echo '
        <div class="wrap">
            <h1 class="wp-heading-inline">Danh Sách</h1>
                <hr class="wp-header-end">
                <h2 class="screen-reader-text">Lọc danh sách</h2>
                <div id="col">
                    ';
        $api_table = '';
        if ($api_table == null)
        {
            $api_table = new Api_Table();
        }
        $api_table->list_table_data($league_id='262') ;
        echo '
            </div>
        </div>';
    }

    public function list_data_spain()
    {
        echo '
        <div class="wrap">
            <h1 class="wp-heading-inline">Danh Sách</h1>
                <hr class="wp-header-end">
                <h2 class="screen-reader-text">Lọc danh sách</h2>
                <div id="col">
                    ';
        $api_table = '';
        if ($api_table == null)
        {
            $api_table = new Api_Table();
        }
        $api_table->list_table_data($league_id='468') ;
        echo '
            </div>
        </div>';
    }

    public function list_data_config()
    {
        // echo '
        // <div class="wrap">
        //     <h1 class="wp-heading-inline">Cấu Hình Số Lượng Hiển Thị Số Trận</h1>
        //         <hr class="wp-header-end">
        //         <h2 class="screen-reader-text">Số Lượng/h2>
        //         <div id="col">';
        $api_table = '';
        if ($api_table == null)
        {
            $api_table = new Api_Table();
        }
        $api_table->list_data_config() ;
        // echo '
        //     </div>
        // </div>';
    }  

    public function api_table_init() 
    {
        $data_value  = array(
                                "match_id"  =>$_POST['match_id'],
                                "ratio1"    =>$_POST['ratio1'],
                                "ratio2"    =>$_POST['ratio2'],
                                "ratio3"    =>$_POST['ratio3'],
                                "button1"   =>$_POST['button1'],
                                "button2"   =>$_POST['button2']
        );

        $format      = array(
                                '%s',
                                '%s',
                                '%s',
                                '%s',
                                '%s'
        );
        $api_db = '';
        if ($api_db == null)
        {
            $api_db = new Api_DB();
        }

        $update = $api_db->update_data($data_value, $format) ;
        
        if(!empty($update))
        {
            wp_send_json_success('Cập Nhật Thành Công');
        }
        else
        {
            wp_send_json_success('Cập Nhật Thất Bại');
        }
        die();
    }

    public function register_bootstrap()
    {
       wp_enqueue_style('style-bootstrap', plugins_url('css/bootstrap.min.css', __DIR__));
    }  

    public function create_menu_list_data()
    {
        add_menu_page(__('Danh Sách', 'danhsach') , 'Danh Sách', 'edit_posts', SLUG, array($this,'list_data'), 'dashicons-admin-links', 3);
        add_submenu_page(SLUG,'Cầu Hình','Cầu Hình','edit_posts','add-url-btn-config-data',array($this,'list_data_config'));
        add_submenu_page(SLUG,'Giải England','Giải England','edit_posts','add-url-btn-england',array($this,'list_data_england'));
        add_submenu_page(SLUG,'Giải Bundesliga','Giải Bundesliga','edit_posts','add-url-btn-bundesliga',array($this,'list_data_bundesliga'));
        add_submenu_page(SLUG,'Giải Seria A','Giải Seria A','edit_posts','add-url-btn-seria-a',array($this,'list_data_seria_a'));
        add_submenu_page(SLUG,'Giải Spain','Giải Spain','edit_posts','add-url-btn-spain',array($this,'list_data_spain'));
    }        
}





