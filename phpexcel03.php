<p>PHPExcel基本操作：<br />
定义EXCEL实体<br />
即定义一个PHPEXCEL对象，并设置EXCEL对象内显示内容</p>
<div>
<pre class="prebrush">
// Excel开始
// 准备EXCEL的包括文件
// Error reporting 
error_reporting(0);
// PHPExcel 
require_once dirname(__FILE__) . 'PHPExcel.php';
// 生成新的excel对象
$objPHPExcel = new PHPExcel();
// 设置excel文档的属性
$objPHPExcel-&gt;getProperties()-&gt;setCreator("Sam.c")
             -&gt;setLastModifiedBy("Sam.c Test")
             -&gt;setTitle("Microsoft Office Excel Document")
             -&gt;setSubject("Test")
             -&gt;setDescription("Test")
             -&gt;setKeywords("Test")
             -&gt;setCategory("Test result file");
// 开始操作excel表
// 操作第一个工作表
$objPHPExcel-&gt;setActiveSheetIndex(0);
// 设置工作薄名称
$objPHPExcel-&gt;getActiveSheet()-&gt;setTitle(iconv('gbk', 'utf-8', 'phpexcel测试'));
// 设置默认字体和大小
$objPHPExcel-&gt;getDefaultStyle()-&gt;getFont()-&gt;setName(iconv('gbk', 'utf-8', '宋体'));
$objPHPExcel-&gt;getDefaultStyle()-&gt;getFont()-&gt;setSize(10);

</pre>
</div>
<p>三、输出文件</p>
<div>
<pre class="prebrush">
// 如果需要输出EXCEL格式
if($m_exportType=="excel"){   
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    // 从浏览器直接输出$filename
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
    header("Content-Type:application/force-download");
    header("Content-Type: application/vnd.ms-excel;");
    header("Content-Type:application/octet-stream");
    header("Content-Type:application/download");
    header("Content-Disposition:attachment;filename=".$filename);
    header("Content-Transfer-Encoding:binary");
    $objWriter-&gt;save("php://output"); 
}
// 如果需要输出PDF格式
if($m_exportType=="pdf"){
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
    $objWriter-&gt;setSheetIndex(0);
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
    header("Content-Type:application/force-download");
    header("Content-Type: application/pdf");
    header("Content-Type:application/octet-stream");
    header("Content-Type:application/download");
    header("Content-Disposition:attachment;filename=".$m_strOutputPdfFileName);
    header("Content-Transfer-Encoding:binary");
    $objWriter-&gt;save("php://output"); 
}

</pre>
</div>
<p>设置一列的宽度：<br />
</p>
<div>
<pre class="prebrush">
$objPHPExcel-&gt;getActiveSheet()-&gt;getColumnDimension('A')-&gt;setWidth(15);
</pre>
</div>
<p>设置一行的高度：<br />
</p>
<div>
<pre class="prebrush">
$objPHPExcel-&gt;getActiveSheet()-&gt;getRowDimension('6')-&gt;setRowHeight(30);
</pre>
</div>
<p>合并单元格：<br />
</p>
<div>
<pre class="prebrush">
$objPHPExcel-&gt;getActiveSheet()-&gt;mergeCells('A1:P1');
</pre>
</div>
<p>设置A1单元格加粗，居中：<br />
</p>
<div>
<pre class="prebrush">
$styleArray1 = array(
  'font' =&gt; array(
    'bold' =&gt; true,
    'size'=&gt;12,
    'color'=&gt;array(
      'argb' =&gt; '00000000',
    ),
  ),
  'alignment' =&gt; array(
    'horizontal' =&gt; PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
);
// 将A1单元格设置为加粗，居中
$objPHPExcel-&gt;getActiveSheet()-&gt;getStyle('A1')-&gt;applyFromArray($styleArray1);

$objPHPExcel-&gt;getActiveSheet()-&gt;getStyle('B1')-&gt;getFont()-&gt;setBold(true);

</pre>
</div>
<p>给特定单元格中写入内容：<br />
</p>
<div>
<pre class="prebrush">
$objPHPExcel-&gt;getActiveSheet()-&gt;setCellValue('A1', 'Hello Baby');
</pre>
</div>
<p>设置单元格样式（水平/垂直居中）：<br />
&nbsp;</p>
<div>
<pre class="prebrush">
$objPHPExcel-&gt;getActiveSheet()-&gt;getStyle('A1')-&gt;getAlignment()-&gt;setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  $objPHPExcel-&gt;getActiveSheet()-&gt;getStyle('A1')-&gt;getAlignment()-&gt;setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
</pre>
</div>
<p>设置单元格样式（黑色字体）：<br />
</p>
<div>
<pre class="prebrush">
$objPHPExcel-&gt;getActiveSheet()-&gt;getStyle('H5')-&gt;getFont()-&gt;getColor()-&gt;setARGB(PHPExcel_Style_Color::COLOR_BLACK); // 黑色
</pre>
</div>
<p>设置单元格格式（背景）：<br />
</p>
<div>
<pre class="prebrush">
$objPHPExcel-&gt;getActiveSheet()-&gt;getStyle('H5')-&gt;getFill()-&gt;getStartColor()-&gt;setARGB('00ff99cc'); // 将背景设置为浅粉色
</pre>
</div>
<p>设置单元格格式（数字格式）：<br />
</p>
<div>
<pre class="prebrush">
$objPHPExcel-&gt;getActiveSheet()-&gt;getStyle('F'.$iLineNumber)-&gt;getNumberFormat()-&gt;setFormatCode('0.000');
</pre>
</div>
<p>给单元格中放入图片：<br />
</p>
<div>
<pre class="prebrush">
// 将数据中心图片放在J1单元格内
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing-&gt;setName('Logo');
$objDrawing-&gt;setDescription('Logo');
$objDrawing-&gt;setPath('test.jpg');
$objDrawing-&gt;setWidth(400);
$objDrawing-&gt;setHeight(123);
$objDrawing-&gt;setCoordinates('J1');
$objDrawing-&gt;setWorksheet($objPHPExcel-&gt;getActiveSheet());
</pre>
</div>
<p><br />
在单元格中设置超链接：<br />
</p>
<div>
<pre class="prebrush">
$objPHPExcel-&gt;getActiveSheet()-&gt;setCellValue('H8', iconv('gbk', 'utf-8', '燕南天'));
$objPHPExcel-&gt;getActiveSheet()-&gt;getCell('H8')-&gt;getHyperlink()-&gt;setUrl('http://www.bitsCN.com/');
</pre>
</div>
<p>设置单元格边框</p>
<div>
<pre class="prebrush">
$styleThinBlackBorderOutline = array(
    'borders' =&gt; array (
       'outline' =&gt; array (
          'style' =&gt; PHPExcel_Style_Border::BORDER_THIN,  //设置border样式
          //'style' =&gt; PHPExcel_Style_Border::BORDER_THICK, 另一种样式
          'color' =&gt; array ('argb' =&gt; 'FF000000'),     //设置border颜色
      ),
   ),
);
$objPHPExcel-&gt;getActiveSheet()-&gt;getStyle( 'A4:E10')-&gt;applyFromArray($styleThinBlackBorderOutline);

//添加一个新的worksheet 
          $objExcel-&gt;createSheet(); 
          $objActSheet = $objExcel-&gt;getSheet($s); 
          $objActSheet-&gt;setTitle('表'.$GSheet);
</pre>
