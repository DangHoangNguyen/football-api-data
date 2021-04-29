<?php
/* Class API Football Data Table */

class Api_Table
{
	public function __construct()
	{
		$this->check_admin_screen();
	}

	public function check_admin_screen()
	{
	    $url =  "screen".$_GET['page'];
	    $screen = strpos($url, SLUG,0);
	    if($screen == true)
	    {
	    	$api_load = '';
	    	if ($api_load == null)
	    	{
	    		$api_load = new Api_Load();	
	    		$api_load->register_bootstrap();  		
	    	}
		}
	}

	public function list_data_config()
	{
		$api_db = '';
		if ($api_db == null)
        {
            $api_db = new Api_DB();
        }
        
        if(!empty ($_POST['save']))
        {
        	$config_data = array(
        		'id'=> $_POST['idconfig'],
        		'config'=> $_POST['config']
        	);
        	$update_config = $api_db->update_config($config_data);

        }

        $get_data 	 = $api_db->get_config();
        $config   	 = $get_data[0]->config;
        $id_config   = $get_data[0]->ID;

        echo '<div class="wrap">
             <h1 class="wp-heading-inline">Cấu Hình Số Lượng Hiển Thị Số Trận</h1>
                 <hr class="wp-header-end">
                <div id="col"><form name="config" id="config" method="post">
               	 <input type="number" name="config" id="config" value="'.$config.'">
               	 <input hidden type="number" name="idconfig" id="idconfig" value="'.$id_config.'">
               	 <input type="submit" name="save" id="publish" class="button button-primary button-large" value="Cập nhật">
                </form>
                </div>
        </div>';
	}

	public function list_table_data($league_id)
	{
		$api_db = '';
		if ($api_db == null)
        {
            $api_db = new Api_DB();
        }

        $get_data = $api_db->get_data_admin();
        
        if($league_id > 0)
        {
			$get_data = $api_db->get_data_with_id($league_id);
        }
		echo '<form method="post"><button class="button button-primary reload" id="reload">Tải Lại</button></form>';
		echo '<style type="text/css" > th{text-align:center !important;} </style>';
		echo '<table class="football_api_data" style="width:100%" border="1">';
		echo '
		<tr>
            <th>Ngày Đá</th>
            <th>Đội Nhà</th>
            <th>Tỉ Số</th>
            <th>Đội Khách</th>
            <th>Tỉ Lệ</th>
        </tr>';
		foreach ($get_data as $key => $data) 
		{
			$match_date = date("d/m/Y",strtotime($data->match_date)) ;
			?>
				<tr>
					<th>
						<?php echo $match_date;  ?>
					</th>
					<th>
						<?php echo $data->match_hometeam_name.'<br />'.'<img class="center-logo" src="'.$data->team_home_badge.'"/>';?>
					</th>
					<?php
					if(!empty($data->match_hometeam_score))
					{ ?>
						<th>
							<?php echo $data->match_hometeam_score.":".$data->match_awayteam_score;?>
						</th>
					<?php 
					}
					else
					{ 
						echo "<th class='vs_class'>VS</th>";
					} 
					?>
					<th>
						<?php echo $data->match_awayteam_name.'<br />'.'<img class="center-logo" rel=nofollow src="'.$data->team_away_badge.'"/>';?>
					</th>
					<?php
						$style = "color:#ffff";
						if( (empty($data->match_hometeam_score)) && (empty ($data->ratio1)) )
						{
							$style = "color: red";
						}
					?>	
					<th>
						<a data-toggle="modal" class="modal-click block" data-target="#infodata<?php echo $data->ID;?>" style="<?php echo $style ;?>" >Nhận định</a>
					</th>
				</tr>
				  
			<div class="modal1 fade in" id="infodata<?php  echo $data->ID;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;z-index: 99999999; opacity: 1;">
							  <div class="modal1-body" style="max-width:450px">
							    <div class="modal1-content">
							      <div class="heading modal1-heading">Thiết Lập Thông Tin</div>
							      <div>
							        <form name="finfodata<?php  echo $data->ID ;?>" id="finfodata<?php  echo $data->ID ;?>" class="bg website-action-form" method="POST">
							        <div>
							          <div class="alert-infoo">Ngày Diễn Ra <?php echo $match_date ?></div>
							          <div class="panel panel-info">
							            <div class="panel-body">

							              <div class="col-md-4">
							              	<label class="lable-apii-left"><?php echo $data->match_hometeam_name ;?></label>
							              	<img class="left-logo" src="<?php echo $data->team_home_badge ;?>"/>
							              </div>

							              <div class="col-md-4">
							              	<!-- <label>Tỉ Lệ 1</label> -->
							              	<input type="text" class="center-text" name="ratio1" id="ratio1<?php echo $data->ID;?>" placeholder="Tỉ Lệ 1" value="<?php echo $data->ratio1 ;?>">
							              	<!-- <label>Tỉ Lệ 2</label> -->
							               	<input type="text" class="center-text" name="ratio2" id="ratio2<?php echo $data->ID;?>" placeholder="Tỉ Lệ 2" value="<?php echo $data->ratio2 ;?>"> 
							              	<!-- <label>Tỉ Lệ 3</label> -->
							              	<input type="text" class="center-text" name="ratio3" id="ratio3<?php echo $data->ID;?>" placeholder="Tỉ Lệ 3" value="<?php echo $data->ratio3 ;?>">
							              </div>

							              <div class="col-md-4">
							              	<label class="lable-apii-right"><?php echo $data->match_hometeam_name ;?></label>
							              	<img class="right-logo" src="<?php echo $data->team_away_badge ;?>"/>
							              </div>

							              <div class="col-sm-6 margin-api"></div>
							              	<input hidden type="text" name="idauto" id="idauto" value="<?php echo $data->ID ;?>" >
							              	<input hidden type="text" name="match_id" id="match_id<?php echo $data->ID;?>" value="<?php echo $data->match_id ;?>" >
							              <div class="col-sm-6 margin-api"></div>

							              <div class="col-sm-6">
							              	<input type="text" name="button1" id="button1<?php echo $data->ID;?>" placeholder="Nút 1" value="<?php echo $data->button1 ;?>">
							              </div>

							              <div class="col-sm-6">
							              	<input type="text" name="button2" id="button2<?php echo $data->ID;?>" placeholder="Nút 2" value="<?php echo $data->button2 ;?>">
							              </div>

							            </div>
							          </div>
							        </div>
							        <div class="website-action-msg hidden alert-danger"></div>
							        <div class="showmss" id="showmss<?php echo $data->ID ;?>"></div>
							        <div class="showmss" id="smss<?php echo $data->ID ;?>"></div>
							        <div class="pd-10 center" id="button" style="margin-top: 0;">
							          <button type="submit" class="bbutton" id="bbutton<?php echo $data->ID ;?>">Cập Nhật</button>
							          <button type="button" class="bbutton" data-dismiss="modal">Thoát</button>
							        </div>
							        <div class="margin-api"></div>
							        </form>
							      </div>
							    </div>
							  </div>
						</div>
			<script type="text/javascript">
				jQuery(document).ready(function($){
				  $('#bbutton<?php echo $data->ID ;?>').click(function(e){
				  	e.preventDefault();
				    $('#showmss<?php echo $data->ID ;?>').html('');
				    $('#smss<?php echo $data->ID ;?>').html('');
				    $('#showmss<?php echo $data->ID ;?>').html('<div class ="showmss">Vui Lòng Chờ Trong Giây Lát</div>');
				    var idauto 		= $('#idauto').val();
				    var match_id= $('#match_id<?php echo $data->ID;?>').val();
				    var ratio1  = $('#ratio1<?php echo $data->ID;?>').val();
				    var ratio2  = $('#ratio2<?php echo $data->ID;?>').val();
				    var ratio3  = $('#ratio3<?php echo $data->ID;?>').val();
				    var button1 = $('#button1<?php echo $data->ID;?>').val();
				    var button2 = $('#button2<?php echo $data->ID;?>').val();		
				    $.ajax({
				      url : '<?php echo admin_url('admin-ajax.php');?>',
				      type: 'post',
				      dataType: 'json',
				      data: {
				      	action: "api_table",
				        idauto: idauto,
				        match_id: match_id,
				        ratio1: ratio1,
				        ratio2: ratio2,
				        ratio3: ratio3,
				        button1: button1,
				        button2: button2
				      },
				      success: function(result) {
				        var mss = result.data;
				        if (mss != '') {
				          $('#smss<?php echo $data->ID ;?>').append(mss);
				        } else {
				          $('#smss<?php echo $data->ID ;?>').append('Cập Nhật Thất Bại');
				        }
				        $('#showmss<?php echo $data->ID ;?>').html('');
				      }
				    });
				    return false;
				  });
				});
				</script>			
		<?php
		} 
	echo '</table>';
	}
}

