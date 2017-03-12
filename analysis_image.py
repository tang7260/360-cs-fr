import io
import cv2
import numpy as np
import sys
import urllib2
import db


def init_db():
    return db.Database('localhost', 'root', '', '360db')


# Load a cascade file for detecting faces
face_cascade = cv2.CascadeClassifier('/Users/fai/opencv/data/haarcascades/haarcascade_frontalface_alt.xml')

headers = {'Connection': 'keep-alive',
           'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
           'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
           'Cookie': 'stream_mode=MJPEG-Stream',
           'DNT': '1',
           }

if len(sys.argv) > 2:
    db_360 = init_db()
    host = 'http://' + sys.argv[1] + '/get_media.php?type=image&file=' + sys.argv[2]
    src_file = sys.argv[2]
    reqHeaders = urllib2.Request(host, headers=headers)
    req = urllib2.urlopen(reqHeaders)
    arr = np.asarray(bytearray(req.read()), dtype=np.uint8)
    img = cv2.imdecode(arr, cv2.IMREAD_COLOR)
    imgHeight, imgWidth, imgChannels = img.shape
    # Convert to grayscale
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

    # Look for faces in the image using the loaded cascade file
    faces = face_cascade.detectMultiScale(gray, 1.1, 4)

    f = 0

    # Draw a rectangle around every found face
    for (x, y, w, h) in faces:
        f += 1
        face = img[y:y + h, x:x + w].copy()
        face_img_name = src_file + '_f' + str(f) + '.jpg'
        faceHeight, faceWidth, faceChannels = face.shape
        cv2.rectangle(img, (x, y), (x + w, y + h), (255, 255, 0), 2)
        cv2.putText(img, "Face " + str(f), (x, (y - 10)), cv2.FONT_HERSHEY_SIMPLEX, 0.68,
                    (255, 255, 0), 1, cv2.LINE_AA)
        cv2.imwrite('/Applications/XAMPP/xamppfiles/htdocs/360m/media/result/faces/' + face_img_name, face,
                    [int(cv2.IMWRITE_JPEG_QUALITY), 95])
        data = (None, None, str(src_file), str(f), str(face_img_name), 'media/result/faces/', str(x), str(y),
                str(w), str(h))
        db_360.insert_photo_face(data)

        print ("FaceNo " + str(f) + ": [x: " + str(x) + " y: " + str(y) + " w: " +
               str(w) + " h: " + str(h) + " ]")

    if len(faces) > 0:
        cv2.putText(img, "Total Face(s): " + str(len(faces)), ((imgWidth - 300), (imgHeight - 10)),
                    cv2.FONT_HERSHEY_SIMPLEX
                    , 0.9, (255, 255, 255), 2, cv2.LINE_AA)
        print ("Found " + str(len(faces)) + " face(s)")

    # Save the result image
    cv2.imwrite('/Applications/XAMPP/xamppfiles/htdocs/360m/media/result/' + src_file + '_result.jpg', img,
                [int(cv2.IMWRITE_JPEG_QUALITY), 95])
    # cv2.imshow('result.jpg', img)
    # cv2.waitKey(0)

    db_360.cursor.close()
    db_360.cnx.close()

print 'Number of arguments:', len(sys.argv), 'arguments.'
print 'Argument List:', str(sys.argv)
