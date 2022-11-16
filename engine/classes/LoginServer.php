<?php
//=======================================================================\\
//  ## ####### #######                                                   \\
//  ## ##      ##   ##                                                   \\
//  ## ##      ## ####  |\  | |¯¯¯ ¯¯|¯¯ \      / |¯¯¯| |¯¯¯| | / |¯¯¯|  \\
//  ## ##      ##       | \ | |--    |    \    /  | | | | |_| |<   ¯\_   \\
//  ## ####### ##       |  \| |___   |     \/\/   |___| | |\  | \ |___|  \\
// --------------------------------------------------------------------- \\
//    Brazillian Developer / WebSite: http://www.icpnetworks.com.br      \\
//                 Email & Skype: ivan1507@gmail.com.br                  \\
//=======================================================================\\
namespace ICPNetworks {
	
	class LoginServer {
		
		public function __construct($db_type,$conn,$config){
			$this->conn = $conn;
			$this->db_type = $db_type;
			foreach($config AS $key => $val){
				$this->{$key} = $val;
			}
		}
		
		public function resposta($msg,$title=null,$type=null,$redirect=null){
			echo "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js\" type=\"text/javascript\"></script><script src=\"//cdn.jsdelivr.net/npm/sweetalert2@10\"></script><script type=\"text/javascript\">$(document).ready(function(){Swal.fire({ title: '".$title."', html: '".$msg."', icon: '".$type."'".(!empty($redirect) ? ", confirmButtonText: 'Ok', preConfirm: () => { return [ window.location.href = '".$redirect."' ] } })" : "})")."})</script>";
		}
		
		private function ICP_encrypt($pass){
			switch ($this->encrypt){
				case 0:
					$password = md5($pass);
					break;
				case 1:
					$password = base64_encode(hash("sha1", $pass, true));
					break;
				case 2:
					$password = base64_encode(hash('whirlpool', $pass, true));
					break;
				case 3:
					$password = base64_encode(hash("sha3-256", $pass, true));
					break;
				case 4:
					$key = array();
					$dst = array();
					$i = 0;
					$nBytes = strlen($pass);
					while ($i < $nBytes){
						$i++;
						$key[$i] = ord(substr($pass, $i - 1, 1));
						$dst[$i] = $key[$i];
					}
					$rslt = $key[1] + $key[2]*256 + $key[3]*65536 + $key[4]*16777216;
					$one = $rslt * 213119 + 2529077;
					$one = $one - intval($one/ 4294967296) * 4294967296;
					$rslt = $key[5] + $key[6]*256 + $key[7]*65536 + $key[8]*16777216;
					$two = $rslt * 213247 + 2529089;
					$two = $two - intval($two/ 4294967296) * 4294967296;
					@$rslt = $key[9] + $key[10]*256 + $key[11]*65536 + $key[12]*16777216;
					$three = $rslt * 213203 + 2529589;
					$three = $three - intval($three/ 4294967296) * 4294967296;
					@$rslt = $key[13] + $key[14]*256 + $key[15]*65536 + $key[16]*16777216;
					$four = $rslt * 213821 + 2529997;
					$four = $four - intval($four/ 4294967296) * 4294967296;
					$key[4] = intval($one/16777216);
					$key[3] = intval(($one - $key[4] * 16777216) / 65535);
					$key[2] = intval(($one - $key[4] * 16777216 - $key[3] * 65536) / 256);
					$key[1] = intval(($one - $key[4] * 16777216 - $key[3] * 65536 - $key[2] * 256));
					$key[8] = intval($two/16777216);
					$key[7] = intval(($two - $key[8] * 16777216) / 65535);
					$key[6] = intval(($two - $key[8] * 16777216 - $key[7] * 65536) / 256);
					$key[5] = intval(($two - $key[8] * 16777216 - $key[7] * 65536 - $key[6] * 256));
					$key[12] = intval($three/16777216);
					$key[11] = intval(($three - $key[12] * 16777216) / 65535);
					$key[10] = intval(($three - $key[12] * 16777216 - $key[11] * 65536) / 256);
					$key[9] = intval(($three - $key[12] * 16777216 - $key[11] * 65536 - $key[10] * 256));
					$key[16] = intval($four/16777216);
					$key[15] = intval(($four - $key[16] * 16777216) / 65535);
					$key[14] = intval(($four - $key[16] * 16777216 - $key[15] * 65536) / 256);
					$key[13] = intval(($four - $key[16] * 16777216 - $key[15] * 65536 - $key[14] * 256));
					$dst[1] = $dst[1] ^ $key[1];
					$i=1;
					while ($i<16){
						$i++;
						@$dst[$i] = $dst[$i] ^ $dst[$i-1] ^ $key[$i];
					}
					$i=0;
					while ($i<16){
						$i++;
						if ($dst[$i] == 0) {
							$dst[$i] = 102;
						}
					}
					$encrypt = "0x";
					$i=0;
					while ($i<16){
						$i++;
						if ($dst[$i] < 16) {
							$encrypt = $encrypt . "0" . dechex($dst[$i]);
						} else {
							$encrypt = $encrypt . dechex($dst[$i]);
						}
					}
					$password = $encrypt;
					break;
				case 5:
					$password = str_replace("$2y$", "$2a$", password_hash($pass, PASSWORD_BCRYPT));
					break;
				default:
					$password = base64_encode(hash("sha1", $pass, true));
					break;
			}
			return $password;
		}
		
