<?php
$query="SELECT `post_id`, `post_title` FROM `wy_post` ORDER BY `post_id` DESC";
$row=$conn->query($query);
$data=$row->num_rows;
if(!$row=$conn->query($query)){
	echo "Could not retrieve data!";
}
else{
	echo "
	<div id='site_sidebar'>
		<div class='side_head'>
			<p>Recent Post</p>
		</div>
		<div class='side_content'>";
		if($data==0){
			echo "No data found in database!";
		}else{
			while($data=$row->fetch_array()){
				echo "<a href='?post_id=".$data['post_id']."'>".$data['post_title']."</a><br>";
			}
		}
	echo"</div>
	</div>";
}
?>