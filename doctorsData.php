<?php 
include('server.php'); 

// استرجاع الأطباء مع إمكانية البحث
$searchName = '';
if (isset($_POST['search_name'])) {
    $searchName = $_POST['search_name'];
}

// استخدام العبارات المحضرة لتجنب الحقن SQL
$query = $db->prepare("
SELECT * FROM doctors
WHERE first_name LIKE ? OR last_name LIKE ?
");
$searchTerm = "%$searchName%";
$query->bind_param('ss', $searchTerm, $searchTerm);
$query->execute();
$results = $query->get_result();

// الحذف
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    // التحقق من وجود الطبيب قبل الحذف
    $checkQuery = $db->prepare("SELECT * FROM doctors WHERE doctor_id = ?");
    $checkQuery->bind_param('i', $id);
    $checkQuery->execute();
    $checkResult = $checkQuery->get_result();

    if ($checkResult->num_rows > 0) {
        $deleteQuery = $db->prepare("DELETE FROM doctors WHERE doctor_id = ?");
        $deleteQuery->bind_param('i', $id);
        if ($deleteQuery->execute()) {
            header('location: doctorsData.php');
            exit(); // التأكد من توقف تنفيذ الكود بعد إعادة التوجيه
        } else {
            echo "حدث خطأ أثناء حذف الطبيب: " . $deleteQuery->error;
        }
    } else {
        echo "الطبيب غير موجود.";
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <title>إدارة الأطباء</title>
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
        function deleteDoctor(doctorId) {
            const userConfirmed = confirm("هل تريد حقا حذف هذا السجل؟");
            if (userConfirmed) {
                window.location.href = 'doctorsData.php?delete_id=' + doctorId;
            }
        }
    </script>

    <?php include('header.php'); ?>
    
     

    <div style="margin: 5px 100px 10px;">
        <h2 class="heading">إدارة الأطباء</h2>
        <!-- نموذج البحث -->
        <form method="POST" action="doctorsData.php" style="margin-bottom: 20px;">
            <h2><label for="search_name">البحث حسب الاسم:</label></h2>
            <input type="text" name="search_name" id="search_name" placeholder="أدخل اسم الطبيب" value="<?php echo htmlspecialchars($searchName); ?>">
            <button type="submit" class="link-btn">بحث</button>
           
        </form>

        <div class='container'>
            <table class='responsive-table' style="width:-webkit-fill-available; font-size: 20px;">
                <?php include('errors.php'); ?>
                <tr>
                    <th>الاسم الأول</th>
                    <th>الاسم الأخير</th>
                    <th>التخصص</th>
                    <th>الهاتف</th>
                    <th>اسم المستخدم</th>
                    <th>البريد الإلكتروني</th>
                    <th>تعديل</th>
                    <th>حذف الطبيب</th>
                </tr>
                <?php
                if ($results->num_rows > 0) {
                    while ($doctor = $results->fetch_assoc()) {
                ?>
                        <tr>
                            <td><?php echo isset($doctor['first_name']) ? htmlspecialchars($doctor['first_name']) : 'غير متوفر'; ?></td>
                            <td><?php echo isset($doctor['last_name']) ? htmlspecialchars($doctor['last_name']) : 'غير متوفر'; ?></td>
                            <td><?php echo isset($doctor['specialization']) ? htmlspecialchars($doctor['specialization']) : 'غير متوفر'; ?></td>
                            <td><?php echo isset($doctor['phone']) ? htmlspecialchars($doctor['phone']) : 'غير متوفر'; ?></td>
                            <td><?php echo isset($doctor['username']) ? htmlspecialchars($doctor['username']) : 'غير متوفر'; ?></td>
                            <td><?php echo isset($doctor['email']) ? htmlspecialchars($doctor['email']) : 'غير متوفر'; ?></td>
                            <td><a href="./addDoctor.php?doctor_id=<?php echo $doctor['doctor_id']; ?>" class="link-btn" style="font-size: x-large;">تعديل</a></td>
                            <td><button id="deleteButton" onclick="deleteDoctor(<?php echo $doctor['doctor_id']; ?>)" class="link-btn" style="font-size: x-large;">حذف الطبيب</button></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='8'><h4>لا يوجد بيانات</h4></td></tr>";
                }
                ?>
                <tr>
                    <td colspan='8'><a href='./addDoctor.php'><h4>إضافة طبيب</h4></a></td>
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
