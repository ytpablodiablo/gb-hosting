<?php

error_reporting(0);

class GTApi {

	private $apilink;
	private $ip;
	private $port;

	/* FOR PLAYER GRAFICON */

	private $plot;
	private $graph;
	private $graphtime;
	private $logplayer;

	/* FOR GetServerHostedMap */

	private $servergeomap;

	function __construct($serverip, $serverport) {

		$this->apilink = json_decode(@file_get_contents('http://api.gametracker.rs/demo/json/server_info/' . $serverip . ':' . $serverport));
		$this->ip 	= $serverip;
		$this->port = $serverport;

	}

	public function GTBigGraficonPlayer() {

		require_once('graficon/phplot.php');
		
		$players_day 	= explode(':', $this->apilink->players_day);
		$replacetime	= array('-1' => 23, '-2' => 22, '-3' => 21, '-4' => '20', '-5' => 19, '-6' => 18, '-7' => 17, '-8' => 16, '-9' => 15, '-10' => 14, '-11' => 13, '-12' => 12, '-13' => 11, '-14' => 10);

		$playersmax 		= $this->apilink->playersmax;
		$this->plot 		= new PHPlot(269,180);

		$this->plot->SetYTickIncrement(16);
		$this->plot->SetPlotType('lines');

		$this->graph = array();
		for ($i = 24; $i >= 0; $i--){
	        if ($i / 2 == round($i / 2)){
	            $vreme = $i;
	            $this->graphtime = date("H") - $vreme;
	            if(substr_count($this->graphtime, '-') > 0) {
	            	$this->graphtime = $replacetime[$this->graphtime];
	            }
	        } else {
	            $this->graphtime = "";
	        }

	        $this->logplayer = array_chunk($players_day, 2);

	        $countval = count($this->logplayer);
	        for($k=0; $k <= 1; $k++){
	        	if($i == 24) {
	        		$broj = isset($this->logplayer[24][0]) ? $this->logplayer[24][0] : $this->logplayer[24][0] = round(($this->logplayer[15][0] + $this->logplayer[23][1]) / 2);
	        	} else {
	            	$broj = round(($broj + $this->logplayer[$i][$k]) / 2);
	        	}
	        }
	 
	 
	        $broj = $broj;
	        
	        if (!is_numeric($broj)){
	            $broj=0;
	        }

	        if(!isset($broj)){
	            $broj=0;
	        }

	        array_push($this->graph, array($this->graphtime, $broj));
		}

		$this->plot->SetDataValues($this->graph);
		$this->plot->SetPlotBorderType("left");

		$this->plot->SetXTickLength("3");
		$this->plot->SetXTickIncrement(22);
		$this->plot->SetPrecisionX(0);
		$this->plot->SetYTickIncrement($playersmax / 4);
		$this->plot->SetLightGridColor("black");
		$this->plot->SetDrawDashedGrid("solid");
		$this->plot->SetDrawYGrid("solid");
		$this->plot->SetBackgroundColor("black");
		$this->plot->SetDataColors('cyan');
		$this->plot->SetTextColor("white");
		$this->plot->SetGridColor("white");
		$this->plot->SetDrawXGrid(True);
		$this->plot->SetDrawYGrid(True);
		$this->plot->SetPlotAreaWorld(null, 0, null, $playersmax);

		return $this->plot->DrawGraph();
	}

	public function GTSmallForBanner() {
		
		$players_day 	= explode(':', $this->apilink->players_day);
		$replacetime	= array('-1' => 23, '-2' => 22, '-3' => 21, '-4' => '20', '-5' => 19, '-6' => 18, '-7' => 17, '-8' => 16, '-9' => 15, '-10' => 14, '-11' => 13, '-12' => 12, '-13' => 11, '-14' => 10);

		$playersmax 		= $this->apilink->playersmax;
		$this->plot 		= new PHPlot(169,80);

		$this->plot->SetYTickIncrement(16);
		$this->plot->SetPlotType('lines');

		$this->graph = array();
		for ($i = 24; $i >= 0; $i--){
	        if ($i / 6 == round($i / 6)){
	            $vreme = $i / 3;
	            $this->graphtime = date("H") - $vreme;
	            if(substr_count($this->graphtime, '-') > 0) {
	            	$this->graphtime = $replacetime[$this->graphtime];
	            }
	        } else {
	            $this->graphtime = "";
	        }

	        $this->logplayer = array_chunk($players_day, 2);

	        $countval = count($this->logplayer);
	        for($k=0; $k <= 1; $k++){
	        	if($i == 24) {
	        		$broj = isset($this->logplayer[24][0]) ? $this->logplayer[24][0] : $this->logplayer[24][0] = round(($this->logplayer[15][0] + $this->logplayer[23][1]) / 2);
	        	} else {
	            	$broj = round(($broj + $this->logplayer[$i][$k]) / 2);
	        	}
	        }
	 
	 
	        $broj = $broj;
	        
	        if (!is_numeric($broj)){
	            $broj=0;
	        }

	        if(!isset($broj)){
	            $broj=0;
	        }

	        array_push($this->graph, array($this->graphtime, $broj));
		}

		$this->plot->SetDataValues($this->graph);
		$this->plot->SetPlotBorderType("left");

		$this->plot->SetXTickLength("3");
		$this->plot->SetXTickIncrement(22);
		$this->plot->SetPrecisionX(0);
		$this->plot->SetYTickIncrement($playersmax / 4);
		$this->plot->SetLightGridColor("black");
		$this->plot->SetDrawDashedGrid("solid");
		$this->plot->SetDrawYGrid("solid");
		$this->plot->SetBackgroundColor ("black") ;
		$this->plot->SetDataColors('white');
		$this->plot->SetTextColor("white");
		$this->plot->SetGridColor("white");
		$this->plot->SetDrawXGrid(True);
		$this->plot->SetDrawYGrid(True);
		$this->plot->SetPlotAreaWorld(null, 0, null, $playersmax);

		return $this->plot->DrawGraph();
	}

