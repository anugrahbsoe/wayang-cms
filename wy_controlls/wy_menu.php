<?php
echo"
<script type='text/javascript'>
function search()
{
var s=document.forms['fsearch']['searchkey'].value;
if (s==null || s=='')
  {
	  alert('Nothing to search, please input a search key!');
	  return false;
  }
}
</script>
<div id='menubar'>
        	<ul id='nav'>
			";
			//menampilkan menu
			do{
				if($datamenu['page_id']==1)
					echo "<li><a href='./'>".$datamenu['page_name']."</a></li>";
				else{
					echo "<li><a href='?page_id=".$datamenu['page_id']."'>".$datamenu['page_name']."</a></li>";
				}
			}
			while($datamenu=$rowmenu->fetch_array());
       echo "</ul>
        	<form name='fsearch' action='./' method='get' onSubmit='return search();'>
            <img src='wy_files/images/search-btn.png' class='searchim'/>
			<input name='searchkey' type='text' class='searchon'>
        	</form>
		</div><!--close menubar-->	
    ";
?>