		private function execute($query,$params=[]){
			if(empty($query)){
				return die("Invalid query.");
			}
			$records = $this->conn->prepare($query,array(\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL));
			if($records->execute(is_array($params) ? count($params) > 0 ? $params : null : null)){
				$query_type = explode(" ",$query);
				if($query_type[0] == "SELECT" ? 1 : 0){
					return $records->fetchAll(\PDO::FETCH_ASSOC);
				}else{
					return 1;
				}
			}else{
				return 0;
			}
		}
		
		public function login($username, $password){
			$errMsg = null;
			$errMsg .= empty($username) ? 'Enter your username.<br>' : null;
			$errMsg .= empty($password) ? 'Enter your password.' : null;
			if(empty($errMsg)){
				if($this->encrypt == 5){
					$results = $this->execute($this->QUERY_LOGIN_1,[$username]);
					if(count($results) == 1){
						if(!password_verify($password,$results[0]["password"])){
							return "pass_login_error";
						}
					}else{
						return "pass_login_error";
					}
				}else{
					$results = $this->execute($this->QUERY_LOGIN_1,[$username,$this->ICP_encrypt($password)]);
					if(count($results) != 1){
						return "pass_login_error";
					}
				}
				if(empty($results[0]["icp_table"])){
					$acc_id = strtotime(date("Y-m-d H:i:s"));
					if($this->CreateAccWithEmail){
						$insert_icp = $this->execute("INSERT INTO icp_accounts (login, email, acc_id, vip_end) VALUES (?,?,?,?)",[$username,"",$acc_id,date("Y-m-d H:i:s")]);
					}else{
						$insert_icp = $this->execute("INSERT INTO icp_accounts (login, email, acc_id, vip_end, status) VALUES (?,?,?,?,'1')",[$username,"",$acc_id,date("Y-m-d H:i:s")]);
					}
					return "email_is_null";
				}else{
					$accStatus = array();
					if(isset($results[0]["uid"]) && !empty($results[0]["uid"])){
						array_push($accStatus, explode(";",$results[0]["uid"]));
					}
					$icpTable = explode(";",$results[0]["icp_table"]);
					if(count($accStatus) > 0){
						if($accStatus[1] == 0 && $icpTable[3] || $icpTable[1] < 0){
							return "acc_banned";
						}
					}else{
						if($results[0]["access_level"] < 0 && $icpTable[3] || $icpTable[1] < 0){
							return "acc_banned";
						}
					}
					if(empty($icpTable[0])){
						return "email_is_null";
					}
					if(!$icpTable[3]){
						return "account_inactivated";
					}
					$this->addLogIp($results[0]["login"]);
					$_SESSION["ICP_UserName"] = $results[0]["login"];
					$_SESSION["ICP_UserAccessLevel"] = empty($icpTable[1]) ? 0 : $icpTable[1];
					$_SESSION["ICP_UserEmail"] = $icpTable[0];
					$_SESSION["ICP_UserVip"] = strtotime($icpTable[2]) < time() ? "Disabled" : "Enabled";
					$_SESSION["ICP_UserVipEnd"] = strtotime($icpTable[2]) < time() ? "Disabled" : $icpTable[2];
					$_SESSION["ICP_UserId"] = count($accStatus) > 0 ? $accStatus[0] : null;
					return "success";
				}
			}else{
				return "ERROR!<br><br>".$errMsg;
			}
		}
		
