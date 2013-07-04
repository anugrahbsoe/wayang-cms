<?php
//post
echo "Post Statistic. <a href='mod_post.php'/><img src='images/super-mono-3d-26.png' class='imgaction'/> See All?</a>";
$query='SELECT `post_title`, `post_category`, `post_user` FROM `wy_post` ORDER BY post_id DEsC LIMIT 0,5';
$row=$conn->query($query);
$data=$row->num_rows;

echo "<table width='100%'>
<th id='head' class='head'>Post Title</th>
<th id='head' class='head' >Post Sender</th>
<th id='head' class='head' >Post Category</th>";

if($data==0)
{
	echo "<tr><td colspan=6 class='data'>No data found in database</td></tr>";
}
else
{
	while($data=$row->fetch_array()){
		echo "<tr>
		<td class='data'>".$data['post_title']."</td>
		<td class='data'>".$data['post_user']."</td>
		<td class='data'>".$data['post_category']."</td>
		</tr>";
	}
}
echo "</table><br/>";

//comment
echo "Comment Statistic. <a href='mod_comment.php'/><img src='images/super-mono-3d-26.png' class='imgaction'/> See All?</a>";
$query='SELECT `wy_comment`.`comment_name`, `wy_comment`.`comment_email`, `wy_post`.`post_title` FROM `wy_comment`, `wy_post` WHERE `wy_comment`.`post_id`=`wy_post`.`post_id` ORDER BY `wy_comment`.`comment_id` DESC LIMIT 0,5';
$row=$conn->query($query);
$data=$row->num_rows;

echo "<table width='100%'>
<th id='head' class='head'>Comment Sender</th>
<th id='head' class='head' >Comment Email</th>
<th id='head' class='head' >Comment on Post</th>";

if($data==0)
{
	echo "<tr><td colspan=6 class='data'>No data found in database</td></tr>";
}
else
{
	while($data=$row->fetch_array()){
		echo "<tr>
		<td class='data'>".$data['comment_name']."</td>
		<td class='data'>".$data['comment_email']."</td>
		<td class='data'>".$data['post_title']."</td>
		</tr>";
	}
}
echo "</table><br/>";

//visitor
echo "Visitor Statistic.";
$qt="SELECT COUNT(*) as num FROM `wy_visitor`";
$rawdata=$conn->query($qt);
$dataresult=$rawdata->fetch_assoc();
$total = $dataresult['num'];

$qd="SELECT COUNT(*) as num FROM `wy_visitor` WHERE `visitor_date` = CURDATE()";
$rawdata=$conn->query($qd);
$dataresult=$rawdata->fetch_assoc();
$today = $dataresult['num'];

$qy="SELECT COUNT(*) as num FROM `wy_visitor` WHERE `visitor_online` = DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
$rawdata=$conn->query($qy);
$dataresult=$rawdata->fetch_assoc();
$yesterday = $dataresult['num'];

$qo="SELECT COUNT(*) as num FROM `wy_visitor` WHERE `visitor_online_status`=1";
$rawdata=$conn->query($qo);
$dataresult=$rawdata->fetch_assoc();
$online = $dataresult['num'];

echo "<table width='100%'>
<th id='head' class='head'>Visitor Today</th>
<th id='head' class='head' >Visitor Online</th>
<th id='head' class='head' >Visitor Yesterday</th>
<th id='head' class='head' >Total Visitor</th>";
echo "<tr>
		<td class='data'>Get ".$today." Visitors Today</td>
		<td class='data'>Currently Online ".$online." Visitors</td>
		<td class='data'>Get ".$yesterday." Visitors Yesterday</td>
		<td class='data'>Have ".$total." total of Visitors</td>
		</tr>";
echo "</table><br/>";
?>