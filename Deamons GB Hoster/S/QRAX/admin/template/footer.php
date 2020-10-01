<script>
var close = document.getElementsByClassName("closebtn");
var i;

for (i = 0; i < close.length; i++) {
    close[i].onclick = function(){
        var div = this.parentElement;
        div.style.opacity = "0";
        setTimeout(function(){ div.style.display = "none"; }, 600);
    }
}
</script>
<div id="footer"><li>
				CopyRight &copy; <?php echo $site_name; ?> 2018
			</li></div>
		</div>
	</body>
</html>