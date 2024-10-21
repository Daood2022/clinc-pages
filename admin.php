<?php include('server.php') ?>
<?php
if (!isset($_SESSION['username'])|| $_SESSION['role']!=='Admin') {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة النظام</title>

    <!--font awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!--custom style file link-->
    <link rel="stylesheet" href="./style.css">

    <!--bootstrap cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css" />
    
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
    <?php include('header.php') ?>

    <div class="container">
        <section class="services" id="services">
            <h1 class="heading">إدارة النظام</h1>

            <div class="box-container">
                <a href='./doctorsData.php' class="box">
                    <img src="./images/process-1.png" alt="ادارة الأطباء">
                    <h3>إدارة الأطباء</h3>
                </a>

                <a href='./employeesData.php' class="box">
                    <img src="./images/process-1.png" alt="إدارة الموظفين">
                    <h3>إدارة الموظفين</h3>
                </a>

                <a href='./pationData.php' class="box">
                    <img src="./images/process-1.png" alt="إدارة المواعيد">
                    <h3>إدارة المواعيد</h3>
                </a>

                <a href='./users.php' class="box">
                    <img src="./images/process-1.png" alt="إدارة المستخدمين">
                    <h3>إدارة المستخدمين</h3>
                </a>
            </div>
        </section>
    </div>


 <div class="container">
        <section class="services" id="services">
            <h1 class="heading">شاشة النظام</h1>

            <div class="box-container">
                <a href='./doctorsData.php' class="box">
                    <img src="./images/process-1.png" alt="ادارة الأطباء">
                    <h3>شاشة الأطباء</h3>
                </a>

                <a href='./doctor.php' class="box">
                    <img src="./images/process-1.png" alt="إدارة الموظفين">
                    <h3>شاشة الموظفين</h3>
                </a>

                <a href='./index.php' class="box">
                    <img src="./images/process-1.png" alt="إدارة المواعيد">
                    <h3>شاشة المواعيد</h3>
                </a>


            </div>
        </section>
    </div>

    <?php include('footer.php') ?>
</body>

</html>
