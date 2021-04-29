<?php
/* Class API Football Data Setting */
defined('ABSPATH') or die('Access denied');

class Api_DB 
{
    private $db, $table_name , $table_config;
    private $table = "api_football_data";
    private $config = "api_football_config";

    public function __construct() 
    {
        global $wpdb;
        $this->db         = $wpdb;
        $this->table_name = $this
            ->db->prefix . $this->table;
        $this->table_config = $this
            ->db->prefix . $this->config;  
    }

    public function get_table()
    {
        return $this->table_name;
    }

    public function insert_db() 
    {
        global $wpdb;
        $table_name = $this->table_name;
        $query      = $wpdb->prepare('SHOW TABLES LIKE %s', $this
            ->db
            ->esc_like($this->table_name));
        if (!$wpdb->get_var($query) == $table_name) 
        {
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();
            $install_sql     = "CREATE TABLE `$this->table_name` ( 
                     `ID` INT(20) UNSIGNED NOT NULL AUTO_INCREMENT , 
					 `match_id` INT(20) NOT NULL , 
					 `league_id` INT(20) NOT NULL , 
                     `match_date` DATE NOT NULL , 
					 `match_time` VARCHAR(300) NOT NULL , 
					 `match_hometeam_name` VARCHAR(300) NOT NULL , 
					 `match_hometeam_score` VARCHAR(300) NOT NULL ,
					 `team_home_badge` VARCHAR(300) NOT NULL , 
					 `match_awayteam_name` VARCHAR(300) NOT NULL ,
					  `match_awayteam_score` VARCHAR(300) NOT NULL , 
					  `team_away_badge` VARCHAR(300) NOT NULL , 
					  `ratio1` VARCHAR(300) NULL , 
					  `ratio2` VARCHAR(300) NULL , 
					  `ratio3` VARCHAR(300) NULL , 
					  `button1` VARCHAR(300) NULL , 
                      `button2` VARCHAR(300) NULL , 
					  PRIMARY KEY (`ID`)) $charset_collate;";
            $wpdb->query($install_sql);
            $install_sql_config     = "CREATE TABLE `$this->table_config` ( 
                     `ID` INT(20) UNSIGNED NOT NULL AUTO_INCREMENT , 
                     `config` INT(20)  DEFAULT '15' , 
                      PRIMARY KEY (`ID`)) $charset_collate;";
            $wpdb->query($install_sql_config);
            $install_sql_config = "INSERT INTO $this->table_config (`config`) VALUES ('15')";
            $wpdb->query($install_sql_config);
            
            if ($api_system == null)
            {
                $api_system = new Api_System();
            }    
            $api_system->cron_api();
        }
    }

    public function get_config() 
    {
        $sql    = "SELECT * FROM $this->table_config Limit 1";
        $result = $this
            ->db
            ->get_results($sql);
        return $result;
    }

    public function update_config($data) 
    {
        extract($data);
        $where = array("ID" => $id);
        unset($data[0]);
        $update = $this
            ->db
            ->update($this->table_config, $data, $where);
        return 1;
    }

    public function get_data() 
    {

        $config =  $this->get_config();
        $limit  = $config[0]->config; 
        $sql    = "SELECT * FROM $this->table_name WHERE match_date >= Now() ORDER BY match_date ASC LIMIT $limit";
        $result = $this
            ->db
            ->get_results($sql);
        return $result;
    }

    public function get_data_admin() 
    {

        $config =  $this->get_config();
        $limit  = $config[0]->config; 
        $sql    = "SELECT * FROM $this->table_name WHERE match_date >= Now() ORDER BY match_date ";
        $result = $this
            ->db
            ->get_results($sql);
        return $result;
    }

    public function get_data_with_id($league_id) 
    {
        $sql    = "SELECT * FROM $this->table_name WHERE league_id = $league_id  AND match_date >= Now() ORDER BY match_date ASC LIMIT 15 ";
        $result = $this
            ->db
            ->get_results($sql);
        return $result;
    }

    public function insert_data($data, $format) 
    {
        $insert = $this
            ->db
            ->insert($this->table_name, $data, $format);
        return $insert;
    }

    public function check_match_id($match_id) 
    {
        $sql_check = "SELECT COUNT(`match_id`) FROM $this->table_name WHERE match_id = '$match_id' ";
        $check     = $this
            ->db
            ->get_var($sql_check);
        return $check ;
    }

    public function update_data($data, $format) 
    {
        extract($data);
        $where = array("match_id" => $match_id);
        unset($data[0]);
        $update = $this
            ->db
            ->update($this->table_name, $data, $where);
        return 1;
    }

    public function uninstall_db()
    {
        $sql    = "DROP TABLE $this->table_name";
        $result = $this
            ->db
            ->get_results($sql);
        return $result;
    }
}

?>