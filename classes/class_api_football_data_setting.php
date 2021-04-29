<?php
/* Class API Football Data Setting */

class Api_System
{
    public function __construct()
    {
        add_action('wp_version_check', array($this, 'cron_api'));
    }

    public function api_call()
    {
        $info_api = $this->info_api();
        extract($info_api);
        $curl_options              = array(
            CURLOPT_URL            => "https://apiv2.apifootball.com/?action=get_events&from=$day_from&to=$day_to&APIkey=$api_key&timezone=$timezone",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 5
        );
        $curl                  = curl_init();
        curl_setopt_array($curl, $curl_options);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }

    private function info_api()
    {
        $day_from = date("Y-m-d");
        $day_to   = date("Y-m-d");
        $day_to   = strtotime($day_to);
        $day_to   = strtotime("+10 day", $day_to);
        $day_to   = date('Y-m-d', $day_to);
        $info     = array(
            "api_key" => "53fe7044dcff41236c7449bd60fe6a36ec406a3f4a8769468d3467b1486e3203",
            "timezone" => "Asia/Ho_Chi_Minh",
            "day_from" => "$day_from",
            "day_to" => "$day_to",
        );
        return $info;
    }

    public function league()
    {
        return $data = array(
            "148",
            "195",
            "262",
            "468"
        );
    }

    public function cron_api()
    {  
        if ($api_db == null)
        {
            $api_db  = new Api_DB();
        }
        $data_api    = $this->api_call();
        $data_info   = json_decode($data_api);
        foreach ($data_info as $num => $data) 
        {
            $data_value        = array(
            "match_id"             => $data->match_id,
            "league_id"            => $data->league_id,
            "match_date"           => $data->match_date,
            "match_time"           => $data->match_time,
            "match_hometeam_name"  => $data->match_hometeam_name,
            "match_hometeam_score" => $data->match_hometeam_score,
            "team_home_badge"      => $data->team_home_badge,
            "match_awayteam_name"  => $data->match_awayteam_name,
            "match_awayteam_score" => $data->match_awayteam_score,
            "team_away_badge"      => $data->team_away_badge
            );

            if ( in_array( $data->league_id,$this->league() ) )
            {
                $check_league_id = $api_db->check_match_id($data->match_id);
                if ($api_db->check_match_id($data->match_id) == 0)
                {
                    $format      = array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                    );
                    $insert_data = $api_db->insert_data($data_value, $format);
                }
                else
                {
                    $format      = array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                    );
                    $update = $api_db->update_data($data_value, $format);
                }
            }
        }
    }
}
?>