		private function addLogIp($username){
			if($this->db_type){
				$logIp = $this->execute("SELECT ip FROM icp_accounts_ip WHERE login = ? ORDER BY id DESC LIMIT 1",[$username]);
			}else{
				$logIp = $this->execute("SELECT TOP 1 ip FROM icp_accounts_ip WHERE login = ? ORDER BY id DESC",[$username]);
			}
			$ip = $this->get_client_ip();
			if(count($logIp) == 1){
				if($logIp[0]["ip"] != $ip){
					$this->execute("INSERT INTO icp_accounts_ip (ip, date, login) VALUES (?,?,?)",[$ip,date("Y/m/d"),$username]);
				}
			}else{
				$this->execute("INSERT INTO icp_accounts_ip (ip, date, login) VALUES (?,?,?)",[$ip,date("Y/m/d"),$username]);
			}
			return null;
		}
		
		private function get_client_ip(){
			$v4mapped_prefix_hex = '00000000000000000000ffff';
			$v4mapped_prefix_bin = hex2bin($v4mapped_prefix_hex);
			$ipaddress = '';
			if (isset($_SERVER['HTTP_CLIENT_IP']))
				$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
			else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
				$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else if(isset($_SERVER['HTTP_X_FORWARDED']))
				$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
			else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
				$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
			else if(isset($_SERVER['HTTP_FORWARDED']))
				$ipaddress = $_SERVER['HTTP_FORWARDED'];
			else if(isset($_SERVER['REMOTE_ADDR']))
				$ipaddress = $_SERVER['REMOTE_ADDR'];
			else
				$ipaddress = 'UNKNOWN';
			$addr_bin = inet_pton($ipaddress);
			if( substr($addr_bin, 0, strlen($v4mapped_prefix_bin)) == $v4mapped_prefix_bin) {
			$addr_bin = substr($addr_bin, strlen($v4mapped_prefix_bin));
			}
			return inet_ntop($addr_bin);
		}
		
		public function addEmail($username, $password, $email){
			$errMsg = null;
			$errMsg .= empty($username) ? 'Enter your username.<br>' : null;
			$errMsg .= empty($password) ? 'Enter your password.' : null;
			$errMsg .= empty($email) ? 'Enter a email.<br>' : null;
			$errMsg .= !filter_var($email, FILTER_VALIDATE_EMAIL) ? 'Invalid email!<br>' : null;
			if(empty($errMsg)){
				if($this->encrypt == 5){
					$results = $this->execute($this->QUERY_LOGIN_2,[$username]);
					if(count($results) == 1){
						if(!password_verify($password,$results["password"])){
							return "pass_login_error";
						}
					}else{
						return "pass_login_error";
					}
				}else{
					$results = $this->execute($this->QUERY_LOGIN_2,[$username,$this->ICP_encrypt($password)]);
					if(count($results) != 1){
						return "pass_login_error";
					}
				}
				return $this->addEmail2($username,$password,$email);
			}else{
				return "ERROR!<br><br>".$errMsg;
			}
		}
		
