"""
This paddle ocr python files is detecting vehicle, 
detecting licence plate, recognising number plate 
and recording into exitlog database. Temporary exitlog 
images store exitlogtemp folder. Permanent exitlog 
images store exitlog folder inside the images folder.

"""
import cv2
import numpy as np
from matplotlib import pyplot as plt
from keras.preprocessing.image import save_img
from PIL import ImageFont, ImageDraw, Image
#from tensorflow.keras.utils import save_img
import os.path

from paddleocr import PaddleOCR,draw_ocr
ocr = PaddleOCR(use_angle_cls=True, lang="en")

import mysql.connector
import uuid
import datetime

cap = cv2.VideoCapture("ANPR\WhatsApp Video 2022-11-09 at 14.39.52.mp4")
#cap = cv2.VideoCapture("rtsp://admin:Matrix40001@192.168.1.60:554/Streaming/Channels/2/")
#cap = cv2.VideoCapture(1)
#cap = cv2.VideoCapture()
#cap.open("rtsp://admin:Matrix40001@192.168.1.60:554/Streaming/Channels/2/")

# Get the video frame count
frame_count = int(cap.get(cv2.CAP_PROP_FRAME_COUNT))

# Set the frame rate to 60 frames per second
fps = 60

# Get the video frame count
#fps = int(cap.get(cv2.CAP_PROP_FPS))

# Set the wait time between frames in milliseconds
wait_time = int(1000 / fps)

# Skip 20 frames per second
skip_frames = 20

# Counter for skipped frames
skipped_frames = 0

net = cv2.dnn.readNetFromONNX("ANPR\yolov5n.onnx")

net_2 = cv2.dnn.readNetFromONNX("ANPR\yolov5n_2.onnx")

file = open("ANPR\coco.txt", "r")
classes = file.read().split('\n')
#print(classes)

file_2 = open("ANPR\coco2.txt","r")
classes_2 = file_2.read().split('\n')
#print(classes_2)

#Image folder location
folder_path = "ANPR\images"
plate = ""
vehicle_id = ""
current_plate = ""
count = 0
found = True
area = [(170,182),(930,194),(936,590),(9,530)]
#area = [(550,271),(990,360),(870,620),(1,502)] 

vehicle_detected = '...'
licence_detected = '...'
number_plate = '...' 
matched = '...'

