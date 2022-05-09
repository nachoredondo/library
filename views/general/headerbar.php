<?php

$user_child = User::get_user_from_user($_SESSION['user'])

?>

<!-- Navigation-->
<nav class="navbar navbar-expand-lg bg-secondary fixed-top" id="mainNav">
	<div class="container">
		<h5>
			<a class="navbar-brand js-scroll-trigger" href="<?php echo APP_ROOT; ?>views/main/">Librería</a>
		</h5>
		<button class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
			Menú
			<i class="fas fa-bars"></i>
		</button>
		<div class="collapse navbar-collapse" id="navbarResponsive">
			<div class="">
				<a class="mr-3 ml-3" href="<?php echo APP_ROOT; ?>views/main/">
					<img src="<?php echo APP_ROOT; ?>/assets/img/library.png" height="80" width="90"/>
				</a>
			</div>
			<ul class="navbar-nav mr-auto">
				<li class="nav-item mx-0 mx-lg-1">
					<a class="nav-link py-3 px-0 px-lg-2 rounded js-scroll-trigger" href="<?php echo APP_ROOT; ?>views/author/index.php">
						<i class='fas fa-id-card'></i> Autores
					</a>
				</li>
				<li class="nav-item ml-1 mx-0 mx-lg-1">
					<a class="nav-link py-3 px-0 px-lg-2 rounded js-scroll-trigger" href="<?php echo APP_ROOT; ?>views/main/index.php">
						<i class='fas fa-book'></i> Catálogo
					</a>
				</li>
				<li class="nav-item ml-1 mx-0 mx-lg-1">
					<a class="nav-link py-3 px-0 px-lg-2 rounded js-scroll-trigger" href="<?php echo APP_ROOT; ?>views/category/index.php">
						<i class='fas fa-align-justify mr-1'></i>Categorías
					</a>
				</li>
				<li class="nav-item ml-1 mx-0 mx-lg-1">
					<a class="nav-link py-3 px-0 px-lg-2 rounded js-scroll-trigger" href="<?php echo APP_ROOT; ?>views/main/index.php?my-books">
						<i class='fas fa-book'></i> Mis libros
					</a>
				</li>
				<li class="nav-item ml-1 mx-0 mx-lg-1">
					<a class="nav-link py-3 px-0 px-lg-2 rounded js-scroll-trigger" href="<?php echo APP_ROOT; ?>views/category/index.php">
						<i class='fas fa-align-justify mr-1'></i>Documentación
					</a>
				</li>
			</ul>
		</div>
		<a class="nav-link dropdown-toggle" href="#" id="navUserDropdown" data-toggle="dropdown">
			<span class="mx-1"><?php echo $_SESSION['name'] ?></span>
			<i class="fas fa-user"></i>
		</a>
		<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navUserDropdown">
			<a class="dropdown-item" href="../../views/users/profile.php">Mi perfil</a>
			<a class="dropdown-item" href="../../views/users/logout.php">
				<i class="fas fa-sign-out-alt"></i> Cerrar sesión
			</a>
		</div>
	</div>
</nav>