		public function addEmail2($username, $password, $email){
			$results = $this->execute('SELECT acc_id, email FROM icp_accounts WHERE login = ?',[$username]);
			if(count($results) == 1){
				if(empty($results[0]["email"])){
					$acc_id = $results[0]["acc_id"];
					if($this->CreateAccWithEmail){
						$assunto = "Register - ".$this->SITE_NAME;
						$mensagem = "<div style=\"border:1px solid #6495ED;background-color:#819FF7;color:#F8F8FF;text-align:center;font-size:36px;padding:10px 5px;text-shadow: -1px 1px #000;\"><strong>Welcome to ".$this->SITE_NAME."!</strong></div><div style=\"text-align:center;border-left:1px solid #D8D8D8;border-right:1px solid #D8D8D8;padding-top:30px;padding-bottom:30px;background-color:#FAFAFA\">Hello <u>".$username."</u>, this is an automatic email generated by our website to complete your registration.<br><br>To activate your account, click on the button below:<br><br><br><a href='https://".$_SERVER['SERVER_NAME']."/index.php?icp=panel&show=activate&acc=".$acc_id."' style=\"border:1px solid #6495ED;background-color:#819FF7;color:#F8F8FF;padding:10px;text-decoration:none\">Activate NOW</a><br><br><br>Your Login is : <b>".$username."</b><br>Your password is : <b>".$password."</b><br><br>You can change your password in our control panel at any time.<br><br>Have a good game!<br><br>Regards...</div><div style=\"text-align:center;border:1px solid #D8D8D8;background-color:#F2F2F2\"><h3><u>Staff ".$this->SITE_NAME."</u></h3></div>";
						if(!$this->sendEmail($this->SITE_NAME, $email, $assunto, $mensagem)){
							return "error_sent_mail";
						}
					}
					$insert_icp = $this->execute('UPDATE icp_accounts SET email = ? WHERE login = ? AND acc_id = ?',[$email,$username,$acc_id]);
					return "success";
				}else{
					return "email_already_exists";
				}
			}
		}
		
