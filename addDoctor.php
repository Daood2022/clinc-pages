<?php
include('server.php');

// جلب بيانات الطبيب للتعديل إذا تم تمرير doctor_id
$doctor = [
    'first_name' => '',
    'last_name' => '',
    'specialization' => '',
    'phone' => '',
    'username' => '',
    'email' => ''
];

if (isset($_GET['doctor_id'])) {
    $doctor_id = intval($_GET['doctor_id']); // تحويل المعرف إلى عدد صحيح
    $query = "SELECT * FROM doctors WHERE doctor_id = ?";
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param('i', $doctor_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $doctor = $result->fetch_assoc();

        if (!$doctor) {
            die("لم يتم العثور على طبيب بهذا المعرف.");
        }
    } else {
        die("فشل تحضير الاستعلام: " . $db->error);
    }
}

// عند إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $specialization = $_POST['specialization'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // إذا كان هناك doctor_id، فنحن بصدد تعديل طبيب موجود
    if (isset($_POST['doctor_id']) && !empty($_POST['doctor_id'])) {
        $doctor_id = intval($_POST['doctor_id']); // تحويل المعرف إلى عدد صحيح
        // تعديل الطبيب
        $query = "UPDATE doctors SET first_name=?, last_name=?, specialization=?, phone=?, username=?, email=? WHERE doctor_id=?";
        if ($stmt = $db->prepare($query)) {
            $stmt->bind_param('ssssssi', $first_name, $last_name, $specialization, $phone, $username, $email, $doctor_id);
            if ($stmt->execute()) {
                // التأكد من أن التعديل تم بنجاح
                if ($stmt->affected_rows > 0) {
                    header('location: doctorsData.php');
                    exit();
                } else {
                    echo "لم يتم إجراء أي تغييرات.";
                }
            } else {
                die("فشل تنفيذ الاستعلام: " . $stmt->error);
            }
        } else {
            die("فشل تحضير الاستعلام: " . $db->error);
        }
    } else {
        // إضافة طبيب جديد
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // هاش لكلمة المرور
        $query = "INSERT INTO doctors (first_name, last_name, specialization, phone, username, password, email) VALUES (?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $db->prepare($query)) {
            $stmt->bind_param('sssssss', $first_name, $last_name, $specialization, $phone, $username, $password, $email);
            if ($stmt->execute()) {
                header('location: doctorsData.php');
                exit();
            } else {
                die("فشل تنفيذ الاستعلام: " . $stmt->error);
            }
        } else {
            die("فشل تحضير الاستعلام: " . $db->error);
        }
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <title><?php echo isset($doctor_id) ? 'تعديل بيانات الطبيب' : 'إضافة طبيب'; ?></title>
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
    <h2 class="heading"><?php echo isset($doctor_id) ? 'تعديل بيانات الطبيب' : 'إضافة طبيب جديد'; ?></h2>

    <div class="container">
        <div class="row min-vh-100 align-items-center" style="text-align: center;">
            <div class="content">
                <div class="contact">
                    <form method="POST" action="addDoctor.php" style="background-color: lightblue; padding: 20px; border-radius: 8px;">
                        <?php include('errors.php'); ?>

                        <!-- حقل مخفي لتحديد doctor_id في حالة التعديل -->
                        <input type="hidden" name="doctor_id" value="<?php echo isset($doctor_id) ? htmlspecialchars($doctor_id) : ''; ?>">

                        <!-- الاسم الأول -->
                        <div class="input-group">
                            <h1><label>الاسم الأول</label></h1>
                            <input type="text" name="first_name" placeholder="من فضلك ادخل الاسم الأول" value="<?php echo htmlspecialchars($doctor['first_name'] ?? ''); ?>" required>
                        </div>

                        <!-- الاسم الأخير -->
                        <div class="input-group">
                            <h1><label>الاسم الأخير</label></h1>
                            <input type="text" name="last_name" placeholder="من فضلك ادخل الاسم الأخير" value="<?php echo htmlspecialchars($doctor['last_name'] ?? ''); ?>" required>
                        </div>

                        <!-- التخصص -->
                        <div class="input-group">
                            <h1><label>التخصص</label></h1>
                            <input type="text" name="specialization" placeholder="من فضلك ادخل التخصص" value="<?php echo htmlspecialchars($doctor['specialization'] ?? ''); ?>">
                        </div>

                        <!-- الهاتف -->
                        <div class="input-group">
                            <h1><label>رقم الهاتف</label></h1>
                            <input type="text" name="phone" placeholder="من فضلك ادخل رقم الهاتف" value="<?php echo htmlspecialchars($doctor['phone'] ?? ''); ?>">
                        </div>

                        <!-- اسم المستخدم -->
                        <div class="input-group">
                            <h1><label>اسم المستخدم</label></h1>
                            <input type="text" name="username" placeholder="من فضلك ادخل اسم المستخدم" value="<?php echo htmlspecialchars($doctor['username'] ?? ''); ?>" required>
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div class="input-group">
                            <h1><label>البريد الإلكتروني</label></h1>
                            <input type="email" name="email" placeholder="من فضلك ادخل البريد الإلكتروني" value="<?php echo htmlspecialchars($doctor['email'] ?? ''); ?>" required>
                        </div>

                        <!-- كلمة المرور عند إضافة طبيب جديد -->
                        <?php if (!isset($doctor_id)) { ?>
                            <div class="input-group">
                                <h1><label>كلمة السر</label></h1>
                                <input type="password" name="password" placeholder="من فضلك ادخل كلمة السر" required>
                            </div>
                        <?php } ?>

                        <!-- زر الإرسال -->
                        <div class="footerForm">
                            <button type="submit" class="btn"><?php echo isset($doctor_id) ? 'تعديل' : 'إضافة'; ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
