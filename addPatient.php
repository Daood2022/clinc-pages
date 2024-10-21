<?php
include('server.php');

// جلب بيانات المريض للتعديل إذا تم تمرير patient_id
$patient = [
    'first_name' => '',
    'last_name' => '',
    'date_of_birth' => '',
    'gender' => '',
    'phone' => '',
    'email' => '',
    'address' => ''
];

if (isset($_GET['patient_id'])) {
    $patient_id = intval($_GET['patient_id']);
    $query = "SELECT * FROM patients WHERE patient_id = ?";
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param('i', $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $patient = $result->fetch_assoc();

        if (!$patient) {
            die("لم يتم العثور على مريض بهذا المعرف.");
        }
    } else {
        die("فشل تحضير الاستعلام: " . $db->error);
    }
}

// عند إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    if (isset($_POST['patient_id']) && !empty($_POST['patient_id'])) {
        $patient_id = intval($_POST['patient_id']);
        $query = "UPDATE patients SET first_name=?, last_name=?, date_of_birth=?, gender=?, phone=?, email=?, address=? WHERE patient_id=?";
        if ($stmt = $db->prepare($query)) {
            $stmt->bind_param('sssssssi', $first_name, $last_name, $date_of_birth, $gender, $phone, $email, $address, $patient_id);
            if ($stmt->execute()) {
                header('Location: pationData.php');
                exit();
            } else {
                die("فشل تنفيذ الاستعلام: " . $stmt->error);
            }
        } else {
            die("فشل تحضير الاستعلام: " . $db->error);
        }
    } else {
        $query = "INSERT INTO patients (first_name, last_name, date_of_birth, gender, phone, email, address) VALUES (?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $db->prepare($query)) {
            $stmt->bind_param('sssssss', $first_name, $last_name, $date_of_birth, $gender, $phone, $email, $address);
            if ($stmt->execute()) {
                header('Location: pationData.php');
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
    <title><?php echo isset($patient_id) ? 'تعديل بيانات المريض' : 'إضافة مريض'; ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h2 class="heading"><?php echo isset($patient_id) ? 'تعديل بيانات المريض' : 'إضافة مريض جديد'; ?></h2>

    <div class="container">
        <div class="row min-vh-100 align-items-center" style="text-align: center;">
            <div class="content">
                <div class="contact">
                    <form method="POST" action="addPatient.php" style="background-color: lightblue; padding: 20px; border-radius: 8px;">
                        <input type="hidden" name="patient_id" value="<?php echo isset($patient['patient_id']) ? htmlspecialchars($patient['patient_id']) : ''; ?>">

                        <!-- الاسم الأول -->
                        <div class="input-group">
                            <h1><label>الاسم الأول</label></h1>
                            <input type="text" name="first_name" placeholder="من فضلك ادخل الاسم الأول" value="<?php echo htmlspecialchars($patient['first_name']); ?>" required>
                        </div>

                        <!-- الاسم الأخير -->
                        <div class="input-group">
                            <h1><label>الاسم الأخير</label></h1>
                            <input type="text" name="last_name" placeholder="من فضلك ادخل الاسم الأخير" value="<?php echo htmlspecialchars($patient['last_name']); ?>" required>
                        </div>

                        <!-- تاريخ الميلاد -->
                        <div class="input-group">
                            <h1><label>تاريخ الميلاد</label></h1>
                            <input type="date" name="date_of_birth" value="<?php echo htmlspecialchars($patient['date_of_birth']); ?>" required>
                        </div>

                        <!-- الجنس -->
                        <div class="input-group">
                            <h1><label>الجنس</label></h1>
                            <select name="gender" required>
                                <option value="ذكر" <?php echo ($patient['gender'] == 'ذكر') ? 'selected' : ''; ?>>ذكر</option>
                                <option value="أنثى" <?php echo ($patient['gender'] == 'أنثى') ? 'selected' : ''; ?>>أنثى</option>
                            </select>
                        </div>

                        <!-- الهاتف -->
                        <div class="input-group">
                            <h1><label>رقم الهاتف</label></h1>
                            <input type="tel" name="phone" placeholder="من فضلك ادخل رقم الهاتف" value="<?php echo htmlspecialchars($patient['phone']); ?>" required>
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div class="input-group">
                            <h1><label>البريد الإلكتروني</label></h1>
                            <input type="email" name="email" placeholder="من فضلك ادخل البريد الإلكتروني" value="<?php echo htmlspecialchars($patient['email']); ?>" required>
                        </div>

                        <!-- العنوان -->
                        <div class="input-group">
                            <h1><label>العنوان</label></h1>
                            <textarea name="address" placeholder="من فضلك ادخل العنوان" required><?php echo htmlspecialchars($patient['address']); ?></textarea>
                        </div>

                        <!-- زر الإرسال -->
                        <div class="footerForm">
                            <button type="submit" class="btn"><?php echo isset($patient_id) ? 'تعديل' : 'إضافة'; ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
