<?php
echo ('
<header class="header fixed-top" id="header">
	<div class="container">
		<div class="container" style="display:flex;">
			<div class="row align-items-center justify-content-between" style="width:80%">
				<a href="#home" class="logo">
					<span>
						<h3 style="text-align-last: auto;"> عيادة BEST CARE </h3>
					</span>
				</a>
			</div>
			<div class="row align-items-center justify-content-between">
				' . (isset($_SESSION["username"]) ? '
				<a class="logo"><span>
						<h3> مرحبا بك عزيزي :' . htmlspecialchars($_SESSION["username"]) . '

						</h3>
					</span></a>' : '') . '
			</div>
		</div>
		<div class="row align-items-center justify-content-between">
			<nav class="nav">
				<a href="#home">
					<h1>الرئيسية </h1>
				</a>
				<a href="#about">
					<h1>من نحن </h1>
				</a>
				<a href="#services">
					<h1>الخدمات </h1>
				</a>
				<a href="#reviews">
					<h1>المراجعات </h1>
				</a>
				<a href="#contact">
					<h1>الاتصال </h1>
				</a>
				<a href="#contact">
					<h1>حجز موعد </h1>
				</a>
				' . (isset($_SESSION["username"]) ? '<a href="login.php?logout=1" style="color: red;">
					<h1>تسجيل خروج</h1>
				</a>' : '') . '
			</nav>
		</div>
	</div>
</header>
');
