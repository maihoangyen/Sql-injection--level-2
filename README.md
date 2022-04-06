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

#### 2. Code mô phỏng lỗi <a name="gioithieu"></a>t

#### 3. Code sửa lỗi sqli cho level2 và level1 <a name="gioithieu"></a>
<br> 3.1 Code sửa lỗi sqli cho level2 <a name="kn"></a></br>

<br> 3.2 Code sửa lỗi sqli cho level1 <a name="kn"></a></br>

