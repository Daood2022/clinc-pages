<?php
include('server.php'); 

// استرجاع الموظفين مع إمكانية البحث
$searchName = '';
if (isset($_POST['search_name'])) {
    $searchName = $_POST['search_name'];
}

// استخدام العبارات المحضرة لتجنب الحقن SQL
$query = $db->prepare("
SELECT * FROM employees
WHERE first_name LIKE ? OR last_name LIKE ?
");
$searchTerm = "%$searchName%";
$query->bind_param('ss', $searchTerm, $searchTerm);
$query->execute();
$results = $query->get_result();

if ($query->error) {
    die("خطأ في الاستعلام: " . $query->error);
}

// الحذف
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']); // التأكد من تحويل المعرف إلى عدد صحيح
    // التحقق من وجود الموظف قبل الحذف
    $checkQuery = $db->prepare("SELECT * FROM employees WHERE employee_id = ?");
    $checkQuery->bind_param('i', $id);
    $checkQuery->execute();
    $checkResult = $checkQuery->get_result();

    if ($checkResult->num_rows > 0) {
        $deleteQuery = $db->prepare("DELETE FROM employees WHERE employee_id = ?");
        $deleteQuery->bind_param('i', $id);
        if ($deleteQuery->execute()) {
            header('location: employeesData.php');
            exit();
        } else {
            echo "حدث خطأ أثناء حذف الموظف: " . $deleteQuery->error;
        }
    } else {
        echo "الموظف غير موجود.";
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <title>إدارة الموظفين</title>
    <link rel="stylesheet" type="text/css" href="style.css">
	 <style>
        body {
            background-color: #f0f8ff;
        }

        .heading {
            font-size: 2.5rem;
            color: #007bff;
            text-align: center;
            margin-bottom: 30px;
            padding-top: 20px;
        }

        .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .box {
            background-color: #e0f7fa;
            border: 1px solid #007bff;
            border-radius: 15px;
            text-align: center;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
            color: black;
        }

        .box img {
            width: 100px;
            height: 100px;
            margin-bottom: 15px;
        }

        .box h3 {
            font-size: 1.5rem;
            color: #007bff;
        }

        .box:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .box-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <script>
        function deleteEmployee(employeeId) {
            const userConfirmed = confirm("هل تريد حقا حذف هذا السجل؟");
            if (userConfirmed) {
                window.location.href = 'employeesData.php?delete_id=' + employeeId;
            }
        }
    </script>

    <?php include('header.php'); ?>

    <div style="margin: 5px 100px 10px;">
        <h2 class="heading">إدارة الموظفين</h2>

        <form method="POST" action="employeesData.php" style="margin-bottom: 20px;">
            <h2><label for="search_name">البحث حسب الاسم:</label></h2>
            <input type="text" name="search_name" id="search_name" placeholder="أدخل اسم الموظف" value="<?php echo htmlspecialchars($searchName); ?>">
            <button type="submit" class="link-btn">بحث</button>
        </form>

        <div class='container'>
            <table class='responsive-table' style="width:-webkit-fill-available; font-size: 20px;">
                <?php include('errors.php'); ?>
                <tr>
                    <th>الاسم الأول</th>
                    <th>الاسم الأخير</th>
                    <th>البريد الإلكتروني</th>
                    <th>رقم الهاتف</th>
                    <th>تاريخ التوظيف</th>
                    <th>المسمى الوظيفي</th>
                    <th>الراتب</th>
                    <th>تعديل</th>
                    <th>حذف الموظف</th>
                </tr>
                <?php
                if ($results && $results->num_rows > 0) {
                    while ($employee = $results->fetch_assoc()) {
                ?>
                        <tr>
                            <td><?php echo isset($employee['first_name']) ? htmlspecialchars($employee['first_name']) : 'غير متوفر'; ?></td>
                            <td><?php echo isset($employee['last_name']) ? htmlspecialchars($employee['last_name']) : 'غير متوفر'; ?></td>
                            <td><?php echo isset($employee['email']) ? htmlspecialchars($employee['email']) : 'غير متوفر'; ?></td>
                            <td><?php echo isset($employee['phone_number']) ? htmlspecialchars($employee['phone_number']) : 'غير متوفر'; ?></td>
                            <td><?php echo isset($employee['hire_date']) ? htmlspecialchars($employee['hire_date']) : 'غير متوفر'; ?></td>
                            <td><?php echo isset($employee['job_title']) ? htmlspecialchars($employee['job_title']) : 'غير متوفر'; ?></td>
                            <td><?php echo isset($employee['salary']) ? htmlspecialchars($employee['salary']) : 'غير متوفر'; ?></td>
                            <td><a href="./addEmployee.php?employee_id=<?php echo $employee['employee_id']; ?>" class="link-btn" style="font-size: x-large;">تعديل</a></td>
                            <td><button id="deleteButton" onclick="deleteEmployee(<?php echo $employee['employee_id']; ?>)" class="link-btn" style="font-size: x-large;">حذف الموظف</button></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='9'><h4>لا يوجد بيانات</h4></td></tr>";
                }
                ?>
                <tr>
                    <td colspan='9'><a href='./addEmployee.php' ><h4>إضافة موظف</h4></a></td>
                </tr>
            </table>
        </div>
        <div class="box-container container">
            <div class="box">
                <a href='./admin.php'>
                    <h3>إدارة النظام</h3>
                </a> 
            </div>
        </div>
        <?php include('footer.php'); ?>
    </div>
</body>
</html>
