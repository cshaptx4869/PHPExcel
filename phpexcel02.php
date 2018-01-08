<?php
require_once './phpexcel/PHPExcel.php';

// 首先创建一个新的对象  PHPExcel object
$objPHPExcel = new PHPExcel();

// 设置文件的一些属性，在xls文件——>属性——>详细信息里可以看到这些值，xml表格里是没有这些值的
$objPHPExcel
      ->getProperties()  //获得文件属性对象，给下文提供设置资源
      ->setCreator( "Maarten Balliauw")                 //设置文件的创建者
      ->setLastModifiedBy( "Maarten Balliauw")          //设置最后修改者
      ->setTitle( "Office 2007 XLSX Test Document" )    //设置标题
      ->setSubject( "Office 2007 XLSX Test Document" )  //设置主题
      ->setDescription( "Test document for Office 2007 XLSX, generated using PHP classes.") //设置备注
      ->setKeywords( "office 2007 openxml php")        //设置标记
      ->setCategory( "Test result file");                //设置类别
// 位置aaa  *为下文代码位置提供锚
// 给表格添加数据
$objPHPExcel->setActiveSheetIndex(0)             //设置第一个内置表（一个xls文件里可以有多个表）为活动的
            ->setCellValue( 'A1', 'Hello' )         //给表的单元格设置数据
            ->setCellValue( 'B2', 'world!' )      //数据格式可以为字符串
            ->setCellValue( 'C1', 12)            //数字型
            ->setCellValue( 'D2', 12)            //
            ->setCellValue( 'D3', true )           //布尔型
            ->setCellValue( 'D4', '=SUM(C1:D2)' );//公式

//得到当前活动的表,注意下文教程中会经常用到$objActSheet
$objActSheet = $objPHPExcel->getActiveSheet();
// 位置bbb  *为下文代码位置提供锚
// 给当前活动的表设置名称
$objActSheet->setTitle('Simple2222');
代码还没有结束，可以复制下面的代码来决定我们将要做什么

我们将要做的是
1,直接生成一个文件
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('myexchel.xlsx');

2、提示下载文件
excel 2003 .xls
// 生成2003excel格式的xls文件
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="01simple.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;

excel 2007 .xlsx
// 生成2007excel格式的xlsx文件
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="01simple.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
$objWriter->save( 'php://output');
exit;

pdf 文件
// 下载一个pdf文件
header('Content-Type: application/pdf');
header('Content-Disposition: attachment;filename="01simple.pdf"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
$objWriter->save('php://output');
exit;
// 生成一个pdf文件
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
$objWriter->save('a.pdf');


CSV 文件
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV')->setDelimiter(',' )  //设置分隔符
                                                                  ->setEnclosure('"' )    //设置包围符
                                                                  ->setLineEnding("\r\n" )//设置行分隔符
                                                                  ->setSheetIndex(0)      //设置活动表
                                                                  ->save(str_replace('.php' , '.csv' , __FILE__));

HTML 文件
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'HTML');       //将$objPHPEcel对象转换成html格式的
$objWriter->setSheetIndex(0);  //设置活动表
//$objWriter->setImagesRoot('http://www.example.com');
$objWriter->save(str_replace('.php', '.htm', __FILE__));     //保存文件




设置表格样式和数据格式
设置默认的字体和文字大小     锚：aaa
$objPHPExcel->getDefaultStyle()->getFont()->setName( 'Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(20);

日期格式      锚：bbb
//获得秒值变量
$dateTimeNow = time();
//三个表格分别设置为当前实际的日期格式、时间格式、日期和时间格式
//首先将单元格的值设置为由PHPExcel_Shared_Date::PHPToExcel方法转换后的excel格式的值，然后用过得到该单元格的样式里面数字样式再设置显示格式
$objActSheet->setCellValue( 'C9', PHPExcel_Shared_Date::PHPToExcel( $dateTimeNow )); 
$objActSheet->getStyle( 'C9')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
$objActSheet->setCellValue( 'C10', PHPExcel_Shared_Date::PHPToExcel( $dateTimeNow ));
$objActSheet->getStyle( 'C10')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);
$objActSheet->setCellValue( 'C10', PHPExcel_Shared_Date::PHPToExcel( $dateTimeNow ));
$objActSheet->getStyle( 'C10')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_TIME4);
//将E4到E13的数字格式设置为EUR
$objPHPExcel->getActiveSheet()->getStyle( 'E4:E13')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);