# Detection Function (Vehicle Detection, licence Plate Detection and OCR Recognition)
def detect():
    global folder_path, plate, vehicle_id, current_plate, count, found, area, vehicle_detected, licence_detected, number_plate, matched
    conn = mysql.connector.connect (
        host = "localhost",
        user = "root",
        password = "",
        database = "anprdb"
    )

    blob = cv2.dnn.blobFromImage(frames, scalefactor = 1/255, size=(640,640), mean=[0,0,0], swapRB=True, crop=False)

    net.setInput(blob)
    detections = net.forward()[0]

    #print(detections.shape)

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
            if classes_score[ind] > 0.5:
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
                        save_img('ANPR\exitlogtemp\saved3.jpg', cv2.cvtColor(v, cv2.COLOR_BGR2RGB))
                        area = [(170,182),(930,194),(936,590),(9,530)] 
                    else:
                        area = [(170,182),(930,194),(936,590),(9,530)]
                    cv2.rectangle(frames,(x1,y1), (x1+w+5, y1+h), (51,51,255), 2)
                    cv2.rectangle(frames, (x1, y1 - 40), (x1 + w + 5, y1), (51,51,255), -2)
                    cv2.putText(frames, text, (x1, y1-10), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)
                    if (x1>0 and y1>0 and h>0 and w>0):
                        # Save real-time vehicle image with bounding box 
                        save_img('ANPR\exitlogtemp\saved.jpg', cv2.cvtColor(v, cv2.COLOR_BGR2RGB))
                        area = [(170,182),(930,194),(936,590),(9,530)] 
                    else:
                        area = [(170,182),(930,194),(936,590),(9,530)]

                    file_exists = os.path.exists("ANPR\exitlogtemp\saved.jpg")
                    
                    if file_exists == True:
                        vehicle_img = cv2.imread('ANPR\exitlogtemp\saved.jpg')
                        vehicle_img_2 = cv2.imread('ANPR\exitlogtemp\saved.jpg')
                        blob_2 = cv2.dnn.blobFromImage(vehicle_img,scalefactor= 1/255,size=(640,640),mean=[0,0,0],swapRB= True, crop= False)
                        net_2.setInput(blob_2)
                        detections_2 = net_2.forward()[0]

                        classes_ids_2 = []
                        confidences_2 = []
                        boxes_2 = []
                        rows_2 = detections_2.shape[0]
                
                        #print(detections.shape)

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
                            save_img('ANPR\exitlogtemp\saved4.jpg', cv2.cvtColor(plate, cv2.COLOR_BGR2RGB))
                            cv2.rectangle(vehicle_img,(x2,y2),(x2+w2, y2+h2) ,(51 ,51,255),2)
                            cv2.rectangle(vehicle_img, (x2, y2 - 30), (x2 + w2, y2), (51,51,255), -2)
                            cv2.putText(vehicle_img, text_2, (x2+5, y2 - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 255, 255), 1)
                            # cv2.imshow('Licence Plate', plate)
                            # Save real-time licence plate image with bounding box 
                            save_img('ANPR\exitlogtemp\saved2.jpg', cv2.cvtColor(plate, cv2.COLOR_BGR2RGB))
                            plate_img = 'ANPR\exitlogtemp\saved2.jpg'
                
                            file_exists = os.path.exists(plate_img)
                            if file_exists == True:
                                result = ocr.ocr(plate_img, cls=True)
                                plate_num = ''
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
                                    print("Number plate is:", plate_num)

                                    plate = plate_num
                                    date = datetime.datetime.now()
                                    img_name = 'exitlog\{}.jpg'.format(uuid.uuid1())
                                    img_name_2 = 'exitlog\{}_2.jpg'.format(uuid.uuid1())
                                    active = True

                                    mycursor = conn.cursor()
                                    sql = "SELECT vehicleID FROM vehicle WHERE licensePlate = %s AND isActive = %s"
                                    z = (plate, active)
                                    mycursor.execute(sql, z)
                                    myresult = mycursor.fetchone()
                                    if(myresult != None):
                                        vehicle_id = myresult[0]
                                        if current_plate != plate:
                                            sql4 = "SELECT vehicleID, referenceID FROM exitLog ORDER BY referenceID DESC LIMIT 1"
                                            mycursor.execute(sql4)
                                            myresult = mycursor.fetchone()
                                            if(myresult != None):
                                                vID = myresult[0]
                                                if(vID == vehicle_id):
                                                    sql2 = "UPDATE exitLog SET exitTime = %s WHERE referenceID = %s"
                                                    rID = myresult[1]
                                                    z = (date, rID)
                                                    mycursor.execute(sql2, z)
                                                    conn.commit()
                                                    count = 0
                                                else:
                                                    sql2 = "INSERT INTO exitLog (vehicleID, exitTime, image, image_2) VALUES (%s, %s, %s, %s)"
                                                    val = (vehicle_id, date, img_name, img_name_2)
                                                    mycursor.execute(sql2, val)
                                                    conn.commit()
                                                    #Save image with Licence Plate detection box
                                                    cv2.imwrite(os.path.join(folder_path, img_name), vehicle_img)
                                                    # Save image without Licence Plate detection box
                                                    cv2.imwrite(os.path.join(folder_path, img_name_2), vehicle_img_2)
                                                    current_plate = plate
                                                    count = 0
                                            else:
                                                sql2 = "INSERT INTO exitLog (vehicleID, exitTime, image, image_2) VALUES (%s, %s, %s, %s)"
                                                val = (vehicle_id, date, img_name, img_name_2)
                                                mycursor.execute(sql2, val)
                                                conn.commit()
                                                #Save image with Licence Plate detection box
                                                cv2.imwrite(os.path.join(folder_path, img_name), vehicle_img)
                                                # Save image without Licence Plate detection box
                                                cv2.imwrite(os.path.join(folder_path, img_name_2), vehicle_img_2)
                                                current_plate = plate
                                                count = 0
                                            found = True
                                            number_plate = plate
                                            matched = "Found"
                                            print("Found")
                                    else:
                                        if (count == 4) & (current_plate != plate):
                                            sql3 = "INSERT INTO deniedAccess (licensePlate, deniedTime, image, image_2) VALUES (%s, %s, %s, %s)"
                                            val = (plate, date, img_name, img_name_2)
                                            mycursor.execute(sql3, val)
                                            conn.commit()
                                            # Save image with Licence Plate detection box
                                            cv2.imwrite(os.path.join(folder_path, img_name), vehicle_img)
                                            # Save image without Licence Plate detection box
                                            cv2.imwrite(os.path.join(folder_path, img_name_2), vehicle_img_2)
                                            found = False
                                            number_plate = plate
                                            matched = "Not Found"
                                            print("Not Found")
                                            current_plate = plate
                                            count = 0
                                        else: 
                                            count += 1
                                        if count > 4:
                                            count = 0 


                        # cv2.imshow('Vehicle', vehicle_img)    
                else:
                    licence_detected = "No"
                    vehicle_detected = "No" 
    
    # Green line box
    cv2.polylines(frames, [np.array(area, np.int32)], True, (15, 220, 10), 6)
                                        
    return frames

ot = False # Close Result Menu
mx = 0
my = 0
# Mouse Pointer Function
def mousePoints(e,x,y,f,p):
    global mx, my
    if e == cv2.EVENT_LBUTTONDOWN:
        mx = x
        my = y
        
while True:
    ret, frames = cap.read()
    
    # Break the loop if we have reached the end of the video
    if not ret:
        break

    # Increment the counter for skipped frames
    skipped_frames += 1
    
    # Display the frame if we have not skipped it
    if skipped_frames % (skip_frames + 1) == 0:
        
        screen_width = 1000
        screen_height = 600
    
        frames = cv2.resize(frames, (screen_width,screen_height))
        
        # Vehicle Detection, licence Plate Detection and OCR Recognition
        frames = detect()
        
        # Show FPS Count
        cv2.putText(frames, "FPS: " + str(round(fps)), (800, 35), cv2.QT_FONT_NORMAL, 0.8, (58, 245, 255), 2)
        
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
        #cv2.putText(frames, "^", (sbx, sby), cv2.FONT_HERSHEY_COMPLEX_SMALL, 1.6, (255, 255, 255), 2)
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