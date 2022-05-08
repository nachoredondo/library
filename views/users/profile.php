<?php
require '../../classes/session.php';
require '../../classes/user.php';

Session::check_login_redirect();
$user = User::get_user_from_user($_SESSION['user']);
$action = $_REQUEST['action'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include "../general/header.php" ?>
		<title>Perfil</title>

	</head>
	<body id="page-top">
		<!-- Navigation-->
		<?php include '../general/headerbar.php' ?>
		<!-- Contact Section-->
		<section class="page-section" id="contact">
			<div class="container">
				<!-- Contact Section Heading-->
				<h2 class="text-center text-uppercase text-secondary mt-4">Datos de usuario</h2>
				<!-- Contact Section Form-->
				<div class="row">
					<div class="col-lg-8 mx-auto">
						<h3 class="row mt-5 ml-1 text-info">Datos perfil:</h3>
						<form id="contactForm" method="post" action="../../api/user/update.php" name="sentMessage" novalidate="novalidate">
							<input class="form-control" name="id" type="hidden" value="<?php echo $user->id();?>"/>
							<input class="form-control" name="old_email" type="hidden" value="<?php echo $user->email();?>"/>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0">
									<div class="row ml-1">
										<label>Usuario</label>
										<i class="d-none d-sm-none d-md-block fas fa-microphone ml-3 mt-4" id="audio-user"></i>
									</div>
									<input class="form-control" id="user" name="user" type="text" required="required" value="<?php echo $user->user();?>"data-validation-required-message="Por favor introduce el usuario." placeholder="Usuario" />
									<p class="help-block text-danger"></p>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0">
									<div class="row ml-1">
										<label>Correo</label>
										<i class="d-none d-sm-none d-md-block fas fa-microphone ml-3 mt-4" id="audio-email"></i>
									</div>
									<input class="form-control" id="new-email" name="new_email" type="email" placeholder="Correo" required="required" value="<?php echo $user->email();?>"data-validation-required-message="Por favor introduce el correo." />
									<p class="help-block text-danger"></p>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0">
									<div class="row ml-1">
										<label>Nombre</label>
										<i class="d-none d-sm-none d-md-block fas fa-microphone ml-3 mt-4" id="audio-name"></i>
									</div>
									<input class="form-control" id="name" name="name" type="text" placeholder="Nombre" required="required" value="<?php echo $user->name();?>"data-validation-required-message="Por favor introduce el nombre" />
									<p class="help-block text-danger"></p>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0">
									<div class="row ml-1">
										<label>Apellidos</label>
										<i class="d-none d-sm-none d-md-block fas fa-microphone ml-3 mt-4" id="audio-surnames"></i>
									</div>
									<input class="form-control" id="surnames" name="surnames" type="text" placeholder="Apellidos" required="required" value="<?php echo $user->surnames();?>"data-validation-required-message="Por favor introduce los apellidos" />
									<p class="help-block text-danger"></p>
								</div>
							</div>
							<br />
							<div id="success"></div>
							<div class="form-group">
								<button class="btn btn-primary btn-lg ml-2 mb-2" id="sendMessageButton" name="form" value="data" type="submit">Actualizar datos</button>
							</div>
						</form>
						<h3 class="row mt-5 ml-1 text-info">Cambiar contraseña:</h3>
						<form id="update-pwd" method="post" action="../../api/user/update.php" name="sentMessage" novalidate="novalidate">
							<input class="form-control" id="id-user-pwd" name="id" type="hidden" required="required" value="<?php echo $user->id();?>" />
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0">
									<label>Contraseña</label>
									<input class="form-control" id="pwd" name="pwd" type="password" placeholder="Contraseña" required="required" data-validation-required-message="Por favor introduce la contraseña." />
									<p class="help-block text-danger"></p>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0">
									<label>Confirmar contraseña</label>
									<input class="form-control" id="pwd-confirm" name="pwd-confirm" type="password" placeholder="Contraseña" required="required" data-validation-required-message="Por favor confirma la contraseña." />
									<p class="help-block text-danger"></p>
								</div>
							</div>
							<br />
							<div class="form-group">
								<button class="btn btn-primary btn-lg ml-2 mb-2" id="button-update-pwd" name="form" value="pwd" type="submit">Cambiar contraseña</button>
								<button class="btn btn-primary btn-lg ml-2 mb-2" id="button-delete-tutor" name="form" value="delete-tutor" type="submit">Eliminar usuario</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
		<!-- Footer-->
		<?php include '../general/footer.php'; ?>
		<!-- Scroll to Top Button (Only visible on small and extra-small screen sizes)-->
		<div class="scroll-to-top d-lg-none position-fixed mt-5">
			<a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top"><i class="fa fa-chevron-up"></i></a>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				let sr = new webkitSpeechRecognition();
				$("#audio-user").mousedown(function(){
					recognition("#user");
				});
				$("#audio-email").mousedown(function(){
					recognition("#new-email");
				});
				$("#audio-name").mousedown(function(){
					recognition("#name");
				});
				$("#audio-surnames").mousedown(function(){
					recognition("#surnames");
				});

				function  recognition(id){
					// start recognition speech
					sr.start();
					const $consequences = document.querySelector(id);

					sr.onresult = result => {
						let last_element = result.results.length - 1;
						let text_listened = result.results[last_element][0].transcript;
						if ($consequences.value != "") {
							$consequences.value += " " + text_listened;
						} else {
							$consequences.value = text_listened;
						}
					}

					sr.onend = () => {
						// Stop when the audio finish
						sr.stop()
					};
				}
			});

			<?php if ($action === 'update' || $action === 'data'): ?>
				swal({
					title: "Usuario actualizado",
					buttonsStyling: false,
					confirmButtonClass: "btn btn-success",
					icon: "success",
					button: "Vale",
				}).catch(swal.noop);
			<?php elseif ($action === 'pwd'): ?>
				swal({
					title: "Contraseña actualizada",
					buttonsStyling: false,
					confirmButtonClass: "btn btn-success",
					icon: "success",
					button: "Vale",
				}).catch(swal.noop);
			<?php endif; ?>
		</script>
	</body>
</html>
