<?php	
	if(!isset($_POST["team"]) && !isset($_POST["tier"])){
		?>
		<form action="" method="post">
			<textarea name="team"></textarea>
			<select name="tier"> 
				<option value="xyani">xyani</option>
				<option value="bw">bw</option>
				<option value="bwani">bwani</option>
				<option value="sprites">sprites</option>
			</select>
			<input type="submit" value="convert" />
		</form>
		<?php
	} else {
		?>
		<form action="" method="post">
			<textarea name="team"><?php echo $_POST["team"] ?></textarea>
			<select name="tier"> 
				<option value="xyani">xyani</option>
				<option value="bw">bw</option>
				<option value="bwani">bwani</option>
				<option value="sprites">sprites</option>
			</select>
			<input type="submit" value="convert" />
		</form>
		<?php
		if(strpos($_POST["tier"], "sprites")!==false){
			$json = get_magic_quotes_gpc() ? stripslashes(file_get_contents("dexdata.json")) : file_get_contents("dexdata.json");
			$json = json_decode($json,true);
			$nameToDex = array();
			foreach($json as $key => $value){
				$nameToDex[trim($value["name"])] = $value["dex"];
				$nameToDex[trim($value["name"])."-Mega"] = $value["dex"]."-m";
				$nameToDex[trim($value["name"])."-Mega-X"] = $value["dex"]."-mx";
				$nameToDex[trim($value["name"])."-Mega-Y"] = $value["dex"]."-my";
			}
		}
		$list = array();
		echo "Links: <br>";
		foreach(preg_split('/\r\n|[\r\n]/',  $_POST["team"]) as $key => $value){
			if(strpos($value, "@")!==false){
				$name = "";
				if(strpos($value, "(M)")!==false || strpos($value, "(F)")!==false){
					$value = str_replace("(M)", "" , $value);
					$value = str_replace("(F)", "" , $value);
				}
				if(strpos($value, "(")!==false){
					$name = substr($value, strpos($value, "(") + 1, strpos($value, ")") - (strpos($value, "(") + 1));
				} else {
					$name = substr($value, 0, strpos($value, "@"));
				}
				if(strpos($_POST["tier"], "sprites")===false){
					$name = strtolower(trim($name));				
					$ext = ".png";
					if(strpos($_POST["tier"], "ani")!==false){
						$ext = ".gif";
					}
				}
				if(strpos($_POST["tier"], "sprites")!==false){
					$name = trim($name);
					echo "http://www.serebii.net/pokedex-xy/icon/" . $nameToDex[$name] . ".png<br>";
					$list[] = "http://www.serebii.net/pokedex-xy/icon/" . $nameToDex[$name] . ".png";
				} else {
					echo "http://play.pokemonshowdown.com/sprites/". htmlentities($_POST["tier"]) . "/".htmlentities($name).$ext."<br>";
					$list[] = "http://play.pokemonshowdown.com/sprites/". htmlentities($_POST["tier"]) . "/".htmlentities($name).$ext;
				}
			}
		}
		echo "BB Code: <br>";
		foreach($list as $key => $value){
			echo "[IMG]" . $value . "[/IMG]";
		}
	}
?>