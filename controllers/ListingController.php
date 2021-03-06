<?php

class ListingController extends BaseController{
    
    
    public function actionIndex(){
	$system = new System();
    $getSettings = $this->db->query('SELECT * FROM ga_settings');
    $settings = $getSettings->fetch();   
    $settings = json_decode($settings['content'], true);
    $title = "Листинг серверов";
	
	
	$befirstServers = [];
    for($i = 1; $i <= $settings['global_settings']['count_servers_befirst']; $i++){
		$isPlace = $this->db->prepare('SELECT * FROM ga_servers WHERE befirst_enabled = :befirst_enabled');
		$isPlace->execute(array(':befirst_enabled' => $i));    
		if($isPlace->rowCount() != '0'){
			$getInfoServerBefirst = $this->db->prepare('SELECT * FROM ga_servers WHERE befirst_enabled = :befirst_enabled');
			$getInfoServerBefirst->execute(array(':befirst_enabled' => $i));
			$getInfoServerBefirst = $getInfoServerBefirst->fetch();
			if(file_exists("public/img/flags/".strtolower($getInfoServerBefirst['country']).".png")){
				$imgCountry = "public/img/flags/".strtolower($getInfoServerBefirst['country']).".png";
			}else{
				$imgCountry = "public/img/flags/unknown.png";
			}
			$show_players = $system->showbar( $getInfoServerBefirst['players'], $getInfoServerBefirst['max_players'] );
			$befirstServers[] = ['id_position' => $i, 'hostname' => $getInfoServerBefirst['hostname'], 'show_players' => $show_players, 'imgCountry' => $imgCountry, 'country' => $getInfoServerBefirst['country'], 'map' => $getInfoServerBefirst['map'], 'players' => $getInfoServerBefirst['players'], 'max_players' => $getInfoServerBefirst['max_players'], 'id' => $getInfoServerBefirst['id'], 'ip' => $getInfoServerBefirst['ip'], 'port' => $getInfoServerBefirst['port'], 'status' => 1, 'befirst_expired_date' => $getInfoServerBefirst['befirst_expired_date']];
		}
    
    }
    
    $topServers = [];
    for($i = 1; $i <= $settings['global_settings']['count_servers_top']; $i++){
		$isPlace = $this->db->prepare('SELECT * FROM ga_servers WHERE top_enabled = :top_enabled');
		$isPlace->execute(array(':top_enabled' => $i));    
		if($isPlace->rowCount() != '0'){
			$getInfoServer = $this->db->prepare('SELECT * FROM ga_servers WHERE top_enabled = :top_enabled');
			$getInfoServer->execute(array(':top_enabled' => $i));
			$getInfoServer = $getInfoServer->fetch();
			if(file_exists("public/img/flags/".strtolower($getInfoServer['country']).".png")){
				$imgCountry = "public/img/flags/".strtolower($getInfoServer['country']).".png";
			}else{
				$imgCountry = "public/img/flags/unknown.png";
			}
			$show_players = $system->showbar( $getInfoServer['players'], $getInfoServer['max_players'] );
			
			$topServers[] = ['id_position' => $i, 'hostname' => $getInfoServer['hostname'], 'show_players' => $show_players, 'imgCountry' => $imgCountry, 'country' => $getInfoServer['country'], 'map' => $getInfoServer['map'], 'players' => $getInfoServer['players'], 'max_players' => $getInfoServer['max_players'], 'id' => $getInfoServer['id'], 'ip' => $getInfoServer['ip'], 'port' => $getInfoServer['port'], 'status' => 1, 'top_expired_date' => $getInfoServer['top_expired_date']];
		}
    
    }
	
	// Add info Befirst
    $max_servers_befirst = $settings['global_settings']['count_servers_befirst'];	# кол-во мест в услуге
    $countBefirstServers = $this->db->prepare('SELECT * FROM ga_servers WHERE befirst_enabled != :befirst_enabled');	# кол-во занятых мест
    $countBefirstServers->execute(array(':befirst_enabled' => 0)); 
    $countBefirstServers = $countBefirstServers->rowCount();
	$free_servers_befirst = $max_servers_befirst - $countBefirstServers;	# кол-во свободных мест
	if($free_servers_befirst == $max_servers_befirst){$free_date_befirst = "~";}# Если все места свободны
	if($getInfoServerBefirst['befirst_enabled'] == '0') $place != 0; else $place = 0;
	$CheckBefirstServerDate = $this->db->prepare("SELECT `befirst_expired_date` FROM `ga_servers` WHERE `befirst_enabled`!='0' ORDER by `befirst_expired_date` ASC LIMIT 1");	# вывод ближайшей даты
	$CheckBefirstServerDate->execute(array(':befirst_enabled' => $place));
	$CheckBefirstServerDate = $CheckBefirstServerDate->fetchAll();
	foreach($CheckBefirstServerDate as $befirst)
		if ($free_servers_befirst  > 0) {
			$free_date_befirst = "~";
		}
		else {
			$free_date_befirst = date("d.m.Y [H:i]", $befirst['befirst_expired_date']);
		}
	// End

	// Add info TOP
    $max_servers_top = $settings['global_settings']['count_servers_top'];	# кол-во мест в услуге
    $countTopServers = $this->db->prepare('SELECT * FROM ga_servers WHERE top_enabled != :top_enabled');	# кол-во занятых мест
    $countTopServers->execute(array(':top_enabled' => 0)); 
    $countTopServers = $countTopServers->rowCount();
	$free_servers_top = $max_servers_top - $countTopServers;	# кол-во свободных мест
	if($free_servers_top == $max_servers_top){$free_date_top = "~";}# Если все места свободны
	if($getInfoServer['top_enabled'] == '0') $place != 0; else $place = 0;
	$CheckTopServerDate = $this->db->prepare("SELECT `top_expired_date` FROM `ga_servers` WHERE `top_enabled`!='0' ORDER by `top_expired_date` ASC LIMIT 1");	# вывод ближайшей даты
	$CheckTopServerDate->execute(array(':top_enabled' => $place));
	$CheckTopServerDate = $CheckTopServerDate->fetchAll();
	foreach($CheckTopServerDate as $top)
		if ($free_servers_top  > 0) {
			$free_date_top = "~";
		}
		else {
			$free_date_top = date("d.m.Y [H:i]", $top['top_expired_date']);
		}
	// End
	
	// Add info Boost
    $max_servers_boost = $settings['global_settings']['count_servers_boost'];	# кол-во мест в услуге
    $countBoostServers = $this->db->prepare('SELECT * FROM ga_servers WHERE boost != :boost');	# кол-во занятых мест
    $countBoostServers->execute(array(':boost' => 0)); 
    $countBoostServers = $countBoostServers->rowCount();
	$free_servers_boost = $max_servers_boost - $countBoostServers;	# кол-во свободных мест
	// End
	
    // Add info VIP
    $max_servers_vip = $settings['global_settings']['count_servers_vip'];	# кол-во мест в услуге
    $countVipServers = $this->db->prepare('SELECT * FROM ga_servers WHERE vip_enabled != :vip_enabled');	# кол-во занятых мест
    $countVipServers->execute(array(':vip_enabled' => 0)); 
    $countVipServers = $countVipServers->rowCount();
	$free_servers_vip = $max_servers_vip - $countVipServers;	# кол-во свободных мест
	if($free_servers_vip == $max_servers_vip){$free_date_vip = "~";}	# Если все места свободны
	$CheckVipServerDate = $this->db->prepare("SELECT `vip_expired_date` FROM `ga_servers` WHERE `vip_enabled`='1' ORDER by `vip_expired_date` ASC LIMIT 1");	# вывод ближайшей даты
	$CheckVipServerDate->execute(array(':vip_enabled' => 0));
	$CheckVipServerDate = $CheckVipServerDate->fetchAll();
	foreach($CheckVipServerDate as $vip)
		if ($free_servers_vip  > 0) {
			$free_date_vip = "~";
		}
		else {
			$free_date_vip = date("d.m.Y [H:i]", $vip['vip_expired_date']);
		}
	// End
	
	// Add info Gamemenu
    $max_servers_gamemenu = $settings['global_settings']['count_servers_gamemenu'];	# кол-во мест в услуге
    $countGamemenuServers = $this->db->prepare('SELECT * FROM ga_servers WHERE gamemenu_enabled != :gamemenu_enabled');	# кол-во занятых мест
    $countGamemenuServers->execute(array(':gamemenu_enabled' => 0)); 
    $countGamemenuServers = $countGamemenuServers->rowCount();
	$free_servers_gamemenu = $max_servers_gamemenu - $countGamemenuServers;	# кол-во свободных мест
	if($free_servers_gamemenu == $max_servers_gamemenu){$free_date_gamemenu = "~";}	# Если все места свободны
	$CheckGamemenuServerDate = $this->db->prepare("SELECT `gamemenu_expired_date` FROM `ga_servers` WHERE `gamemenu_enabled`='1' ORDER by `gamemenu_expired_date` ASC LIMIT 1");	# вывод ближайшей даты
	$CheckGamemenuServerDate->execute(array(':gamemenu_enabled' => 0));
	$CheckGamemenuServerDate = $CheckGamemenuServerDate->fetchAll();
	foreach($CheckGamemenuServerDate as $gamemenu)
		if ($free_servers_gamemenu  > 0) {
			$free_date_gamemenu = "~";
		}
		else {
			$free_date_gamemenu = date("d.m.Y [H:i]", $gamemenu['gamemenu_expired_date']);
		}
	// End
	
	// Add info Color
    $max_servers_color = $settings['global_settings']['count_servers_color'];	# кол-во мест в услуге
    $countColorServers = $this->db->prepare('SELECT * FROM ga_servers WHERE color_enabled != :color_enabled');	# кол-во занятых мест
    $countColorServers->execute(array(':color_enabled' => 0)); 
    $countColorServers = $countColorServers->rowCount();
	$free_servers_color = $max_servers_color - $countColorServers;	# кол-во свободных мест
	if($free_servers_color == $max_servers_color){$free_date_color = "~";}	# Если все места свободны
	$CheckColorServerDate = $this->db->prepare("SELECT `color_expired_date` FROM `ga_servers` WHERE `color_enabled`!= '0' ORDER by `color_expired_date` ASC LIMIT 1");	# вывод ближайшей даты
	$CheckColorServerDate->execute(array(':color_enabled' => 0));
	$CheckColorServerDate = $CheckColorServerDate->fetchAll();
	foreach($CheckColorServerDate as $color)
		if ($free_servers_color  > 0) {
			$free_date_color = "~";
		}
		else {
			$free_date_color = date("d.m.Y [H:i]", $color['color_expired_date']);
		}
	// End
	
    $getVipServers = $this->db->prepare('SELECT * FROM ga_servers WHERE vip_enabled != :vip_enabled');
    $getVipServers->execute(array(':vip_enabled' => 0));   
    $getVipServers = $getVipServers->fetchAll();

    $getBoostServers = $this->db->prepare('SELECT * FROM ga_servers WHERE boost != :boost ORDER BY boost_position DESC');
    $getBoostServers->execute(array(':boost' => 0)); 
    $getBoostServers = $getBoostServers->fetchAll();
	
	$getColorServers = $this->db->prepare('SELECT * FROM ga_servers WHERE color_enabled != :color_enabled');
    $getColorServers->execute(array(':color_enabled' => 0));   
    $getColorServers = $getColorServers->fetchAll();
	
	$getGamemenuServers = $this->db->prepare('SELECT * FROM ga_servers WHERE gamemenu_enabled != :gamemenu_enabled');
    $getGamemenuServers->execute(array(':gamemenu_enabled' => 0));   
    $getGamemenuServers = $getGamemenuServers->fetchAll();

    $content = $this->view->renderPartial("listing", [
	'settings' => $settings,
	'free_servers_boost' => $free_servers_boost,
	'free_servers_befirst' => $free_servers_befirst,
	'free_date_befirst' => $free_date_befirst,
	'max_servers_befirst' => $max_servers_befirst,
	'free_servers_top' => $free_servers_top,
	'free_date_top' => $free_date_top,
	'max_servers_top' => $max_servers_top,
	'free_servers_vip' => $free_servers_vip,
	'free_date_vip' => $free_date_vip,
	'max_servers_vip' => $max_servers_vip,
	'free_servers_color' => $free_servers_color,
	'free_date_color' => $free_date_color,
	'max_servers_color' => $max_servers_color,
	'free_servers_gamemenu' => $free_servers_gamemenu,
	'free_date_gamemenu' => $free_date_gamemenu,
	'max_servers_gamemenu' => $max_servers_gamemenu,
	'befirstServers' => $befirstServers,
	'topServers' => $topServers,
	'vipServers' => $getVipServers,
	'gamemenuServers' => $getGamemenuServers,
	'colorServers' => $getColorServers,
	'countBefirstServers' => $countBefirstServers,
	'countTopServers' => $countTopServers,
	'countBoostServers' => $countBoostServers,
	'countVipServers' => $countVipServers,
	'countColorServers' => $countColorServers,
	'countGamemenuServers' => $countGamemenuServers,
	'boostServers' => $getBoostServers
	]);
	
    $this->view->render("main", ['content' => $content, 'title' => $title]);
    }
    
}