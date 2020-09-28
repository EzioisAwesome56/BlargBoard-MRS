<?php
//  Blargboard MRS - User account registration page
//  Access: any, but meant for guests.

//require('lib/common.php');
require_once('lib/recaptchalib.php');

//Recaptcha secret key
$secret = "key goes here";

//Empty response
$response = null;

//Check the secret key
$reCaptcha = new ReCaptcha($secret);

$daword = Settings::get('registrationWord');
$title = __("Register");
MakeCrumbs(array('' => __('Register')));
print "<script src='https://www.google.com/recaptcha/api.js'></script>";

$sexes = array(__("Male"), __("Female"), __("N/A"));

//If submitted check the reponse
if($_POST['g-recaptcha-response'])
{
	$response = $reCaptcha->verifyResponse(
		$_SERVER['REMOTE_ADDR'],
		$_POST['g-recaptcha-response']
	);
}

if($_POST['register'])
{
	if (IsProxy())
	{
		$adminemail = Settings::get('ownerEmail');
		if ($adminemail) $halp = '<br><br>If you aren\'t using a proxy, contact the board owner at: '.$adminemail;
		else $halp = '';
		
		$err = __('Registrations from proxies are not allowed. Turn off your proxy and try again.'.$halp);
	}
	else
	{
		$name = $_POST['name'];
		$cname = trim(str_replace(" ","", strtolower($name)));

		$rUsers = Query("select name, displayname from {users}");
		while($user = Fetch($rUsers))
		{
			$uname = trim(str_replace(" ", "", strtolower($user['name'])));
			if($uname == $cname)
				break;
			$uname = trim(str_replace(" ", "", strtolower($user['displayname'])));
			if($uname == $cname)
				break;
		}

		$ipKnown = FetchResult("select COUNT(*) from {users} where lastip={0}", $_SERVER['REMOTE_ADDR']);

		if (stripos($_POST['email'], '@dispostable.com') !== FALSE)
			$err = __('Registration failed. Try again later.');
		/*else if ($_SERVER['REMOTE_ADDR'] !== "lulxd") {
			if ($_POST['word'] !== $daword){
		
			Report("A visitor from [b]".$_SERVER['REMOTE_ADDR']."[/] tried to register as [b]".$cname."[/] using the word ".$_POST['word']."[/].", 1);
			$err = __("You dont know the word? Send an email to email to get the word");
		}*/
		else if (!$cname or !$name)
			$err = __('Enter a username and try again.');
		elseif($ipKnown >= 3 and $_SERVER['REMOTE_ADDR'] != "208.108.121.31")
			$err = __('Another user is already using this IP address');
		else if ($response == null)
			$err = __('You forgot to do the Captcha.');
		//else if ($response->success != true)
			//$err = __('You failed the Captcha. Please try again.');
		else if (!$_POST['email'])
			$err = __('Enter a email and try again.');
		elseif($uname == $cname)
			$err = __("This user name is already taken. Please choose another.");
		else if(!$_POST['readFaq'])
			$err = format(__("You really should {0}read the FAQ{1}&hellip;"), "<a href=\"".actionLink("faq")."\">", "</a>");
		else if ($_POST['likesCake'])
			$err = __("Robots not allowed.");
		else if(strlen($_POST['pass']) < 4)
			$err = __("Your password must be at least four characters long.");
		else if ($_POST['pass'] !== $_POST['pass2'])
			$err = __("The passwords you entered don't match.");
		else if (preg_match("@^(MKDS|MK7|SM64DS|SMG|NSMB)\d*?@si", $uname))
			$err = __("Come on, you could be a little more original with your username!");
	}}

	if($err)
	{
		Alert($err, __('Error'));
	}
	elseif ($response->success)
	{
		$newsalt = Shake();
		$sha = doHash($_POST['pass'].SALT.$newsalt);
		
		old user id generation
		$uid = FetchResult("SELECT id+1 FROM {users} WHERE (SELECT COUNT(*) FROM {users} u2 WHERE u2.id={users}.id+1)=0 ORDER BY id ASC LIMIT 1");
		
		// New user id generation, based on the 32bit interger limit rng
		/*$uid = mt_rand(1, 2147483647);
		$heymanedgy = FetchResult("SELECT * from {users} where `id` = {0}", $uid);
		$checkifnuked = FetchResult("SELECT * from {nuked} where `id` = {0}", $uid);
		while ($checkifnuked == $uid or $heymanedgy == $uid) {
			while ($heymanedgy == $uid) {
				$uid = mt_rand(1, 2147483647);
				$heymanedgy = FetchResult("SELECT * from {users} where `id` = {0}", $uid);
			}
			$uid = mt_rand(1, 2147483647);
			$heymanedgy = FetchResult("SELECT * from {users} where `id` = {0}", $uid);
			$checkifnuked = FetchResult("SELECT * from {nuked} where `id` = {0}", $uid);
		}*/

		$rUsers = Query("insert into {users} (id, name, password, pss, primarygroup, regdate, lastactivity, lastip, email, sex, theme, rankset) values ({0}, {1}, {2}, {3}, {4}, {5}, {5}, {6}, {7}, {8}, {9}, 'mario')", 
			$uid, $_POST['name'], $sha, $newsalt, Settings::get('defaultGroup'), time(), $_SERVER['REMOTE_ADDR'], $_POST['email'], (int)$_POST['sex'], Settings::get("defaultTheme"));

		//if($uid == 1)
		//	Query("update {users} set primarygroup = {0} where id = 1", Settings::get('rootGroup'));

		Report("New user: [b]".$_POST['name']."[/] (#".$uid.") -> [g]#HERE#?uid=".$uid);

		$user = Fetch(Query("select * from {users} where id={0}", $uid));
		$user['rawpass'] = $_POST['pass'];

		$bucket = "newuser"; include("lib/pluginloader.php");
		
		
		$rLogUser = Query("select id, pss, password from {users} where 1");
		$matches = array();

		while($testuser = Fetch($rLogUser))
		{
			if($testuser['id'] == $user['id'])
				continue;

			$sha = doHash($_POST['pass'].SALT.$testuser['pss']);
			if($testuser['password'] === $sha)
				$matches[] = $testuser['id'];
		}
		
		if (count($matches) > 0)
			Query("INSERT INTO {passmatches} (date,ip,user,matches) VALUES (UNIX_TIMESTAMP(),{0},{1},{2})", $_SERVER['REMOTE_ADDR'], $user['id'], implode(',',$matches));
		
		// mark threads older than 15min as read
		Query("INSERT INTO {threadsread} (id,thread,date) SELECT {0}, id, {1} FROM {threads} WHERE lastpostdate<={2} ON DUPLICATE KEY UPDATE date={1}", $uid, time(), time()-900);


		if($_POST['autologin'])
		{
			$sessionID = Shake();
			setcookie("logsession", $sessionID, 0, BOARD_ROOT, "", false, true);
			Query("INSERT INTO {sessions} (id, user, autoexpire) VALUES ({0}, {1}, {2})", doHash($sessionID.SALT), $user['id'], 0);
			die(header("Location: ".actionLink('profile', $user['id'], '', $user['name'])));
		}
		else
			die(header("Location: ".actionLink("login")));
	}
}
else
{
	$_POST['name'] = '';
	$_POST['email'] = '';
	$_POST['sex'] = 2;
	$_POST['autologin'] = 0;
	$err = __("You failed the captcha");
	
}


