<?php include('server.php'); ?>

<?php 
if (isset($_POST['login_user'])) {
    // استقبال اسم المستخدم وكلمة السر من الطلب POST
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    // تحقق مما إذا كانت الحقول فارغة
    if (empty($username)) {
        array_push($errors, "اسم المستخدم مطلوب");
    }
    if (empty($password)) {
        array_push($errors, "كلمة السر مطلوبة");
    }

    // إذا لم يكن هناك أخطاء
    if (count($errors) == 0) {
        // تشفير كلمة المرور (يجب استخدام password_hash في البيئات الإنتاجية بدلاً من md5)
        $password = md5($password);

        // البحث في قاعدة البيانات عن المستخدم
        $query = "SELECT * FROM users WHERE (username='$username' OR email='$username') AND password='$password'";
        $results = mysqli_query($db, $query);

        // تحقق من صحة البيانات المدخلة
        if (mysqli_num_rows($results) == 1) {
            $user = mysqli_fetch_assoc($results);
            $_SESSION['username'] = $user['username'];
            $roleId = $user['role_id'];

            // جلب اسم الدور من جدول الأدوار
            $roleQuery = "SELECT role_name FROM roles WHERE role_id=$roleId";
            $roleResult = mysqli_query($db, $roleQuery);
            $role = mysqli_fetch_assoc($roleResult)['role_name'];

            $_SESSION['role'] = $role; // تخزين الدور في الجلسة

            $_SESSION['success'] = "تم تسجيل الدخول بنجاح";

            // توجيه المستخدم بناءً على دوره
            if ($role == 'Admin') {
                header('location: admin.php');
            } elseif ($role == 'Doctor') {
                header('location: doctor.php');
            } elseif ($role == 'Patient') {
                header('location: patient.php');
            } elseif ($role == 'Receptions') {
                header('location: receptions.php');
            } else {
                header('location: index.php'); // صفحة افتراضية إذا كان الدور غير معروف
            }
            exit(); // إنهاء السكريبت بعد إعادة التوجيه
        } else {
            array_push($errors, "اسم المستخدم أو كلمة المرور غير صحيحة");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>نظام تسجيل الدخول</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body background="images/home-bg.jpg">
    <section>
        <div class="container">
            <div class="row min-vh-100 align-items-center" style="text-align: -webkit-center;">
                <div class="content text-center text-md-left" style="margin: 100px 0px 0px 0px;">
                    <div class="contact">
                        <form id="loginForm" method="post" action="login.php" style="background-color: lightblue;">
                            <div class="input-group">
                                <h1 style="text-align-last: center; font-size: xxx-large;"><label> شاشة الدخول </label></h1>
                            </div>

                            <!-- عرض الأخطاء إن وجدت -->
                            <?php if (count($errors) > 0): ?>
                                <div class="error" style="color:red;">
                                    <?php foreach ($errors as $error): ?>
                                        <p><?php echo $error; ?></p>
                                    <?php endforeach ?>
                                </div>
                            <?php endif ?>

                            <div class="input-group">
                                <h1><label>اسم المستخدم</label></h1>
                                <input type="text" id="email" name="username" placeholder="من فضلك ادخل اسم المستخدم" required>
                            </div>

                            <div class="input-group">
                                <h1><label>كلمة السر</label></h1>
                                <input type="password" id="password" name="password" placeholder="من فضلك ادخل كلمة السر" required>
                            </div>
                            <div class="footerForm">
                                <button type="submit" class="link-btn" style="font-size: x-large;" name="login_user">تسجيل الدخول</button>
                                <a href="register.php">
                                    <h3 class="link-btn" style="font-size: x-large;">انشاء حساب</h3>
                                </a>
                            </div>
                        </form>
                    </div>

                    <script>
                        document.getElementById('loginForm').addEventListener('submit', function(event) {
                            const email = document.getElementById('email').value;
                            const password = document.getElementById('password').value;
                            if (email && password) {
                                alert('تم تقديم بيانات تسجيل الدخول بنجاح!\nالبريد الإلكتروني: ' + email);
                            } else {
                                alert('يرجى إدخال كل من البريد الإلكتروني وكلمة المرور.');
                            }
                        });
                    </script>

                </div>
            </div>
        </div>
    </section>

<?php include('footer.php')?>
</body>

</html>
