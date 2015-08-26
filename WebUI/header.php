<?php


	#ini_set("display_errors", false);

	require("config.php");
	require("jsonRPCClient.php");
	include("printarray.php");
	include("currency.php");

	session_start();
	if (isset($_GET['currentWallet']) && !empty($_GET['currentWallet']))
		$_SESSION['currentWallet'] = $_GET['currentWallet'];

	if (isset($_SESSION['currentWallet']) && !empty($_SESSION['currentWallet']))
		$currentWallet = $_SESSION['currentWallet'];
	else {
		$keys = array_keys($wallets);
		$currentWallet = $keys[0];
		$_SESSION['currentWallet'] = $currentWallet;
	}

	$coinu = $wallets[$currentWallet];

	$coin = new jsonRPCClient("{$coinu['protocol']}://{$coinu['user']}:{$coinu['pass']}@{$coinu['host']}:{$coinu['port']}", true);

	$ticker = $coinu['ticker'];

	$pair = "$ticker"."_btc";

	// fetch price in BTC price of current coin
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "http://api.cryptocoincharts.info/tradingPair/".$pair);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$rawData = curl_exec($curl);
	curl_close($curl);
	$data = json_decode($rawData);
	$price = "$data->price";

	// fetch fiat value of BTC
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "http://api.cryptocoincharts.info/tradingPair/btc_".$currency);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$rawData1 = curl_exec($curl);
	curl_close($curl);
	$data1 = json_decode($rawData1);
	$fiatBTC = "$data1->price";
?>

<html><head><title>StakeBox</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href='css/slate.css' rel='stylesheet' >
<link href="css/main.css" rel="stylesheet" >
<script src='libs/jquery.js'></script>
<script src='libs/bootstrap.js'></script>
	<script>
		$(function(){
			$('#selectwallet a').click(function(){
				window.location.href="./?currentWallet="+$(this).text();
				return false;
			});
		});
	</script>
</head>
<body>
	<nav class="navbar navbar-default navbar-fixed-top navbar-custom" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="./"><img src="logo.png" height="170%"></a>

			</div>
			<div class="collapse navbar-collapse" id="navbar-collapse">
				<ul class="nav navbar-nav">
					<li><a href="last20transactions">Transactions</a></li>
					<li><a href="sendcoins">Send Coins</a></li>
					<li><a href="control">Control</a></li>
				</ul>
				<div class="navbar-right">
					<p class="navbar-text"><?php 	echo "Current best price is {$price} BTC on ".$data->best_market.""; ?></p>
					<p class="navbar-text">Select Wallet:</p>
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $currentWallet;?> <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu" id="selectwallet">
								<?php
									foreach ($wallets as $walletName => $walletData)
										echo '<li><a href="#">'.$walletName.'</a></li>';
								?>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</nav>
<div class="container-fluid">
<div class='content'>
<div class="well">
<?php
	try {
		$coininfo = $coin->getinfo();
	} catch(exception $e) {
		echo "<br><p class='bg-danger'><b>Error: Your wallet server is not running. Please restart your StakeBox via the power option in the server section on the control page. If you have just restarted it, or powered it on, please allow it up to several minutes before attempting to restart it again.</b></p>";
	}

?>