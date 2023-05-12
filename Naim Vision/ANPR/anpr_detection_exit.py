"""
This anpr_detection_exit python files is detecting vehicle, detecting licence plate, recognising number plate, 
recording into exitlog database when the system result is "found" and recording into deniedaccess database 
when the system result is "not found". The camera type is "exit", the temporary exitlog images will store in 
exitlogtemp folder and the permanent exitlog images store will in exitlog folder inside the images folder.

"""

# Import Python Library
import cv2
import numpy as np
from matplotlib import pyplot as plt
from keras.preprocessing.image import save_img
from PIL import ImageFont, ImageDraw, Image
#from tensorflow.keras.utils import save_img
import os.path
from paddleocr import PaddleOCR,draw_ocr
import mysql.connector
import uuid
import datetime
import ftplib

# Initialise camera
#cap = cv2.VideoCapture(0)
cap = cv2.VideoCapture("Naim Vision\ANPR\WhatsApp Video 2022-11-09 at 14.39.54.mp4")
#cap = cv2.VideoCapture("rtsp://admin:Matrix40001@192.168.1.60:554/Streaming/Channels/2/")

#Camere Types (entry/exit)
#camera = "entry"
camera = "exit"

# Get the video frame count
frame_count = int(cap.get(cv2.CAP_PROP_FRAME_COUNT))

# Set the frame rate per second (based on video lagging)
target_fps = 10

# Get the video frame count
video_fps = int(cap.get(cv2.CAP_PROP_FPS))

# Skip frames per second
skip_frames = round(video_fps / target_fps)

# Counter for skipped frames
skipped_frames = 0

# Initialise Yolov5 for vehicle detection
net = cv2.dnn.readNetFromONNX("Naim Vision\ANPR\yolov5n.onnx")

file = open("Naim Vision\ANPR\coco.txt", "r")
classes = file.read().split('\n')

# Initialise Yolov5 for licence plate detection
net_2 = cv2.dnn.readNetFromONNX("Naim Vision\ANPR\yolov5n_2.onnx")

file_2 = open("Naim Vision\ANPR\coco2.txt","r")
classes_2 = file_2.read().split('\n')

# Initialise paddle ocr for OCR Recognition
ocr = PaddleOCR(use_angle_cls=True, lang="en")

# Image folder location
folder_path = "Naim Vision\ANPR\images"
plate = ""
vehicle_id = ""
current_plate = ""
count = 0
found = True
#area = [(170,182),(930,194),(936,590),(9,530)]
area = [(937,585),(920,150),(180,150),(10,530)]

# Initialise Result Menu
vehicle_detected = '...'
licence_detected = '...'
number_plate = '...' 
matched = '...'

## Connect to database
conn = mysql.connector.connect (
    host = "b6tbs7zg8rgt7bzirw0b-mysql.services.clever-cloud.com",
    user = "ukh3yi0dkztfb7zu",
    password = "d5CpElwU7CB9gqa8n6aZ",
    database = "b6tbs7zg8rgt7bzirw0b",
    port = "3306"
)

#Connect InfinityFree FTP 
ftp = ftplib.FTP("ftpupload.net", "epiz_33897000", "Eb2Zpg63lM")

#Set InfinityFree upload directory
if camera.lower() == "entry":
    ftp.cwd('/htdocs/Naim Vision/ANPR/images/entrylog')
elif camera.lower() == "exit":
    ftp.cwd('/htdocs/Naim Vision/ANPR/images/exitlog')
    
ftp_img = ""
ftp_img_2 = ""
file = False

