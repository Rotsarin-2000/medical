<script>
    var amountInput = document.getElementById('amount');
    var balanceInput = document.getElementById('balance');
    var priceInput = document.getElementById('price');

    // ฟังก์ชันสำหรับคำนวณ balance
    function calculateBalance() {
        var amount = parseFloat(amountInput.value) || 0;
        var price = parseFloat(priceInput.value) || 0;

        var balance = amount - price;

        balanceInput.value = balance.toFixed(2);
    }

    priceInput.addEventListener('input', calculateBalance);

    // หากต้องการคำนวณ balance ตอนโหลดหน้า
    window.onload = calculateBalance;
</script>

<script>
    function toggleForms(id) {
        // หาฟอร์มที่มี id ตามที่คลิก
        var form = document.getElementById('form_' + id);

        const employeeForm = document.getElementById('employee-form');
        const editForm = document.getElementById('edit-form');
        // เช็คว่าฟอร์มแสดงอยู่หรือไม่
        if (form.style.display === "none") {
            form.style.display = "block"; // แสดงฟอร์ม
        } else {
            form.style.display = "none"; // ซ่อนฟอร์ม
        }
    }

    function toggleEditForm() {
        var editForm = document.getElementById('edit-form');
        var employeeForm = document.getElementById('employee-form');

        if (editForm.style.display === 'none' || editForm.style.display === '') {
            editForm.style.display = 'block';
            employeeForm.style.display = 'none';
        } else {
            editForm.style.display = 'none';
            employeeForm.style.display = 'block';
        }
    }

    if (startWork) {
        var startDate = new Date(startWork);
        startDate.setDate(startDate.getDate() + 119);
        duoWorkInput.value = startDate.toISOString().split('T')[0];

        // คำนวณอายุงาน (ในปีและเดือน) โดยใช้วันที่ในฟิลด์ duo_work
        var today = new Date();
        var yearsDifference = today.getFullYear() - startDate.getFullYear();
        var monthsDifference = today.getMonth() - startDate.getMonth();
        // var daysDifference = today.getDate() - startDate.getDate();

        // if (daysDifference < 0) {
        //     monthsDifference--;
        // }

        if (monthsDifference < 0) {
            yearsDifference--;
            monthsDifference += 12;
        }

        // let amount = 0;
        // if (yearsDifference >= 1) {
        //     amount = 5000;
        // } else {
        let amount = 0;
        if (yearsDifference === 0 && today.getFullYear() > startDate.getFullYear() && monthsDifference >= 1) {
            yearsDifference = 1;
            monthsDifference = 0;
            amount = 5000;
        } else if (yearsDifference >= 1) {
            amount = 5000;
        } else {
            switch (monthsDifference) {
                case 0:
                    amount = 0;
                    break;
                case 1:
                    amount = 250;
                    break;
                case 2:
                    amount = 500;
                    break;
                case 3:
                    amount = 750;
                    break;
                case 4:
                    amount = 1000;
                    break;
                case 5:
                    amount = 1500;
                    break;
                case 6:
                    amount = 2000;
                    break;
                case 7:
                    amount = 2500;
                    break;
                case 8:
                    amount = 3000;
                    break;
                case 9:
                    amount = 3500;
                    break;
                case 10:
                    amount = 4000;
                    break;
                case 11:
                    amount = 4500;
                    break;
                default:
                    amount = 5000;
            }
        }

        // แสดงอายุงานในฟิลด์ age_year
        if (yearsDifference >= 1) {
            ageYearInput.value = '1 Y'; // แสดงเป็น 1 ปี หากครบปี
        } else {
            ageYearInput.value = monthsDifference + ' M'; // แสดงเดือนเมื่อยังไม่ครบปี
        }

        amountInput.value = amount;

        let expenses = 0;
        let balance = amount - expenses;
        balanceInput.value = balance;
    } else {
        duoWorkInput.value = '';
        ageYearInput.value = '';
        amountInput.value = '';
    }
</script>


<!-- คำนวณ balance หน้า create -->
<script>
    var employeeSelect = document.getElementById('employee_id');
    var amountInput = document.getElementById('amount');
    var valueInput = document.getElementById('value');
    var balanceInput = document.getElementById('balance');
    var priceInput = document.getElementById('price');

    // ฟังก์ชันคำนวณ balance กรณีไม่มีข้อมูลในฐานข้อมูล
    function calculateBalanceWithAmount() {
        var amount = parseFloat(amountInput.value) || 0;
        var price = parseFloat(priceInput.value) || 0;
        var balance = amount - price;
        balanceInput.value = balance.toFixed(2);
    }

    // ฟังก์ชันสำหรับคำนวณ balance - price ในกรณีที่ดึง balance จากฐานข้อมูลมาแล้ว
    function calculateBalanceWithDatabase(balanceFromDB) {
        var price = parseFloat(priceInput.value) || 0;
        balanceInput.value = (balanceFromDB - price).toFixed(2);
    }

    // ฟังก์ชันเรียกข้อมูลจากฐานข้อมูลเมื่อเลือกพนักงาน
    employeeSelect.addEventListener('change', function() {
        var employee_id = employeeSelect.value;

        if (employee_id !== "" && employee_id !== "--- All ---") {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "fetch_employee_data.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);

                    if (response.balance !== null) {
                        amountInput.value = response.amount;
                        valueInput.value = response.value;
                        calculateBalanceWithDatabase(parseFloat(response.balance));

                        priceInput.addEventListener('input', function() {
                            calculateBalanceWithDatabase(parseFloat(response.balance));
                        });
                    } else {
                        calculateBalanceWithAmount();
                        priceInput.addEventListener('input', calculateBalanceWithAmount);
                    }
                }
            };

            xhr.send("employee_id=" + encodeURIComponent(employee_id));
        } else {
            amountInput.value = '';
            valueInput.value = '0';
            balanceInput.value = '';
        }
    });

    window.onload = calculateBalanceWithAmount;
</script>