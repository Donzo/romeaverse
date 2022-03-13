<script>
	//Front End Database Interactions (JS in inline PHP)
	async function sendUserWalletAddressToDB() {
			
			
		//Create Form Data Object to be sent to server
		let formData = new FormData();
		
		//Append account number
		formData.append('userAccountNum', window['userAccountNumber']);
		
		//var loadingWheel = document.getElementById('loading-wheel');
		//loadingWheel.style.visibility="hidden";
		//setInnerHTML('upload-status-text', "Upload Complete. Redirecting after confirmation...");
		
		//Send it!
		await fetch('/code/php/BE-db-int.php', {
			method: "POST", 
			body: formData
		}).then((response) => {
 			console.log(response);
 			console.log(window['userAccountNumber'] + ' account entered in DB.');
			//window.location.replace("https://nftest.net/my-tests?userAccountNum=" + window['userAccountNumber']);
		})
			
			
	}
</script>