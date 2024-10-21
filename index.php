<?php include('server.php'); ?>
<?php
if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}
// echo $_SESSION['role'];

if (isset($_GET['logout'])) {
    session_unset(); // مسح جميع المتغيرات الجلسة
    session_destroy(); // تدمير الجلسة
    header("Location: login.php"); // إعادة توجيه إلى صفحة تسجيل الدخول
    exit();
}

?>
<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dentist website</title>

    <!--font awesome cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--custom style file link-->
    <link rel="stylesheet" href="./style.css">

    <!--bootstrap cdn link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css" integrity="sha512-T584yQ/tdRR5QwOpfvDfVQUidzfgc2339Lc8uBDtcp/wYu80d7jwBgAxbyMh0a9YM9F8N3tdErpFI8iaGx6x5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background-color: #f0f8ff;
        }

        .heading :header {
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
    <!--home section starts-->
    <section class="home" id="home">
        <div class="container">
            <div class="row min-vh-100 align-items-center">
                <div class="content text-center text-md-left">
                    <h3>نحن نهتم بصحتك </h3>
                    <p>تلتزم مجموعتنا من أطباء يتميزون بتقديم أفضل رعاية لك، مثلك تمامًا. احصل على رعاية كاملة وتجربة مريحة لا مثيل لها.</p>
                    <a href="#contact" class="link-btn">
                        <h1>حجز موعد </h1>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!--qabout section starts-->
    <section class="about" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 image">
                    <img src="./images/about-img.jpeg" alt="" class="w-100 mb-4 mb-md-0">
                </div>

                <div class="col-md-6 content">
                    <span style="    align-self: start;">من نحن </span>
                    <h3>كل ما يهمنا هو صحتك</h3>
                    <p>لدينا كل الخدمات التي تحتاجها للحصول على جسم صحي وتجربة لا مثيل لها مع المريض. يفتخر أطباءنا المهتمون ذوو الخبرة لدينا بالاعتراف بهم من قبل المجتمعات التي نعيش فيها ونخدمها!!</p>
                    <ul>
                        <li>استشارات طبية</li>
                        <li> تشخيص الأمراض</li>
                        <li> علاج الأمراض</li>
                        <li> إجراء الفحوصات المخبرية</li>
                        <li>إجراء الفحوصات التشخيصية</li>
                        <li>التطعيمات واللقاحات</li>
                        <li>العناية بالأسنان</li>
                        <li>الطب الوقائي</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!--services section stars-->
    <section class="services" id="services">
        <h1 class="heading">خدماتنا</h1>
        <div class="box-container container">
            <div class="box">
                <img src="./images/process-1.png" alt="">
                <h3> استشارات طبية</h3>
                <p>تقديم مشورة طبية حول مختلف الحالات الصحية والأعراض</p>
            </div>

            <div class="box">
                <img src="./images/process-1.png" alt="">
                <h3> تشخيص الأمراض</h3>
                <p>إجراء الفحوصات والتشخيص الدقيق للحالات المرضية.</p>
            </div>

            <div class="box">
                <img src="./images/lip.png" alt="">
                <h3>إجراء الفحوصات المخبرية</h3>
                <p>تحليل العينات مثل الدم والبول للتشخيص والمتابعة.</p>
            </div>

            <div class="box">
                <img src="./images/icon-4.svg" alt="">
                <h3>العناية بالأسنان</h3>
                <p>مثل الفحوصات الدورية، التنظيف، وعلاج مشاكل الأسنان.</p>
            </div>

            <div class="box">
                <img src="./images/ta.png" alt="">
                <h3>التطعيمات واللقاحات</h3>
                <p> تقديم التطعيمات الوقائية للأطفال والكبار.</p>
            </div>

            <div class="box">
                <img src="./images/nf.png" alt="">
                <h3>الاستشارات النفسية</h3>
                <p>تقديم المشورة والعلاج للأمور النفسية والعاطفية.</p>
            </div>
        </div>
    </section>

    <!--process section starts-->
    <section class="process" id="process">
        <h1 class="heading">
            عملية العلاج
        </h1>

        <div class="box-container container">
            <div class="box">
                <img src="./images/process-1.png" alt="" height="250" width="250">
                <h3>استشارات طبية</h3>
                <p> تقديم مشورة طبية حول مختلف الحالات الصحية والأعراض</p>
            </div>

            <div class="box">
                <img src="./images/LEBR.png" alt="" height="250" width="250">
                <h3>لفحوصات المخبرية</h3>
                <p>الفحوصات المخبرية هي نافذتنا إلى صحة الجسم، والتي تكشف التفاصيل الدقيقة التي توجه مسار العلاج والوقاية.</p>
            </div>

            <div class="box">
                <img src="./images/process-3.png" alt="">
                <h3>طب الأسنان</h3>
                <p>حالات طب الأسنان والفم والإجراءات والجراحة. توصيات تمت مراجعتها من قبل الأقران وتحديثها</p>
            </div>
        </div>
    </section>

    <!--reviews section starts-->
    <section class="reviews" id="reviews">
        <h1 class="heading">تقييمات العملاء </h1>
        <div class="box-container container">
            <div class="box">
                <img src="./images/pic-1.png" alt="">
                <p>طبيب رائع يقدم خدمة شخصية. أذهب دائمًا إلى الدكتور. تعالوا لتفقدوا هذا المكان.</p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <h3>اخمد محمد</h3>
                <span>عميل راض</span>
            </div>

            <div class="box">
                <img src="./images/pic-2.png" alt="">
                <p>أنا مريض جديد وقد مررت بإحدى أكثر التجارب الممتعة التي مررت بها على الإطلاق لدى الطبيب . أثناء الإجراء بأكمله، كانت راحتي هي الأولوية القصوى.</p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <h3>العنود محمد</h3>
                <span>عميل راض</span>
            </div>

            <div class="box">
                <img src="./images/pic-3.png" alt="">
                <p>لقد أتيت لعمل فحص وقد حصلت على أفضل خدمة. كانت عملية العلاج مريحة للغاية. الموظفون ممتازون وودودون للغاية.</p>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h3>فيصل القرني</h3>
                <span>عميل راض</span>
            </div>
        </div>
    </section>

    <!--conatct section starts-->
    <section class="contact" id="contact">
        <h1 class="heading">المواعيد</h1>

        <form action="appointment.HTML" method="POST" id="appointmentForm">

            <span>اسم المريض </span>
            <input type="number" name="pation_id" id="pationName" placeholder=" اختر المريض " class="box" required>

            <span>اسم الدكتور </span>
            <input type="number" name="doctor_id" placeholder="اختر الدكتور  " id="doctorName" class="box" required>

            <span>تاريخ حجز الموعد</span>
            <input type="datetime-local" name="date" class="box" required id="date">
            <span>سبب الزيارة </span>
            <input type="text" id="reason" name="reason" placeholder="ادخل سبب الزيارة" class="box" required>


            <button type="submit" class="link-btn" style="font-size: x-large;" name="reg_user" value="admin.html">حفظ البيانات </button>
            <!--input type="#submit" value="حجز الموعد" name="appointment" class="link-btn" inline-size="-webkit-fill-available"-->
        </form>
    </section>

    <!--footer section starst-->
    <?php include('footer.php') ?>
    <br><br>
    <script>
        document.getElementById('appointmentForm').addEventListener('submit', function(event) {
            const pationName = document.getElementById('pationName').value;
            const date = document.getElementById('date').value;
            // التحقق من وجود قيم في الحقول
            if (pationName && doctorName && date && reason) {
                // عرض رسالة تنبيه
                alert('تم تقديم بيانات تسجيل الدخول بنجاح!\nاسم العميل : ' + pationName + '\n' + ' تاريخ الحجز:  ' + date);

                // يمكنك هنا إضافة منطق لإرسال البيانات إلى الخادم إذا لزم الأمر
            } else {
                // عرض رسالة تنبيه في حالة عدم وجود قيم
                alert('يرجى إدخال كل من البريد الإلكتروني وكلمة المرور.');
            }
        });
    </script>
    <script src="./index.js"></script>

</body>

</html>