<?php

	//Khai báo sử dụng session
	session_start();
 
	//Khai báo utf-8 để hiển thị được tiếng việt
	header('Content-Type: text/html; charset=UTF-8');
	$conn = new mysqli('localhost','root', '','demo');

	$username = mysqli_real_escape_string($conn, $_POST['username']);
	$password = mysqli_real_escape_string($conn, $_POST['password']);

	
	$sql = "SELECT * FROM member WHERE username = '$username'";
	$result = $conn->query($sql);

	//$stmt = $conn->prepare("SELECT * FROM member WHERE username = ?");

   	// Liên kết một biến với tham số dưới dạng chuỗi. 
   	//$stmt->bind_param("s", $username);

   	// Thực hiện câu lệnh. 
   	//$stmt->execute();

   	//  Lấy các biến từ truy vấn. 
   	//$result = $stmt->get_result();

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