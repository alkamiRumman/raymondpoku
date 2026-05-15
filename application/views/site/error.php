<style>
	body {
		text-align: center;
		padding: 40px 0;
		background: #EBF0F5;
	}

	h1 {
		color: #FF6347; /* Red color for error message */
		font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
		font-weight: 900;
		font-size: 40px;
		margin-bottom: 10px;
	}

	p {
		color: #404F5E;
		font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
		font-size: 20px;
		margin: 0;
	}

	i {
		color: #FF6347; /* Red color for error icon */
		font-size: 100px;
		line-height: 200px;
		margin-left: -15px;
	}

	.card {
		background: white;
		padding: 60px;
		border-radius: 4px;
		box-shadow: 0 2px 3px #C8D0D8;
		display: inline-block;
		margin: 0 auto;
		position: relative; /* Add position relative for absolute positioning */
	}

	.btn-container {
		position: absolute;
		bottom: 20px; /* Adjust the distance from the bottom */
		left: 0;
		right: 0;
	}

	.btn {
		padding: 10px 20px;
		background-color: #FF6347; /* Red color for button */
		color: white;
		border: none;
		border-radius: 4px;
		font-size: 18px;
		cursor: pointer;
	}
</style>
<div class="card">
	<div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
		<i class="checkmark">!</i> <!-- Use a different icon or symbol for failure -->
	</div>
	<h1>Payment Failed</h1>
	<p>We're sorry, but your payment couldn't be processed.<br/> Please try again later or contact support.</p>
	<br><br>
	<div class="btn-container">
		<button class="btn" onclick="retryPayment()">Retry Payment</button>
	</div>
</div>
<script>
	function retryPayment() {
		window.location.href = '<?= base_url('site/signup') ?>';
	}
</script>