	public function GraficonInBanner($bannercolor = null) {

		if($this->apilink->apiError != 1) {

			if($bannercolor != null) {
                header('Content-Type: image/png');
                $bg = imagecreatefrompng('graficon/sabloni/' . $bannercolor . '.png');
	        } else {
                header('Content-Type: image/png');
                $bg = imagecreatefrompng('graficon/sabloni/banner.png');
	        }

	        $img = imagecreatefrompng('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '/graficon/graph.php');
	         
	        $black = imagecolorallocate($img, 255,255, 255);    
	         
	        imagettftext($bg, 7.5, 0, 110, 90, imagecolorallocate($bg,255,255,255), "graficon/fonts/slova.otf", $this->ip);
	        imagettftext($bg, 7.5, 0, 211, 90, imagecolorallocate($bg,255,255,255), "graficon/fonts/slova.otf", $this->port);
	        
	        if($this->apilink->status == 1) {
	       		imagettftext($bg, 7.5, 0, 278, 90, imagecolorallocate($bg,42,187,83), "graficon/fonts/slova.otf", "Online!");
	        } else {
	        	imagettftext($bg, 7.5, 0, 278, 90, imagecolorallocate($bg,255,0,0), "graficon/fonts/slova.otf", "Offline!");
	        }

	        imagettftext($bg, 7.5, 0, 110, 133, imagecolorallocate($bg,255,255,255), "graficon/fonts/slova.otf", $this->apilink->map);
	        imagettftext($bg, 7.5, 0, 211, 133, imagecolorallocate($bg,255,255,255), "graficon/fonts/slova.otf", $this->apilink->players . "/" . $this->apilink->playersmax);
	        imagettftext($bg, 7.5, 0, 298, 133, imagecolorallocate($bg,255,255,255), "graficon/fonts/slova.otf", $this->apilink->rank);
	        imagettftext($bg, 7.5, 0, 110, 50, imagecolorallocate($bg,255,255,255), "graficon/fonts/slova.otf", $this->apilink->name);
	        imagettftext($bg, 7.5, 0, 327, 30, imagecolorallocate($bg,255,255,255), "graficon/fonts/slova.otf", "#OF PLAYERS [past 24hours]");
	        imagettftext($bg, 7.5, 0, 370, 140, imagecolorallocate($bg,255,255,255), "graficon/fonts/slova.otf", "www.gb-hoster.me");

	        imagettftext($bg, 7.5, 0, 5, 111, imagecolorallocate($bg,255,255,255), "slova.otf", $this->apilink->gameshort);

	        $logo = imagecreatefrompng("graficon/gamelogo/" . $this->apilink->gameshort . ".png");  
	         
	        for ($i=0;$i<=6;$i++){
	                imagettftext($img, 5, 90, 30+$i*20, 63, $black, "graficon/fonts/arial.ttf", "_");
	                imagettftext($img, 5, 90, 43+$i*20, 62, $black, "graficon/fonts/arial.ttf", "-");
	        }

	        for ($i=0;$i<=4;$i++){
	                imagettftext($img, 5, 0, 22, 59-$i*11, $black, "graficon/fonts/arial.ttf", "_");
	        }
	        imagecopymerge($bg, $img, 343, 40, 0, 0, 160, 77, 75);

	        $marge_right = 0;
	        $marge_bottom = 10;
	        $sx = imagesx($logo);
	        $sy = imagesy($logo);

	        imagecopy($bg, $logo, 0, 20, 0, 0, imagesx($logo), imagesy($logo));
	         
	         
	        imagepng($bg, null, 9);
	    } else {
	    	header("Content-type: image/png");

	        $bg = imagecreatefrompng('graficon/sabloni/nepostojecibanner.png');

	        imagepng($bg, null, 9);
	        imagedestroy($bg);
	    }

	    return $bg;

	}

	public function GetServerHostedMap() {

		$geomapjson = @file_get_contents('http://freegeoip.net/json/' . $this->ip);

		$lokacija = json_decode($geomapjson);

		$zumiraj = $lokacija->city != '' ? 6 : 6;

		$this->servergeomap = '<img src="http://maps.googleapis.com/maps/api/staticmap?center=' . $lokacija->latitude . ',' . $lokacija->longitude . '&zoom=' . $zumiraj . '&size=300x240&sensor=false&&markers=size:mid|color:red|' . $lokacija->city . ',' . $lokacija->country_name . '">';

		return $this->servergeomap;

	}

	public function GetCurrentMapImage() {

		$mapname 	= $this->apilink->map;
		$gamename	= $this->apilink->gameshort;

		$includemap = '<img src="http://banners.gametracker.rs/map/' . $gamename . '/' . $mapname . '.jpg" alt="Niste dodali sliku ako nema mape u maps folderu">';

		return $includemap;

	}

}

?>
