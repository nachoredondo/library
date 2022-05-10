<?php
require '../../classes/session.php';
require '../../classes/author.php';
require '../../classes/user.php';

Session::check_login_redirect();
$message = $_REQUEST['message'] ?? '';

if (isset($_REQUEST['id'])) {
	$id = $_REQUEST['id'];
	$author = Author::get_author($_REQUEST['id']);
	$birthdate = inverse_date($author->birthdate);
	$death_date = inverse_date($author->death_date);
	$value_submit = "Editar";
} else {
	$id = null;
	$author = new Author();
	$birthdate = null;
	$death_date = null;
	$value_submit = "Crear";
}

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
				<h2 class="text-center text-uppercase text-secondary"><?php echo $value_submit;?> autor</h2>
				<!-- Contact Section Form-->
				<div class="row">
					<div class="col-lg-8 mx-auto">
						<form id="contactForm" method="post" action="../../api/author/edit_create.php" name="sentMessage" novalidate="novalidate">
							<input class="form-control" name="id" type="hidden" value="<?php echo $id;?>"/>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0">
									<div class="row ml-1">
										<label>Nombre</label>
										<i class="d-none d-sm-none d-md-block fas fa-microphone ml-3 mt-4" id="audio-name"></i>
									</div>
									<input class="form-control" id="name" name="name" type="text" placeholder="Nombre" required="required" value="<?php echo $author->name();?>"data-validation-required-message="Por favor introduce el nombre" />
									<p class="help-block text-danger"></p>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0">
									<div class="row ml-1">
										<label>Pseudónimo</label>
										<i class="d-none d-sm-none d-md-block fas fa-microphone ml-3 mt-4" id="audio-pseudonym"></i>
									</div>
									<input class="form-control" id="pseudonym" name="pseudonym" type="text" placeholder="Pseudónimo" required="required" value="<?php echo $author->pseudonym();?>"data-validation-required-message="Por favor introduce el pseudónimo" />
									<p class="help-block text-danger"></p>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0 pb-2">
									<div class="row ml-1">
										<label>Fecha de nacimiento</label>
									</div>
									<input type="text" id="birthdate" class="form-control monthpicker" name="birthdate" autocomplete="off" value="<?php echo $birthdate; ?>"/>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0 pb-2">
									<div class="row ml-1">
										<label>Fecha de muerte</label>
									</div>
									<input type="text" id="death_date" class="form-control monthpicker" name="death_date" autocomplete="off" value="<?php echo $death_date; ?>"/>
								</div>
							</div>
							<br />
							<div id="success"></div>
							<div class="form-group">
								<input class="btn btn-primary ml-2 mb-2" id="sendMessageButton" name="form" value="<?php echo $value_submit;?>" type="submit"></button>
								<a href="index.php">
									<input type="button" class="btn btn-primary ml-2 mb-2" value="Volver"/>
								</a>
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
				$("#audio-name").mousedown(function(){
					recognition("#name");
				});
				$("#audio-pseudonym").mousedown(function(){
					recognition("#pseudonym");
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

			moment.updateLocale('en', {
				week: { dow: 1 } // Monday is the first day of the week
			});

			$('.monthpicker').datetimepicker({
				format: 'DD-MM-YYYY',
				viewMode: 'months',
				useCurrent: false,
				icons: {
					time: "far fa-clock",
					date: "far fa-calendar",
					up: "fa fa-chevron-up",
					down: "fa fa-chevron-down",
					previous: 'fa fa-chevron-left',
					next: 'fa fa-chevron-right',
					today: 'fa fa-screenshot',
					clear: 'fa fa-trash',
					close: 'fas fa-times'
				},
			});
		</script>
	</body>
</html>
