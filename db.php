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
        $sql = "SELECT pos_face_no, img_name, file_path FROM videoface WHERE video_name = '$name' AND video_position = '$video_position';";
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

    function get_image(){
        $jsonarray = array();
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
    
    function get_camrea($id){
        $jsonarray = array();
        $sql = "SELECT * FROM `camrea` WHERE `id` = '$id';";
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
            $camrea = $this->get_camrea($row['recordBy']);
            $row['recordBy'] = $camrea[0]['model'];
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
}
?>