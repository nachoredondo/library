<?php
require '../../classes/session.php';
require '../../classes/user.php';
require '../../classes/book.php';

Session::check_login_redirect();

if (isset($_REQUEST['id'])) {
	$book = Book::get_book($_REQUEST['id']);
	$value_submit = "Editar";
} else {
	$book = new Book();
	$value_submit = "Crear";
}

$action = "";
// var_dump($book);
// echo $book->id();
// exit();

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include "../general/header.php" ?>
		<title>Librería</title>
	</head>
	<body id="page-top">
		<!-- Navigation-->
		<?php include '../general/headerbar.php' ?>
		<!-- Contact Section-->
		<section class="page-section" id="contact">
			<div class="container">
				<!-- Contact Section Heading-->
				<h2 class="text-center text-uppercase text-secondary mt-4">
					<?php if (isset($_REQUEST['id'])) {
						echo "Editar libro";
					} else { 
						echo "Nuevo libro";
					} ?>
				</h2>
				<!-- Contact Section Form-->
				<div class="row">
					<div class="col-lg-8 mx-auto">
						<form id="contactForm" method="post" action="" name="sentMessage" novalidate="novalidate">
							<input class="form-control" name="id" type="hidden" value="<?php echo $book->id();?>"/>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0">
									<div class="row ml-1">
										<label>Título</label>
										<i class="d-none d-sm-none d-md-block fas fa-microphone ml-3 mt-4" id="audio-user"></i>
									</div>
									<input class="form-control" id="title" name="title" type="text" required="required" value="<?php echo $book->title();?>"data-validation-required-message="Por favor introduce el título." placeholder="Título" />
									<p class="help-block text-danger"></p>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0">
									<div class="row ml-1">
										<label>ISBN</label>
									</div>
									<input class="form-control" id="ISBN" name="ISBN" type="text" required="required" value="<?php echo $book->isbn();?>"data-validation-required-message="Por favor introduce el ISBN." placeholder="ISBN" />
									<p class="help-block text-danger"></p>
								</div>
							</div>
							<div class="control-group">
								<div class="form-group floating-label-form-group controls mb-0">
									<div class="row ml-1">
										<label>Description</label>
										<i class="d-none d-sm-none d-md-block fas fa-microphone ml-3 mt-4" id="audio-user"></i>
									</div>
									<input class="form-control" id="description" name="description" type="text" required="required" value="<?php echo $book->description();?>"data-validation-required-message="Por favor introduce la descripción." placeholder="Descripción" />
									<p class="help-block text-danger"></p>
								</div>
							</div>
							<div class="form-group mt-3">
								<button class="btn btn-primary ml-4 mb-2" id="createEditButton" name="form" value="<?php echo $value_submit;?>" type="submit"><?php echo $value_submit;?></button>

								<button class="btn btn-danger mr-4 mb-2 float-right" id="deleteButton" name="form" value="delete" type="submit">Eliminar</button>
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
				$("#audio-title").mousedown(function(){
					recognition("#title");
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

			<?php if ($action === 'update'): ?>
				swal({
					title: "Libro actualizado",
					buttonsStyling: false,
					confirmButtonClass: "btn btn-success",
					icon: "success",
					button: "Vale",
				}).catch(swal.noop);
			<?php elseif ($action === 'create'): ?>
				swal({
					title: "Libro creado",
					buttonsStyling: false,
					confirmButtonClass: "btn btn-success",
					icon: "success",
					button: "Vale",
				}).catch(swal.noop);
			<?php endif; ?>
		</script>
	</body>
</html>
