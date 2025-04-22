<?php
include './db/conn.php';
include '../inserview/medical_create.php';
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
    'margin_top' => 35,
    'margin_left' => 10,
    'margin_right' => 10
]);

$mpdf->SetHTMLHeader('
  <table width="100%" style="border-collapse: collapse; border: none; padding-bottom: 5px;">
        <tr>
           <td style="border: none; text-align: center; vertical-align: top;" colspan="3">
             <h3 style="font-weight: bold;">Medical expense reimbursement report</h3>
          </td>
        </tr>
        <br />
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

$mpdf->SetHTMLFooter(' 
    <table class="table p-4">
            <tr>
            <th colspan="3" style="border: none;"></th>
            <th colspan="3" style="border: none;"></th>
                <th class="text-center" style="border: none;"></th>
                <table class="table table-striped" style="width: 210px;">
                    <tr>
                        <th class="right-center" style="text-align: center; height: 30px;">
                            <p style="text-align: center; font-weight: bold; font-family: Arial, sans-serif; font-size: 10px;">ISSUE</p>
                        </th>
                        <th class="right-center" style="text-align: center; height: 30px;">
                            <p style="text-align: center; font-weight: bold; font-family: Arial, sans-serif; font-size: 10px;">PERSONAL MANAGER</p>
                        </th>

                    </tr>
                    <tr>
                        <th class="right-center" style="text-align: center; width: 210px;">
                            <h5 class="text-center"></h5><br>
                            <p></p><br />
                        </th>
                        <th class="right-center" style="text-align: center; width: 210px;">
                            <h4 class="text-center"></h4><br>
                            <p></p><br />
                        </th>
                    </tr>
                    <tr>
                        <th class="right-center" style="text-align: center; width: 210px;">
                            <p style="font-size: 13px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( MS.Netchanok Niyomthong) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                        </th>
                        <th class="right-center" style="text-align: center; width: 210px;">
                            <p style="font-size: 12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( MS.Thunnanach Rungrojsuwan) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                        </th>
                    </tr>
                </table>

                <th colspan="3" style="border: none;"></th>
                <th class="text-center" style="border: none;">
                    <table class="table table-striped" style="width: 210px;">
                        <tr>
                            <th class="right-center" style="text-align: center; height: 30px;">
                                <p style="text-align: center; font-weight: bold; font-family: Arial, sans-serif; font-size: 10px;">MANAGING DIRECTOR</p>
                            </th>
                        </tr>
                        <tr>
                            <th class="right-center" style="text-align: center;">
                                <h4 class="text-center"></h4><br>
                                <p></p><br />
                            </th>
                        </tr>
                        <tr>
                            <th class="right-center" style="text-align: center;">
                                <p style="font-size: 13px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( MS.Sunan Thongsong ) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                            </th>
                        </tr>
                    </table>
                </th>
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
    <title>รายงานการเบิกจ่ายค่ารักษาพยาบาล</title>
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
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php
        $totalPrice = 0;
        $totalRows = 0;

        if ($result_date && $result_date->num_rows > 0) :
            $first_row = $result_date->fetch_assoc();
            $medical_data = json_decode($first_row['medical'], true);
            $date = isset($medical_data['date']) ? $medical_data['date'] : 'N/A';

            if ($date !== 'N/A' && strtotime($date)) {
                $formatted_date = date('d/m/Y', strtotime($date));
            } else {
                $formatted_date = 'N/A';
            }

            do {
                $medical_data = json_decode($first_row['medical'], true);

                if (isset($medical_data['price']) && is_numeric($medical_data['price'])) {
                    $totalPrice += $medical_data['price'];
                }

                $totalRows++;
            } while ($first_row = $result_date->fetch_assoc());

        ?>
            <h4 align="right" style="font-weight: bold; height: 40px; font-size: 17px;">
                Date :
                <strong>
                    <span style="font-weight: normal; border-bottom: 1px solid #000;">
                        &nbsp;&nbsp;<?php echo $formatted_date; ?>&nbsp;&nbsp;
                    </span>
                </strong>
            </h4>

            <!-- <table class="table" style="border-collapse: collapse; border: none;">
                <tr>
                    <th style="border: none;"> -->
            <h4 style="font-weight: bold; height: 40px; font-size: 18px;">
                <strong><span style="font-weight: normal;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Medical expense reimbursement report <?php echo $formatted_date; ?> </strong><br>
            </h4>
            <!-- </th>
                </tr>
            </table> -->
        <?php endif; ?>

        <table class="table">
            <thead>
                <tr>
                    <th class="text-center" style="font-weight: bold; height: 40px; width:10px; font-size: 15px;">No.</th>
                    <th class="text-center" style="font-weight: bold; height: 40px; width:10px; font-size: 15px;">Name</th>
                    <th class="text-center" style="font-weight: bold; height: 40px; width:10px; font-size: 15px;">Emp.ID</th>
                    <th class="text-center" style="font-weight: bold; height: 40px; width:10px; font-size: 15px;">Department</th>
                    <th class="text-center" style="font-weight: bold; height: 40px; width:250px; font-size: 15px;">Detail</th>
                    <th class="text-center" style="font-weight: bold; height: 40px; width:10px; font-size: 15px;">Amount</th>
                    <th class="text-center" style="font-weight: bold; height: 40px; width:10px; font-size: 15px;">Balance</th>
                    <th class="text-center" style="font-weight: bold; height: 40px; width:10px; font-size: 15px;">&nbsp;&nbsp;&nbsp;&nbsp;Note&nbsp;&nbsp;&nbsp;&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $id = 1;
                $totalPrice = 0;
                $rowsPerPage = 15;
                $currentPageRows = 0;

                if ($result_create && $result_create->num_rows > 0) :
                    while ($row = $result_create->fetch_assoc()) :
                        $medical_data = json_decode($row['medical'], true);

                        if (isset($medical_data['price']) && is_numeric($medical_data['price'])) {
                            $totalPrice += $medical_data['price'];
                        }
                ?>
                        <tr>
                            <td class="text-center" scope="row"><?php echo $id++; ?></td>
                            <td><?php echo $medical_data['employee_name']; ?></td>
                            <td class="text-center"><?php echo $medical_data['employee_id']; ?></td>
                            <td class="text-center"><?php echo $medical_data['department']; ?></td>
                            <td><?php echo $medical_data['remark_memo']; ?></td>
                            <td class="text-right"><?php echo number_format($medical_data['price'], 2); ?></td>
                            <td class="text-right"><?php echo number_format($medical_data['balance'], 2); ?></td>
                            <td class="text-center" style=" font-size: 13px;"><?php echo $medical_data['owner']; ?></td>
                        </tr>
                        <?php
                        $currentPageRows++;
                        if ($currentPageRows == $rowsPerPage) {
                            $currentPageRows = 0;
                        ?>
            </tbody>
        </table>
        <div style="page-break-after: always;"></div>
        <br />
        <br />
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center" style="font-weight: bold; height: 40px; width:10px; font-size: 15px;">No.</th>
                    <th class="text-center" style="font-weight: bold; height: 40px; width:10px; font-size: 15px;">Name</th>
                    <th class="text-center" style="font-weight: bold; height: 40px; width:10px; font-size: 15px;">Emp.ID</th>
                    <th class="text-center" style="font-weight: bold; height: 40px; width:10px; font-size: 15px;">Department</th>
                    <th class="text-center" style="font-weight: bold; height: 40px; width:250px; font-size: 15px;">Detail</th>
                    <th class="text-center" style="font-weight: bold; height: 40px; width:10px; font-size: 15px;">Amount</th>
                    <th class="text-center" style="font-weight: bold; height: 40px; width:10px; font-size: 15px;">Balance</th>
                    <th class="text-center" style="font-weight: bold; height: 40px; width:10px; font-size: 15px;">&nbsp;&nbsp;&nbsp;&nbsp;Note&nbsp;&nbsp;&nbsp;&nbsp;</th>
                </tr>
            </thead>
            <tbody>
            <?php
                        }
                    endwhile;

                    $remainingRows = $rowsPerPage - $currentPageRows;
                    for ($i = 0; $i < $remainingRows; $i++) :
            ?>
            <tr>
                <td class="text-center" scope="row">&nbsp;</td>
                <td>&nbsp;</td>
                <td class="text-center">&nbsp;</td>
                <td class="text-center">&nbsp;</td>
                <td>&nbsp;</td>
                <td class="text-right">&nbsp;</td>
                <td class="text-right">&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        <?php
                    endfor;
                else :
        ?>
        <tr>
            <td colspan="8" class="text-center">No records found</td>
        </tr>
    <?php endif; ?>

            </tbody>
        </table>

        <p align="right">
            <span style="font-weight: bold; font-family: Arial, sans-serif; font-size: 14px;">
                Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </span>
            <strong>
                <span style="font-weight: bold; font-family: Arial, sans-serif; font-size: 14px; position: relative; padding-bottom: 4px; border-bottom: 3px double black;">
                    &nbsp; <?php echo number_format($totalPrice, 2); ?>
                </span>
            </strong>
        </p>

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