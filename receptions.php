<?php include('server.php') ?>
<?php
// connect to database
$db = mysqli_connect('localhost', 'root', '', 'registration');
$query = "SELECT * FROM users";
$results = mysqli_query($db, $query);
$db->close();
?>
<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <title>receptions data</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <style>
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
            text-align-last: center;

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

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

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
    <?php include('header.php') ?>
    <div style="margin: 5px 100px 10px;">
        <h2 class="heading">الاستقبال</h1>
            <div class='container'>
                <table class='responsive-table '>
                    <?php include('errors.php'); ?>
                    <tr>
                        <th> رقم السجل الطبي</th>
                        <th>اسم المريض</th>
                        <th>اسم الطبيب</th>
                        <th> تاريخ وموعد الزيارة</th>
                        <th>سبب الزيارة </th>
                        <th>تاكيد الحالة </th>
                        <th>تعديل</th>
                        <th>حذف السجل</th>

                    </tr>
                    <?php
                    if ($results->num_rows > 0) {
                        while ($rows = $results->fetch_assoc()) {
                    ?>
                            <tr>
                                <td>11</td>
                                <td>محمد عبدالله</td>
                                <td>علي صالح</td>
                                <td>1/5/2024 </td>
                                <td>مراجعة</td>
                                <td>
                                    <div>
                                        <select name="type" style="font-size: x-large; text-align: -webkit-center;   margin: 10px 100px 0px 100px; width: 200px;">
                                            <option value="arabic">تم التأكيد</option>
                                            <option value="english">لم يتم التأكيد بعد</option>
                                        </select>
                                    </div>
                                </td>
                                <td><button type="submit" class="link-btn" style="font-size: x-large;" name="reg_user" value="admin.html"> تعديل</button></td>
                                <td><button type="submit" class="link-btn" style="font-size: x-large;" name="reg_user" value="admin.html">حذف السجل</button></td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "0 Results";
                    }
                    ?>
                    <tr>
                        <td Colspan='8' class='input-group'><a href='./addAreservation.php'>
                                <h4>إضافة موعد </h4>
                            </a></td>
                    </tr>
                </table>
            </div>
        </h2>
    </div>
    <?php include('footer.php') ?>
</body>

</html>