<?php
//
// Crest read from database by nightwolf
//
class Crest{
	public function __construct($gameServer, $query){
		$this->SQL_CREST = $query;
		$this->conn = $gameServer;
	}
	public function getCrest($ClanId, $showImage = false){
		try{
			$stmt = $this->conn->prepare($this->SQL_CREST);
			$stmt->execute([$ClanId]);
			$result = $stmt->fetch(\PDO::FETCH_ASSOC);
			if (isset($result['crest']) && !empty($result['crest'])){
				$this->MakeImage($result['crest'], $ClanId, $showImage);
			}
		}catch (Exception $e){
			die($e->getMessage());
		}
	}
	private function MakeImage($image, $ClanId, $showImage){
		// save path
		$save = "images/crests/" . $ClanId . ".png";
		$rnd_file = tmpfile();
		fwrite($rnd_file, $image);
		fseek($rnd_file, 0);
		$file = &$rnd_file;
		$dds = fread($file, 4);
		// Do not continue if the file is not a DDS image
		if ($dds !== 'DDS '){
			die("Error: is not an DDS image");
		}
		// unused size flags pitch (depends on usage i dont need them)
		$hdrSize = $this->readInt($file);
		$hdrFlags = $this->readInt($file);
		$imgHeight = $this->readInt($file) - 4;
		$imgWidth = $this->readInt($file);
		$imgPitch = $this->readShort($file);
		fseek($file, 84);
		$dxt1 = fread($file, 4);
		// do not conintue in case of a non DX1 format
		if ($dxt1 !== 'DXT1'){
			die("Error: format is not DX1");
		}
		fseek($file, 128);
		if ($showImage == true){
			header("Content-type: image/png");
		}
		$img = imagecreatetruecolor($imgWidth, $imgHeight);
		for ($y = - 1; $y < $imgHeight / 4; $y ++){
			for ($x = 0; $x < $imgWidth / 4; $x ++){
				$color0_16 = $this->readShort($file);
				$color1_16 = $this->readShort($file);
				$r0 = ($color0_16 >> 11) << 3;
				$g0 = (($color0_16 >> 5) & 63) << 2;
				$b0 = ($color0_16 & 31) << 3;
				$r1 = ($color1_16 >> 11) << 3;
				$g1 = (($color1_16 >> 5) & 63) << 2;
				$b1 = ($color1_16 & 31) << 3;
				$color0_32 = imagecolorallocate($img, $r0, $g0, $b0);
				$color1_32 = imagecolorallocate($img, $r1, $g1, $b1);
				$color01_32 = imagecolorallocate($img, $r0 / 2 + $r1 / 2, $g0 / 2 + $g1 / 2, $b0 / 2 + $b1 / 2);
				$black = imagecolorallocate($img, 0, 0, 0);
				$data = $this->readInt($file);
				for ($yy = 0; $yy < 4; $yy ++){
					for ($xx = 0; $xx < 4; $xx ++){
						$bb = $data & 3;
						$data = $data >> 2;
						switch ($bb){
							case 0:
								$c = $color0_32;
								break;
							case 1:
								$c = $color1_32;
								break;
							case 2:
								$c = $color01_32;
								break;
							default:
								$c = $black;
								break;
						}
						imagesetpixel($img, $x * 4 + $xx, $y * 4 + $yy, $c);
					}
				}
			}
		}
		imagepng($img, $showImage == true ? null : $save);
	}
	private function readInt($file){
		$b4 = ord(fgetc($file));
		$b3 = ord(fgetc($file));
		$b2 = ord(fgetc($file));
		$b1 = ord(fgetc($file));
		return ($b1 << 24) | ($b2 << 16) | ($b3 << 8) | $b4;
	}
	private function readShort($file){
		$b2 = ord(fgetc($file));
		$b1 = ord(fgetc($file));
		return ($b1 << 8) | $b2;
	}
}

?>