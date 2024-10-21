<?php 
include('server.php'); 

// استرجاع المرضى مع إمكانية البحث
$searchName = '';
if (isset($_POST['search_name'])) {
    $searchName = $_POST['search_name'];
}

// استخدام العبارات المحضرة لتجنب الحقن SQL
$query = $db->prepare("
SELECT * FROM patients
WHERE first_name LIKE ? OR last_name LIKE ?
");
$searchTerm = "%$searchName%";
$query->bind_param('ss', $searchTerm, $searchTerm);
$query->execute();
$results = $query->get_result();

// الحذف
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']); // التأكد من تحويل المعرف إلى عدد صحيح
    // التحقق من وجود المريض قبل الحذف
    $checkQuery = $db->prepare("SELECT * FROM patients WHERE patient_id = ?");
    $checkQuery->bind_param('i', $id);
    $checkQuery->execute();
    $checkResult = $checkQuery->get_result();

    if ($checkResult->num_rows > 0) {
        $deleteQuery = $db->prepare("DELETE FROM patients WHERE patient_id = ?");
        $deleteQuery->bind_param('i', $id);
        if ($deleteQuery->execute()) {
            header('Location: pationData.php'); // تأكد من إعادة التوجيه إلى pationData.php
            exit();
        } else {
            echo "حدث خطأ أثناء حذف المريض: " . $deleteQuery->error;
        }
    } else {
        echo "المريض غير موجود.";
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <title>إدارة المرضى</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
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

    <script>
        function deletePatient(patientId) {
            const userConfirmed = confirm("هل تريد حقا حذف هذا السجل؟");
            if (userConfirmed) {
                window.location.href = 'pationData.php?delete_id=' + patientId; // تأكد من أن الرابط صحيح
            }
        }
    </script>

    <?php include('header.php'); ?>

    <div style="margin: 5px 100px 10px;">
        <h2 class="heading">إدارة المرضى</h2>

        <!-- نموذج البحث -->
        <form method="POST" action="pationData.php" style="background-color: lightblue; padding: 20px; border-radius: 8px;">
            <h2><label for="search_name">البحث حسب الاسم:</label></h2>
            <input type="text" name="search_name" id="search_name" placeholder="أدخل اسم المريض" value="<?php echo htmlspecialchars($searchName); ?>">
            <button type="submit" class="link-btn">بحث</button>
        </form>

        <div class='container'>
            <table class='responsive-table' style="width: -webkit-fill-available; font-size: 20px;">
                <?php include('errors.php'); ?>
                <tr>
                    <th>الاسم الأول</th>
                    <th>الاسم الأخير</th>
                    <th>تاريخ الميلاد</th>
                    <th>الجنس</th>
                    <th>الهاتف</th>
                    <th>البريد الإلكتروني</th>
                    <th>تعديل</th>
                    <th>حذف المريض</th>
                </tr>
                <?php
                if ($results && $results->num_rows > 0) {
                    while ($patient = $results->fetch_assoc()) {
                ?>
                        <tr>
                            <td><?php echo isset($patient['first_name']) ? htmlspecialchars($patient['first_name']) : 'غير متوفر'; ?></td>
                            <td><?php echo isset($patient['last_name']) ? htmlspecialchars($patient['last_name']) : 'غير متوفر'; ?></td>
                            <td><?php echo isset($patient['date_of_birth']) ? htmlspecialchars($patient['date_of_birth']) : 'غير متوفر'; ?></td>
                            <td><?php echo isset($patient['gender']) ? htmlspecialchars($patient['gender']) : 'غير متوفر'; ?></td>
                            <td><?php echo isset($patient['phone']) ? htmlspecialchars($patient['phone']) : 'غير متوفر'; ?></td>
                            <td><?php echo isset($patient['email']) ? htmlspecialchars($patient['email']) : 'غير متوفر'; ?></td>
                            <td><a href="./addPatient.php?patient_id=<?php echo $patient['patient_id']; ?>" class="link-btn" style="font-size: x-large;">تعديل</a></td>
                            <td><button id="deleteButton" onclick="deletePatient(<?php echo $patient['patient_id']; ?>)" class="link-btn" style="font-size: x-large;">حذف المريض</button></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='8'><h4>لا يوجد بيانات</h4></td></tr>";
                }
                ?>
                <tr>
                    <td colspan='8'><a href='./addPatient.php'><h4>إضافة مريض</h4></a></td>
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
