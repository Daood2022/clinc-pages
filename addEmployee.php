<?php
include('server.php'); // تأكد من تضمين ملف الاتصال بقاعدة البيانات هنا

// إعداد مصفوفة الموظف الافتراضية
$employee = [
    'first_name' => '',
    'last_name' => '',
    'email' => '',
    'phone_number' => '',
    'hire_date' => '',
    'job_title' => '',
    'salary' => ''
];

// تحقق مما إذا كان هناك معرف موظف لتحديث بياناته
if (isset($_GET['employee_id'])) {
    $employee_id = intval($_GET['employee_id']); // تحويل المعرف إلى عدد صحيح
    $query = "SELECT * FROM employees WHERE employee_id = ?";
    if ($stmt = $db->prepare($query)) {
        $stmt->bind_param('i', $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $employee = $result->fetch_assoc();

        if (!$employee) {
            die("لم يتم العثور على موظف بهذا المعرف.");
        }
    } else {
        die("فشل تحضير الاستعلام: " . $db->error);
    }
}

// معالجة إضافة أو تعديل الموظف
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $hire_date = $_POST['hire_date'];
    $job_title = $_POST['job_title'];
    $salary = $_POST['salary'];

    // إذا كان هناك employee_id، نقوم بالتعديل
    if (isset($_POST['employee_id']) && !empty($_POST['employee_id'])) {
        $employee_id = intval($_POST['employee_id']); // تحويل المعرف إلى عدد صحيح
        $query = "UPDATE employees SET first_name=?, last_name=?, email=?, phone_number=?, hire_date=?, job_title=?, salary=? WHERE employee_id=?";
        if ($stmt = $db->prepare($query)) {
            $stmt->bind_param('ssssssii', $first_name, $last_name, $email, $phone_number, $hire_date, $job_title, $salary, $employee_id);
            if ($stmt->execute()) {
                header('Location: employeesData.php'); // إعادة التوجيه إلى صفحة البيانات
                exit();
            } else {
                die("فشل تنفيذ الاستعلام: " . $stmt->error);
            }
        } else {
            die("فشل تحضير الاستعلام: " . $db->error);
        }
    } else {
        // إضافة موظف جديد
        $query = "INSERT INTO employees (first_name, last_name, email, phone_number, hire_date, job_title, salary) VALUES (?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $db->prepare($query)) {
            $stmt->bind_param('ssssssi', $first_name, $last_name, $email, $phone_number, $hire_date, $job_title, $salary);
            if ($stmt->execute()) {
                header('Location: employeesData.php'); // إعادة التوجيه إلى صفحة البيانات
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
    <title><?php echo isset($employee_id) ? 'تعديل بيانات الموظف' : 'إضافة موظف'; ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h2 class="heading"><?php echo isset($employee_id) ? 'تعديل بيانات الموظف' : 'إضافة موظف جديد'; ?></h2>

    <div class="container">
        <div class="row min-vh-100 align-items-center" style="text-align: center;">
            <div class="content">
                <div class="contact">
                    <form method="POST" action="addEmployee.php" style="background-color: lightblue; padding: 20px; border-radius: 8px;">
                        <?php include('errors.php'); ?>

                        <!-- حقل مخفي لتحديد employee_id في حالة التعديل -->
                        <input type="hidden" name="employee_id" value="<?php echo isset($employee_id) ? htmlspecialchars($employee_id) : ''; ?>">

                        <!-- الاسم الأول -->
                        <div class="input-group">
                            <h1><label>الاسم الأول</label></h1>
                            <input type="text" name="first_name" placeholder="من فضلك ادخل الاسم الأول" value="<?php echo htmlspecialchars($employee['first_name'] ?? ''); ?>" required>
                        </div>

                        <!-- الاسم الأخير -->
                        <div class="input-group">
                            <h1><label>الاسم الأخير</label></h1>
                            <input type="text" name="last_name" placeholder="من فضلك ادخل الاسم الأخير" value="<?php echo htmlspecialchars($employee['last_name'] ?? ''); ?>" required>
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div class="input-group">
                            <h1><label>البريد الإلكتروني</label></h1>
                            <input type="email" name="email" placeholder="من فضلك ادخل البريد الإلكتروني" value="<?php echo htmlspecialchars($employee['email'] ?? ''); ?>" required>
                        </div>

                        <!-- رقم الهاتف -->
                        <div class="input-group">
                            <h1><label>رقم الهاتف</label></h1>
                            <input type="text" name="phone_number" placeholder="من فضلك ادخل رقم الهاتف" value="<?php echo htmlspecialchars($employee['phone_number'] ?? ''); ?>">
                        </div>

                        <!-- تاريخ التوظيف -->
                        <div class="input-group">
                            <h1><label>تاريخ التوظيف</label></h1>
                            <input type="date" name="hire_date" value="<?php echo htmlspecialchars($employee['hire_date'] ?? ''); ?>" required>
                        </div>

                        <!-- المسمى الوظيفي -->
                        <div class="input-group">
                            <h1><label>المسمى الوظيفي</label></h1>
                            <input type="text" name="job_title" placeholder="من فضلك ادخل المسمى الوظيفي" value="<?php echo htmlspecialchars($employee['job_title'] ?? ''); ?>">
                        </div>

                        <!-- الراتب -->
                        <div class="input-group">
                            <h1><label>الراتب</label></h1>
                            <input type="number" name="salary" placeholder="من فضلك ادخل الراتب" value="<?php echo htmlspecialchars($employee['salary'] ?? ''); ?>" step="0.01" required>
                        </div>

                        <!-- زر الإرسال -->
                        <div class="footerForm">
                            <button type="submit" class="link-btn" style="font-size: x-large;"><?php echo isset($employee_id) ? 'تعديل' : 'إضافة'; ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