设置列的宽度      锚：bbb
$objActSheet->getColumnDimension( 'B')->setAutoSize(true);   //内容自适应
$objActSheet->getColumnDimension( 'A')->setWidth(30);         //30宽

设置文件打印的页眉和页脚      锚：bbb
//设置打印时候的页眉页脚（设置完了以后可以通过打印预览来看效果）字符串中的&*好像是一些变量
$objActSheet->getHeaderFooter()->setOddHeader( '&L&G&C&HPlease treat this document as confidential!');
$objActSheet->getHeaderFooter()->setOddFooter( '&L&B' . $objPHPExcel->getProperties()->getTitle() . '&RPage &P of &N' );

设置页面文字的方向和页面大小    锚：bbb
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup:: ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup:: PAPERSIZE_A4);     //A4纸大小

为页眉添加图片     office中有效 wps中无效  锚：bbb
$objDrawing = new PHPExcel_Worksheet_HeaderFooterDrawing();
$objDrawing->setName('PHPExcel logo');
$objDrawing->setPath('./images/phpexcel_logo.gif');
$objDrawing->setHeight(36);
$objPHPExcel->getActiveSheet()->getHeaderFooter()->addImage($objDrawing, PHPExcel_Worksheet_HeaderFooter::IMAGE_HEADER_LEFT );

设置单元格的批注    锚：bbb
//给单元格添加批注
$objPHPExcel->getActiveSheet()->getComment( 'E13')->setAuthor('PHPExcel' );     //设置作者
$objCommentRichText = $objPHPExcel->getActiveSheet()->getComment('E13' )->getText()->createTextRun('PHPExcel:');  //添加批注
$objCommentRichText->getFont()->setBold( true);  //将现有批注加粗
$objPHPExcel->getActiveSheet()->getComment( 'E13')->getText()->createTextRun("\r\n" );      //添加更多批注
$objPHPExcel->getActiveSheet()->getComment( 'E13')->getText()->createTextRun('Total amount on the current invoice, including VAT.' );
$objPHPExcel->getActiveSheet()->getComment( 'E13')->setWidth('100pt' );      //设置批注显示的宽高，在office中有效在wps中无效
$objPHPExcel->getActiveSheet()->getComment( 'E13')->setHeight('100pt' );
$objPHPExcel->getActiveSheet()->getComment( 'E13')->setMarginLeft('150pt' );
$objPHPExcel->getActiveSheet()->getComment( 'E13')->getFillColor()->setRGB('EEEEEE' );      //设置背景色，在office中有效在wps中无效

添加文字块    看效果图 office中有效 wps中无效  锚：bbb
//大概翻译  创建一个富文本框  office有效  wps无效
$objRichText = new PHPExcel_RichText();
$objRichText->createText('This invoice is ');    //写文字
//添加文字并设置这段文字粗体斜体和文字颜色
$objPayable = $objRichText->createTextRun( 'payable within thirty days after the end of the month');
$objPayable->getFont()->setBold( true);
$objPayable->getFont()->setItalic( true);
$objPayable->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_DARKGREEN ) );
$objRichText->createText(', unless specified otherwise on the invoice.');
//将文字写到A18单元格中
$objPHPExcel->getActiveSheet()->getCell( 'A18')->setValue($objRichText);
 PHPExcel <wbr><wbr>学习笔记

合并拆分单元格    锚：bbb
$objPHPExcel->getActiveSheet()->mergeCells( 'A28:B28');      // A28:B28合并
$objPHPExcel->getActiveSheet()->unmergeCells( 'A28:B28');    // A28:B28再拆分

