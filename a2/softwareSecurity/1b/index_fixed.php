<?
        session_start();

	###################################################################################################
	# https://www.owasp.org/index.php/Top_10_2013-Top_10
	###################################################################################################

	###################################################################################################
	# FIX OWASP A8: CSRF (Cross Site Request Forgery)
	# First verify that this is vulnerable. That is, if attacker gets target to follow a malicious
	# URL, the attacker can get the user to perform an operation they had not intended.
	# For example, craft a URL that gets the target to delete one of their entries.
	# Fix this by creating a random page token. The page token is placed in the
	# session. Now every reply to a page, includes the page token, in the URL, as a hidden variable etc.
	# Before processing a request, first verify the page token.
  # password_hash(password, PASSWORD_BCRYPT, salt)
	###################################################################################################

  #May need to change from md5 to something more secure
  # Need to verify page token
  if (!isset($_SESSION['token'])) {
    $token= md5(uniqid());
    $_SESSION['token'] = $token;
    $_SESSION['token_time'] = time();
  }
  else
  {
      $token = $_SESSION['token'];
  }

	if(!isset($_SESSION['isLoggedIn']))$_SESSION['isLoggedIn']=False;
	$operation=$_REQUEST['operation'];
	$g_debug="";
	$g_errors="";

	// overridden database functions
        function pg_connect_db(){

		###################################################################################################
		# FIX OWASP A6: SENSITIVE DATA EXPOSURE
		# Take a look at the db, whats wrong with the passwords?
		# Fix by hashing passwords, as well, you need to fix authentication to check hashes
		# Additionally, the application is not using https to communicate, fix this
    # Add a check for port 443 need to generate an SSL via htaccess
		###################################################################################################

                $dbconn = pg_connect("dbname=fourfours user=ff host=localhost password=adg135sfh246");
                pg_set_client_encoding($dbconn, 'UTF8');
                return $dbconn;
        }
	if($operation == "login"){
    #Change to something stronger than md5
		$user=$_REQUEST['user'];
		$password=md5($_REQUEST['password']);
		$dbconn = pg_connect_db();
		# FIX OWASP 2013 A1: SQL Injection, use prepared statements -> Needs testing


		$query= "SELECT id, username, firstName, lastName, passwd FROM account WHERE username=$1 AND passwd=$2";
		$result = pg_prepare($dbconn, "", $query);
		$result = pg_execute($dbconn, "", array($user, $password));
		if($row = pg_fetch_row($result)) {

			###################################################################################################
			# FIX OWASP 2013 A2: SESSION FIXATION
			# Check that this application is vulnerable by bringing up firefox developer tools,
			# visiting the website, then logging in, notice that the same cookie is used after
			# change of privilege.
			# Fix this by...
			# Destroy current session and get a new session id when change in privilege.
			# Check to make sure that the cookie changes when logged in
			# Note: Some browsers now protect against reflection attacks, but not all.
			# See: http://php.net/manual/en/function.session-regenerate-id.php
			###################################################################################################
      session_regenerate_id ();
			$_SESSION['accountId']=$row[0];
			$_SESSION['user']=$row[1];
			$_SESSION['firstName']=$row[2];
			$_SESSION['lastName']=$row[3];
			$_SESSION['isLoggedIn']=True;
		} else {
			$g_debug = "$user not logged in";
			$_SESSION['isLoggedIn']=False;
		}
	} elseif($operation == "deleteExpression"){
		$expressionId = $_REQUEST['expressionId'];
		$accountId=$_SESSION['accountId'];
		$dbconn = pg_connect_db();

		###################################################################################################
		# FIX OWASP 2013 A4: INSECURE DIRECT OBJECT REFERENCES
		# Prove that it is vulnerable by logging in as one user and deleting another users entry
		# Fix this by...
		# Either fix the insecure part, that is, verify that the user can perform the operation
		# of the direct object reference part, that is, fix the id's so they don't directly
		# reference the expressionId, or both (even better).
		# Another problem: why get account id from the request? In this case, this is part of the
		# insecure direct object reference, that is, referencing the account id.
		# Note: Not: Simply not giving the user interface the option to delete is not sufficient.
		###################################################################################################

		$result = pg_prepare($dbconn, "", "DELETE FROM solution WHERE id=$1 AND accountId=$2");
		$result = pg_execute($dbconn, "", array($expressionId, accountId));
	} elseif($operation == "addExpression"){

		###################################################################################################
		# FIX: XSS: user input/output is not vetted
		# First check that the application is vulnerable by placing html in the
		# database and then viewing the HTML as it exits the db
		# Fix this by ...
		# Either whitelisting the input, or escape the input
		# Do the same for all untrusted input and output!
		# http://stackoverflow.com/questions/46483/htmlentities-vs-htmlspecialchars
		###################################################################################################

		$expression = $_REQUEST['expression'];
		$value=$_REQUEST['value'];
		$accountId=$_REQUEST['accountId'];

		$dbconn = pg_connect_db();
		$result = pg_prepare($dbconn, "", "SELECT * FROM solution WHERE expression='$expression'");
		$result = pg_execute($dbconn, "", array());
		if(!($row = pg_fetch_row($result))) {
			$result = pg_prepare($dbconn, "", "insert into solution (value, expression, accountId) values ($value, '$expression', $accountId)");
			$result = pg_execute($dbconn, "", array());
		} else {
			$g_errors="$expression is already in our database";
		}
	} elseif($operation == "logout"){
		unset($_SESSION);
		$_SESSION['isLoggedIn']=False;
	}
	$g_isLoggedIn=$_SESSION['isLoggedIn'];
	$g_index="";
	for($i=0;$i<=100;$i+=10){ $g_index=$g_index . "<a href=#$i>$i</a> "; }
	$g_userFullName=$_SESSION['firstName'] . " " . $_SESSION['lastName'];
	$g_userFirstName=$_SESSION['firstName'];
	$g_accountId=$_SESSION['accountId'];
