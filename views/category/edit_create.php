<?php
require '../../classes/session.php';
require '../../classes/category.php';
require '../../classes/user.php';

Session::check_login_redirect();
$message = $_REQUEST['message'] ?? '';

if (isset($_REQUEST['id'])) {
	$category = Category::get_category($_REQUEST['id']);
	$id = $_REQUEST['id'];
	$value_submit = "Editar";
} else {
	$category = new Category();
	$id = null;
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
				<h2 class="text-center text-uppercase text-secondary"><?php echo $value_submit;?> categoría</h2>
				<!-- Contact Section Form-->
				<div class="row">
					<div class="col-lg-8 mx-auto">
						<form id="contactForm" method="post" action="../../api/category/edit_create.php" name="sentMessage" novalidate="novalidate">
							<input class="form-control" name="id" type="hidden" value="<?php echo $id;?>"/>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0">
									<div class="row ml-1">
										<label>Nombre</label>
										<i class="d-none d-sm-none d-md-block fas fa-microphone ml-3 mt-4" id="audio-name"></i>
									</div>
									<input class="form-control" id="name" name="name" type="text" required="required" value="<?php echo $category->name();?>"data-validation-required-message="Por favor introduce el nombre." placeholder="Nombre" />
									<p class="help-block text-danger"></p>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0">
									<div class="row ml-1">
										<label>Descripción</label>
										<i class="d-none d-sm-none d-md-block fas fa-microphone ml-3 mt-4" id="audio-description"></i>
									</div>
									<input class="form-control" id="description" name="description" type="text" required="required" value="<?php echo $category->description;?>"data-validation-required-message="Por favor introduce la descripción." placeholder="Descripción" />
									<p class="help-block text-danger"></p>
								</div>
							</div>
							<br />
							<div id="success"></div>
							<div class="form-group">
								<input class="btn btn-primary ml-2 mb-2" id="sendMessageButton" name="form" value="<?php echo $value_submit;?>" type="submit"></button>
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
				$("#audio-description").mousedown(function(){
					recognition("#description");
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
