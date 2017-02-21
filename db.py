# -*- coding: UTF-8 -*-
import numpy
import json
import mysql.connector


class Database:
    cnx = None

    def __init__(self, host, username, password, database):
        self.cnx = mysql.connector.connect(user=username, password=password,
                                           host=host,
                                           database=database)
        self.cursor = self.cnx.cursor()

    def db_version(self):
        self.cursor.execute("SELECT VERSION()")
        _data = self.cursor.fetchone()
        print "Database version : %s " % _data

    def insert_video_face(self, data_video_face):
        add_video_face = ("INSERT INTO videoface "
                          "(video_position, pos_face_no,file_name, file_path, videoID, faceID, face_x, face_y, face_w, "
                          "face_h, width, height) "
                          "VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)")
        self.cursor.execute(add_video_face, data_video_face)
        self.cnx.commit()
        print ("Insert OK")

    def insert_photo_face(self, data_video_face):
        add_video_face = ("INSERT INTO photoface "
                          "(photoID, faceID, photo_name, pos_face_no, img_name, file_path, face_x, face_y, "
                          "width, height) "
                          "VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)")
        self.cursor.execute(add_video_face, data_video_face)
        self.cnx.commit()
        print ("Insert OK")


# db1 = Database("localhost", "root", "", "360db")
#
# db1.db_version()
#
# data = (1, 'demo1.mp4_1_001', '/result/face', 1, None, 150, 150, 300, 300, 125, 200)
#
# db1.insert_video_face(data)