# Detection Function (Vehicle Detection, Licence Plate Detection and OCR Recognition)
def detect():
    global folder_path, plate, vehicle_id, current_plate, count, found, area, vehicle_detected, licence_detected, number_plate, matched, ftp_img, ftp_img_2, file
    
    ## Vehicle Detection Start Here
    blob = cv2.dnn.blobFromImage(frames, scalefactor = 1/255, size=(640,640), mean=[0,0,0], swapRB=True, crop=False)

    net.setInput(blob)
    detections = net.forward()[0]
    classes_ids = []
    confidences = []
    boxes = []
    rows = detections.shape[0]

    image_width, image_height = frames.shape[1], frames.shape[0]
    x_scale = image_width/640
    y_scale = image_height/640
 
    for i in range(rows):
        row = detections[i]
        confidence = row[4]

        if confidence > 0.5:
            classes_score = row[5:]
            ind = np.argmax(classes_score)
            if classes_score[ind] > 0.3:
                classes_ids.append(ind)
                confidences.append(confidence)
                cx, cy, w, h =  row[:4]
                x1 = int((cx - w/2)*x_scale)
                y1 = int((cy - h/2)*y_scale)
                width = int((w)*x_scale)
                height = int((h)*y_scale)
                box = np.array([x1,y1,width,height])
                boxes.append(box)
    
    indices = cv2.dnn.NMSBoxes(boxes,confidences, 0.5, 0.5)
    
    for i in indices:
        x1, y1, w, h = boxes[i]
        cx = int((x1 + w)/2)
        cy = int((y1 + h)/2)
        label = classes[classes_ids[i]]
        conf = confidences[i]
        if(label == "car" or label == "truck" or label == "bus" or label == "motorbike"):
            text = "Vehicle" + " {:.2f}".format(conf)
            if w >= h:
                result = cv2.pointPolygonTest(np.array(area, np.int32), (int(cx), int(cy)), False)
                if result >= 0:
                    area = [(0,0),(0,0),(0,0),(0,0)]
                    vehicle_detected = "Yes" 
                    v = frames[y1:y1 + h, x1:x1+w+5]
                    if (x1>0 and y1>0 and h>0 and w>0):
                        # Save real-time vehicle image without bounding box 
                        if camera.lower() == "entry":
                            save_img('Naim Vision\ANPR\entrylogtemp\saved3.jpg', cv2.cvtColor(v, cv2.COLOR_BGR2RGB))
                        elif camera.lower() == "exit":
                            save_img('Naim Vision\ANPR\exitlogtemp\saved3.jpg', cv2.cvtColor(v, cv2.COLOR_BGR2RGB))
                        #area = [(170,182),(930,194),(936,590),(9,530)]
                        area = [(937,585),(920,150),(180,150),(10,530)]
                    else:
                        #area = [(170,182),(930,194),(936,590),(9,530)]
                        area = [(937,585),(920,150),(180,150),(10,530)]
                    cv2.rectangle(frames,(x1,y1), (x1+w+5, y1+h), (51,51,255), 2)
                    cv2.rectangle(frames, (x1, y1 - 40), (x1 + w + 5, y1), (51,51,255), -2)
                    cv2.putText(frames, text, (x1, y1-10), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)
                    if (x1>0 and y1>0 and h>0 and w>0):
                        # Save real-time vehicle image with bounding box 
                        if camera.lower() == "entry":
                            save_img('Naim Vision\ANPR\entrylogtemp\saved.jpg', cv2.cvtColor(v, cv2.COLOR_BGR2RGB))
                        elif camera.lower() == "exit":
                            save_img('Naim Vision\ANPR\exitlogtemp\saved.jpg', cv2.cvtColor(v, cv2.COLOR_BGR2RGB))
                        #area = [(170,182),(930,194),(936,590),(9,530)]
                        area = [(937,585),(920,150),(180,150),(10,530)] 
                    else:
                        #area = [(170,182),(930,194),(936,590),(9,530)]
                        area = [(937,585),(920,150),(180,150),(10,530)]

                    if camera.lower() == "entry":
                        file_exists = os.path.exists("Naim Vision\ANPR\entrylogtemp\saved.jpg")
                    elif camera.lower() == "exit":
                        file_exists = os.path.exists("Naim Vision\ANPR\exitlogtemp\saved.jpg")
                    
                    ## Licence Plate Detection Start Here
                    if file_exists == True:
                        if camera.lower() == "entry":
                            vehicle_img = cv2.imread('Naim Vision\ANPR\entrylogtemp\saved.jpg')
                            vehicle_img_2 = cv2.imread('Naim Vision\ANPR\entrylogtemp\saved.jpg')
                        elif camera.lower() == "exit":
                            vehicle_img = cv2.imread('Naim Vision\ANPR\exitlogtemp\saved.jpg')
                            vehicle_img_2 = cv2.imread('Naim Vision\ANPR\exitlogtemp\saved.jpg')
                        blob_2 = cv2.dnn.blobFromImage(vehicle_img,scalefactor= 1/255,size=(640,640),mean=[0,0,0],swapRB= True, crop= False)
                        net_2.setInput(blob_2)
                        detections_2 = net_2.forward()[0]

                        classes_ids_2 = []
                        confidences_2 = []
                        boxes_2 = []
                        rows_2 = detections_2.shape[0]

                        img_width_2, img_height_2 = vehicle_img.shape[1], vehicle_img.shape[0]
                        x_scale_2 = img_width_2/640
                        y_scale_2 = img_height_2/640

                        for i in range(rows_2):
                            row_2 = detections_2[i]
                            confidence_2 = row_2[4]
                            if confidence_2 > 0.5:
                                classes_score_2 = row_2[5:]
                                ind_2 = np.argmax(classes_score_2)
                                if classes_score_2[ind_2] > 0.5:
                                    classes_ids_2.append(ind_2)
                                    confidences_2.append(confidence_2)
                                    cx_2, cy_2, w_2, h_2 = row_2[:4]
                                    x1_2 = int((cx_2- w_2/2)*x_scale_2)
                                    y1_2 = int((cy_2-h_2/2)*y_scale_2)
                                    width_2 = int(w_2 * x_scale_2)
                                    height_2 = int(h_2 * y_scale_2)
                                    box_2 = np.array([x1_2,y1_2,width_2,height_2])
                                    boxes_2.append(box_2)

                        indices_2 = cv2.dnn.NMSBoxes(boxes_2,confidences_2,0.5,0.5)

                        for i in indices_2:
                            x2,y2,w2,h2 = boxes_2[i]
                            label_2 = classes_2[0]
                            conf_2 = confidences_2[i]
                            text_2 = " " + label_2 + " {:.2f}".format(conf_2)
                            plate = vehicle_img[y2:y2+h2, x2:x2+w2]
                            licence_detected = "Yes" 
                            # Save real-time licence plate image without bounding box 
                            if camera.lower() == "entry":
                                save_img('Naim Vision\ANPR\entrylogtemp\saved4.jpg', cv2.cvtColor(plate, cv2.COLOR_BGR2RGB))
                            elif camera.lower() == "exit":
                                save_img('Naim Vision\ANPR\exitlogtemp\saved4.jpg', cv2.cvtColor(plate, cv2.COLOR_BGR2RGB))
                            cv2.rectangle(vehicle_img,(x2,y2),(x2+w2, y2+h2) ,(51 ,51,255),2)
                            cv2.rectangle(vehicle_img, (x2, y2 - 30), (x2 + w2, y2), (51,51,255), -2)
                            cv2.putText(vehicle_img, text_2, (x2+5, y2 - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 255, 255), 1)
                            # Save real-time licence plate image with bounding box 
                            if camera.lower() == "entry":
                                save_img('Naim Vision\ANPR\entrylogtemp\saved2.jpg', cv2.cvtColor(plate, cv2.COLOR_BGR2RGB))
                                plate_img = 'Naim Vision\ANPR\entrylogtemp\saved2.jpg'
                            elif camera.lower() == "exit":
                                save_img('Naim Vision\ANPR\exitlogtemp\saved2.jpg', cv2.cvtColor(plate, cv2.COLOR_BGR2RGB))
                                plate_img = 'Naim Vision\ANPR\exitlogtemp\saved2.jpg'
                            
                            ## OCR Recognition Start Here
                            file_exists = os.path.exists(plate_img)
                            if file_exists == True:
                                try:
                                    result = ocr.ocr(plate_img, cls=True)
                                    plate_num = ''
                                    uid = uuid.uuid1()
                                    uid_2 = uuid.uuid1()
                                    for line in result:
                                        if(len(line) == 1):
                                            plate = line[0][1][0]
                                        elif(len(line) == 2):
                                            plate = line[0][1][0] + line[1][1][0]
                                        
                                        plate = plate.upper() 
                                        for y in plate:
                                            a = ['1','2','3','4','5','6','7','8','9','0','A','B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','N', 'M','O','P','Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a','b','c','d','e','f','g','h','i','j','k','l','n','m','o','p','q','r','s','t','u','v','w','x','y','z']
                                            for x in a:
                                                if(y == x):
                                                    plate_num += x

                                        plate = plate_num
                                        date = datetime.datetime.now()
                                        if camera.lower() == "entry":
                                            img_name = 'entrylog\{}.jpg'.format(uid)
                                            img_name_2 = 'entrylog\{}_2.jpg'.format(uid_2)
                                        elif camera.lower() == "exit":
                                            img_name = 'exitlog\{}.jpg'.format(uid)
                                            img_name_2 = 'exitlog\{}_2.jpg'.format(uid_2)
                                        active = True
                                        
                                        # Check if the plate starts with "O" and "0" and result is not found, then replace to Q.
                                        if plate.startswith("O") or plate.startswith("0") and matched == "Not Found":
                                            plate = "Q" + plate[1:]
                                            
                                        print("Number plate is:", plate)

                                        mycursor = conn.cursor()
                                        sql = "SELECT vehicleID FROM vehicle WHERE licensePlate = %s AND isActive = %s"
                                        z = (plate, active)
                                        mycursor.execute(sql, z)
                                        myresult = mycursor.fetchone()
                                        if(myresult != None):
                                            vehicle_id = myresult[0]
                                            if current_plate != plate:
                                                if camera.lower() == "entry":
                                                    sql4 = "SELECT vehicleID, referenceID FROM entrylog ORDER BY referenceID DESC LIMIT 1"
                                                elif camera.lower() == "exit":
                                                    sql4 = "SELECT vehicleID, referenceID FROM exitlog ORDER BY referenceID DESC LIMIT 1"
                                                mycursor.execute(sql4)
                                                myresult = mycursor.fetchone()
                                                if(myresult != None):
                                                    vID = myresult[0]
                                                    if(vID == vehicle_id):
                                                        if camera.lower() == "entry":
                                                            sql2 = "UPDATE entrylog SET entryTime = %s WHERE referenceID = %s"
                                                        elif camera.lower() == "exit":
                                                            sql2 = "UPDATE exitlog SET exitTime = %s WHERE referenceID = %s"
                                                        rID = myresult[1]
                                                        z = (date, rID)
                                                        mycursor.execute(sql2, z)
                                                        conn.commit()
                                                        file = False
                                                        count = 0
                                                    else:
                                                        if camera.lower() == "entry":
                                                            sql2 = "INSERT INTO entrylog (vehicleID, entryTime, image, image_2) VALUES (%s, %s, %s, %s)"
                                                        elif camera.lower() == "exit":
                                                            sql2 = "INSERT INTO exitlog (vehicleID, exitTime, image, image_2) VALUES (%s, %s, %s, %s)"
                                                        val = (vehicle_id, date, img_name, img_name_2)
                                                        mycursor.execute(sql2, val)
                                                        conn.commit()
                                                        #Save image with Licence Plate detection box
                                                        cv2.imwrite(os.path.join(folder_path, img_name), vehicle_img)
                                                        # Save image without Licence Plate detection box
                                                        cv2.imwrite(os.path.join(folder_path, img_name_2), vehicle_img_2)
                                                        file = True
                                                        current_plate = plate
                                                        count = 0
                                                else:
                                                    if camera.lower() == "entry":
                                                        sql2 = "INSERT INTO entrylog (vehicleID, entryTime, image, image_2) VALUES (%s, %s, %s, %s)"
                                                    elif camera.lower() == "exit":
                                                        sql2 = "INSERT INTO exitlog (vehicleID, exitTime, image, image_2) VALUES (%s, %s, %s, %s)"
                                                    val = (vehicle_id, date, img_name, img_name_2)
                                                    mycursor.execute(sql2, val)
                                                    conn.commit()
                                                    #Save image with Licence Plate detection box
                                                    cv2.imwrite(os.path.join(folder_path, img_name), vehicle_img)
                                                    # Save image without Licence Plate detection box
                                                    cv2.imwrite(os.path.join(folder_path, img_name_2), vehicle_img_2)
                                                    file = True
                                                    current_plate = plate
                                                    count = 0
                                                found = True
                                                number_plate = plate
                                                matched = "Found"
                                                print("Found")
                                                ftp_img = img_name
                                                ftp_img_2 = img_name_2
                                        else:
                                            if count == 4 and current_plate != plate:
                                                sql3 = "INSERT INTO deniedAccess (licensePlate, deniedTime, image, image_2) VALUES (%s, %s, %s, %s)"
                                                val = (plate, date, img_name, img_name_2)
                                                mycursor.execute(sql3, val)
                                                conn.commit()
                                                # Save image with Licence Plate detection box
                                                cv2.imwrite(os.path.join(folder_path, img_name), vehicle_img)
                                                # Save image without Licence Plate detection box
                                                cv2.imwrite(os.path.join(folder_path, img_name_2), vehicle_img_2)
                                                file = True
                                                current_plate = plate
                                                count = 0
                                            else: 
                                                count += 1
                                                file = False
                                            found = False
                                            number_plate = plate
                                            matched = "Not Found"
                                            print("Not Found")
                                            ftp_img = img_name
                                            ftp_img_2 = img_name_2
                                            if count > 4:
                                                count = 0 
                                                file = False

                                        
                                except:
                                    continue
 
                else:
                    licence_detected = "No"
                    vehicle_detected = "No" 
    
    # Green line box
    cv2.polylines(frames, [np.array(area, np.int32)], True, (15, 220, 10), 6)
                                        
    return frames