$kuriseed = crc32(KURIKEY.microtime());
srand($kuriseed);
$check = time();
$kurichallenge = "{$kuriseed}|{$check}|".rand(3,12);

$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
$kurichallenge = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, md5(KURIKEY.$check, true), $kurichallenge, MCRYPT_MODE_ECB, $iv);
$kurichallenge = base64_encode($kurichallenge);
$kuridata = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, md5(KURIKEY, true), "{$kuriseed}|{$check}|{$kurichallenge}", MCRYPT_MODE_ECB, $iv);
$kuridata = base64_encode($kuridata);

$fields = array(
	'username' => "<input type=\"text\" name=\"name\" maxlength=20 size=24 value=\"".htmlspecialchars($_POST['name'])."\" class=\"required\">",
	'password' => "<input type=\"password\" name=\"pass\" size=24 class=\"required\">",
	'password2' => "<input type=\"password\" name=\"pass2\" size=24 class=\"required\">",
	'email' => "<input type=\"email\" name=\"email\" value=\"".htmlspecialchars($_POST['email'])."\" maxlength=\"60\" size=24>",
	'daword' => "<input type=\"text\" name=\"word\" size=24 value=\"".htmlspecialchars($_POST['word'])."\" class=\"required\">",
	'sex' => MakeOptions("sex",$_POST['sex'],$sexes),
	'readfaq' => "<label><input type=\"checkbox\" name=\"readFaq\">".format(__("I have read the {0}FAQ{1}"), "<a href=\"".actionLink("faq")."\">", "</a>")."</label>",
	'kurichallenge' => "<img src=\"".resourceLink("kurichallenge.php?data=".urlencode($kuridata))."\" alt=\"[reload the page if the image fails to load]\"><br>
		<input type=\"text\" name=\"kurichallenge\" size=\"10\" maxlength=\"6\" class=\"required\">
		<input type=\"hidden\" name=\"kuridata\" value=\"".htmlspecialchars($kuridata)."\">",
	'autologin' => "<label><input type=\"checkbox\" checked=\"checked\" name=\"autologin\"".($_POST['autologin']?' checked="checked"':'').">".__("Log in afterwards")."</label>",
	
	'btnRegister' => "<input type=\"submit\" name=\"register\" value=\"".__("Register")."\">",
);

echo "<form action=\"".htmlentities(actionLink("register"))."\" method=\"post\">";

$tpl->assign('isSchoolIP', $_SERVER['REMOTE_ADDR'] !== "208.108.130.140");
RenderTemplate('form_register', array('fields' => $fields));

echo "<span style=\"display : none;\"><input type=\"checkbox\" name=\"likesCake\"> I am a robot</span></form>";


function MakeOptions($fieldName, $checkedIndex, $choicesList)
{
	$checks[$checkedIndex] = " checked=\"checked\"";
	foreach($choicesList as $key=>$val)
		$result .= format("
					<label>
						<input type=\"radio\" name=\"{1}\" value=\"{0}\"{2}>
						{3}
					</label>", $key, $fieldName, $checks[$key], $val);
	return $result;
}

function IsProxy()
{
	if ($_SERVER['HTTP_X_FORWARDED_FOR'] && $_SERVER['HTTP_X_FORWARDED_FOR'] != $_SERVER['REMOTE_ADDR'])
		return true;
		
	$result = QueryURL('http://www.stopforumspam.com/api?ip='.urlencode($_SERVER['REMOTE_ADDR']));
	if (!$result)
		return false;

	if (stripos($result, '<appears>yes</appears>') !== FALSE)
		return true;
		
	return false;
}

?>