class Api_Table_Short_Code 
{
	public function list_table_data_sc($league_id)
	{
		$api_db = '';
		if ($api_db == null)
        {
            $api_db = new Api_DB();
        }

        $get_data = $api_db->get_data();
        
        if($league_id > 0)
        {
			$get_data = $api_db->get_data_with_id($league_id);
        }
		foreach ($get_data as $key => $data) 
		{
			$match_date = date("d/m/Y ",strtotime($data->match_date)) ;
			$match_time = $data->match_time;
			?>	
			<div class="row ratio_wrap_list">
				<div class="match_date_ratio col-md-12">Ngày Diễn Ra
					<?php echo $match_time . " - ".$match_date ?>
				</div>	
				<div class="col-md-3">
					<img class="team_logo" src="<?php echo $data->team_home_badge ;?>" />
					<p class="team_name">
						<?php echo $data->match_hometeam_name ;?></p>
				</div>
				<div class="col-md-3">
					<p class="ratio_class">
						<?php echo $data->ratio1; ?></p>
					<p class="ratio_class">
						<?php echo $data->ratio2; ?></p>
					<p class="ratio_class">
						<?php echo $data->ratio3; ?></p>
				</div>
				<div class="col-md-3">
					<img class="team_logo" src="<?php echo $data->team_away_badge ;?>" />
					<p class="team_name">
						<?php echo $data->match_awayteam_name ;?></p>
				</div>
				<div class="col-md-3 ratio_btn_wrap">
					<div>
						<button onclick="window.location.href='<?php echo $data->button1;?>'" class="ratio_btn_first" name="button1" id="button1<?php echo $data->ID;?>" placeholder="Nút 1" value="<?php echo $data->button1 ;?>">Soi kèo</button>
					</div>
					<div>	
					<button onclick="window.location.href='<?php echo $data->button2;?>'" class="ratio_btn_2nd" name="button2" hefr id="button2<?php echo $data->ID;?>" placeholder="Nút 2" value="<?php echo $data->button2 ;?>">Đặt cược</button>
					</div>
				</div>
		</div>	    
			<?php
		} 
		// echo '</div>';
	}
}

?>