单元格密码保护    锚：bbb
// 单元格密码保护不让修改
$objPHPExcel->getActiveSheet()->getProtection()->setSheet( true);  // 为了使任何表保护，需设置为真
$objPHPExcel->getActiveSheet()->protectCells( 'A3:E13', 'PHPExcel' ); // 将A3到E13保护  加密密码是 PHPExcel
$objPHPExcel->getActiveSheet()->getStyle( 'B1')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED); //去掉保护

设置单元格字体   锚：bbb
//将B1的文字字体设置为Candara，20号的粗体下划线有背景色
$objPHPExcel->getActiveSheet()->getStyle( 'B1')->getFont()->setName('Candara' );
$objPHPExcel->getActiveSheet()->getStyle( 'B1')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle( 'B1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle( 'B1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$objPHPExcel->getActiveSheet()->getStyle( 'B1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

文字对齐方式  锚：bbb
$objPHPExcel->getActiveSheet()->getStyle( 'D11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);    //水平方向上对齐
$objPHPExcel->getActiveSheet()->getStyle('A18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);//水平方向上两端对齐
$objPHPExcel->getActiveSheet()->getStyle( 'A18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);       //垂直方向上中间居中

设置单元格边框  锚：bbb
$styleThinBlackBorderOutline = array(
       'borders' => array (
             'outline' => array (
                   'style' => PHPExcel_Style_Border::BORDER_THIN,   //设置border样式
                   //'style' => PHPExcel_Style_Border::BORDER_THICK,  另一种样式
                   'color' => array ('argb' => 'FF000000'),          //设置border颜色
            ),
      ),
);
$objPHPExcel->getActiveSheet()->getStyle( 'A4:E10')->applyFromArray($styleThinBlackBorderOutline);

背景填充颜色     锚：bbb
//设置填充的样式和背景色
$objPHPExcel->getActiveSheet()->getStyle( 'A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle( 'A1:E1')->getFill()->getStartColor()->setARGB('FF808080');

综合设置样例
$objPHPExcel->getActiveSheet()->getStyle( 'A3:E3')->applyFromArray(
             array(
                   'font'    => array (
                         'bold'      => true
                   ),
                   'alignment' => array (
                         'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT ,
                  ),
                   'borders' => array (
                         'top'     => array (
                               'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                  ),
                   'fill' => array (
                         'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR ,
                         'rotation'   => 90,
                         'startcolor' => array (
                               'argb' => 'FFA0A0A0'
                         ),
                         'endcolor'   => array (
                               'argb' => 'FFFFFFFF'
                         )
                  )
            )
);
PHPExcel <wbr><wbr>学习笔记


给单元格内容设置url超链接      锚：bbb
$objActSheet->getCell('E26')->getHyperlink()->setUrl( 'http://www.phpexcel.net');    //超链接url地址
$objActSheet->getCell('E26')->getHyperlink()->setTooltip( 'Navigate to website');  //鼠标移上去连接提示信息

给表中添加图片     锚：bbb
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Paid');
$objDrawing->setDescription('Paid');
$objDrawing->setPath('./images/paid.png'); //图片引入位置
$objDrawing->setCoordinates('B15'); //图片添加位置
$objDrawing->setOffsetX(210); //设置图片所在单元格的格式
$objDrawing->setRotation(25); //设置图片所在单元格的格式
$objDrawing->setHeight(36); //设置图片高度
$objDrawing->getShadow()->setVisible (true ); //设置图片所在单元格的格式
$objDrawing->getShadow()->setDirection(45);  //设置图片所在单元格的格式
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet()); 
//还可以添加有gd库生产的图片，详细见自带实例25

创建一个新工作表和设置工作表标签颜色     锚：bbb
$objExcel->createSheet(); 
$objPHPExcel->setActiveSheetIndex(1);   //设置第2个表为活动表，提供操作句柄
$objExcel->getSheet(1)->setTitle( '测试2');   //直接得到第二个表进行设置,将工作表重新命名为测试2
$objPHPExcel->getActiveSheet()->getTabColor()->setARGB( 'FF0094FF');     //设置标签颜色

添加或删除行和列     锚：bbb
$objPHPExcel->getActiveSheet()->insertNewRowBefore(6, 10);   //在行6前添加10行
$objPHPExcel->getActiveSheet()->removeRow(6, 10);                  //从第6行往后删去10行
$objPHPExcel->getActiveSheet()->insertNewColumnBefore( 'E', 5);    //从第E列前添加5类
$objPHPExcel->getActiveSheet()->removeColumn( 'E', 5);             //从E列开始往后删去5列

隐藏和显示某列     锚：bbb
$objPHPExcel->getActiveSheet()->getColumnDimension( 'C')->setVisible(false);          //隐藏
$objPHPExcel->getActiveSheet()->getColumnDimension( 'D')->setVisible(true);           //显示

重新命名活动的表的标签名称     锚：bbb
$objPHPExcel->getActiveSheet()->setTitle( 'Invoice');

设置工作表的安全
$objPHPExcel->getActiveSheet()->getProtection()->setPassword( 'PHPExcel');
$objPHPExcel->getActiveSheet()->getProtection()->setSheet( true); // This should be enabled in order to enable any of the following!
$objPHPExcel->getActiveSheet()->getProtection()->setSort( true);
$objPHPExcel->getActiveSheet()->getProtection()->setInsertRows( true);
$objPHPExcel->getActiveSheet()->getProtection()->setFormatCells( true);

设置文档安全   锚：bbb
$objPHPExcel->getSecurity()->setLockWindows( true);
$objPHPExcel->getSecurity()->setLockStructure( true);
$objPHPExcel->getSecurity()->setWorkbookPassword( "PHPExcel");     //设置密码

样式复制      锚：bbb
//将B2的样式复制到B3至B7
$objPHPExcel->getActiveSheet()->duplicateConditionalStyle(
                        $objPHPExcel->getActiveSheet()->getStyle( 'B2')->getConditionalStyles(),
                         'B3:B7'
                   );

Add conditional formatting    锚：bbb
echo date('H:i:s' ) , " Add conditional formatting" , PHP_EOL;
$objConditional1 = new PHPExcel_Style_Conditional ();
$objConditional1->setConditionType(PHPExcel_Style_Conditional ::CONDITION_CELLIS );
$objConditional1->setOperatorType(PHPExcel_Style_Conditional ::OPERATOR_BETWEEN );
$objConditional1->addCondition('200');
$objConditional1->addCondition('400');

设置分页（主要用于打印）    锚：bbb
//设置某单元格为页尾
$objPHPExcel->getActiveSheet()->setBreak( 'A' . $i, PHPExcel_Worksheet::BREAK_ROW );


用数组填充表    锚：bbb
//吧数组的内容从A2开始填充
$dataArray = array( array("2010" ,    "Q1",  "United States",  790),
                   array("2010" ,    "Q2",  "United States",  730),
                  );
$objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');

设置自动筛选     锚：bbb
$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
//$objPHPExcel->getActiveSheet()->calculateWorksheetDimension()....得到A1行的所有内容个

打印出的到所有的公式
$objCalc = PHPExcel_Calculation::getInstance();
print_r($objCalc->listFunctionNames())

设置单元格值的范围     锚：bbb
$objValidation = $objPHPExcel->getActiveSheet()->getCell('B3' )->getDataValidation();
$objValidation->setType( PHPExcel_Cell_DataValidation:: TYPE_WHOLE );
$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation:: STYLE_STOP );
$objValidation->setAllowBlank(true);
$objValidation->setShowInputMessage( true);            //设置显示提示信息
$objValidation->setShowErrorMessage( true);            //设置显示错误信息
$objValidation->setErrorTitle('Input error');    //错误标题
//$objValidation->setShowDropDown(true);
$objValidation->setError('Only numbers between 10 and 20 are allowed!');       //错误内容
$objValidation->setPromptTitle('Allowed input');       //设置提示标题
$objValidation->setPrompt('Only numbers between 10 and 20 are allowed.'); //提示内容
$objValidation->setFormula1(10);     //设置最大值
$objValidation->setFormula2(120);    //设置最小值
//或者这样设置  $objValidation->setFormula2(1,5,6,7);  设置值是1，5，6，7中的一个数

其他
$objPHPExcel->getActiveSheet()->getStyle( 'B5')->getAlignment()->setShrinkToFit(true); //长度不够显示的时候是否自动换行
$objPHPExcel->getActiveSheet()->getStyle( 'B5')->getAlignment()->setShrinkToFit(true); //自动转换显示字体大小,使内容能够显示
$objPHPExcel->getActiveSheet()->getCell(B14)->getValue();           //获得值，有可能得到的是公式
$objPHPExcel->getActiveSheet()->getCell(B14)->getCalculatedValue();//获得算出的值


导入或读取文件
//通过PHPExcel_IOFactory::load方法来载入一个文件，load会自动判断文件的后缀名来导入相应的处理类，读取格式保含xlsx/xls/xlsm/ods/slk/csv/xml/gnumeric
require_once '../Classes/PHPExcel/IOFactory.php';
$objPHPExcel = PHPExcel_IOFactory::load(
//吧载入的文件默认表（一般都是第一个）通过toArray方法来返回一个多维数组
$dataArray = $objPHPExcel->getActiveSheet()->toArray();
//读完直接写到一个xlsx文件里
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); //$objPHPExcel是上文中读的资源
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

读取xml文件
$objReader = PHPExcel_IOFactory:: createReader('Excel2003XML' );
$objPHPExcel = $objReader->load( "Excel2003XMLTest.xml" );
读取ods文件
$objReader = PHPExcel_IOFactory:: createReader('OOCalc' );
$objPHPExcel = $objReader->load("OOCalcTest.ods" );
读取numeric文件
$objReader = PHPExcel_IOFactory:: createReader('Gnumeric' );
$objPHPExcel = $objReader->load( "GnumericTest.gnumeric" );
读取slk文件
$objPHPExcel = PHPExcel_IOFactory:: load("SylkTest.slk" );


循环遍历数据
$objReader = PHPExcel_IOFactory::createReader('Excel2007' ); //创建一个2007的读取对象
$objPHPExcel = $objReader->load ("05featuredemo.xlsx" );             //读取一个xlsx文件
foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {     //遍历工作表
       echo 'Worksheet - ' , $worksheet->getTitle() , PHP_EOL;
       foreach ($worksheet->getRowIterator() as $row) {       //遍历行
             echo '    Row number - ' , $row->getRowIndex() , PHP_EOL;
            $cellIterator = $row->getCellIterator();   //得到所有列
            $cellIterator->setIterateOnlyExistingCells( false); // Loop all cells, even if it is not set
             foreach ($cellIterator as $cell) {  //遍历列
                   if (!is_null($cell)) {  //如果列不给空就得到它的坐标和计算的值
                         echo '        Cell - ' , $cell->getCoordinate() , ' - ' , $cell->getCalculatedValue() , PHP_EOL;
                  }
            }
      }
}

吧数组插入的表中
//插入的数据 3行数据
$data = array( array('title'      => 'Excel for dummies',
                     'price'      => 17.99,
                     'quantity'   => 2
                           ),
                    array('title'       => 'PHP for dummies',
                           'price'      => 15.99,
                           'quantity'  => 1
                           ),
                    array('title'      => 'Inside OOP',
                           'price'      => 12.95,
                           'quantity'  => 1
                           )
                   );
$baseRow = 5;      //指定插入到第5行后
foreach($data as $r => $dataRow) {
      $row = $baseRow + $r;    //$row是循环操作行的行号
      $objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);  //在操作行的号前加一空行，这空行的行号就变成了当前的行号
       //对应的咧都附上数据和编号
      $objPHPExcel->getActiveSheet()->setCellValue( 'A'.$row, $r+1);     
      $objPHPExcel->getActiveSheet()->setCellValue( 'B'.$row, $dataRow['title']);
      $objPHPExcel->getActiveSheet()->setCellValue( 'C'.$row, $dataRow['price']);
      $objPHPExcel->getActiveSheet()->setCellValue( 'D'.$row, $dataRow['quantity']);
      $objPHPExcel->getActiveSheet()->setCellValue( 'E'.$row, '=C'.$row.'*D' .$row);
}
$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);     //最后删去第4行，这是示例需要，在此处为大家提供删除实例

      
/*小栗子*/
include(dirname(__FILE__).'/phpexcel/PHPExcel.php');

$Obj = new PHPExcel_Reader_Excel5();
$Obj->setReadDataOnly(true);
      
$phpExcel = $Obj->load(dirname(__FILE__).'/output.xls');  //读取demo.xls文件
$objWorksheet = $phpExcel->getActiveSheet(); //获取当前活动sheet
$highestRow = $objWorksheet->getHighestRow(); //获取行数
//获取列数
$highestColumn = $objWorksheet->getHighestColumn();
$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
//循环输出数据
$data = array();
for($row = 1; $row <= $highestRow; ++$row) {
      for($col = 0; $col < $highestColumnIndex; ++$col) {
        $val = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
        $data[$row][$col] = trim($val);
      }
}
      
//导入
function handle_good_input_cmd(){
  global $smarty,$conn,$catalog_id;
    if($_FILES['file_stu']){
        //判断文件上传的类型
        $name= $_FILES['file_stu']['name'];
        $file = $_FILES['file_stu']['tmp_name'];
        $file_types = explode('.', $name);
        //获得后缀名
        $file_type = $file_types[count($file_types)-1];
        $type = array('xls','xlsx');
        if(!in_array($file_type, $file_types)){
            reload_js('请正确选择上传文件类型','handler.php?catalog_id='.$catalog_id.'&cmd=good_input');
        }
        //创建文件夹,返回保存地址
        $dsFile = get_file_save_path($name);
        //移动地址
        $dsFile = $dsFile.$name;

        $test = move_uploaded_file($file,$dsFile);
        //判断是否是合法的上传文件
        if($test){
            if($file_type == 'xls'){
                $inputFileType = 'Excel5';
            }elseif($file_type == 'xlsx'){
                $inputFileType = 'Excel2007';
            }
            //读取文件
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);

            $objPHPExcel = $objReader->load($dsFile);//加载文件

            $objWorksheet = $objPHPExcel->getActiveSheet();//获得当前活动sheet
            //return int Highest row number
            $highestRow = $objWorksheet->getHighestRow();//取得总行数
            //return string Highest column name
            $highestColumn = $objWorksheet->getHighestColumn();//取得总列数 String
            //return 	int Column index (base 1 !!!)
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

            $headtitle=array();
            //从第二行开始读取
            for ($row = 2;$row <= $highestRow;$row++) {
                $strs = array();
                //注意highestColumnIndex的列数索引从0开始
                for ($col = 0; $col < $highestColumnIndex; $col++) {
                    //return 	PHPExcel_Cell   return $this->_value;
                    $strs[$col] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                }
                $info = array(
                    // 'id'=>"$strs[0]",
                    'spbianhao' => iconv('utf-8', 'gbk', $strs[0]),
                    'spcname' => iconv('utf-8', 'gbk', $strs[1]),
                    'spbname' => iconv('utf-8', 'gbk', $strs[2]),
                    'spename' => iconv('utf-8', 'gbk', $strs[3]),
                    'price' => iconv('utf-8', 'gbk', $strs[4]),
                    'spfenzishi' => iconv('utf-8', 'gbk', $strs[5]),
                    'spdescript' => iconv('utf-8', 'gbk', $strs[6]),
                    'spchundu' => iconv('utf-8', 'gbk', $strs[7]),
                    'sprongliang' => iconv('utf-8', 'gbk', $strs[8]),
                    'spguige' => iconv('utf-8', 'gbk', $strs[9]),
                    'spstock' => "$strs[10]",
                    'changjiaid' => iconv('utf-8', 'gbk', $strs[11]),
                    'cateid' => iconv('utf-8', 'gbk', $strs[12]),
                    'uptime' => time(),
                );
                $result = $conn->AutoExecute('goods', $info, 'INSERT');
            }
            if($result){
                reload_js('数据导入成功','handler.php?cmd=list&catalog_id='.$catalog_id);
            }
        }
    }
  print $smarty->fetch('input.html');

}
