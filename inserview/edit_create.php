<?php
include '../db/conn.php';

if (!$conn) {
    die("Database connection failed.");
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql_edit = "SELECT * FROM m_creates WHERE id = $id";
    $result_edit = mysqli_query($conn, $sql_edit);

    if ($result_edit) {
        $data = mysqli_fetch_assoc($result_edit);
    } else {
        echo "เกิดข้อผิดพลาดในการดึงข้อมูล: " . mysqli_error($conn);
        exit;
    }

} else {
    echo "ไม่มีการส่งค่า ID มา";
    exit;
}

$conn->close();

// ฝาก script หน้า create
// <script>
// var employeeSelect = document.getElementById('employee_id');
// var employeeNameInput = document.getElementById('employee_name');
// var departmentInput = document.getElementById('department');
// var startWorkInput = document.getElementById('start_work');
// var duoWorkInput = document.getElementById('duo_work');
// var birthdayInput = document.getElementById('birthday');
// var ageYearInput = document.getElementById('age_year');
// var amountInput = document.getElementById('amount');
// var balanceInput = document.getElementById('balance');

// if (employeeSelect) {
//     employeeSelect.addEventListener('change', function() {
//         var selectedOption = employeeSelect.options[employeeSelect.selectedIndex];
//         var employee_name = selectedOption.getAttribute('data-employee_name');
//         var department = selectedOption.getAttribute('data-department');
//         var startWork = selectedOption.getAttribute('data-startwork');
//         var birthday = selectedOption.getAttribute('data-birthday');

//         // แสดงข้อมูลในฟิลด์
//         employeeNameInput.value = employee_name ? employee_name : '';
//         departmentInput.value = department ? department : '';
//         startWorkInput.value = startWork ? startWork : '';
//         birthdayInput.value = birthday ? birthday : '';

//         // คำนวณหา duo_work โดยวันที่เริ่มงาน start work + 119 วัน
//         if (startWork) {
//             var startDate = new Date(startWork);
//             startDate.setDate(startDate.getDate() + 119);
//             duoWorkInput.value = startDate.toISOString().split('T')[0];

//             // คำนวณอายุงาน (ในปีและเดือน) โดยใช้วันที่ในฟิลด์ duo_work
//             var today = new Date();
//             var yearsDifference = today.getFullYear() - startDate.getFullYear();
//             var monthsDifference = today.getMonth() - startDate.getMonth();
//             var daysDifference = today.getDate() - startDate.getDate();

//             if (daysDifference < 0) {
//                 monthsDifference--;
//             }

//             if (monthsDifference < 0) {
//                 yearsDifference--;
//                 monthsDifference += 12;
//             }

//             // แสดงอายุงานในฟิลด์ age_year
//             if (yearsDifference >= 1) {
//                 ageYearInput.value = '1 Y'; // แสดงเป็น 1 ปี หากครบปี
//             } else {
//                 ageYearInput.value = monthsDifference + ' M'; // แสดงเดือนเมื่อยังไม่ครบปี
//             }

//             // หา amount จาก age_year
//             let amount = 0;
//             if (yearsDifference >= 1) {
//                 amount = 5000; // ถ้าอายุงาน 1 ปีขึ้นไป
//             } else {
//                 switch (monthsDifference) {
//                     case 0:
//                         amount = 0;
//                         break;
//                     case 1:
//                         amount = 250;
//                         break;
//                     case 2:
//                         amount = 500;
//                         break;
//                     case 3:
//                         amount = 750;
//                         break;
//                     case 4:
//                         amount = 1000;
//                         break;
//                     case 5:
//                         amount = 1500;
//                         break;
//                     case 6:
//                         amount = 2000;
//                         break;
//                     case 7:
//                         amount = 2500;
//                         break;
//                     case 8:
//                         amount = 3000;
//                         break;
//                     case 9:
//                         amount = 3500;
//                         break;
//                     case 10:
//                         amount = 4000;
//                         break;
//                     case 11:
//                         amount = 4500;
//                         break;
//                     default:
//                         amount = 5000; // default ไว้ที่ 5000 สำหรับอายุงาน 1 ปี ขึ้นไป
//                 }
//             }

//             amountInput.value = amount;

//             let expenses = 0;
//             let balance = amount - expenses;
//             balanceInput.value = balance;
//         } else {
//             duoWorkInput.value = '';
//             ageYearInput.value = '';
//             amountInput.value = '';
//         }

