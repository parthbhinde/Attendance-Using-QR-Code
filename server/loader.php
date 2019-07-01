<!--PreLoader for files -->
<style>
	.loader {
		position: fixed;
		left: 0px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 9998;
		background: rgb(249,249,249);
	}
	.spinner{
		border: 16px solid #f3f3f3; /* Light grey */
		border-top: 16px solid #3498DB; /* Blue */
		border-radius: 50%;
		width: 120px;
		height: 120px;
		z-index: 9999;
        position:absolute;
        left:0; right:0;
        top:0; bottom:0;
        margin:auto;
        max-width:100%;
        max-height:100%;
        overflow:auto;
		animation: spin 1s linear infinite;
	}
	@keyframes spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	}
</style>
<div class="loader" id="preloader">
	<div class="spinner"></div>
</div>
<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">

	$(document).ready(function(){
		$("#preloader").fadeOut();
	});
</script>