		public function register($username,$email,$password1,$password2,$rules){
			$errMsg = null;
			$errMsg .= empty($username) ? 'Enter a username.<br>' : null;
			$errMsg .= empty($email) ? 'Enter a email.<br>' : null;
			$errMsg .= !filter_var($email, FILTER_VALIDATE_EMAIL) ? 'Invalid email!<br>' : null;
			$errMsg .= empty($password1) ? 'Enter a password.<br>' : null;
			$errMsg .= empty($password2) ? 'Enter password confirmation.<br>' : null;
			$errMsg .= $password1 != $password2 ? 'Passwords do not match.<br>' : null;
			$errMsg .= empty($errMsg) ? null : '<br>Try again';
			if(empty($errMsg)){
				$pass = $this->ICP_encrypt($password1);
				$acc_id = strtotime(date("Y-m-d H:i:s"));
				$assunto = "Register - ".$this->SITE_NAME;
				$mensagem = "<div style=\"border:1px solid #6495ED;background-color:#819FF7;color:#F8F8FF;text-align:center;font-size:36px;padding:10px 5px;text-shadow: -1px 1px #000;\"><strong>Welcome to ".$this->SITE_NAME."!</strong></div><div style=\"text-align:center;border-left:1px solid #D8D8D8;border-right:1px solid #D8D8D8;padding-top:30px;padding-bottom:30px;background-color:#FAFAFA\">Hello <u>".$username."</u>, this is an automatic email generated by our website to complete your registration.<br><br>To activate your account, click on the button below:<br><br><br><a href='https://".$_SERVER['SERVER_NAME']."/index.php?icp=panel&show=activate&acc=".$acc_id."' style=\"border:1px solid #6495ED;background-color:#819FF7;color:#F8F8FF;padding:10px;text-decoration:none\">Activate NOW</a><br><br><br>Your Login is : <b>".$username."</b><br>Your password is : <b>".$password1."</b><br><br>You can change your password in our control panel at any time.<br><br>Have a good game!<br><br>Regards...</div><div style=\"text-align:center;border:1px solid #D8D8D8;background-color:#F2F2F2\"><h3><u>Staff ".$this->SITE_NAME."</u></h3></div>";
				$results = $this->execute($this->QUERY_LOGIN_3,[$username]);
				if(count($results) == 0){
					$answer = $this->ICP_encrypt("icpnetworks");
					$ssn = mt_rand(1000000,9999999).mt_rand(100000,999999);
					if($this->CreateAccWithEmail){
						if(!$this->sendEmail($this->SITE_NAME, $email, $assunto, $mensagem)){
							return $this->resposta("Account has not been created.<br>Please contact an Admin.","Oops...","error");
						}else{
							if($this->db_type){
								$insert_acc = $this->execute($this->QUERY_REGISTER_1,[$username,$pass]);
								$insert_icp = $this->execute('INSERT INTO icp_accounts (login, email, acc_id, vip_end) VALUES (?,?,?,?)',[$username,$email,$acc_id,date("Y-m-d H:i:s")]);
							}else{
								$insert_ssn = $this->execute($this->QUERY_REGISTER_1,[$ssn,$username,$email,"0","telphone","123456","","","1"]);
								$insert_acc = $this->execute($this->QUERY_REGISTER_2,[$username,"0"]);
								$insert_inf = $this->execute($this->QUERY_REGISTER_3,[$username,$ssn,"99"]);
								$insert_auth = $this->execute($this->QUERY_REGISTER_4,[$username,$pass,$answer,$answer]);
								$insert_icp = $this->execute($this->QUERY_REGISTER_5,[$username,$email,$acc_id,date("Y-m-d H:i:s")]);
							}
							return $this->resposta("Account created successfully.<br>Access your email to activate your account.<br>If the email doesn\'t arrive, check your spam box.<br>Welcome to ".$this->SITE_NAME."!","Success!","success","?icp=panel");
						}
					}else{
						if($this->db_type){
							$insert_acc = $this->execute($this->QUERY_REGISTER_2,[$username,$pass]);
							$insert_icp = $this->execute('INSERT INTO icp_accounts (login, email, acc_id, status, vip_end) VALUES (?,?,?,"1",?)',[$username,$email,$acc_id,date("Y-m-d H:i:s")]);
						}else{
							$insert_ssn = $this->execute($this->QUERY_REGISTER_1,[$ssn,$username,$email,"0","telphone","123456","","","1"]);
							$insert_acc = $this->execute($this->QUERY_REGISTER_2,[$username,"1"]);
							$insert_inf = $this->execute($this->QUERY_REGISTER_3,[$username,$ssn,"99"]);
							$insert_auth = $this->execute($this->QUERY_REGISTER_4,[$username,$pass,$answer,$answer]);
							$insert_icp = $this->execute($this->QUERY_REGISTER_5,[$username,$email,$acc_id,date("Y-m-d H:i:s")]);
						}
						return $this->resposta("Account created successfully.<br>Welcome to ".$this->SITE_NAME."!","Success!","success","?icp=panel");
					}
				}else{
					return $this->resposta("The account name ".$username." is already in use.<br>Choose another and try again.","Oooh no!","error");
				}
			}else{
				return $this->resposta($errMsg,"Oops...","error");
			}
		}
		
