<?php
	if(isset($_POST["tier"])) $tier = $_POST["tier"]; else $tier = "";
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Sprite Converter</title>
	<link rel="stylesheet" href="/css/bootstrap.min.css">
	<script src="/js/jquery.min.js"></script>
	<script src="/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
        <a href="/" style="position:absolute;top:0;right:0;z-index:20;padding:5px;"><img border="0" alt="FullLifeGames" src="../../img/profile.jpg" width="100" height="100" /></a>
	<h1>Sprite Converter</h1>
			
			<hr />
			
			<form action="" method="post">
				<div class="form-group">
					<label for="team">Import:</label>
					<textarea class="form-control" name="team"><?php echo (isset($_POST["team"])?$_POST["team"]:'') ?></textarea>
				</div>
				<div class="form-group">
					<label for="tier">Sprites:</label>
					<select class="form-control" name="tier"> 
						<option value="xyani" <?php echo (($tier == "xyani")?"selected":"") ?>>xyani</option>
						<option value="bw" <?php echo (($tier == "bw")?"selected":"") ?>>bw</option>
						<option value="bwani" <?php echo (($tier == "bwani")?"selected":"") ?>>bwani</option>
						<option value="sprites" <?php echo (($tier == "sprites")?"selected":"") ?>>sprites</option>
					</select>
				</div>
				<div class="form-group">
					<input class="btn btn-primary form-control" type="submit" value="Convert" />
				</div>
			</form>
			<p>
