<?php
$query="SELECT `wy_comment`.`comment_name`,`wy_post`.`post_title`,`wy_comment`.`post_id` FROM `wy_comment`,`wy_post` WHERE  `wy_comment`.`post_id`=`wy_post`.`post_id` order by `wy_comment`.`comment_id` desc";
$row=$conn->query($query);
$data=$row->num_rows;
if(!$row=$conn->query($query)){
	echo "Could not retrieve data!";
}
else{
	echo "
	<div id='site_sidebar'>
		<div class='side_head'>
			<p>Recent Comment</p>
		</div>
		<div class='side_content'>";
		if($data==0){
			echo "No data found in database!";
		}else{
			while($data=$row->fetch_array()){
				echo $data['comment_name']." comment on <a href='?post_id=".$data['post_id']."'>".$data['post_title']."</a><br>";
			}
		}
	echo"</div>
	</div>";
}
?>