<?php

if (isset($_GET['error'])) {
	if ($_GET['error'] == "password-length") {
		$text_error = "Contraseña incorrecta, mínimo 8 caracteres";
	} elseif ($_GET['error'] == "no-pass-tutor") {
		$text_error = "Contraseña incorrecta";
	} elseif ($_GET['error'] == "no-same-password") {
		$text_error = "Las contraseñas no coinciden";
	} elseif ($_GET['error'] == "incorrect_camp") {
		if (isset($_GET['message'])) {
			$text_error = $_GET['message'];
		} else {
			$text_error = "Error";
		}
	} else {
		$text_error = "Error";
	}
}

$user = $_REQUEST['user'] ?? '';
$name = $_REQUEST['name'] ?? '';
$email = $_REQUEST['email'] ?? '';
$surnames = $_REQUEST['surnames'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include "../general/header.php" ?>
		<title>Crear usuario</title>
	</head>
	<body id="image-login">
		<div class="text-secondary text-center">
			<h1 class="text-uppercase text-secondary mt-4">
				Crear usuario
			</h1>
			<div class="mt-4 container d-flex align-items-center flex-column">
				<div class="card-header">
					<?php
					if (isset($_GET['success'])):
						if ($_GET['success'] == 'false'):
							if (isset($_GET['error'])):
									?>
								<span class="badge badge-danger mb-2"><?php echo $text_error; ?></span>
								<?php
							endif;
						endif;
					endif;
					?>
					<form class="form" method="post" action="create_user.php" role="form" id="the-form">
						<div class="row mt-1">
							<div class="input-group no-border mr-2">
								<input type="text" placeholder="Usuario" class="form-control ml-3" name="user" id="user" value="<?php echo $user; ?>" required size="25"/>
								<label class="text-danger mt-1">✱</label>
								<i class="d-none d-sm-none d-md-block fas fa-microphone ml-1 mt-2" id="audio-user"></i>
							</div>
						</div>
						<div class="row mt-3">
							<div class="input-group no-border mr-2">
								<input type="email" placeholder="Correo" class="form-control ml-3" name="email" id="email" value="<?php echo $email; ?>" required/>
								<label class="text-danger mt-1">✱</label>
								<i class="d-none d-sm-none d-md-block fas fa-microphone ml-1 mt-2 hidden" id="audio-email"></i>
							</div>
						</div>
						<div class="row mt-3">
							<div class="input-group no-border mr-2">
								<input type="text" placeholder="Nombre" class="form-control ml-3" name="name" id="name" value="<?php echo $name; ?>" required/>
								<label class="text-danger mt-1">✱</label>
								<i class="d-none d-sm-none d-md-block fas fa-microphone ml-1 mt-2" id="audio-name"></i>
							</div>
						</div>
						<div class="row mt-3">
							<div class="input-group no-border mr-2">
								<input type="text" placeholder="Apellidos" class="form-control ml-3" name="surnames" value="<?php echo $surnames; ?>" id="surnames"/>
								<label class="text-danger hidden mt-1">✱</label>
								<i class="d-none d-sm-none d-md-block fas fa-microphone ml-1 mt-2" id="audio-surnames"></i>
							</div>
						</div>
						<div class="row mt-3">
							<div class="input-group no-border mr-2">
								<input type="password" placeholder="Contraseña" class="form-control ml-3" name="password" maxLength="128" id="password" required/>
								<label class="text-danger mt-1">✱</label>
								<i class="d-none d-sm-none d-md-block fas fa-microphone ml-1 mt-2 hidden" id="audio-password"></i>
							</div>
						</div>
						<div class="row mt-3">
							<div class="input-group no-border mr-2">
								<input type="password" placeholder="Confirmar contraseña" maxLength="128" class="form-control ml-3" name="password-confirm" id="confirm-password" required/>
								<label class="text-danger mt-1">✱</label>
								<i class="d-none d-sm-none d-md-block fas fa-microphone ml-1 mt-2 hidden" id="audio-confirm-password"></i>
							</div>
						</div>
						<div class="text-center mt-3 ml-5 mr-5">
							<button type="submit" class="btn btn-primary mr-2" name="login">Crear</button>
							<a href="login.php">
								<input type="button" class="btn btn-primary ml-2" value="Volver"/>
							</a>
						</div>
					</form>
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
			$(document).ready(function(){
				let sr = new webkitSpeechRecognition();

				$("#audio-user").mousedown(function(){
					recognition("#user");
				});

				$("#audio-email").mousedown(function(){
					recognition("#email");
				});

				$("#audio-name").mousedown(function(){
					recognition("#name");
				});

				$("#audio-surnames").mousedown(function(){
					recognition("#surnames");
				});

				$("#audio-password").mousedown(function(){
					recognition("#password");
				});

				$("#audio-confirm-password").mousedown(function(){
					recognition("#confirm-password");
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

		</script>
	</body>
</html>