		public function recovery($username,$email){
			$errMsg = null;
			$errMsg .= empty($username) ? 'Enter a user.<br>' : null;
			$errMsg .= empty($email) ? 'Enter a email.<br>' : null;
			$errMsg .= !filter_var($email, FILTER_VALIDATE_EMAIL) ? 'Invalid email!' : null;
			if(empty($errMsg)){
				$CaracteresAceitos = 'abcdxywzABCDZYWZ0123456789';
				$password = null;
				for($i=0; $i < 8; $i++)
					$password .= $CaracteresAceitos[mt_rand(0, (strlen($CaracteresAceitos)-1))];
				$assunto = "Password recovery - ".$this->SITE_NAME;
				$mensagem = "<div style=\"border:1px solid #6495ED;background-color:#819FF7;color:#F8F8FF;text-align:center;font-size:36px;padding:10px 5px;text-shadow: -1px 1px #000;\"><strong>Reset Password</strong></div><div style=\"text-align:center;border-left:1px solid #D8D8D8;border-right:1px solid #D8D8D8;padding-top:30px;padding-bottom:30px;background-color:#FAFAFA\">Hello <u>".$username."</u>, this is an automatic email generated by our website for password recovery.<br><br>Your Login is : <b>".$username."</b><br>Your password is : <b>".$password."</b><br><br>You can change your password in our control panel at any time.<br><br>Have a good game!<br><br>Regards...</div><div style=\"text-align:center;border:1px solid #D8D8D8;background-color:#F2F2F2\"><h3><u>Staff ".$this->SITE_NAME."</u></h3></div>";
				$results = $this->execute('SELECT accessLevel FROM icp_accounts WHERE login = ? AND email = ?',[$username,$email]);
				if(count($results) == 1){
					if($results[0]["accessLevel"] >= 0){
						if($this->RecoveryAccWithEmail){
							if(!$this->sendEmail($this->SITE_NAME, $email, $assunto, $mensagem)){
								return $this->resposta("Failed to send email.<br>Please contact an Admin.","Oops...","error");
							}
						}
						$alterandosenha = $this->execute($this->QUERY_PASS_CHANGE,[$this->ICP_encrypt($password),$username]);
						return $this->resposta($this->RecoveryAccWithEmail ? "A new password has been sent to your email." : "Hi ".$username.", please write down your new password : ".$password."<br><br>Thanks!<br>Staff ".$this->SITE_NAME,"Success!","success");
					}else{
						return $this->resposta("You\'ve been banned!","Oooh no!","error");
					}
				}else{
					return $this->resposta("Data do not match!<br>Try again.","Oops...","error");
				}
			}else{
				return $this->resposta($errMsg,"Oops...","error");
			}
		}
		
		public function passChange($senha1,$senha2,$senha3,$username){
			$errMsg = null;
			$errMsg .= empty($senha1) ? 'Enter current password.<br>' : null;
			$errMsg .= empty($senha2) ? 'Enter your new password.<br>' : null;
			$errMsg .= empty($senha3) ? 'Repeat your new password.<br>' : null;
			$errMsg .= empty($username) ? 'Invalid login.<br>' : null;
			$errMsg .= $senha2 != $senha3 ? 'New passwords do not match.<br>' : null;
			$errMsg .= empty($errMsg) ? null : '<br>Try again';
			if(empty($errMsg)){
				if($this->encrypt == 5){
					$results = $this->execute($this->QUERY_LOGIN_2,[$username]);
					if(count($results) == 1){
						if(!password_verify($senha1,$results["password"])){
							return $this->resposta("Invalid password.<br>Try again.","Oooh no!","error");
						}
					}else{
						return $this->resposta("Invalid password.<br>Try again.","Oooh no!","error");
					}
				}else{
					$results = $this->execute($this->QUERY_LOGIN_2,[$username,$this->ICP_encrypt($senha1)]);
					if(count($results) != 1){
						return $this->resposta("Invalid password.<br>Try again.","Oooh no!","error");
					}
				}
				$alterandosenha = $this->execute($this->QUERY_PASS_CHANGE,[$this->ICP_encrypt($senha2),$username]);
				return $this->resposta("Your password has been changed!","Success!","success");
			}else{
				return $this->resposta($errMsg,"Oops...","error");
			}
		}
		
