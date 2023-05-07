# SWE40002 Group 5 Matrix

Software Engineering Project B 

Real-Time Vehicle License Plate Recognition.


### Libraries and Dependencies 
**Web Portal**

Database Server
  +	Server Type: MariaDB
  +	Server Version: 10.4.24-MariaDB - mariadb.org binary distribution
  
Web Server
  +	PHP Version: 7.4.29 or above
  
phpMyAdmin
  +	Version: 5.2.0 or above

CDN/ Library/ Framework
  +	JQuery
  +	Bootstrap 3
  +	Google Fonts (Lato, Bungee Hairline)
  +	FontAwesome
  +	DataTables
  +	PHPMailer

**ANPR System**

Install python 3.7.6 through this link: [Download python 3.7.6](https://github.com/NgJingPing/Test_repo/raw/main/python/python-3.7.6-amd64.exe)

Upgrade pip version: 

Copy here:
```bash
python -m pip install --upgrade pip
```
Libraries:
+ pip install opencv-python
+ pip install numpy
+ pip install matplotlib
+ pip install Pillow
+ pip install keras
+ pip install DateTime
+ pip install paddlepaddle-gpu
+ pip install paddlepaddle
+ pip install "paddleocr>=2.0.1"
+ pip install mysql-connector-python
+ pip install uuid
+ pip install requests
+ pip install tensorflow

Copy here:
```bash
pip install opencv-python numpy matplotlib Pillow keras DateTime paddlepaddle-gpu paddlepaddle "paddleocr>=2.0.1" mysql-connector-python uuid requests tensorflow
```

### Database Creation

**Method 1**

Run the create_database.php in the web_portal folder through a web browser. Change the $servername, $username, and $password to your own hosted service credential.

*File path: Naim Vision > web_portal > create_database.php*

![image](https://user-images.githubusercontent.com/71062682/232187982-cb21262a-5a82-4731-9345-5f704c15c52c.png)

If you are using localhost, amend the codes as follows:

![image](https://user-images.githubusercontent.com/71062682/232189157-c726e126-38cc-4900-9677-b80353c51b29.png)

**Method 2**

Step 1: In the Naim Vision folder copy the codes in the database.txt. 

*File path: Naim Vision > database.txt.*

If you are using localhost, you can copy all the codes in database.txt.

If you are using your own hosted service credential, you can copy all the codes in database.txt except line 1 CREATE DATABASE anprdb. The hosted service credential will create a database in its phpMyAdmin, and you need to replace the database name in the text file below.

![image](https://user-images.githubusercontent.com/71062682/230804924-c638671e-6af6-4686-81bd-7711cdc85e5c.png)

Step 2: Paste the codes to phpMyAdmin SQL tab and click the Go button. 

![image](https://user-images.githubusercontent.com/71062682/230805322-32797368-2168-46b3-b0f8-5e28204ae60b.png)

**Actions**

The database and tables will be created: 

![image](https://user-images.githubusercontent.com/71062682/230805386-2e924489-a070-441b-b530-90b0ee28d12a.png)

Six users will be created in the users table, which can be used to log in to the web portal. 

![image](https://user-images.githubusercontent.com/71062682/230805068-2849aedd-2215-46a5-890e-7b7d354633cb.png)

The user information is as follows:

![image](https://user-images.githubusercontent.com/71062682/232776017-7c30c54c-41e5-4816-8750-55d0fd8966db.png)

### Amend the Configuration File

In the config.php, change the $servername, $username, $password, and $database to your own hosted service credential.

*File path: Naim Vision > web_portal > include > config.php*

![image](https://user-images.githubusercontent.com/71062682/232188497-3111cb97-9e32-49eb-82c1-c5df8a59062a.png)

If you are using localhost, amend the codes as follows:

![image](https://user-images.githubusercontent.com/71062682/232188979-f8c2b327-f5e1-4f66-ac3d-facb633840d0.png)


### Configuration for PHPMailer

In controllerBeforeLogin.php, change Host (line 72), Username (line 74) and Password (line75) if not using Google Gmail service to send mail.
If you plan to use Google Gmail service to send mail, you can change the Username and Password to the Gmail that you want to use for this feature. 
For further reference, refer to this link: https://github.com/PHPMailer/PHPMailer

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

Step 2: After clicking the Analytics button, the page should look like the image below.

![image](https://user-images.githubusercontent.com/71062682/230812825-3d2d0fea-c4cb-456e-b758-792fae517cf1.png)

Step 3: By default, the charts will show the records in daily mode. The big chart with the title Flows is a stacked chart that shows the entry, exit and denied flow all at once. The Total Entry & Exit Flows chart shows the sum of the entry and exit flows. The other three small charts individually show the entry, exit, and denied flow.

![image](https://user-images.githubusercontent.com/71062682/230812941-e4092097-6410-4cb7-bb97-eaeefac3d1b6.png)

![image](https://user-images.githubusercontent.com/71062682/230812950-2279c08b-ae34-4183-b48d-5a7d46ad80b8.png)

Step 4: Step 4: Click on the chart legend to remove the displayed data.

![image](https://user-images.githubusercontent.com/71062682/230812978-630702fb-92f6-4b0c-9a8f-0d58bee133b7.png)

Step 5: Hover the mouse over the bar to view each flow's label.

![image](https://user-images.githubusercontent.com/71062682/230812998-452edd50-b578-4f59-95d8-1f7141daa4fb.png)

Step 6: Click on the bar to view the details on the Report page. The report page will filter the data based on the clicked date on the bar.

![image](https://user-images.githubusercontent.com/71062682/230813037-b5ecff3e-7f51-49af-9cb0-5ede9b712260.png)

![image](https://user-images.githubusercontent.com/71062682/230813047-82e44ecf-51cf-4044-b5b1-98f8e00d7c60.png)

Step 7: The search functionality allows the users to filter the data based on the selected date range.

![image](https://user-images.githubusercontent.com/71062682/230813085-b3cb4d86-5e8d-4fa8-9aa9-089dffae6ab3.png)

![image](https://user-images.githubusercontent.com/71062682/230813107-dd67008d-72c4-476d-8beb-ab85646f0760.png)

Step 8: Click the Weekly, Monthly or Yearly button to switch the chart to the respective mode.

![image](https://user-images.githubusercontent.com/71062682/230813131-a624e660-5b33-4f8e-9aa5-a70d3aaf32bf.png)

![image](https://user-images.githubusercontent.com/71062682/230813159-4502d38c-8d3e-41ca-ad95-ca82c4af30e1.png)

![image](https://user-images.githubusercontent.com/71062682/230813180-f6a1c6f4-2528-42bc-9950-26f84133d83e.png)

Step 9: Click the PDF button to download the charts.

![image](https://user-images.githubusercontent.com/71062682/230813218-5a6a5b16-3d19-4d61-90f3-36be2dd41392.png)

Step 10: Click the Line button to switch the bar chart to the line chart.

![image](https://user-images.githubusercontent.com/71062682/230813264-55dd9fff-e6a4-4896-a258-4ef57a247c7a.png)

Step 11: Click the Bar button to switch the line chart to the bar chart.

![image](https://user-images.githubusercontent.com/71062682/230813293-67702cbb-feaf-4c89-9ea6-ab40f59b2290.png)


**Registration of New User (register_user.php)**

Registration of new users is a feature that is only available for super admin. Other user groups, such as admin and security, do not have this feature in their web portal. This feature enables the super admin to create new users to the database, either admin or security. 

Step 1: Click on the Add User button on the navigation bar.

![image](https://user-images.githubusercontent.com/71062682/230813381-4184b52e-8155-4cdc-9d89-3974c2a82d5a.png)

Step 2: After clicking the Analytics button, the page should look like the image below.

![image](https://user-images.githubusercontent.com/71062682/230813395-031c977b-f0d0-4c3a-af4a-83b7d15901ea.png)

Step 3: Choose the type of user you wish to create. In this case, the created user type will be an admin as the admin radio button is selected.

![image](https://user-images.githubusercontent.com/71062682/230813428-75b752f8-c905-4a94-9a78-47fdd14d9b09.png)

Step 4: Enter the email address of the new user.

![image](https://user-images.githubusercontent.com/71062682/230813459-819cdf32-e467-4fc3-88be-47d9e7a2a61d.png)

Step 5: Enter the password for the new user.

![image](https://user-images.githubusercontent.com/71062682/230813489-d963ce91-e002-41ee-b95b-48b5f2341f06.png)

Step 6: Re-enter the password for the new user.

![image](https://user-images.githubusercontent.com/71062682/230813519-60afe0b6-3257-423d-a9ea-53d2986121d0.png)

Step 7: Click the Register button to register the new user.

![image](https://user-images.githubusercontent.com/71062682/230813549-0500f92d-4b1b-4915-a483-2bcebe4587fb.png)

Step 8: A successful message will be shown after clicking the register button.

![image](https://user-images.githubusercontent.com/71062682/230813580-321777a1-7088-4b34-b0e0-43ea8c89b53e.png)

Step 8a: After clicking the register button, an alert message will be shown if the registered email address exists in the database.

![image](https://user-images.githubusercontent.com/71062682/230813609-c736cbfd-8695-4521-aa24-e154aca0bdcd.png)

Step 8b: After clicking the register button, an alert message will be shown if the enter and re-enter password does not match.

![image](https://user-images.githubusercontent.com/71062682/230813635-5eec9f5b-bcb5-468b-8e28-34756ee9829f.png)


**Account Management (manage_user.php)**

This feature is only available for super admin.

Step 1: Click on the View User button on the navigation bar.

![image](https://user-images.githubusercontent.com/71062682/230813691-cb65c886-2abf-42ae-84f4-9032e5ff7fa1.png)

Step 2: After clicking the View User button, the page should look like the image below.

 ![image](https://user-images.githubusercontent.com/71062682/230813717-d69bda6b-3ae9-4663-981f-e288ac944436.png)
 
Step 3: To hide a column from the table, click on the nothing selected text. A drop-down menu will be shown.

![image](https://user-images.githubusercontent.com/71062682/230813748-2e123920-cfea-431c-a02f-fc92e19eb0b7.png)

![image](https://user-images.githubusercontent.com/71062682/230813759-56baa866-c756-43b6-ae8e-88bbaf3a1728.png)

Step 4: Click the Action text to hide the Action column. A cross icon will be displayed, and the Action column will be hidden. 

![image](https://user-images.githubusercontent.com/71062682/230813787-26eadf61-751c-4ff1-82cc-c54d23d02020.png)

Step 5: Click the Action text again to show the Action column. The cross icon will be removed, and the Action column will be displayed in table. 

![image](https://user-images.githubusercontent.com/71062682/230813814-e79949a2-b113-4409-a2f7-0f75c7dfc98b.png)

Step 6: Search the user with the role security by entering the security text in the search bar. The table will filter the data based on the search result.

![image](https://user-images.githubusercontent.com/71062682/230813838-58ebad7e-e759-4ee7-adfb-85ca0bf6bcad.png)

Step 7: Click the buttons below to either copy the table, download the table in CSV, excel or PDF format, or print the table.

![image](https://user-images.githubusercontent.com/71062682/230813864-8f0f49a4-f128-41b7-b7d8-84c72808aaf7.png)


**Edit User (edit_user.php)**

This feature is only available for super admin.

Step 1: Click on the Edit icon to edit the user.

![image](https://user-images.githubusercontent.com/71062682/230813906-7c42ba2e-8fe6-4de0-896e-be9761a5a19e.png)

Step 2: After clicking the Edit icon, the page should look like the image below. The information displayed is based on the user that have been selected.

![image](https://user-images.githubusercontent.com/71062682/230813926-7cbdf398-21da-454f-b829-60a525b5783f.png)

Step 3: Change the user type from Security to Admin by selecting the admin radio button, and click the submit button.

![image](https://user-images.githubusercontent.com/71062682/230813965-cbd86dbf-e04b-42f6-95ac-4c443228dedd.png)

Step 4: A successful message will be shown after clicking the submit button.

![image](https://user-images.githubusercontent.com/71062682/230814002-56e25409-2ead-459c-9dc2-0286b7fd4b89.png)

Step 5: Navigate back to the View User page, and the information is updated accordingly.

![image](https://user-images.githubusercontent.com/71062682/230814036-6f316732-ca37-4c64-876d-c6a2de95c313.png)


**Delete User (delete_user.php)**

This feature is only available for super admin.

Step 1: Click on the Delete icon to delete the user.

![image](https://user-images.githubusercontent.com/71062682/230814085-c3ef6a61-3184-49ee-a6c6-2768579f4a15.png)

Step 2: An alert box will appear and click on the OK button.

![image](https://user-images.githubusercontent.com/71062682/230814108-9e4c0c6f-2928-4c4e-83ae-b25ddbee849e.png)

Step 3: The user has been removed after the OK button is clicked.

![image](https://user-images.githubusercontent.com/71062682/230814147-9a0385d9-aacc-4dac-911f-486e7525d01e.png)


**User Profile Card (profile.php)**

This user profile page will display a card containing the user’s email address, role, company, and a change password button that enables the user to change their account password.

![image](https://user-images.githubusercontent.com/71062682/230814185-503fd44c-f718-45e9-9744-1dde20222ca3.png)

To change the account password, follow the steps below:

Step 1:  Click the Change Password button.

![image](https://user-images.githubusercontent.com/71062682/230814205-3592209d-2b2c-4c27-be8b-bea19ca6f336.png)
Step 2: A form will pop out. The “x” symbol at the top right corner and the “Cancel” button at the bottom left of the form can be used to close the pop-out form.

![image](https://user-images.githubusercontent.com/71062682/230814227-99de3c98-01e2-44d4-8ff1-bc4f71e46d0e.png)

Step 3: Enter the old password of the account.

![image](https://user-images.githubusercontent.com/71062682/230814259-d025eed3-6c28-41f3-88f5-5b310f2f5713.png)

Step 4: Enter the new password for the account.

![image](https://user-images.githubusercontent.com/71062682/230814287-e5e423fc-a794-4ab0-ade8-52348198877a.png)

Step 5: Re-enter the new password that creates for this account.

![image](https://user-images.githubusercontent.com/71062682/230814313-ef5af421-fc51-4587-ad0b-581725589260.png)

Step 6: Click the “Submit” button at the bottom right of the form to submit the changes.

![image](https://user-images.githubusercontent.com/71062682/230814340-06e06e9c-f68c-4682-a17f-4087da92d7a7.png)

Step 7a: An alert message with green background will pop out at the top of the user profile card to indicate that the user changes the password successfully.

![image](https://user-images.githubusercontent.com/71062682/230814363-2cec10ff-75ea-4d3e-88dc-357ad1983eb4.png)

Step 7b: When the user enters the wrong old password, a red background alert message will appear at the top of the profile card to indicate that the user has entered the wrong old password.

![image](https://user-images.githubusercontent.com/71062682/230814388-313a56d0-9784-4320-bbf9-05a876cff2e6.png)

Step 7c: When the user re-enters the new password that does not match the new password entered before, a red background alert message will appear at the top of the profile card.

![image](https://user-images.githubusercontent.com/71062682/230814420-32d7b82e-02ef-4d0e-8157-4e2e20513239.png)


**Forgot Password (forgot_password.php)**

This feature is only available on the login page. Users could click the “Forgot Password?” link in the login form if they forgot their password.

![image](https://user-images.githubusercontent.com/71062682/230814446-93fc5500-a7c9-40dd-9f13-a80dd179e3c5.png)

Step 1: Enter the email address that the user forgot its password.

![image](https://user-images.githubusercontent.com/71062682/230814458-6a853829-e108-4ba6-99c3-565fd98da09d.png)

Step 2: Click the “Continue” button below and wait a few seconds.

![image](https://user-images.githubusercontent.com/71062682/230814476-6c0dc9b7-246a-467a-89aa-2d599a485d4d.png)

Step 3a: A green background alert message will appear at the top of the form.

![image](https://user-images.githubusercontent.com/71062682/230814500-b370419d-5392-4e03-80f3-44e1d93154e8.png)

Step 3b: A red background alert message will pop out if the user enters an email that does not exist in the database.

![image](https://user-images.githubusercontent.com/71062682/230814524-8119e676-f493-4703-b046-8888c0654520.png)

Step 4: Enter the 6-digit code that sends to the user’s mailbox.

![image](https://user-images.githubusercontent.com/71062682/230814544-f338e42a-1591-41c3-a8da-520d8e72823d.png)

Below is an example of the mail content shown using Outlook:

![image](https://user-images.githubusercontent.com/71062682/230814578-1ed747bd-c277-457b-af75-7b057fb61248.png)

Step 5: Click the “Check Code” button to proceed to the next page.

![image](https://user-images.githubusercontent.com/71062682/230814598-08bbae04-6ede-42d5-9322-bbf7b575e34d.png)

Step 6a: A green background alert message will appear at the top of the page.

![image](https://user-images.githubusercontent.com/71062682/230814613-b8e531f3-bbfe-4640-be8c-735aae8c22db.png)

Step 6b: Enter an incorrect code the alert message pops out.

![image](https://user-images.githubusercontent.com/71062682/230814629-0b87f695-ce9e-42e2-91d8-c8c16c2ae6aa.png)

Step 6c: The alert message will pop out if the user enters the alphabet in the input field since it only accepts numbers.

![image](https://user-images.githubusercontent.com/71062682/230814650-9b0bcfa9-8f68-4afa-9939-4e45eb600e42.png)

Step 7: Fill in the form and click the “Change” button to change the password.

![image](https://user-images.githubusercontent.com/71062682/230814667-b2da9e8c-61a0-4154-95a2-a12b091593de.png)

Step 8a: The user will be redirected to the login page, and a green background alert message will appear at the top of the login form.

![image](https://user-images.githubusercontent.com/71062682/230814686-a41c29fe-3b73-4ac3-8b0c-1c3537c96e5f.png)

Step 8b: A red alert message appears if the user enters passwords that do not match.

![image](https://user-images.githubusercontent.com/71062682/230814712-b736150f-d877-4ddf-ab30-4b62949b5942.png)


### ANPR System
**Paddle OCR**

*File path: Naim Vision > ANPR > anpr_detection_entry.py*

*File path: Naim Vision > ANPR > anpr_detection_exit.py*

Step 1: Open the anpr_detection_entry.py or anpr_detection_exit.py with any Python IDLE Environment. The following is a suggested Python IDLE editor. Click the download button to install Visual Studio Code: (https://code.visualstudio.com/)

![image](https://user-images.githubusercontent.com/71062682/230814795-ebc343ec-e584-4aea-97e4-a4227ccb484f.png)

Step 2: To use Python in Visual Studio Code, look for and install the appropriate Python extension. 

![image](https://user-images.githubusercontent.com/71062682/230814815-2e5fc03d-2d32-4038-b634-8966f0a40b1f.png)

Step 3: Open the Naim Vision Folder in visual studio code. 

![image](https://user-images.githubusercontent.com/71062682/230814837-93b96832-cd40-4baf-beb3-ea41de6e69af.png)

Step 4: In the Naim Vision folder, anpr_detection_entry.py will use to detect the entry log and anpr_detection_exit.py will use to detect the exit log. These two files can be duplicated and renamed in the future. The entrylogtemp and exitlogtemp folders will temporarily store the entry and exit log images during the ANPR detection. The images folder will store the entry and exit log images permanently, which will be used on the web portal. Although entrylog folder is used for storing the entry log images and the exitlog folder is used for storing the exit log images, both folders are also used for storing the denied log images. The yolov5 is the YOLO version 5 library folder that is used for detection purposes. The coco file contains the detection text label.

![image](https://user-images.githubusercontent.com/71062682/230814847-17116e21-7179-48da-add6-f69a8fce4546.png)

Download Yolov5 (optional): https://github.com/ultralytics/yolov5
Step 5: After setup, the environment installs all the python dependencies listed above. Check all the libraries installed correctly by typing the pip list in the terminal.

![image](https://user-images.githubusercontent.com/71062682/230814881-42a3bb3f-b80c-4568-b780-7e2199c909d5.png)

Step 6: Replace the video path or camera API address inside the cv2.VideoCapture function. Change the camera type to either entry or exit. The entry and exit mean the camera input will insert the records into the database for the respective table.

![image](https://user-images.githubusercontent.com/71062682/230814906-aa704789-5bd6-4add-aa47-3b83a90f49ce.png)

Step 7: Increase the target_fps value if the video needs a smoother frame. Otherwise, decrease the target_fps value to reduce the video lagging problem. The higher fps value requires more power to process the frame per second.

![image](https://user-images.githubusercontent.com/71062682/230814921-c2a7d279-3013-4cab-9453-8ba09b9b2c3f.png)

Step 8: Scroll down the anpr_detection_entry.py or anpr_detection_exit.py. The code shown in the image below is used to connect the file to the database. Change the codes if you are using your own hosted service credential.

![image](https://user-images.githubusercontent.com/71062682/232194605-c9f87dc2-3800-451b-a68e-1b13f44b4956.png)

If you are using localhost, you can remove the port and follow the code below:

![image](https://user-images.githubusercontent.com/71062682/232194643-41cc509f-1bba-4a9b-ba9c-27afc8e1b403.png)

Step 9: Skip this step and remove the codes if you are using localhost. Otherwise, you can amend the code below if you are using your own hosted service credential. The hosted service credential able to support FTP (File Transfer Protocol). Replace the hostname, username and password follow the format below.

Format: ftp = ftplib.FTP("your FTP hostname", "your FTP username", "your FTP password")

![image](https://user-images.githubusercontent.com/71062682/232194693-63dd7218-b3b0-432b-a6ef-7e41f91a6d06.png)

Set the upload file directory to your own hosted service credential.

![image](https://user-images.githubusercontent.com/71062682/232194722-5aee99a5-1198-4249-8ba6-629a3c8e20b7.png)

If the FTP is not working, you need to ensure that the code below is amend correctly. Otherwise, you can ignore or remove the code if you are using localhost. 

![image](https://user-images.githubusercontent.com/71062682/232194795-d541c266-7839-4a93-b8a6-cc126fdb68ec.png)

Step 10: Run the anpr_detection_entry.py or anpr_detection_exit.py to detect the vehicle and license plate and recognise the license plate number (OCR). The OCR stand for Optical Character Recognition. The ANPR system window will open, and it looks:

![image](https://user-images.githubusercontent.com/71062682/230814956-63e260a6-a841-41c5-9846-96ccb95f40bd.png)

The window will be able to indicate the fps rate. The detection will detect when the vehicle position is inside the green box. The up arrow red button is used to show the detection result, and it can expand and close the resulting menu.

Step 11: Click the up red arrow button to expand the resulting menu. It will indicate the status of vehicle detection, license plate detection, license plate number and match result. To close the resulting menu, click the X button.

![image](https://user-images.githubusercontent.com/71062682/230814980-89490b0c-a4fd-42c8-b20a-49134e40b353.png)

Step 12: The terminal will display the match result and the license plate number. 

![image](https://user-images.githubusercontent.com/71062682/230815020-0c340f46-d6b4-44f9-9c78-34d756b30b6d.png)

Step 13: After finishing running anpr_detection_entry.py or anpr_detection_exit.py, close all the files and the python IDLE editor.

**Plate Recognizer**

Link:  https://platerecognizer.com/

Step 1: After registering the account, click here:

![image](https://user-images.githubusercontent.com/71062682/233334691-913a54aa-9689-4e76-93bf-a5bf2d5792d8.png)

Step 2: Click the setting icon.

![image](https://user-images.githubusercontent.com/71062682/233334792-1fa6c8da-dd9d-49fd-911f-9c773d162dd0.png)

Step 3: Copy the token and replace the text XXXXXXXXXXXX in line 259 as your token.

![image](https://user-images.githubusercontent.com/71062682/233334945-ee64f6a3-6e1a-48a8-bc1d-968ecff6ce97.png)

*File path: Naim Vision > ANPR > plate_recognizer.py*

![image](https://user-images.githubusercontent.com/71062682/234573758-f157b536-5d04-4f70-a909-76fb0c903310.png)
