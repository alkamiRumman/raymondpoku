<style>
	body {
		text-align: center;
		padding: 40px 0;
		background: #EBF0F5;
	}

	h1 {
		color: #88B04B;
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
		color: #9ABC66;
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
		position: relative;
	}

	.btn-container {
		position: absolute;
		bottom: 20px; /* Adjust the distance from the bottom */
		left: 0;
		right: 0;
	}

	.btn {
		padding: 10px 20px;
		background-color: #00CC00; /* Red color for button */
		color: white;
		border: none;
		border-radius: 4px;
		font-size: 18px;
		cursor: pointer;
	}

</style>
<div class="card">
	<div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
		<i class="checkmark">✓</i>
	</div>
	<h1>Success</h1>
	<p>We received your purchase request;<br/> we'll be in touch shortly!</p>
	<br><br>
	<div class="btn-container">
		<button class="btn" onclick="login()">Login</button>
	</div>
</div>
<script>
	function login() {
		window.location.href = '<?= base_url('site/index') ?>';
	}
</script>