		function emailChange($email1,$email2,$email3,$login){
			$errMsg = null;
			$errMsg .= empty($email1) ? 'The current email is invalid.<br>' : null;
			$errMsg .= empty($email2) ? 'The new email is invalid.<br>' : null;
			$errMsg .= empty($email3) ? 'The confirmation of the new email is invalid.<br>' : null;
			$errMsg .= !filter_var($email1, FILTER_VALIDATE_EMAIL) ? 'The current email is invalid.<br>' : null;
			$errMsg .= !filter_var($email2, FILTER_VALIDATE_EMAIL) ? 'The new email is invalid.<br>' : null;
			$errMsg .= !filter_var($email3, FILTER_VALIDATE_EMAIL) ? 'The confirmation of the new email is invalid.<br>' : null;
			$errMsg .= $email2 != $email3 ? 'The confirmation of the new email is incorrect.<br>' : null;
			$errMsg .= empty($login) ? 'Invalid login.<br>' : null;
			$errMsg .= empty($errMsg) ? null : '<br>Try again';
			if(empty($errMsg)){
				$records = $this->execute("SELECT * FROM icp_accounts WHERE login = ? AND email = ?",[$login,$email1]);
				if(count($records) == 1){
					$email_changing = $this->execute("UPDATE icp_accounts SET email = ? WHERE login = ? AND email = ?",[$email2,$login,$email1]);
					$_SESSION["ICP_UserEmail"] = $email2;
					return $this->resposta("Email exchanged.","Success!","success","?icp=panel&show=accounts");
				}else{
					return $this->resposta("Incorrect current email.<br>Try again.","Oooh no!","error");
				}
			}else{
				return $this->resposta($errMsg,"Oops...","error");
			}
		}
		
		private function sendEmail($nome_remetente, $email_remetente, $assunto, $mensagem, $contato = false){
			require_once('engine/phpmailer/src/PHPMailer.php');
			require_once('engine/phpmailer/src/SMTP.php');
			$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
			try {
				$mail->IsSMTP(); 
				$mail->Host = $this->SMTP_HOST;
				$mail->SMTPAuth = true;
				$mail->Username = $this->SMTP_EMAIL;
				$mail->Password = $this->SMTP_PASS;
				$mail->Port = $this->SMTP_PORT;
				if($contato){
					$mail->setFrom($email_remetente, $nome_remetente);
					$mail->addAddress($this->SMTP_EMAIL);
				}else{
					$mail->setFrom($this->SMTP_EMAIL, $this->SITE_NAME);
					$mail->addAddress($email_remetente);
				}
				$mail->WordWrap = 50;
				$mail->IsHTML(true);
				$mail->CharSet = "utf-8";
				$mail->Subject = $assunto;
				$mail->Body = $mensagem;
				return $mail->Send();
			} catch (\Exception $e) {
				return false;
			}
		}
		
		public function setPrivilege($privId,$login,$senderPrivId){
			if($senderPrivId == 10){
				$getUsername = $this->execute($this->QUERY_LOGIN_4,[$login]);
				if(count($getUsername) == 1){
					$getUsername2 = $this->execute("SELECT * FROM icp_accounts WHERE login = ?",[$login]);
					if(count($getUsername2) == 1){
						if($privId <= 0){
							$BanUnban = $privId == 0 ? 1 : $privId;
							$BanUnban = $privId < 0 ? 0 : $BanUnban;
							$setBan = $this->execute($this->QUERY_BAN_ACC,[$this->db_type ? $privId : $BanUnban,$login]);
						}
						$setPriv = $this->execute("UPDATE icp_accounts SET accessLevel = ? WHERE login = ?",[$privId,$login]);
						return $this->resposta("privilege successfully given!","Success!","success");
					}else{
						return $this->resposta("The account does not exist.","Oops...","error");
					}
				}else{
					return $this->resposta("The account does not exist.","Oops...","error");
				}
			}else{
				return $this->resposta("You are not allowed to do this.","Oops...","error");
			}
		}
		
	}
	
}