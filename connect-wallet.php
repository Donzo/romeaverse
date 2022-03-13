	<script>
		var debugging = true;
		const ethereumButton = document.getElementById('connectToWalletButton');
		const userAccountNumber = document.getElementById('userAccountNumber');
		var provider = false;
		var networkName = false;
		var buttonName = false;
		var networkSwitchExists = false;
		var contractAddress = false;
		var chainId = false;
		var checkThisID = false;
		var blockExplorerBase = 'https://kovan.etherscan.io/tx/';
		
		
		async function connectWallet() {
			
			const accounts = await ethereum.request({ method: 'eth_requestAccounts' });
			const account = accounts[0];
			
			if (account){
				//userAccountNumber.innerHTML = "User Account: " + account;
				window['userAccountNumber'] = account;
				
				sendUserWalletAddressToDB();
				
				//alert(window['userAccountNumber']);
				ig.game.startGame();
				ig.game.playerAssets.leather = checkRewards(window['userAccountNumber'], 'leather');
				ig.game.playerAssets.wool = checkRewards(window['userAccountNumber'], 'wool');
				ig.game.playerAssets.grain = checkRewards(window['userAccountNumber'], 'grain');
				ig.game.playerAssets.wood = checkRewards(window['userAccountNumber'], 'wood');
				ig.game.playerAssets.iron = checkRewards(window['userAccountNumber'], 'iron');
			}
			else{
				alert("I can't connect to your wallet!");
			}
			
		}
		
		async function sendRewardsToServer(userAccountNumber, whichCommodity, howManyOfThem){
			
			var account = userAccountNumber;
			var reward = whichCommodity;
			var amount = howManyOfThem;
						
			var getLink = "/code/php/send-rewards.php?which=" + reward + "&howMany=" + amount + "&uAN=" + account;
			
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
    			if (this.readyState == 4 && this.status == 200) {
    				console.log('updating ' + updateThis + ' to ' + this.responseText);
					updateThis = this.responseText;
   				}
			};
			xhttp.open("GET", getLink, true);
			xhttp.send();

		};
		
		//Retrieve reward counts from the server
		async function checkRewards(userAccountNumber, whichCommodity){
			
			var account = userAccountNumber;
			var getLink = "/code/php/check-rewards.php?&uAN=" + account + "&which=" + whichCommodity;
			
			
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
    			if (this.readyState == 4 && this.status == 200) {
					console.log('this.responseText = ' + this.responseText);
					//alert(this.responseText);
					
					if (whichCommodity == 'leather'){
						window['leather'] = this.responseText;
					}
					else if (whichCommodity == 'wool'){
						window['wool'] = this.responseText;
					}
					else if (whichCommodity == 'wood'){
						window['wood'] = this.responseText;
					}
					else if (whichCommodity == 'grain'){
						window['grain'] = this.responseText;
					}
					else if (whichCommodity == 'iron'){
						window['iron'] = this.responseText;
					}
					return this.responseText;
   				}
			};
			xhttp.open("GET", getLink, true);
			xhttp.send();

		};
		
		function mintIt(){
						
			let web3 = new Web3(Web3.givenProvider);
			var contract = new web3.eth.Contract(abi, contractAddress, {
				from: window['userAccountNumber'],
				//gasPrice: '20000000000'
			});
			
			var metadataURI = "https://nftest.net" + pathToMyResults;
			
			//Send mint call if this is the user who took this test
			if (ogUser == window['userAccountNumber'] ){
				contract.methods.mint(window['userAccountNumber'], testID, metadataURI).send({
					_to:window['userAccountNumber'],
					_tokenId:testID,
					_uri:pathToMyResults
					//from: window['userAccountNumber'];
				}, function(){
					var progressDiv = document.getElementById('progress-div');
					progressDiv.style.margin = ".25em";	
					progressDiv.style.textAlign = "center";	
					progressDiv.style.color = "#FAF566";	
					progressDiv.innerHTML = "Mining Transaction...";
					var loadWheel = document.getElementById('loading-wheel');
					loadWheel.style.display = "flex";
					document.getElementById('connection-in-progress').style.display = 'flex'; 
				})
				.on('receipt', function(receipt){
    				console.log;
    				//Loading wheel;
					var loadWheel = document.getElementById('loading-wheel');
					var progressDiv = document.getElementById('progress-div');
					progressDiv.style.margin = "1.5em 0em .25em 0em";
					progressDiv.style.color = "lime";	
					progressDiv.innerHTML = "NFT Minted!";
					loadWheel.style.display = 'flex';
					loadWheel.style.display.flexdirection = 'column';
					loadWheel.innerHTML = "<p style='text-align:center;'>Hash: </p><p><a href='" + blockExplorerBase + "" + receipt.transactionHash + "' target='_new'> "+ receipt.transactionHash + "</a></p>";	
				});
			}
			else{
				alert('I cannot mint this NFT for you because I do not think that these are your results.')
			}
			
		}
		function makeMintButton(nn){
			if (nn == "avalanche"){
				networkName = "Avalanche";
				contractAddress = "0x26e85B9e83964E6cdDF47fF304b99338a1723a89";
				checkThisID = 'snAvax';
				buttonName = 'avalanche';
				blockExplorerBase = "https://snowtrace.io/tx/";
			}
			else if (nn == "ethereum"){
				networkName = "Ethereum Mainnet";
				contractAddress = "0xD2B6262Fad281dc298F0785439CC87E6c782E4b0";
				checkThisID = 'snEth';
				buttonName = 'ethereum';
				blockExplorerBase = "https://etherscan.io/tx/";
			}
			else if (nn == "kovan"){
				networkName = "Kovan Testnet";
				contractAddress = "0x35D250Ac305EbD40c58f27E7EfA63B9EFE869BeA";
				checkThisID = 'snKovan';
				buttonName = 'kovan';
				blockExplorerBase = "https://kovan.etherscan.io/tx/";
			}
			else if (nn == "polygon"){
				networkName = "Polygon";
				contractAddress = "0xa356017aA0d16f556c802d8F2f2B7eFE826e3047";
				checkThisID = 'snPolygon';
				buttonName = 'polygon';
				blockExplorerBase = "https://polygonscan.com/tx/";
			}
			else if (nn == "rinkeby"){
				networkName = "Rinkeby";
				contractAddress = "0x7977872cF7e3F4dbaD7547EE77601b14D8B63ad6";
				checkThisID = 'snRinkeby';
				buttonName = 'rinkeby';
				blockExplorerBase = "https://rinkeby.etherscan.io/tx/";
			}
			else if (nn == "arbitrum"){
				networkName = "Arbitrum";
				contractAddress = "0x7977872cF7e3F4dbaD7547EE77601b14D8B63ad6";
				checkThisID = 'snArbitrum';
				buttonName = 'arbitrum';
				blockExplorerBase = "https://arbiscan.io/tx/";
			}
			
			var avaxButton = "<div class='qStyleRadio'><input type='radio' class='qStyleChoice' onclick='switchNetwork(1)' id='snAvax' name='networkSelector' value='snAvax'><label for='networkSelector'><span class='selector-font'>Avalanche Network</span></label></div>";
			var polygonButton = "<div class='qStyleRadio'><input type='radio' class='qStyleChoice' onclick='switchNetwork(2)' id='snPolygon' name='networkSelector' value='snPolygon'><label for='networkSelector'><span class='selector-font'>Polygon Network</span></label></div>";
			var ethereumButton = "<div class='qStyleRadio'><input type='radio' class='qStyleChoice' onclick='switchNetwork(3)' id='snEth' name='networkSelector' value='snEth'><label for='networkSelector'><span class='selector-font'>Ethereum Mainnet</span></label></div>";
			var kovanButton = "<div class='qStyleRadio'><input type='radio' class='qStyleChoice' onclick='switchNetwork(4)' id='snKovan' name='networkSelector' value='snKovan'><label for='networkSelector'><span class='selector-font'>Kovan Testnet</span></label></div>";
			var rinkebyButton = "<div class='qStyleRadio'><input type='radio' class='qStyleChoice' onclick='switchNetwork(5)' id='snRinkeby' name='networkSelector' value='snRinkeby'><label for='networkSelector'><span class='selector-font'>Rinkeby Testnet</span></label></div>";
			var arbitrumButton = "<div class='qStyleRadio'><input type='radio' class='qStyleChoice' onclick='switchNetwork(6)' id='snArbitrum' name='networkSelector' value='snArbitrum'><label for='networkSelector'><span class='selector-font'>Arbitrum Network</span></label></div>";
			
			var networkSelectorHTML = "<div class='content-section'><div class='cu-header-txt' style='text-align:center'>Or Mint Your NFT on a Different Network</div><div class='cu-dir2' style='text-align:center'>You can mint your NFT on any of these networks.</div><div id='networkSwitchContainer' class='radioButtonContainer'><div class='radioButtons'><fieldset>" + avaxButton + polygonButton + ethereumButton + kovanButton + rinkebyButton + "</fieldset></div></div></div>";
			var feedbackDivs = "<div id='progress-div'></div><div id='loading-wheel'><img id='connection-in-progress' src='/images/loading-wheel.gif'></div>";

			var buttonHTML = "<div class='greenButton bigButton' id='mintTestButton' onclick='mintIt()'>Mint Your NFT on " + networkName + "</div>" + feedbackDivs;
			
			
			if (!networkSwitchExists){
				document.getElementById("start-div").insertAdjacentHTML('afterend', networkSelectorHTML);	
			}
			
			setInnerHTML("my-results-connect-button", buttonHTML);
			
			if (!networkSwitchExists){
				networkSwitchExists = true;	
				document.getElementById(checkThisID).checked = true;
			}
			
			//Loading wheel;
			if (document.getElementById('connection-in-progress')){
				document.getElementById('connection-in-progress').style.display = "none";	
			}
			window.scrollTo(0,document.body.scrollHeight);
		

		}
		async function switchNetwork(which){
			//Polygon
			var theChainID = '0x89';
			var theRPCURL = 'https://polygon-rpc.com';
			var nn = false;
			
			if (which == 1){
				//AVAX
				theChainID = '0xa86a';
				theRPCURL = 'https://api.avax.network/ext/bc/C/rpc';
				nn = "avalanche";
			}
			else if (which == 2){
				//Polygon
				theChainID = '0x89';
				theRPCURL = 'https://polygon-rpc.com';
				nn = "polygon";
			}
			else if (which == 3){
				//Mainnet
				theChainID = '0x1';
				theRPCURL = 'https://main-light.eth.linkpool.io/';
				nn = "ethereum";
			}
			else if (which == 4){
				//Kovan
				theChainID = '0x2a';
				theRPCURL = 'https://kovan.infura.io';
				nn = "kovan";
			}
			else if (which == 5){
				//Rinkeby
				theChainID = '0x4';
				theRPCURL = 'https://rinkeby-light.eth.linkpool.io/';
				nn = "rinkeby";
			}
			else if (which == 6){
				//Arbitrum
				theChainID = '0xa4b1';
				theRPCURL = 'https://arb1.arbitrum.io/rpc';
				nn = 'arbitrum';
			}
			
			try {
					await window.ethereum.request({
						method: 'wallet_switchEthereumChain',
						params: [{ chainId: theChainID }],
					});
				} catch (switchError) {
  				// This error code indicates that the chain has not been added to MetaMask.
				if (switchError.code === 4902) {
					try {
						await window.ethereum.request({
							method: 'wallet_addEthereumChain',
							params: [{ chainId: theChainID, rpcUrl: theRPCURL}],
						});
					}
					catch (addError) {
				
					}
				}
			}
			finally{
				if (buttonName != nn){
					makeMintButton(nn);
				}
				
			}
			
		}
		function reportProvider(){
			if (window.ethereum) {
  		 		chainId = window.ethereum.chainId;
  		 		console.log('chainID = ' + chainId)
			}
			if (chainId == "0xa86a" || provider == 43114){
				networkName = "avalanche";
				console.log('User is on Avalanche C-Chain.');
				makeMintButton(networkName);
			}
			else if (chainId == "0x1" || provider == 1){
  				console.log('User is on Ethereum Mainnet.');
  				networkName = "ethereum";
  				makeMintButton(networkName);
  			}
  			else if (chainId == "0x2a" || provider == 42){
  				console.log('User is on Kovan Testnet.');
  				networkName = "kovan";
  				makeMintButton(networkName);
  			}
  			else if (chainId == "0x89" || provider == 137){
  				console.log('User is on Polygon Network.');
  				networkName = "polygon";
  				makeMintButton(networkName);
  			}
  			else if (chainId == "0x4" || provider == 4){
  				console.log('User is on Rinkeby Testnet.');
  				networkName = "rinkeby";
  				makeMintButton(networkName);
  			}
  			else if (chainId == "0xa4b1" || provider == 42161){
  				console.log('User is on Arbitrum.');
  				networkName = "arbitrum";
				alert('I cant seem to deploy a contract to Arbitrum using RemixJS so its not gonna mint here till I can fix that. Maybe try a different network? All the others work.')
  				switchNetwork(3);
  				makeMintButton(networkName);
  				
  			}
  			else{
  				alert('User is on unhandled network with ID number ' + provider + ' and chainid ' + chainId + '.');
  			}
  			
		}
		
		function offerNoAccountPreview(method){
			var msg = "I can't connect to your wallet so I can't save your test! Want to try making one anyway?";
			if (method == 2){
				msg = "I can't connect to your wallet so I can't save your results or mint an NFT! Want to try the test anyway?";
			}
			if (typeof window['userAccountNumber'] != 'string'){
				//Let users know we cant save but let them play with the program if they want			
				var resp = confirm(msg);
				if (resp == true) {
					if (method == 1){
						makeTest();
					}
					else if (method == 2){
						takeTest();
					}
					
				}	
			}
		}
		
	var abi = [
	{
		"inputs": [],
		"stateMutability": "nonpayable",
		"type": "constructor"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"internalType": "address",
				"name": "_owner",
				"type": "address"
			},
			{
				"indexed": true,
				"internalType": "address",
				"name": "_approved",
				"type": "address"
			},
			{
				"indexed": true,
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "Approval",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"internalType": "address",
				"name": "_owner",
				"type": "address"
			},
			{
				"indexed": true,
				"internalType": "address",
				"name": "_operator",
				"type": "address"
			},
			{
				"indexed": false,
				"internalType": "bool",
				"name": "_approved",
				"type": "bool"
			}
		],
		"name": "ApprovalForAll",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"internalType": "address",
				"name": "previousOwner",
				"type": "address"
			},
			{
				"indexed": true,
				"internalType": "address",
				"name": "newOwner",
				"type": "address"
			}
		],
		"name": "OwnershipTransferred",
		"type": "event"
	},
	{
		"anonymous": false,
		"inputs": [
			{
				"indexed": true,
				"internalType": "address",
				"name": "_from",
				"type": "address"
			},
			{
				"indexed": true,
				"internalType": "address",
				"name": "_to",
				"type": "address"
			},
			{
				"indexed": true,
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "Transfer",
		"type": "event"
	},
	{
		"inputs": [],
		"name": "CANNOT_TRANSFER_TO_ZERO_ADDRESS",
		"outputs": [
			{
				"internalType": "string",
				"name": "",
				"type": "string"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [],
		"name": "NOT_CURRENT_OWNER",
		"outputs": [
			{
				"internalType": "string",
				"name": "",
				"type": "string"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_approved",
				"type": "address"
			},
			{
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "approve",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_owner",
				"type": "address"
			}
		],
		"name": "balanceOf",
		"outputs": [
			{
				"internalType": "uint256",
				"name": "",
				"type": "uint256"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "getApproved",
		"outputs": [
			{
				"internalType": "address",
				"name": "",
				"type": "address"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_owner",
				"type": "address"
			},
			{
				"internalType": "address",
				"name": "_operator",
				"type": "address"
			}
		],
		"name": "isApprovedForAll",
		"outputs": [
			{
				"internalType": "bool",
				"name": "",
				"type": "bool"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_to",
				"type": "address"
			},
			{
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			},
			{
				"internalType": "string",
				"name": "_uri",
				"type": "string"
			}
		],
		"name": "mint",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [],
		"name": "name",
		"outputs": [
			{
				"internalType": "string",
				"name": "_name",
				"type": "string"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [],
		"name": "owner",
		"outputs": [
			{
				"internalType": "address",
				"name": "",
				"type": "address"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "ownerOf",
		"outputs": [
			{
				"internalType": "address",
				"name": "_owner",
				"type": "address"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_from",
				"type": "address"
			},
			{
				"internalType": "address",
				"name": "_to",
				"type": "address"
			},
			{
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "safeTransferFrom",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_from",
				"type": "address"
			},
			{
				"internalType": "address",
				"name": "_to",
				"type": "address"
			},
			{
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			},
			{
				"internalType": "bytes",
				"name": "_data",
				"type": "bytes"
			}
		],
		"name": "safeTransferFrom",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_operator",
				"type": "address"
			},
			{
				"internalType": "bool",
				"name": "_approved",
				"type": "bool"
			}
		],
		"name": "setApprovalForAll",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "bytes4",
				"name": "_interfaceID",
				"type": "bytes4"
			}
		],
		"name": "supportsInterface",
		"outputs": [
			{
				"internalType": "bool",
				"name": "",
				"type": "bool"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [],
		"name": "symbol",
		"outputs": [
			{
				"internalType": "string",
				"name": "_symbol",
				"type": "string"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "tokenURI",
		"outputs": [
			{
				"internalType": "string",
				"name": "",
				"type": "string"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_from",
				"type": "address"
			},
			{
				"internalType": "address",
				"name": "_to",
				"type": "address"
			},
			{
				"internalType": "uint256",
				"name": "_tokenId",
				"type": "uint256"
			}
		],
		"name": "transferFrom",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_newOwner",
				"type": "address"
			}
		],
		"name": "transferOwnership",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	}
]
	
    </script>