# Close Result Menu
ot = False
mx = 0
my = 0
# Mouse Pointer Function
def mousePoints(e,x,y,f,p):
    global mx, my
    if e == cv2.EVENT_LBUTTONDOWN:
        mx = x
        my = y

# Create and store current image path
same_files = ["",""]

while True:
    ret, frames = cap.read()
    
    # Break the loop if we have reached the end of the video
    if not ret:
        break

    # Increment the counter for skipped frames
    skipped_frames += 1
    
    # Display the frame if we have not skipped it
    if skipped_frames % (skip_frames + 1) == 0:
        
        # Initialise window screen
        screen_width = 1000
        screen_height = 600
    
        frames = cv2.resize(frames, (screen_width,screen_height))
        
        # Vehicle Detection, licence Plate Detection and OCR Recognition
        frames = detect()
        
        if (matched == "Found" or matched == "Not Found") and file == True:
            # Current image path is not same as previous image path
            if same_files[0] != ftp_img and  same_files[1] != ftp_img_2:
                #Open files and upload files to InfinityFree
                dir_files = [ftp_img, ftp_img_2]
                # Store current image file path
                same_files = [ftp_img, ftp_img_2]
                for dir_file in dir_files:
                    with open(folder_path+"\\"+dir_file, "rb") as f:
                        print("Upload image to InfinityFree")
                        command = "STOR " + os.path.basename(dir_file)
                        ftp.storbinary(command, f)

        # Show FPS Count
        cv2.putText(frames, "FPS: " + str(round(target_fps)), (800, 35), cv2.QT_FONT_NORMAL, 0.8, (58, 245, 255), 2)
        
        # Show Result Menu
        sbx = 20 
        sby = screen_height - 40
        sbw = 30
        sbh = 30
        cv2.rectangle(frames, (sbx-12, sby-5), (sbx+sbw, sby+sbh), (51,51,255), -2)
        fontpath = "arialbd.ttf"
        font = ImageFont.truetype(fontpath, 30)
        img_pil = Image.fromarray(frames)
        draw = ImageDraw.Draw(img_pil)
        draw.text((sbx, sby),  "^", font = font, fill = (255, 255, 255))
        frames = np.array(img_pil)
        cv2.imshow('Automatic number-plate recognition (ANPR) System', frames)
        cv2.setMouseCallback('Automatic number-plate recognition (ANPR) System',mousePoints)
        
        sx = 18
        sy = screen_height - 150
        sw = 335
        sh = 140
        sv = 40
        
        if mx > 325 and mx < 366 and my > 430 and my < 441 and ot == True:
            ot = False
        elif mx > sbx-10 and mx < sbx+sbw and my > sby-30 and my < sby+sbh or ot == True:
            cv2.rectangle(frames, (sx-10, sy-30), (sx+sw, sy+sh), (51,51,255), -2)
            cv2.putText(frames, "x", (sw-10, sy-10), cv2.FONT_HERSHEY_SIMPLEX, 0.8, (255, 255, 255), 4)
            cv2.putText(frames, f"Vehicle Detected: {vehicle_detected}", (sx, sy), cv2.FONT_HERSHEY_SIMPLEX, 0.6, (255, 255, 255), 2)
            cv2.putText(frames, f"Licence Plate Detected: {licence_detected}", (sx, sy+(sv*1)), cv2.FONT_HERSHEY_SIMPLEX, 0.6, (255, 255, 255), 2)
            cv2.putText(frames, f"Licence Plate Number: {number_plate}", (sx, sy+(sv*2)), cv2.FONT_HERSHEY_SIMPLEX, 0.6, (255, 255, 255), 2)
            cv2.putText(frames, f"Matched: {matched}", (sx, sy+(sv*3)), cv2.FONT_HERSHEY_SIMPLEX, 0.6, (255, 255, 255), 2)
            ot = True
            
        cv2.imshow('Automatic number-plate recognition (ANPR) System', frames)
        
        k = cv2.waitKey(1) 
        if k == 27:
            break
        
    

cap.release()
cv2.destroyAllWindows()
