var opwd;
	var npwd;
	var cpwd;
	$(document).ready(function(){
		
		$("#cpass").focus(function(){
			opwd = $("#opass").val();
			npwd = $("#npass").val(); 
			if(opwd==npwd && opwd!="" && npwd!=""){
				$("#nreport").html("<font color='red'> same with old password</font>");
			}
			else{
				if(npwd.length<8){
					$("#nreport").html("<font color='red'> password to sort</font>");
				}
				else{
					$("#nreport").html("");
				}
			}
			return false;
		});
		$("#change").click(function(e){
			e.preventDefault();
			npwd = $("#npass").val();
			cpwd = $("#cpass").val();
			opwd = $("#opass").val();
			if(npwd!=cpwd && opwd!="" && npwd!="" && cpwd !=""){
				$("#creport").html("<font color='red'>confirm password not match with new password</font>");
			}
			else{
				var datanya = "&opwd="+opwd+"&npwd="+npwd;
				$("#sreport").html("<img src='../images/loading.gif' /> updating password ... ");
				$.ajax({
					type : "post",
					url: "../controller/controll_pwd.php",
					data: "op=changepwd"+datanya,
					cache: false,
					success: function(msg){
						if(msg=="sukses"){
							$("#sreport").html("<img src='../images/tick.png' /> update success");
							$("#npass").val("");
							$("#cpass").val("");
							$("#opass").val("");
						}
						else{
							$("#sreport").html("<img src='../images/error.png' /> update failed, try again");
						}
					}
				});	
			}
		});
	});