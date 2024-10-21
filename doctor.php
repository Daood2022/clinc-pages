<?php include('server.php'); ?>

<?php
// عرض المستخدمين الذين نوعهم دكتور فقط
$query = "
SELECT u.user_id, u.username, u.email, r.role_name 
FROM users u
LEFT JOIN roles r ON u.role_id = r.role_id
WHERE r.role_name = 'دكتور'
";
$results = mysqli_query($db, $query);

// الحذف
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $query = "DELETE FROM users WHERE user_id=$id";
    mysqli_query($db, $query);
    header('location: users.php');
    exit();
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
        /* تنسيقات الجدول */
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:nth-child(odd) {
            background-color: #ffffff;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-size: x-large;
        }

        thead {
            background-color: #007BFF;
            color: #fff;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            font-weight: bold;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* تنسيق لشاشات أصغر */
        @media (max-width: 600px) {
            .responsive-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            table {
                width: 100%;
            }

            th,
            td {
                display: block;
                width: 100%;
                box-sizing: border-box;
            }

            th {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            td {
                position: relative;
                padding-left: 50%;
                border: none;
                border-bottom: 1px solid #ddd;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 10px;
                font-weight: bold;
                white-space: nowrap;
            }
        }
    </style>

    <script>
        function deletedoctor(user_id) {
            const userConfirmed = confirm("هل تريد حقا حذف هذا المستخدم؟");
            if (userConfirmed) {
                window.location.href = 'users.php?delete_id=' + user_id;
            }
        }
    </script>

    <?php include('header.php') ?>
    <div style="margin: 5px 100px 10px;">
        <h2 class="heading">إدارة الأطباء</h2>
        <div class='container'>
            <table class='responsive-table'>
                <thead>
                    <tr>
                        <th>اسم الطبيب</th>
                        <th>البريد الإلكتروني</th>
                        <th>الدور</th>
                        <th>تعديل</th>
                        <th>حذف الطبيب</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($results->num_rows > 0) {
                        while ($rows = $results->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo $rows['username'] ?></td>
                                <td><?php echo $rows['email'] ?></td>
                                <td><?php echo $rows['role_name'] ?></td>
                                <td><a href='./register.php?user_id=<?php echo $rows['user_id'] ?>' class="link-btn" style="font-size: large;">تعديل</a></td>
                                <td><button type="button" class="link-btn" style="font-size: large;" onclick="deletedoctor(<?php echo $rows['user_id'] ?>)">حذف</button></td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='5'>لا توجد نتائج</td></tr>";
                    }
                    ?>
                    <tr>
                        <td colspan='5' class='input-group'>
                            <a href='./AddDoctor.php'><h4>إضافة طبيب جديد</h4></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?php include('footer.php') ?>
</body>

</html>
