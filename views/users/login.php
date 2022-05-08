<?php

session_start();
$action = $_REQUEST['action'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include "../general/header.php" ?>
		<title>Login</title>
		<script src="../../assets/sweetalert/sweetalert.min.js"></script>
	</head>
	<body id="image-login">
		<div class="container">
		</div>
		<div class="mt-3 text-secondary text-center">
			<div class='mt-5 mb-5'>
			 <!-- Contact Section Heading-->
				<h1 class="page-section-heading text-center text-uppercase text-secondary mb-0">Librería</h1>
				<div class='mt-4 mb-4'>
					<!-- Icon Divider-->
					<div class="divider-custom">
						<div class="divider-custom-line"></div>
						<div class="divider-custom-icon"><i class="fas fa-star"></i></div>
						<div class="divider-custom-line"></div>
					</div>
				</div>
			</div>
			<!-- Contact Section Form-->
			<div class='mt-5 mb-5'>
				<div class="container d-flex align-items-center flex-column">
					<h4 class="text-uppercase text-secondary col-lg-6">
						Iniciar sesión
					</h4>
					<div class="card-header">
						<div class="text-center">
						<?php if (isset($_SESSION['login-error'])): unset($_SESSION['login-error']) ?>
							<span class="badge badge-danger mb-0">Contraseña o correo incorrecto</span>
						<?php endif ?>
						</div>
						<div class="flex-group">
							<form class="form" method="post" action="checklogin.php" role="form" id="the-form">
								<div class="row">
									<div class="input-group no-border">
										<div class="form-control-lg">
											<input type="email" placeholder="Correo" class="form-control" name="email" required>
										</div>
										<div class="form-control-lg">
											<input type="password" placeholder="Contraseña" class="form-control" name="password" maxLength="128" required>
										</div>
									</div>
								</div>
								<div class="mt-4">
									<button type="submit" class="btn btn-primary ml-3 mb-2" name="login">Entrar</button>
									<a href="registrer.php">
										<input type="button" class="btn btn-primary ml-3 mb-2" value="Crear usuario"/>
									</a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<?php include '../general/footer.php'; ?>
		</div>
		<!-- Bootstrap core JS-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
		<!-- Third party plugin JS-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
		<script type="text/javascript">
			<?php if ($action === 'created'): ?>
				swal({
					title: "Usario creado",
					buttonsStyling: false,
					confirmButtonClass: "btn btn-success",
					icon: "success",
					button: "Vale",
				}).catch(swal.noop);
			<?php endif; ?>
		</script>
	</body>
</html>
