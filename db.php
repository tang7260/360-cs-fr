<?php
class db{
    
    private $conn;
    
    function __construct() {
        // connecting to database
        $this->conn = $this->connect();
    }
    
    public function connect() {
        require_once 'db_config.php';
        // connecting to mysql
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_DATABASE);

        // return database handler
        return $conn;
    }
    
    function close() {
        $this->conn->close();
    }
    
    function get_image_info($name){
        $jsonarray = array();
        $sql = "SELECT * FROM photo WHERE fileName = '$name';";
        $result = mysqli_query($this->conn, $sql) 
            or die("Error in Selecting " . mysqli_error($this->conn));
        
        while($row =mysqli_fetch_assoc($result)){
            $camera = $this->get_camera($row['recordBy']);
            $row['recordBy'] = $camera[0]['model'];
            $jsonarray[] = $row;
        }
        return $jsonarray;
    }
    
    function get_image_face($name) {
        $jsonarray = array();
        $sql = "SELECT * FROM photoface WHERE photo_name = '$name';";
        $result = mysqli_query($this->conn, $sql) 
            or die("Error in Selecting " . mysqli_error($this->conn));

        while($row =mysqli_fetch_assoc($result)){
            $jsonarray[] = $row;
        }
        return $jsonarray;
    }
    
    function get_image_result($name){
        $jsonarray = array();
        $jsonarray['result_data'] = $this->get_image_face($name);
        $jsonarray['file_info'] = $this->get_image_info($name);
        
        return $jsonarray;
    }
    
    
    function get_video_lpos($name,$video_position) {
        $jsonarray = array();
        if($video_position==0){
            $video_position=1;
        }
        $sql = "SELECT * FROM videoface WHERE video_name = '$name' AND video_position <= '$video_position';";
        $result = mysqli_query($this->conn, $sql) 
            or die("Error in Selecting " . mysqli_error($this->conn));

        while($row =mysqli_fetch_assoc($result)){
            $jsonarray[] = $row;
        }
        return $jsonarray;
    }
    
    function get_video_apos($name,$video_position) {
        $jsonarray = array();
        if($video_position==0){
            $video_position=1;
        }
        $sql = "SELECT * FROM videoface WHERE video_name = '$name' AND video_position = '$video_position';";
        $result = mysqli_query($this->conn, $sql) 
            or die("Error in Selecting " . mysqli_error($this->conn));

        while($row =mysqli_fetch_assoc($result)){
            $jsonarray[] = $row;
        }
        return $jsonarray;
    }
    
    function get_video_face_pos($name,$video_position) {
        $jsonarray = array();
        if($video_position==0){
            $video_position=1;
        }
        $sql = "SELECT pos_face_no, name, percentage, faceStrID, confidence, img_name, file_path FROM videoface WHERE video_name = '$name' AND video_position = '$video_position';";
        $result = mysqli_query($this->conn, $sql) 
            or die("Error in Selecting " . mysqli_error($this->conn));

        while($row =mysqli_fetch_assoc($result)){
            $jsonarray[] = $row;
        }
        return $jsonarray;
    }
    
    function get_video($name) {
        $jsonarray = array();
        $sql = "SELECT * FROM videoface WHERE video_name = '$name';";
        $result = mysqli_query($this->conn, $sql) 
            or die("Error in Selecting " . mysqli_error($this->conn));

        while($row =mysqli_fetch_assoc($result)){
            $jsonarray[] = $row;
        }
        return $jsonarray;
    }
    
    function get_video_charts($name){
        $jsonarray = array();
        $sql = "SELECT `video_name`,`video_position`, MAX(`pos_face_no`) AS total_face FROM videoface WHERE `video_name` = '$name' GROUP BY `video_position`;";
        $result = mysqli_query($this->conn, $sql) 
            or die("Error in Selecting " . mysqli_error($this->conn));

        while($row =mysqli_fetch_assoc($result)){
            $position = $row['video_position'];
            if($position / 60 > 1 ){
                $mins = (int)($position / 60);
                $seconds = $position - $mins * 60;
                if($seconds < 10){
                    $row['video_position_str'] = $mins. ":0" . $seconds;
                }else{
                    $row['video_position_str'] = $mins. ":" . $seconds;
                }
            }else{
                $row['video_position_str'] = $position .'s';
            }
            $jsonarray[] = $row;
        }
        return $jsonarray;
    }
    
    function get_charts_faceimg($name){
        $jsonarray = array();
        $sql = "SELECT `video_name`,`video_position`, MAX(`pos_face_no`) AS total_face FROM videoface WHERE `video_name` = '$name' GROUP BY `video_position`;";
        $result = mysqli_query($this->conn, $sql) 
            or die("Error in Selecting " . mysqli_error($this->conn));

        while($row =mysqli_fetch_assoc($result)){
            $position = $row['video_position'];
            if($position / 60 > 1 ){
                $mins = (int)($position / 60);
                $seconds = $position - $mins * 60;
                if($seconds < 10){
                    $row['video_position_str'] = $mins. ":0" . $seconds;
                }else{
                    $row['video_position_str'] = $mins. ":" . $seconds;
                }
            }else{
                $row['video_position_str'] = $position .'s';
            }
            $row['faceimg'] = $this->get_video_face_pos($name,$position);
            $jsonarray[] = $row;
        }
        return $jsonarray;
    }
    
    function get_camera($id){
        $jsonarray = array();
        $sql = "SELECT * FROM `camera` WHERE `id` = '$id';";
        $result = mysqli_query($this->conn, $sql) 
            or die("Error in Selecting " . mysqli_error($this->conn));

        while($row =mysqli_fetch_assoc($result)){
            $jsonarray[] = $row;
        }
        return $jsonarray;
    }
    
    function get_video_info($name){
        $jsonarray = array();
        $sql = "SELECT * FROM `video` WHERE `fileName` = '$name';";
        $result = mysqli_query($this->conn, $sql) 
            or die("Error in Selecting " . mysqli_error($this->conn));

        while($row =mysqli_fetch_assoc($result)){
            $camera = $this->get_camera($row['recordBy']);
            $row['recordBy'] = $camera[0]['model'];
            $jsonarray[] = $row;
        }
        return $jsonarray;
    }
    
    function get_video_result($name){
        $jsonarray = array();
        $jsonarray['result_data'] = $this->get_charts_faceimg($name);
        $jsonarray['file_info'] = $this->get_video_info($name);
        
        return $jsonarray;
    }
    
    function insert_photo($fileName, $recordDate, $fileSize, $width, $height){
        mysqli_query($this->conn,"INSERT INTO photo (recordBy, fileName, recordDate, fileSize, fileFormat, width, height) 
        VALUES ('1', '$fileName', '$recordDate', '$fileSize', 'jpg', '$width', '$height')");
    }
    
    
    function get_video_report_list(){
        $json_array = array();
        $sql = "SELECT * FROM `video` 
                  WHERE `totalFace` != -1;";
        $result = mysqli_query($this->conn, $sql)
        or die("Error in Selecting " . mysqli_error($this->conn));
        while ($row = mysqli_fetch_assoc($result)) {
            $json_array[] = $row;
        }
        return $json_array;
    }
}
?>