?>
<html>
	<body>
		<center>
		<h1>Four Fours</h1>
		<font color="red"><?=$g_errors ?></font><br/><br/>
		<? if($g_isLoggedIn){ ?>
			<a href=?operation=logout>Logout</a>
			<br/>
			<br/>
			<div style="width:400px; text-align:left;">
			Welcome <?= $g_userFirstName ?>.
			Using only four 4s' and the operations +,-,*,/,^ (=exponentiation) and sqrt (=square root)
			create as many of the values below as you can. For example, for 2, I have ((4/4)+(4/4)), for 16, I have sqrt(4*4*4*4).
			</div>
			<br/>
			<table>
				<tr>
					<th>value</th><th>expression and author</th>
				</tr>
				<?php
				for($i=0;$i<=100;$i++){
					if($i%10==0){ ?>
						<td align="center" colspan="2" style="border-bottom:2pt solid black;"><?=$g_index ?></td>
					<? } ?>

					<tr>
						<td valign="top" style="border-bottom:2pt solid black;"> <a name="<?=$i?>" ><?=$i ?></a></td>
						<td valign="top" style="border-bottom:2pt solid black;">
							<table>
								<?php
									$dbconn = pg_connect_db();
									$result = pg_prepare($dbconn, "", "SELECT firstName, lastName, value, expression, s.accountId, s.id FROM account a, solution s WHERE a.id=s.accountId AND value=$i ORDER BY firstName, lastName, expression");
									$result = pg_execute($dbconn, "", array());
									# FIX XSS: Output from users must be whitelisted or escaped

									while ($row = pg_fetch_row($result)) {
										$count=0;
										$firstName=$row[$count++];
										$lastName=$row[$count++];
										$value=$row[$count++];
										$expression=$row[$count++];
										$expressionAccountId=$row[$count++];
										$expressionId=$row[$count++];
										if($expressionAccountId==$g_accountId){
											$deleteLink="<a href=\"?operation=deleteExpression&expressionId=$expressionId&accountId=$g_accountId\"><img src=\"delete.png\" width=\"20\" border=\"0\" /></a>";
										} else {
											$deleteLink="";
										}
										echo("<tr> <td>$expression</td><td>$deleteLink</td><td>$firstName $lastName</td></tr>");
									}
								?>
								<tr>
									<form method="post">
										<td><input type="text" name="expression"/> </td>
										<td><input type="submit" value="add"/></td>
										<input type="hidden" name="value" value="<?=$i?>"/>
										<input type="hidden" name="operation" value="addExpression"/>
										<input type="hidden" name="accountId" value="<?=$g_accountId ?>"/>
									</form>
								</tr>
							</table>
						</td>
					</tr>
				<? } ?>
			</table>
		<? } else { ?>
			<form method="post">
				<table>
					<tr>
						<td>user name: <input type="text" size="10" name="user"/></td>
						<td>password: <input type="password" size="10" name="password"/> </td>
						<td>
							<input type="hidden" name="operation" value="login"/>
							<input type="submit" value="login"/>
						</td>
					</tr>
					<tr>
						<td colspan="3"><?php echo($g_debug); ?></td>
					</tr>
				</table>
			</form>
		<? } ?>
		</center>
	</body>
</html>
