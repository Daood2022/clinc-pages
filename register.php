<?php
include('server.php');

// جلب بيانات المستخدم للتعديل إذا تم تمرير user_id
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $query = "SELECT * FROM users WHERE user_id=$user_id";
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);
} else {
    // إذا لم يتم تمرير user_id، نكون في حالة إضافة مستخدم جديد
    $user = ['username' => '', 'email' => '', 'role_id' => ''];
}

// عند إرسال النموذج
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role_id = $_POST['role_id'];
    $password_1 = $_POST['password_1'];
    $password_2 = $_POST['password_2'];
    if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
        // تعديل المستخدم
        $user_id = $_POST['user_id'];
        if ($password_1) {
            $password = md5($password_1); //encrypt the password before saving in the database
            $query = "UPDATE users SET username='$username',password='$password', email='$email', role_id='$role_id' WHERE user_id=$user_id";
        } else {
            $query = "UPDATE users SET username='$username', email='$email', role_id='$role_id' WHERE user_id=$user_id";
        }
    } elseif ($password_1 != $password_2) {
        echo ("The password do not match");
    } else {
        $password = md5($password_1); //encrypt the password before saving in the database
        // إضافة مستخدم جديد
        $query = "INSERT INTO users (username,password, email, role_id) VALUES ('$username','$password','$email', '$role_id')";
    }

    mysqli_query($db, $query);
    header('location: users.php');
    exit();
}

// جلب الصلاحيات
$rolesQuery = "SELECT * FROM roles";
$resultRole = mysqli_query($db, $rolesQuery);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <title><?php echo isset($user_id) ? 'تعديل بيانات المستخدم' : 'إضافة مستخدم'; ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <h2 class="heading"><?php echo isset($user_id) ? 'تعديل بيانات المستخدم' : 'إضافة مستخدم جديد'; ?></h2>

    <div class="container">
        <div class="row min-vh-100 align-items-center" style="text-align: center;">
            <div class="content">
                <div class="contact">
                    <form method="POST" action="register.php" style="background-color: lightblue; padding: 20px; border-radius: 8px;">
                        <?php include('errors.php'); ?>

                        <!-- حقل مخفي لتحديد user_id في حالة التعديل -->
                        <input type="hidden" name="user_id" value="<?php echo isset($user_id) ? $user_id : ''; ?>">

                        <!-- اسم المستخدم -->
                        <div class="input-group">
                            <h1><label>اسم المستخدم</label></h1>
                            <input type="text" name="username" placeholder="من فضلك ادخل اسم المستخدم" value="<?php echo $user['username']; ?>" required>
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div class="input-group">
                            <h1><label>البريد الإلكتروني</label></h1>
                            <input type="email" name="email" placeholder="من فضلك ادخل البريد الإلكتروني" value="<?php echo $user['email']; ?>" required>
                        </div>

                        <!-- كلمة المرور عند إضافة مستخدم جديد -->
                        <?php if (!isset($user_id)) { ?>
                            <div class="input-group">
                                <h1><label>كلمة السر</label></h1>
                                <input type="password" name="password_1" placeholder="من فضلك ادخل كلمة السر" required>
                            </div>

                            <div class="input-group">
                                <h1><label>تأكيد كلمة السر</label></h1>
                                <input type="password" name="password_2" placeholder="من فضلك ادخل كلمة السر للتأكيد" required>
                            </div>
                        <?php } else { ?>
                            <!-- تحديث كلمة المرور عند التعديل (اختياري) -->
                            <div class="input-group">
                                <h1><label>تحديث كلمة السر (اختياري)</label></h1>
                                <input type="password" name="password_1" placeholder="اتركه فارغاً إذا كنت لا ترغب في تغيير كلمة السر">
                            </div>
                        <?php } ?>

                        <!-- صلاحية المستخدم -->
                        <div class="input-group">
                            <h1><label>صلاحية المستخدم</label></h1>
                            <select name="role_id" required>
                                <?php
                                if ($resultRole->num_rows > 0) {
                                    while ($role = $resultRole->fetch_assoc()) {
                                        $selected = ($user['role_id'] == $role['role_id']) ? 'selected' : '';
                                        echo "<option value='{$role['role_id']}' $selected>{$role['role_name']}</option>";
                                    }
                                } else {
                                    echo "<option disabled>لا توجد صلاحيات</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- زر الإرسال -->
                        <div class="footerForm">
                            <button type="submit" class="btn"><?php echo isset($user_id) ? 'تعديل' : 'إضافة'; ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>