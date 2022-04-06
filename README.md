 # <div align="center"><p> Sql_injection-level2 </p></div>
 ## Họ và tên: Mai Thị Hoàng Yến
 ## Ngày báo cáo: Ngày 6/4/2022
 ### MỤC LỤC
 1. [Khai thác sqli level2](#gioithieu)
 
 2. [Code mô phỏng lỗi](#tha) 
       
 3. [Code sửa lỗi sqli cho level2 và level1](#ths)
 
### Nội dung báo cáo 
#### 1. Khai thác sqli level2 <a name="gioithieu"></a>
 - B1: Sử dụng `netdiscover` để quét Giao thức ARP và nhận các thiết bị trên Mạng LAN Chúng ta có thể thấy rằng IP thứ ba là IP mong muốn và IP thứ hai là IP Kali của chúng ta
 
     ![image](https://user-images.githubusercontent.com/101852647/162014093-0980aab0-9ec5-4cf1-a4a7-5fe324b6b38d.png)
 
 - B2:Bây giờ sử dụng nmap để tìm ra các cổng đang mở. Ta sẽ thấy cổng 80 đang mở.
 
     ![image](https://user-images.githubusercontent.com/101852647/162014392-8e3c3e63-18d9-40f7-a664-fd449050c1c4.png)

 - B3: Mở trang web lên và nhấn vào test bây giờ chúng ta sẽ test thử xem nó có url có dễ bị chèn sqli hay không bằng cách thêm `?id=1'` thì thấy không có hiện tượng gì chứng tỏ trang web này không dễ bị chèn sqli. Vì vậy chúng ta sẽ khai thác sql mù dựa trên việc thực thi mã từ xa bằng cách sử dụng một webshell php.
 
     ![image](https://user-images.githubusercontent.com/101852647/162015980-bdc27640-da14-4d99-876c-7507aa24229d.png)
     
 - B4: Chúng ta sẽ sử dụng công cụ sqlmap để tìm `username` và `password` với câu lệnh `sqlmap -u http://192.168.199.130/ --headers = ”X-Forwarded-For: 1*” --dbs`
 
     ![image](https://user-images.githubusercontent.com/101852647/162016532-d600fa0e-c2e1-40ab-8863-079462f33ef9.png)
     
     ![image](https://user-images.githubusercontent.com/101852647/162017326-e6b70db2-be7b-4805-a066-49f0675e050f.png)


 - B5: Bây giờ chúng ta sẽ tìm tất cả các bảng có trong database bằng câu lệnh `sqlmap -u "http://192.168.199.130/" --headers="X-forwarded-for:1*" --tables -D photoblog --smart --batch`
 
     ![image](https://user-images.githubusercontent.com/101852647/162017389-d55355b2-0f4d-4148-9481-7ecc2ed029cc.png)
     
     ![image](https://user-images.githubusercontent.com/101852647/162017421-4bd631c3-625e-4adc-946e-100b2936f67e.png)
  
 - B6: Tiếp theo ta sẽ tìm được `username` và `password` bằng câu lệnh `sqlmap -u "http://192.168.199.130/" --headers="X-forwarded-for:1*" --dump -T users -D photoblog --smart --batch`

     ![image](https://user-images.githubusercontent.com/101852647/162017841-25e25328-971e-404b-a3e2-3c5c7f7a3d3f.png)
    
     ![image](https://user-images.githubusercontent.com/101852647/162017894-34b9a0d1-7031-4a27-84a6-6ac0120379e8.png)
     
 - B7: Sau khi có được `username` và `password` thì chúng ta sẽ đăng nhập vào trang `admin` và nhiệm vụ chúng ta bây giờ là tải lên webshell php. 
 
     ![image](https://user-images.githubusercontent.com/101852647/162019096-a6011726-c622-488a-9c9d-5e12a5b89db3.png)
     
     ![image](https://user-images.githubusercontent.com/101852647/162019124-3e624378-e516-4dfa-b015-1cdee9bd6861.png)

 - B8: Bây giờ chúng ta sẽ thử tải tệp có đuôi là `.php.jpg` nhưng không được.
 
     ![image](https://user-images.githubusercontent.com/101852647/162019280-a8aba18e-0a05-44b1-877f-a8a6fe4ea755.png)
     
     ![image](https://user-images.githubusercontent.com/101852647/162019207-a55f96c2-8164-4143-8378-3fdde4bcaa8f.png)
     
 - B9: Sau đó, ta sử dụng `ExifTool` để liên kết một tệp php độc hại sẽ tạo ra lỗ hổng thực thi mã từ xa. Ta sẽ tải 1 file ảnh với tên là `img.png` và sao chép nó vào trong file `simple-backdoor.php` từ đường dẫn `/ usr / share / webshells / php `
 
     ![image](https://user-images.githubusercontent.com/101852647/162020135-98df924b-fb1b-4ad9-a618-2e138b5c7454.png)
     
     ![image](https://user-images.githubusercontent.com/101852647/162020213-e37aab41-c7af-4194-b660-5c88da062734.png)
     
     ![image](https://user-images.githubusercontent.com/101852647/162020337-5626efcb-c544-4631-abe4-9f3b2ee87206.png)

 - B10: Bây giờ gõ lệnh cho `ExifTool` để ẩn mã độc của tệp php bên trong hình ảnh png bằng lệnh `exiftool "-comment <= simple-backdoor.php" img.png `
 
     ![image](https://user-images.githubusercontent.com/101852647/162020722-4a15a8db-6214-4cb6-a558-b786805b9b19.png)
    
 - B11: Tiếp theo ta sẽ kiểm tra thông tin của hình ảnh bằng lệnh `exiftool img.png`. Như chúng ta có thể quan sát, mã độc được ẩn bên trong hình ảnh
 
     ![image](https://user-images.githubusercontent.com/101852647/162021212-1b8e0bd0-135e-457b-9151-fdddcaf11bc8.png)
     
 - B12: Bây giờ chúng ta tải ảnh có ẩn mã đọc bên trong lên trang web cũng chính là cái webshell php của chúng ta

     ![image](https://user-images.githubusercontent.com/101852647/162022504-3f5eac14-6994-423c-a22e-11e885f301e2.png)

 - B13: file ảnh đã tải thành công bây giờ chúng ta sẽ nhấn vào backdoor và bắt đầu thực thi nó

     ![image](https://user-images.githubusercontent.com/101852647/162023190-e558d0be-35bc-4e70-b784-18b471a911a9.png)

 - B14: Bây giờ chúng ta mở mã nguồn nó lên và kiểm tra xem hình ảnh được tải lên liên kết chưa. Như chúng ta thấy thì chúng ta đã tìm thấy liên kết và mở nó lên.
 
     ![image](https://user-images.githubusercontent.com/101852647/162023611-4e3d3896-2c8a-42b0-9a27-e9ac8ccc9207.png)
 
 - B15: Như chúng ta đã biết hình ảnh chứa một trình bao web sẽ cho phép thực thi mã từ xa. Do đó, sau khi khám phá đường dẫn được liệt kê ở trên, ta lấy tệp `/ etc / passwd`
 
 

#### 2. Code mô phỏng lỗi <a name="gioithieu"></a>

#### 3. Code sửa lỗi sqli cho level2 và level1 <a name="gioithieu"></a>
<br> 3.1 Code sửa lỗi sqli cho level2 <a name="kn"></a></br>

<br> 3.2 Code sửa lỗi sqli cho level1 <a name="kn"></a></br>
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





