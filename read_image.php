<?php
    header('content-type:text/html;charset=utf-8');
    ini_set("display_errors",1);
    //引入PHPExcel类
    include 'PHPExcel/PHPExcel.php';
    include 'PHPExcel/PHPExcel/IOFactory.php';
    //定义常量
    define('EXCEL_EXTENSION_2003', "xls");
    define('EXCEL_EXTENSION_2007', "xlsx");

    $filePath       = 'read_img.xlsx';
    //$filePath       = 'read_img.xls';

    //获得文件的类型(后缀名)
    function getExtendFileName($file_name) {
        $extend = pathinfo($file_name);
        $extend = strtolower($extend["extension"]);
        return $extend;
    }
    // 分excel类别读取
    if(getExtendFileName($filePath) == EXCEL_EXTENSION_2003) {
        $reader = PHPExcel_IOFactory::createReader('Excel5');
    } else if(getExtendFileName($filePath) == EXCEL_EXTENSION_2007) {
        $reader = new PHPExcel_Reader_Excel2007();
    }
    //获得所有资源型图片信息
    function extractImageFromWorksheet($worksheet,$basePath){
        $result = array();
        $imageFileName = "";
        foreach ($worksheet->getDrawingCollection() as $drawing) {
            $xy=$drawing->getCoordinates();
            $path = $basePath;
            // for xlsx
            if ($drawing instanceof PHPExcel_Worksheet_Drawing) {
                $filename = $drawing->getPath(); //原目录
                $imageFileName = $drawing->getIndexedFilename();
                $path = $path . $imageFileName; //保存目录
                copy($filename, $path); // 保存到本地
                $result[$xy] = $imageFileName;  //返回保存路径
            } else if ($drawing instanceof PHPExcel_Worksheet_MemoryDrawing) { // for xls
                $image = $drawing->getImageResource(); //Get image resource
                $renderingFunction = $drawing->getRenderingFunction();
                switch ($renderingFunction) {
                    case PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG: //imagejpeg
                        $imageFileName = $drawing->getIndexedFilename();
                        $path = $path . $imageFileName;
                        imagejpeg($image, $path);// 保存图片
                        break;
                    case PHPExcel_Worksheet_MemoryDrawing::RENDERING_GIF: //imagegif
                        $imageFileName = $drawing->getIndexedFilename();
                        $path = $path . $imageFileName;
                        imagegif($image, $path);
                        break;
                    case PHPExcel_Worksheet_MemoryDrawing::RENDERING_PNG: //imagepng
                        $imageFileName = $drawing->getIndexedFilename();
                        $path = $path . $imageFileName;
                        imagepng($image, $path);
                        break;
                    case PHPExcel_Worksheet_MemoryDrawing::RENDERING_DEFAULT: //imagepng
                        $imageFileName = $drawing->getIndexedFilename();
                        $path = $path . $imageFileName;
                        imagepng($image, $path);
                        break;
                }
                $result[$xy] = $imageFileName;
            }
        }
        return $result;
    }
    /* 调用 */
    $PHPExcel = $reader->load($filePath); //载入excel文件
    $worksheet = $PHPExcel->getActiveSheet(); //返回一个对象
    $imageInfo = extractImageFromWorksheet($worksheet,"images/");//获得所有的资源型图片信息
    $data_array = $PHPExcel->getSheet()->toArray();//获取所有数据
    echo '<pre>';
    var_dump($imageInfo);
