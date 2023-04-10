# SWE40002 Group 5 Matrix

Software Engineering Project B 

Real-Time Vehicle License Plate Recognition.


### Libraries and Dependencies 
**Web Portal**
Database Server
  •	Server Type: MariaDB
  •	Server Version: 10.4.24-MariaDB - mariadb.org binary distribution
Web Server
  •	PHP Version: 7.4.29 or above
phpMyAdmin
  •	Version: 5.2.0 or above
CDN/ Library/ Framework
  •	JQuery
  •	Bootstrap 3
  •	Google Fonts (Lato, Bungee Hairline)
  •	FontAwesome
  •	DataTables
  •	PHPMailer

**ANPR System**
Install python 3.9.7 or above through this link: https://www.python.org/downloads/
Libraries:
'''
pip install opencv-python
pip install numpy
pip install matplotlib
pip install Pillow
pip install keras
pip install DateTime
pip install os-sys
pip install paddlepaddle-gpu
pip install paddlepaddle
pip install "paddleocr>=2.0.1"
pip install mysql-connector-python
pip install uuid
'''

### Database Creation
**Method 1**
Run the create_database.php in the web_portal folder through a web browser. If you are not using the localhost, please change the following codes: 
*File path: Naim Vision > web_portal > create_database.php*
![image](https://user-images.githubusercontent.com/71062682/230804908-2f4275ff-0046-47ab-8a9d-e8fc79b46a56.png)

**Method 2**
Step 1: In the Naim Vision folder copy the codes in the database.txt. 
*File path: Naim Vision > database.txt.*
![image](https://user-images.githubusercontent.com/71062682/230804924-c638671e-6af6-4686-81bd-7711cdc85e5c.png)
Step 2: Paste the codes to phpMyAdmin SQL tab and click the Go button. 
![image](https://user-images.githubusercontent.com/71062682/230805322-32797368-2168-46b3-b0f8-5e28204ae60b.png)
**Actions**
The database and tables will be created: 
![image](https://user-images.githubusercontent.com/71062682/230805386-2e924489-a070-441b-b530-90b0ee28d12a.png)
Six users will be created in the users table, which can be used to log in to the web portal. 
![image](https://user-images.githubusercontent.com/71062682/230805068-2849aedd-2215-46a5-890e-7b7d354633cb.png)
The user information is as follows: 
Super Admin
Email:	naim000@naim.com.my
Password:	naim000
Admin
Email:	naim001@naim.com.my
Password:	naim001
Email:	naim002@naim.com.my
Password:	naim002
Security
Email:	naim100@naim.com.my
Password:	naim100
Email:	naim101@naim.com.my
Password:	naim101
Email:	naim102@naim.com.my
Password:	naim102

### Amend the Configuration File
In the config.php, change the $servername, $username, and $password if you are not using the localhost.
*File path: Naim Vision > web_portal > include > config.php*
![image](https://user-images.githubusercontent.com/71062682/230805182-2abe11e5-fe33-4309-be71-aa6c25ecbfce.png)

### Configuration for PHPMailer
In controllerBeforeLogin.php, change Host (line 72), Username (line 74) and Password (line75) if not using Google Gmail service to send mail.
If you plan to use Google Gmail service to send mail, you can change the Username and Password to the Gmail that you want to use for this feature. 
For further reference, refer to this link:
GitHub - PHPMailer/PHPMailer: The classic email sending library for PHP
*File path: Naim Vision > web_portal > include > controllerBeforeLogin.php*
![image](https://user-images.githubusercontent.com/71062682/230805235-4294f571-d525-4411-8166-3169fd9c0a7a.png)

### Introduction to the Web Portal
**Login (login.php)**
The login page will contain a form with two input fields and a login button. It also consists of the forgot password feature, which will be explained later. The user must enter the correct email address and password to log in. 
![image](https://user-images.githubusercontent.com/71062682/230805472-1c4263b2-be1b-4a9d-8576-cb3640b8884e.png)
Step 1: Enter a valid email address and password, then click the login button. This email address *naim000@naim.com.my* and password *naim000* have been registered into the database after creating the database by following the showcase above.
![image](https://user-images.githubusercontent.com/71062682/230805500-571e02f7-2053-484b-9a49-f699bdd3c442.png)
Step 2a: You will be redirected to the dashboard page if the login is successful.
![image](https://user-images.githubusercontent.com/71062682/230805512-214ab8fb-bfa1-4c61-8ca6-fbed817668ad.png)
Step 2b: When the user enters an invalid email address or password will lead to login failure. An alert message will appear at the top of the form to indicate the mistake made.
![image](https://user-images.githubusercontent.com/71062682/230805543-216de665-864f-4186-a269-84ad19481058.png)

**Dashboard (index.php)**
The dashboard page will be loaded as default after logging in. However, it can be accessed from every page by doing the following:
Step 1: Click on the Dashboard button on the navigation bar. The dashboard data will be reset daily at 12:00 a.m. The total flow today shows the count of summing the entries and exits today. 
![image](https://user-images.githubusercontent.com/71062682/230805571-70ff7416-a1eb-4f99-ae03-4261ed60c899.png)

**Registration (register_vehicle.php)**
Step 1: Click on the Registration button on the navigation bar.
![image](https://user-images.githubusercontent.com/71062682/230805616-34a9f08c-165c-4669-ae55-a7bc3527dcfc.png)
Step 2: Fill in the data in the input fields then click on submit.
![image](https://user-images.githubusercontent.com/71062682/230805641-8fce8d86-24e9-4fb2-b63c-fb4acb8e5b85.png)
Step 3a: Confirm that your inputs are valid, and no error messages appear. You will see a banner indicating your registration process was successful.
![image](https://user-images.githubusercontent.com/71062682/230805662-56b5de76-cdc5-466a-92be-827f0197d626.png)
Step 3b: An error message indicating the reason for failure will appear when submission fails.
![image](https://user-images.githubusercontent.com/71062682/230805672-5d23e739-4450-4452-8010-c5531778ad1e.png)

**Database (view_vehicle.php)**
Step 1: Click on the Database button on the navigation bar.
![image](https://user-images.githubusercontent.com/71062682/230805692-b63c0995-0e6e-442f-8153-b982fb3be5f1.png)
Step 2: Search the database for the tenant you have just created. In this example, we used John Doe with the tenant lot number AJ1234.
![image](https://user-images.githubusercontent.com/71062682/230805708-ac15d3df-463f-4f41-b39d-f173615e6560.png)

**Edit Tenant and Vehicle (edit_vehicle.php)**
Step 1: Click on the Database button on the navigation bar.
![image](https://user-images.githubusercontent.com/71062682/230805728-f9dcc4e5-6d1f-400b-8442-19fd2d35f5c4.png)
Step 2: Click on the edit action button on the row that you want to edit.
![image](https://user-images.githubusercontent.com/71062682/230805776-d027376a-dc75-4c01-8af3-4f5f9716d893.png)
Step 3: You may modify any information on the edit page except for Tenant Lot Number.
![image](https://user-images.githubusercontent.com/71062682/230806328-84cc8c19-5f08-4d70-b800-eeb718fccbbf.png)
Step 4: Once the submit button has been clicked, you will see a banner indicating your edit process was successful.
![image](https://user-images.githubusercontent.com/71062682/230806344-90e5c172-419f-4eda-ac77-04b6920fe38c.png)
Step 5: Confirm that the data has been updated.
![image](https://user-images.githubusercontent.com/71062682/230806361-29244df0-0d03-4e55-931c-72ad2b61e066.png)

**Delete Tenant and Vehicle (delete_vehicle.php)**
Step 1: Click on the Database button on the navigation bar.
![image](https://user-images.githubusercontent.com/71062682/230806399-13385baa-5f00-40b2-9ee8-a8a32632ef83.png)
Step 2: Click the delete action button on the row you want to delete. Notice that some rows do not have a delete action button. This is because records of entry or exits of these vehicles/tenants have been logged into the database.
![image](https://user-images.githubusercontent.com/71062682/230806418-6c0f641e-e296-4ccc-8d4b-00a235fc8dfc.png)
Step 3: An alert box will appear. Click ok to confirm the deletion.
![image](https://user-images.githubusercontent.com/71062682/230806438-8f0873c8-d26e-482b-a0ee-248c9ece067e.png)

**Report Page**
Step 1: Click on the Report button on the navigation bar.
![image](https://user-images.githubusercontent.com/71062682/230806469-b35c5c9f-c63e-42f0-a777-32275fcb2617.png)
Step 2: After clicking the Report button, the page should look like the image below.
![image](https://user-images.githubusercontent.com/71062682/230806490-77df8ea3-8da1-4962-9423-1e7f416e03c9.png)
Step 3: Select the Start Date.
![image](https://user-images.githubusercontent.com/71062682/230806505-b8eae9ea-ecb1-49cf-98ab-7ab552c06c9f.png)
Step 4: Select the End Date.
![image](https://user-images.githubusercontent.com/71062682/230806512-342c2c2a-5481-40f5-af40-d46fc9d9f237.png)
Step 5: Click the Search button.
![image](https://user-images.githubusercontent.com/71062682/230806526-32c34929-2bb8-4f55-8dd8-5fd3e49e7425.png)
Step 6: The Entry Log, Exit Log and Denied Access Log tables will be shown after clicking the Search button. Each table record will be filtered based on the selected date range. 
![image](https://user-images.githubusercontent.com/71062682/230806532-9912ec02-c446-4cfc-b20c-b21e978a4960.png)
![image](https://user-images.githubusercontent.com/71062682/230806536-df80fda0-dfcf-4d0b-834d-b7d30325cbfd.png)
![image](https://user-images.githubusercontent.com/71062682/230806539-777b4586-bf90-4902-882f-69e9f38080b6.png)
Step 7: To hide a column from the table, click on the nothing selected text. A drop-down menu will be shown.
![image](https://user-images.githubusercontent.com/71062682/230806558-620ffba9-8865-42d0-bb05-2e35e3f0cfb5.png)
![image](https://user-images.githubusercontent.com/71062682/230806569-4c41bb74-8ca7-4191-bfd0-2277fcc12489.png)
Step 8: Click the Tenant Lot Number and Action text to hide the Tenant Lot Number and Action column. A cross icon will be displayed, and the Tenant Lot Number and Action columns will be hidden. 
![image](https://user-images.githubusercontent.com/71062682/230806599-7b04e1fd-12b1-4c0d-be52-6fdb3769a889.png)
Step 9: Click the Tenant Lot Number and Action text again to show the Tenant Lot Number and Action columns. The cross icon will be removed, and the Tenant Lot Number and Action columns will be displayed in table. 
![image](https://user-images.githubusercontent.com/71062682/230806620-485afc70-298d-40ff-aa19-d43e774d49a5.png)
Step 10: Search the license plate number by entering the EMN9573 text in the search bar. The table will filter the data based on the search result.
![image](https://user-images.githubusercontent.com/71062682/230806640-9a9dd414-42d3-466d-a01b-c1a046100a0c.png)
Step 11: Click the buttons below to either copy the table, download the table in CSV, excel or PDF format, or print the table.
![image](https://user-images.githubusercontent.com/71062682/230807856-806de803-8ee8-45af-a18d-9a5008f336a6.png)

**Entry Log**
Step 1: Click on the Entry Log button on the navigation bar.
![image](https://user-images.githubusercontent.com/71062682/230807907-0a0e17db-ecf7-49bd-8e27-e0d5bff11b25.png)
Step 2: Check if the vehicle you are looking for is in the database.
![image](https://user-images.githubusercontent.com/71062682/230807933-cda47ec4-2745-4400-8182-d706b5fe46b1.png)

**Entry Log Details (entry_log_details.php)**
Step 1: While on the Entry Log page, click on the action button.
![image](https://user-images.githubusercontent.com/71062682/230807988-761a4d24-4320-4ce3-8e9b-85dfe1d3bb25.png)
Step 2: The Entry Log Details page contains the information about the vehicle, timestamp, tenant lot number and an image of the vehicle and its license plate number.
![image](https://user-images.githubusercontent.com/71062682/230808009-cd863357-0b9c-4b92-a99c-06dcafa946d3.png)

**Exit Log (exit_log.php)**
Step 1: Click on the Entry Log button on the navigation bar.
![image](https://user-images.githubusercontent.com/71062682/230808068-b50e2422-36ee-4957-9145-13daf3aabc9e.png)
Step 2: Check if the vehicle you are looking for is in the database.
![image](https://user-images.githubusercontent.com/71062682/230808083-c750c29c-c4c4-4817-afc4-1045d087671a.png)

**Exit Log Details (exit_log_details.php)**
Step 1: While on the Exit Log page, click on the action button.
![image](https://user-images.githubusercontent.com/71062682/230808125-e6eec495-f7d3-4331-a3dd-ed7fdd3572c2.png)
Step 2: The Exit Log Details page contains the information about the vehicle, timestamp, tenant lot number and an image of the vehicle and its license plate number.
![image](https://user-images.githubusercontent.com/71062682/230808147-18adacdd-2c55-4166-b6e9-ce5d2df571ee.png)

**Denied Access Log (denied_access.php)**
Step 1: Click on the Entry Log button on the navigation bar.
![image](https://user-images.githubusercontent.com/71062682/230808240-1392d759-15e9-45e5-8223-a681969fd605.png)
Step 2: Check if the vehicle you are looking for is in the database.
![image](https://user-images.githubusercontent.com/71062682/230808251-69513588-f57a-4c7a-bc60-9e592da286ce.png)

**Denied Access Log Details (denied_details.php)**
Step 1: While on the Denied Access Log page, click on the action button.
![image](https://user-images.githubusercontent.com/71062682/230808318-7743596b-dda4-4d50-9ec1-97232bd2f9cf.png)
Step 2: The Denied Access Log Details page contains the timestamp and an image of the vehicle and its license plate number.
![image](https://user-images.githubusercontent.com/71062682/230808331-e60c3b8c-cea0-4cec-94a6-236f6e80b9cf.png)

**Analytics (analytic.php)**
Step 1: Click on the Analytics button on the navigation bar.
![image](https://user-images.githubusercontent.com/71062682/230808410-f4c5a810-cc7c-4466-995c-aa092fc0f116.png)
