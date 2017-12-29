<?php  
/* PHP读取EXCEL文件(包括资源型的图片信息)  */
header('content-type:text/html;charset=utf-8');  
ini_set("display_errors",1);    
     
include 'Classes/PHPExcel.php';    
include 'Classes/PHPExcel/IOFactory.php';    
     
define('EXCEL_EXTENSION_2003', "xls");    
define('EXCEL_EXTENSION_2007', "xlsx");    
  
$fileName2003   = $_FILES['file']['name'];  
$filePath       = $_FILES['file']['tmp_name'];  
  
     
$fileName = $fileName2003;    
     
if(getExtendFileName($fileName) == EXCEL_EXTENSION_2003)    
{    
    $reader = PHPExcel_IOFactory::createReader('Excel5');    
}    
else if(getExtendFileName($fileName) == EXCEL_EXTENSION_2007)    
{    
    $reader = new PHPExcel_Reader_Excel2007();    
}    
  
function getExtendFileName($file_name) {    
     
    $extend = pathinfo($file_name);    
    $extend = strtolower($extend["extension"]);    
    return $extend;    
}    
     
function extractImageFromWorksheet($worksheet,$basePath){    
     
    $result = array();    
      
    $imageFileName = "";    
     
    foreach ($worksheet->getDrawingCollection() as $drawing) {    
        $xy=$drawing->getCoordinates();    
        $path = $basePath;    
        // for xlsx    
        if ($drawing instanceof PHPExcel_Worksheet_Drawing) {    
     
            $filename = $drawing->getPath();    
     
            $imageFileName = $drawing->getIndexedFilename();    
                     
            $path = $path . $drawing->getIndexedFilename();    
     
            copy($filename, $path);    
     
            $result[$xy] = $path;    
     
          // for xls
        } else if ($drawing instanceof PHPExcel_Worksheet_MemoryDrawing) {    
     
            $image = $drawing->getImageResource();    
     
            $renderingFunction = $drawing->getRenderingFunction();    
     
            switch ($renderingFunction) {    
     
                case PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG:    
                             
                    $imageFileName = $drawing->getIndexedFilename();    
                    $path = $path . $drawing->getIndexedFilename();    
                    //imagejpeg($image, $path);    
                    break;    
     
                case PHPExcel_Worksheet_MemoryDrawing::RENDERING_GIF:    
                    $imageFileName = $drawing->getIndexedFilename();    
                    $path = $path . $drawing->getIndexedFilename();    
                    //imagegif($image, $path);    
                    break;    
     
                case PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG:    
                    $imageFileName = $drawing->getIndexedFilename();    
                    $path = $path . $drawing->getIndexedFilename();    
                    //imagegif($image, $path);    
                    break;    
     
                case PHPExcel_Worksheet_MemoryDrawing::RENDERING_DEFAULT:    
                    $imageFileName = $drawing->getIndexedFilename();    
                    $path = $path . $drawing->getIndexedFilename();    
                    //imagegif($image, $path);    
                    break;    
            }    
            $result[$xy] = $imageFileName;    
        }    
    }    
    return $result;    
}    
$PHPExcel = $reader->load($filePath); //载入excel文件   
$worksheet = $PHPExcel->getActiveSheet(); //返回一个对象  
$imageInfo = extractImageFromWorksheet($worksheet,"");//获得所有的资源型图片信息    
$data_array = $PHPExcel->getSheet()->toArray();//获取所有数据  