<?php	
	if(!isset($_POST["team"]) && !isset($_POST["tier"])){
	} else {
		if(strpos($_POST["tier"], "sprites")!==false){
			$json = get_magic_quotes_gpc() ? stripslashes(file_get_contents("dexdata.json")) : file_get_contents("dexdata.json");
			$json = json_decode($json,true);
			$nameToDex = array();
			foreach($json as $key => $value){
				if(!isset($nameToDex[trim($value["name"])])) $nameToDex[trim($value["name"])] = $value["dex"];
				if(!isset($nameToDex[trim($value["name"])."-Alola"])) $nameToDex[trim($value["name"])."-Alola"] = $value["dex"]."-a";
				if(!isset($nameToDex[trim($value["name"])."-Mega"])) $nameToDex[trim($value["name"])."-Mega"] = $value["dex"]."-m";
				if(!isset($nameToDex[trim($value["name"])."-Mega-X"])) $nameToDex[trim($value["name"])."-Mega-X"] = $value["dex"]."-mx";
				if(!isset($nameToDex[trim($value["name"])."-Mega-Y"])) $nameToDex[trim($value["name"])."-Mega-Y"] = $value["dex"]."-my";
				if(!isset($nameToDex[trim($value["name"])."-Primal"])) $nameToDex[trim($value["name"])."-Primal"] = $value["dex"]."-p";
				if(!isset($nameToDex[trim($value["name"])."-Therian"])) $nameToDex[trim($value["name"])."-Therian"] = $value["dex"]."-s";
			}
			$nameToDex["Hoopa-Unbound"] = "720-u";
		}
		$list = array();
		echo "Links: <br>";
		$import = preg_split('/\r\n|[\r\n]/',  $_POST["team"]);
		foreach($import as $key => $value){
			if((strpos($value, "@")!==false || (isset($import[$key+1]) && strpos($import[$key+1], "Ability:") !== false) || (isset($import[$key-1]) && trim($import[$key-1]) === "") || (!isset($import[$key-1]))) && trim($value) !== ""){
				$name = "";
				if(strpos($value, "(M)")!==false || strpos($value, "(F)")!==false){
					$value = str_replace("(M)", "" , $value);
					$value = str_replace("(F)", "" , $value);
				}
				if(strpos($value, "(")!==false){
					$name = substr($value, strrpos($value, "(") + 1, strrpos($value, ")") - (strrpos($value, "(") + 1));
				} else {
					if(strpos($value, "@")!==false){
						$name = substr($value, 0, strpos($value, "@"));
					} else {
						$name = $value;
					}
				}
				if(strpos($_POST["tier"], "sprites")===false){
					$name = str_replace(' ', '', strtolower(trim($name)));			
					$name = str_replace('%', '', $name);
					$name = str_replace("'", '', $name);
					if(strpos($name, "kommo-")!==false || strpos($name, "hakamo-")!==false || strpos($name, "jangmo-")!==false){
						$name = str_replace('-', '', $name);
					}
					$ext = ".png";
					if(strpos($_POST["tier"], "ani")!==false){
						$ext = ".gif";
					}
				}
				if(strpos($_POST["tier"], "sprites")!==false){
					$name = trim($name);
					if((strpos($name, "Arceus")!==false || strpos($name, "Basculin")!==false || strpos($name, "Gourgeist")!==false || strpos($name, "Pumpkaboo")!==false || strpos($name, "Pikachu")!==false) && strpos($name, "-")!==false && strpos($name, "-Alola") === false){
						$name = substr($name, 0, strpos($name, "-"));
					}					
					if(strpos($name, "-")!==false && strpos($name, "Rotom")!==false || strpos($name, "Deoxys")!==false || strpos($name, "Giratina")!==false){
						if(strpos($name, "-Fan")!==false){
							$ext = "s";			
						} else {
							$ext = strtolower(substr($name, strpos($name, "-") + 1, 1));
						}
						$name = substr($name, 0, strpos($name, "-"));
						echo "https://www.serebii.net/pokedex-swsh/icon/" . $nameToDex[$name] . $ext . ".png<br>";
						$list[] = "https://www.serebii.net/pokedex-swsh/icon/" . $nameToDex[$name] . $ext . ".png";
					} else if(strpos($name, "-")!==false && strpos($name, "Necrozma")!==false){
						$ext = strtolower(substr($name, strpos($name, "-") + 1, 1));
						$ext .= strtolower(substr($name, strrpos($name, "-") + 1, 1));
                                                $name = substr($name, 0, strpos($name, "-"));
                                                echo "https://www.serebii.net/pokedex-swsh/icon/" . $nameToDex[$name] . "-" . $ext . ".png<br>";
                                                $list[] = "https://www.serebii.net/pokedex-swsh/icon/" . $nameToDex[$name] . "-" . $ext . ".png";
					} else if(strpos($name, "Zygarde-10%")!==false){
						$ext = strtolower(substr($name, strpos($name, "-"), 3));
						$name = substr($name, 0, strpos($name, "-"));
						echo "https://www.serebii.net/pokedex-swsh/icon/" . $nameToDex[$name] . $ext . ".png<br>";
						$list[] = "https://www.serebii.net/pokedex-swsh/icon/" . $nameToDex[$name] . $ext . ".png";
					} else if((!isset($nameToDex[$name]) || strpos($name, "Oricorio")!==false) && strpos($name, "-")!==false){
						$ext = strtolower(substr($name, strpos($name, "-"), 2));
						$name = substr($name, 0, strpos($name, "-"));
						echo "https://www.serebii.net/pokedex-swsh/icon/" . $nameToDex[$name] . $ext . ".png<br>";
						$list[] = "https://www.serebii.net/pokedex-swsh/icon/" . $nameToDex[$name] . $ext . ".png";
					} else {
						echo "https://www.serebii.net/pokedex-swsh/icon/" . $nameToDex[$name] . ".png<br>";
						$list[] = "https://www.serebii.net/pokedex-swsh/icon/" . $nameToDex[$name] . ".png";
					}
				} else {
					echo "https://play.pokemonshowdown.com/sprites/". htmlentities($_POST["tier"]) . "/".htmlentities($name).$ext."<br>";
					$list[] = "https://play.pokemonshowdown.com/sprites/". htmlentities($_POST["tier"]) . "/".htmlentities($name).$ext;
				}
			}
		}
		echo "<br>BB Code: <br>";
		foreach($list as $key => $value){
			echo "[IMG]" . $value . "[/IMG]";
		}
	}
?>
		</p>
	</div>
	</body>
</html>
