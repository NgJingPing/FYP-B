import cv2
import numpy as np
from matplotlib import pyplot as plt
from keras.preprocessing.image import save_img
#from tensorflow.keras.utils import save_img
import os.path

from paddleocr import PaddleOCR,draw_ocr
ocr = PaddleOCR(use_angle_cls=True, lang="en")

import mysql.connector
import uuid
import datetime

cap = cv2.VideoCapture("ANPR\WhatsApp Video 2022-11-09 at 14.39.54.mp4")
#cap = cv2.VideoCapture("rtsp://admin:Matrix40001@192.168.1.60:554/Streaming/Channels/2/")
#cap = cv2.VideoCapture(0)
#cap = cv2.VideoCapture()
#cap.open("rtsp://admin:Matrix40001@192.168.1.60:554/Streaming/Channels/2/")

net = cv2.dnn.readNetFromONNX("ANPR\yolov5n.onnx")

net_2 = cv2.dnn.readNetFromONNX("ANPR\yolov5n_2.onnx")

file = open("ANPR\coco.txt", "r")
classes = file.read().split('\n')
#print(classes)

file_2 = open("ANPR\coco2.txt","r")
classes_2 = file_2.read().split('\n')
#print(classes_2)

folder_path = "ANPR\images"
plate = ""
vehicle_id = ""
current_plate = ""
count = 0
found = True
area = [(170,182),(930,194),(936,590),(9,530)]
#area = [(550,271),(990,360),(870,620),(1,502)] 

def detect():
    global folder_path, plate, vehicle_id, current_plate, count, found, area
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
                    v = frames[y1:y1 + h, x1:x1+w+5]
                    if (x1>0 and y1>0 and h>0 and w>0):
                        # Save real-time vehicle image without bounding box 
                        save_img('ANPR\saved3.jpg', cv2.cvtColor(v, cv2.COLOR_BGR2RGB))
                        area = [(170,182),(930,194),(936,590),(9,530)] 
                    else:
                        area = [(170,182),(930,194),(936,590),(9,530)]
                    cv2.rectangle(frames,(x1,y1), (x1+w+5, y1+h), (51,51,255), 2)
                    cv2.rectangle(frames, (x1, y1 - 40), (x1 + w + 5, y1), (51,51,255), -2)
                    cv2.putText(frames, text, (x1, y1-10), cv2.FONT_HERSHEY_COMPLEX, 0.5, (255, 255, 255), 1)
                    if (x1>0 and y1>0 and h>0 and w>0):
                        # Save real-time vehicle image with bounding box 
                        save_img('ANPR\saved.jpg', cv2.cvtColor(v, cv2.COLOR_BGR2RGB))
                        area = [(170,182),(930,194),(936,590),(9,530)] 
                    else:
                        area = [(170,182),(930,194),(936,590),(9,530)]

                    file_exists = os.path.exists("ANPR\saved.jpg")
                    
                    if file_exists == True:
                        vehicle_img = cv2.imread('ANPR\saved.jpg')
                        vehicle_img_2 = cv2.imread('ANPR\saved.jpg')
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
                            # Save real-time licence plate image without bounding box 
                            save_img('ANPR\saved4.jpg', cv2.cvtColor(plate, cv2.COLOR_BGR2RGB))
                            cv2.rectangle(vehicle_img,(x2,y2),(x2+w2, y2+h2) ,(51 ,51,255),2)
                            cv2.rectangle(vehicle_img, (x2, y2 - 30), (x2 + w2, y2), (51,51,255), -2)
                            cv2.putText(vehicle_img, text_2, (x2+5, y2 - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 255, 255), 1)
                            cv2.imshow('Licence Plate', plate)
                            # Save real-time licence plate image with bounding box 
                            save_img('ANPR\saved2.jpg', cv2.cvtColor(plate, cv2.COLOR_BGR2RGB))
                            plate_img = 'ANPR\saved2.jpg'
                
                            file_exists = os.path.exists(plate_img)
                            if file_exists == True:
                                result = ocr.ocr(plate_img, cls=True)
                                plate_num = ''
                                for line in result:
                                    if(len(line) == 1):
                                        plate = line[0][1][0]
                                    elif(len(line) == 2):
                                        plate = line[0][1][0] + line[1][1][0]
                                    for y in plate:
                                        a = ['1','2','3','4','5','6','7','8','9','0','A','B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','N', 'M','O','P','Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a','b','c','d','e','f','g','h','i','j','k','l','n','m','o','p','q','r','s','t','u','v','w','x','y','z']
                                        for x in a:
                                            if(y == x):
                                                plate_num += x 
                                    print("Number plate is:", plate_num) 

                                    plate = plate_num
                                    date = datetime.datetime.now()
                                    img_name = '{}.jpg'.format(uuid.uuid1())
                                    img_name_2 = '{}_2.jpg'.format(uuid.uuid1())
                                    active = True

                                    mycursor = conn.cursor()
                                    sql = "SELECT vehicleID FROM vehicle WHERE licensePlate = %s AND isActive = %s"
                                    z = (plate, active)
                                    mycursor.execute(sql, z)
                                    myresult = mycursor.fetchone()
                                    if(myresult != None):
                                        vehicle_id = myresult[0]
                                        if current_plate != plate:
                                            sql4 = "SELECT vehicleID, referenceID FROM entryLog ORDER BY referenceID DESC LIMIT 1"
                                            mycursor.execute(sql4)
                                            myresult = mycursor.fetchone()
                                            if(myresult != None):
                                                vID = myresult[0]
                                                if(vID == vehicle_id):
                                                    sql2 = "UPDATE entryLog SET entryTime = %s WHERE referenceID = %s"
                                                    rID = myresult[1]
                                                    z = (date, rID)
                                                    mycursor.execute(sql2, z)
                                                    conn.commit()
                                                    count = 0
                                                else:
                                                    sql2 = "INSERT INTO entryLog (vehicleID, entryTime, image, image_2) VALUES (%s, %s, %s, %s)"
                                                    val = (vehicle_id, date, img_name, img_name_2)
                                                    mycursor.execute(sql2, val)
                                                    conn.commit()
                                                    #Save image with Licence Plate detection box
                                                    cv2.imwrite(os.path.join(folder_path, img_name), vehicle_img)
                                                    # Save image without Licence Plate detection box
                                                    cv2.imwrite(os.path.join(folder_path, img_name_2), vehicle_img_2)
                                                    found = True
                                                    print("Found")
                                                    current_plate = plate
                                                    count = 0
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
                                            print("Not Found")
                                            current_plate = plate
                                            count = 0
                                        else: 
                                            count += 1
                                        if count > 4:
                                            count = 0 


                        cv2.imshow('Vehicle', vehicle_img)    
    
    # Green line box
    cv2.polylines(frames, [np.array(area, np.int32)], True, (15, 220, 10), 6)
                                        
    return frames

c = 0
while True:
    ret, frames = cap.read()
    if frames is None:
        break

    c += 1
    if c % 5 != 0:
        continue

    frames = cv2.resize(frames, (1000,600))

    frames = detect()
    
    cap.set(cv2.CAP_PROP_BUFFERSIZE, 1)
    cv2.imshow('Automatic number-plate recognition (ANPR) System', frames)
    
    k = cv2.waitKey(1) 
    if k == 27:
        break

cap.release()
cv2.destroyAllWindows()
