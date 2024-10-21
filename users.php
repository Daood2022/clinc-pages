<?php include('server.php'); ?>

<?php
// استرجاع المستخدمين مع الأدوار مع إمكانية البحث
$searchRole = '';
if (isset($_POST['search_role'])) {
    $searchRole = $_POST['search_role'];
}

$query = "
SELECT u.user_id, u.username, u.email, r.role_name 
FROM users u
LEFT JOIN roles r ON u.role_id = r.role_id
WHERE r.role_name LIKE '%$searchRole%'
";
$results = mysqli_query($db, $query);

// الحذف
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $query = "DELETE FROM users WHERE user_id=$id"; // تعديل الحقل ليكون مطابقاً لـ user_id
    mysqli_query($db, $query);
    header('location: users.php');
    exit(); // التأكد من توقف تنفيذ الكود بعد إعادة التوجيه
}

$db->close();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <title>إدارة المستخدمين</title>
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
        function deleteUser(userId) {
            const userConfirmed = confirm("هل تريد حقا حذف هذا السجل؟");
            if (userConfirmed)
                window.location.href = 'users.php?delete_id=' + userId;
        }
    </script>

    <?php include('header.php'); ?>

    <div style="margin: 5px 100px 10px;">
        <h2 class="heading">المستخدمين</h2>

        <!-- نموذج البحث -->
        <form method="POST" action="users.php" style="margin-bottom: 20px;">
           <h2> <label for="search_role">البحث حسب الصلاحية:</label></h2>
            <input type="text" name="search_role" id="search_role" placeholder="أدخل اسم الصلاحية" value="<?php echo htmlspecialchars($searchRole); ?>">
            <button type="submit" class="link-btn">بحث</button>
        </form>

        <div class='container'>
            <table class='responsive-table' style="WIDTH:-WEBKIT-FILL-AVAILABLE;FONT-SIZE: +20PX;">
                <?php include('errors.php'); ?>
                <tr>
                    <th>اسم المستخدم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الصلاحية</th>
                    <th>تعديل</th>
                    <th>حذف المستخدم</th>
                </tr>
                <?php
                if ($results->num_rows > 0) {
                    while ($users = $results->fetch_assoc()) {
                ?>
                        <tr>
                            <td><?php echo htmlspecialchars($users['username']); ?></td>
                            <td><?php echo htmlspecialchars($users['email']); ?></td>
                            <td><?php echo htmlspecialchars($users['role_name']); ?></td>
                            <td><a href="./register.php?user_id=<?php echo $users['user_id']; ?>" class="link-btn" style="font-size: x-large;">تعديل</a></td>
                            <td><button id="deleteButton" onclick="deleteUser(<?php echo $users['user_id']; ?>)" class="link-btn" style="font-size: x-large;">حذف المستخدم</button></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='5'><h4>لا يوجد بيانات</h4></td></tr>";
                }
                ?>
                <tr>
                    <td colspan='5'><a href='./register.php'><h4>إضافة مستخدم</h4></a></td>
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
