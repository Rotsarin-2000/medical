<?php
require_once __DIR__ . '/vendor/autoload.php';

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf([
  'fontDir' => array_merge($fontDirs, [
    __DIR__ . '/tmp',
  ]),

  'fontdata' => $fontData + [
    'sarabun' => [
      'R' => 'THSarabunNew.ttf',
      'I' => 'THSarabunNew Italic.ttf',
      'B' => 'THSarabunNew Bold.ttf',
      'BI' => 'THSarabunNew BoldItalic.ttf'
    ]
  ],

  'default_font' => 'sarabun',
  'margin_top' => 38, // ตั้งค่าระยะขอบด้านบนที่ต้องการ (หน่วยเป็นมิลลิเมตร)
  'margin_left' => 10, // ระยะขอบซ้าย
  'margin_right' => 10 // ระยะขอบขวา
]);

$mpdf->SetHTMLHeader('
    <table width="100%" style="border-collapse: collapse; border: none; padding-bottom: 5px;">
        <tr>
           <td style="border: none; text-align: center; vertical-align: top;" colspan="3">
             <div style="font-weight: bold; font-size: 20pt; font-family: Arial, sans-serif;">MEDICAL</div>
          </td>
        </tr>
        <tr>
          <td style="border: none; text-align: left; vertical-align: top; width: 100px;">
              <img src="https://storage.googleapis.com/jobfinfin_etl_image/1691741438313-d2b2da51-8b41-42b5-99a4-1cd2aef8972b.jpg" width="100" height="100" style="border-radius: 10px; margin-top: -35px;">
          </td>
          <td style="border: none; text-align: left; vertical-align: top; padding: 0; margin: 0;">
              <h4 style="font-weight: bold; margin: 0;">บริษัท ทีเอส โมลิเมอร์ จำกัด (สำนักงานใหญ่)</h4>
              <h4 style="font-weight: bold; margin: 0;">TS MOLYMER CO., LTD. (HEAD OFFICE)</h4>
          </td>
        </tr>
    </table>
');


ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delivery Order</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <style>
    body,
    h1,
    h2,
    p {
      font-family: 'Sarabun', sans-serif;
      color: black;
    }

    .text-center {
      text-align: center;
    }

    .right-align {
      text-align: right;
      font-weight: bold;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid black;
      padding: 5px;
    }

    .footer {
      position: fixed;
      bottom: 0;
      width: 100%;
    }
  </style>
</head>

<body>
  <div class="container">
    <table class="table" style="border-collapse: collapse; border: none;">
      <tr>
        <th style="border: none;">
          <h4 style="font-weight: bold; font-family: Arial, sans-serif; height: 40px; font-size: 12px;">
            DO No. : <strong><span style="font-weight: normal;">do test</span></strong><br>
            Pick-up Date : <strong><span style="font-weight: normal;">pick test</span></strong><br>
            Round No. : <strong><span style="font-weight: normal;">round test</span></strong>
          </h4>
        </th>
        <th colspan="3" style="border: none;"></th>
        <th class="right-align" style="border: none;">
          <h4 style="font-weight: bold; font-family: Arial, sans-serif; height: 40px; font-size: 12px;">
            INV No. :
            <strong>
              <span style="font-weight: normal; border-bottom: 1px solid #000;">
               inv test
              </span>
            </strong>
            <br>
          </h4>
          <h4 style="font-weight: bold; font-family: Arial, sans-serif; height: 40px; font-size: 12px;">
            INV No. :
            <strong>
              <span style="font-weight: normal; border-bottom: 1px solid #000;">
                inv test
              </span>
            </strong>
            <br>
          </h4>
        </th>
      </tr>
    </table>

    <table class="table">
      <thead>
        <tr>
          <th class="text-center" style="font-weight: bold; font-family: Arial, sans-serif; height: 40px; width:10px; font-size: 12px;">Item No.</th>
          <th class="text-center" style="font-weight: bold; font-family: Arial, sans-serif; height: 40px; width:15px; font-size: 12px;">Unit Code</th>
          <th class="text-center" style="font-weight: bold; font-family: Arial, sans-serif; height: 40px; width:15px; font-size: 12px;">Item Code</th>
          <th class="text-center" style="font-weight: bold; font-family: Arial, sans-serif; height: 40px; width:15px; font-size: 12px;">Item Name</th>
          <th class="text-center" style="font-weight: bold; font-family: Arial, sans-serif; height: 40px; width:15px; font-size: 12px;">Serial</th>
          <th class="text-center" style="font-weight: bold; font-family: Arial, sans-serif; height: 40px; width:15px; font-size: 12px;">Qty</th>
          <th class="text-center" style="font-weight: bold; font-family: Arial, sans-serif; height: 40px; width:15px; font-size: 12px;">Date</th>
          <th class="text-center" style="font-weight: bold; font-family: Arial, sans-serif; height: 40px; width:15px; font-size: 12px;">DL CHECK</th>

        </tr>
      </thead>
      <tbody>
     
      </tbody>
    </table>
  </div>
  <div class="footer">
    <div class="col">
      <div class="text">
        <h4>
          <span class="note-label" style="font-weight: bold; text-decoration: underline;">หมายเหตุ :</span>
          ตรวจสอบสภาพของกล่องสินค้าแล้ว ไม่มีความเสียหายเช่น รอยบุบ, ฉีกขาด, รอยกระแทก ฯลฯ
        </h4>
        <br />
      </div>
    </div>
    <table class="table p-2">
      <tr>
        <th class="text-center" style=" height: 150px; border: 1px solid gray; color: black;">
          <div class="card text-center">
            <table class="table table-striped">
              <tr>
                <th class="right-center" style="border: none; text-align: center;">
                  <h3 style="border: none; text-align: center;">สำหรับ TS MOLYMER</h3>
                </th>
              </tr>
            </table>
            <table class="table table-striped">
              <tr>
                <th style="border: none; text-align: center;">
                  <h4 class="text-center">ลงชื่อ</h4><br>
                  <p>............................................................</p><br />
                  <h4>พนักงานโหลดสินค้า TSMB</h4>
                </th>
                <th colspan="3" style="border: none;"></th>
                <th class="right-align" style="border: none; text-align: center;">
                  <h4 class="text-center">ลงชื่อ</h4><br>
                  <p>............................................................</p><br />
                  <h4>พนักงานโหลดสินค้า TSMB</h4>
                </th>
              </tr>
            </table>
          </div>
        </th>
        <th colspan="3" style="border: none;"></th>
        <th class="text-center" style=" height: 150px; border: 1px solid gray;">
          <div class="card text-center">
            <table class="table table-striped">
              <tr>
                <th class="right-center" style="border: none; text-align: center;">
                  <h3 style="border: none; text-align: center;">สำหรับ Yusen</h3>
                </th>
              </tr>
            </table>
            <table class="table table-striped">
              <tr>
                <th style="border: none; text-align: center;">
                  <h4 class="text-center">ลงชื่อ</h4><br>
                  <p>............................................................</p><br />
                  <h4>พนักงานโหลดสินค้า Yusen</h4>
                </th>
                <th colspan="3" style="border: none;"></th>
                <th class="right-align" style="border: none; text-align: center;">
                  <h4 class="text-center">ลงชื่อ</h4><br>
                  <p>............................................................</p><br />
                  <h4>พนักงานโหลดสินค้า Yusen FTZ</h4>
                </th>
              </tr>
            </table>
          </div>
        </th>
      </tr>
    </table>
  </div>
</body>

</html>

<?php
$html = ob_get_clean();
$mpdf->WriteHTML($html);
// $filename = date("d-m-Y") . ".pdf";
// $mpdf->Output($filename, \Mpdf\Output\Destination::DOWNLOAD);
$filename = date("d-m-Y") . ".pdf";
$mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
?>