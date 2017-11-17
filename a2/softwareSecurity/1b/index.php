<?
        session_start(); 

	if(!isset($_SESSION['isLoggedIn']))$_SESSION['isLoggedIn']=False;
	$operation=$_REQUEST['operation'];
	$g_debug="";
	$g_errors="";

	// overridden database functions
        function pg_connect_db(){
                $dbconn = pg_connect("dbname=fourfours user=ff host=localhost password=adg135sfh246");
                pg_set_client_encoding($dbconn, 'UTF8');
                return $dbconn;
        }
	if($operation == "login"){
		$user=$_REQUEST['user'];
		$password=$_REQUEST['password'];
		$dbconn = pg_connect_db();
		$query= "SELECT id, username, firstName, lastName, passwd FROM account WHERE username='$user' AND passwd='$password'";
		$result = pg_prepare($dbconn, "", $query);
		$result = pg_execute($dbconn, "", array());
		if($row = pg_fetch_row($result)) {
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
		$accountId=$_REQUEST['accountId']; 
		$dbconn = pg_connect_db();
		$result = pg_prepare($dbconn, "", "DELETE FROM solution WHERE id=$expressionId AND accountId=$accountId");
		$result = pg_execute($dbconn, "", array());
	} elseif($operation == "addExpression"){
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