//         console.log('Employee Name: ' + employee_name);
//         console.log('Department: ' + department);
//         console.log('Start Work: ' + startWork);
//         console.log('Duo Work: ' + duoWorkInput.value);
//         console.log('Age Year: ' + ageYearInput.value);
//         console.log('Amount: ' + amountInput.value);
//         console.log('Balance: ' + balanceInput.value);
//         console.log('Birthday: ' + birthday);
//     });
// }

// // var amountInput = document.getElementById('amount');
// // var balanceInput = document.getElementById('balance');
// // var priceInput = document.getElementById('price');

// // // ฟังก์ชันสำหรับคำนวณ balance
// // function calculateBalance() {
// //     var amount = parseFloat(amountInput.value) || 0;
// //     var price = parseFloat(priceInput.value) || 0;

// //     var balance = amount - price;

// //     balanceInput.value = balance.toFixed(2);
// // }

// // priceInput.addEventListener('input', calculateBalance);

// // // หากต้องการคำนวณ balance ตอนโหลดหน้า
// // window.onload = calculateBalance;

// var valueInput = document.getElementById('value');
// var priceInput = document.getElementById('price');

// // ฟังก์ชันคำนวณ value
// function calculateValue() {
//     var currentValue = parseFloat(valueInput.value) || 0;
//     var price = parseFloat(priceInput.value) || 0;

//     var newValue = currentValue + price;

//     valueInput.value = newValue.toFixed(2);

// }

// priceInput.addEventListener('change', calculateValue);

// var employeeSelect = document.getElementById('employee_id');
// var amountInput = document.getElementById('amount');
// var valueInput = document.getElementById('value');
// var balanceInput = document.getElementById('balance');
// var priceInput = document.getElementById('price');

// // ฟังก์ชันคำนวณ balance กรณีไม่มีข้อมูลในฐานข้อมูล
// function calculateBalanceWithAmount() {
//     var amount = parseFloat(amountInput.value) || 0;
//     var price = parseFloat(priceInput.value) || 0;
//     var balance = amount - price;
//     balanceInput.value = balance.toFixed(2);
// }

// // ฟังก์ชันสำหรับคำนวณ balance - price ในกรณีที่ดึง balance จากฐานข้อมูลมาแล้ว
// function calculateBalanceWithDatabase(balanceFromDB) {
//     var price = parseFloat(priceInput.value) || 0;
//     balanceInput.value = (balanceFromDB - price).toFixed(2);
// }

// // ฟังก์ชันเรียกข้อมูลจากฐานข้อมูลเมื่อเลือกพนักงาน
// employeeSelect.addEventListener('change', function() {
//     var employee_id = employeeSelect.value;

//     if (employee_id !== "" && employee_id !== "--- All ---") {
//         var xhr = new XMLHttpRequest();
//         xhr.open("POST", "fetch_employee_data.php", true);
//         xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

//         xhr.onreadystatechange = function() {
//             if (xhr.readyState === 4 && xhr.status === 200) {
//                 var response = JSON.parse(xhr.responseText);

//                 if (response.balance !== null) {
//                     amountInput.value = response.amount;
//                     valueInput.value = response.value;
//                     calculateBalanceWithDatabase(parseFloat(response.balance));

//                     priceInput.addEventListener('input', function() {
//                         calculateBalanceWithDatabase(parseFloat(response.balance));
//                     });
//                 } else {
//                     calculateBalanceWithAmount();
//                     priceInput.addEventListener('input', calculateBalanceWithAmount);
//                 }
//             }
//         };

//         xhr.send("employee_id=" + encodeURIComponent(employee_id));
//     } else {
//         amountInput.value = '';
//         valueInput.value = '0';
//         balanceInput.value = '';
//     }
// });

// window.onload = calculateBalanceWithAmount;

// // function calculateScriptValues() {
// //     // ฟังก์ชันที่คำนวณค่า Amount, Value, Balance ตาม script
// //     var defaultAmount = 0;
// //     var defaultValue = parseFloat(valueInput.value) || 0;
// //     var defaultPrice = parseFloat(priceInput.value) || 0;

// //     // ตัวอย่างการตั้งค่าตัวเลขเริ่มต้น
// //     amountInput.value = defaultAmount;
// //     valueInput.value = defaultValue;
// //     balanceInput.value = (defaultAmount - defaultPrice).toFixed(2);
// // }
// </script>