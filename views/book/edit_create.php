<?php
require '../../classes/session.php';
require '../../classes/user.php';
require '../../classes/book.php';

Session::check_login_redirect();
$message = $_REQUEST['message'] ?? '';

if (isset($_REQUEST['id'])) {
	$book = Book::get_book($_REQUEST['id']);
	$value_submit = "Editar";
	$id = $_REQUEST['id'];
	$hidden = "";
} else {
	$book = new Book();
	$value_submit = "Crear";
	$id = null;
	$hidden = "hidden";
}

$action = $_REQUEST['action'] ?? '';

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
						<div class="card p-3 mt-2">
							<h3 class="row mt-2 ml-1 text-info" id="first-part">1 - Datos generales:</h3>
							<form id="contactForm" method="post" action="../../api/book/create_update.php" name="sentMessage" novalidate="novalidate" enctype="multipart/form-data">
								<input class="form-control" name="id" type="hidden" value="<?php echo $id;?>"/>
								<input name="file_saved" type="hidden" value="<?php echo $book->image; ?>"/>
								<div class="control-group">
									<div class="form-group floating-label-form-group controls mb-0">
										<div class="row ml-1">
											<label>Título</label>
											<i class="d-none d-sm-none d-md-block fas fa-microphone ml-3 mt-4" id="audio-user"></i>
											<label class="text-danger ml-3">✱</label>
										</div>
										<input class="form-control" id="title" name="title" type="text" required="required" value="<?php echo $book->title();?>"data-validation-required-message="Por favor introduce el título." placeholder="Título" />
										<p class="help-block text-danger"></p>
									</div>
								</div>
								<div class="control-group">
									<div class="form-group floating-label-form-group controls mb-0">
										<div class="row ml-1">
											<label>ISBN</label>
											<label class="text-danger ml-3">✱</label>
										</div>
										<input class="form-control" id="ISBN" name="ISBN" type="text" required="required" value="<?php echo $book->ISBN();?>"data-validation-required-message="Por favor introduce el ISBN." placeholder="ISBN" />
										<p class="help-block text-danger"></p>
									</div>
								</div>
								<div class="control-group">
										<div class="form-group floating-label-form-group controls mb-0 pb-2">
											<p class="help-block text-danger" required style="display:none;"></p>
											<?php 
												if (isset($_REQUEST['id'])) {
													echo "<img class='mt-2 ml-2' src='../../files/book/" . $book->image . "' height='150'/>";
													echo "<label for='files'>Cambiar imagen</label>";
												} else {
													echo '<div class="row ml-1">
														<label>Adjuntar imagen</label>
														<label class="text-danger ml-2">✱</label>
													</div>';
												}
											?>
											<input id="file_image" class="form-control mb-3" type="file" name="file" accept="image/gif, image/jpeg, image/png" style="font-size: large"/>
											<span id="text_file_image">Ningún archivo seleccionado</span>
										</div>
									</div>
								<div class="control-group">
									<div class="form-group floating-label-form-group controls mb-0">
										<div class="row ml-1">
											<label>Description</label>
											<i class="d-none d-sm-none d-md-block fas fa-microphone ml-3 mt-4" id="audio-user"></i>
										</div>
										<input class="form-control" id="description" name="description" type="text" required="required" value="<?php echo $book->description;?>"data-validation-required-message="Por favor introduce la descripción." placeholder="Descripción" />
										<p class="help-block text-danger"></p>
									</div>
								</div>
								<div class="form-group mt-3">
									<button class="btn btn-primary ml-4" id="createEditButton" name="form" value="<?php echo $value_submit;?>" type="submit"><?php echo $value_submit;?></button>
									<?php if (isset($_REQUEST['id'])) { ?>
										<button class="btn btn-danger mr-4 float-right" id="deleteButton" name="form" value="delete" type="submit">Eliminar</button>
									<?php } ?>
								</div>
							</form>
						</div>
						<div class="card p-3 mt-3" <?php echo $hidden; ?>>
							<h3 class="row mt-2 ml-1 text-info" id="author-part">2 - Asignar autor/es:</h3>
							<form>
								<div class="control-group">
									<div class="form-group floating-label-form-group controls mb-2 pb-2">
										<h5 class="text-info mt-3">Listado de autores asignados</h5>
										<div class="table-responsive">
											<table id="author-assigned" class="table table-striped compact nowrap" style="min-width:100%">
												<thead><!-- Leave empty. Column titles are automatically generated --></thead>
											</table>
										</div>
										<h5 class="text-info mt-3">Listado de autores sin asignar</h5>
										<div class="table-responsive">
											<table id="author-not-assigned" class="table table-striped compact nowrap" style="min-width:100%">
												<thead><!-- Leave empty. Column titles are automatically generated --></thead>
											</table>
										</div>
										<p class="help-block text-danger" style="display:none;"></p>
									</div>
									<a href="../author/edit_create.php">
										<input type="button" class="btn btn-primary ml-5 mb-2" value="Crear nuevo autor"/>
									</a>
								</div>
							</form>
						</div>
						<div class="card p-3 mt-3" <?php echo $hidden; ?>>
							<h3 class="row mt-2 ml-1 text-info" id="category-part">3 - Asignar categoría/s:</h3>
							<form>
								<div class="control-group">
									 <div class="form-group floating-label-form-group controls mb-2 pb-2">
										<h5 class="text-info mt-3">Listado de categorías asignadas</h5>
										<div class="table-responsive">
											<table id="category-assigned" class="table table-striped compact nowrap" style="min-width:100%">
												<thead><!-- Leave empty. Column titles are automatically generated --></thead>
											</table>
										</div>
										<h5 class="text-info mt-3">Listado de categorías sin asignar</h5>
										<div class="table-responsive">
											<table id="category-not-assigned" class="table table-striped compact nowrap" style="min-width:100%">
												<thead><!-- Leave empty. Column titles are automatically generated --></thead>
											</table>
										</div>
										<p class="help-block text-danger" style="display:none;"></p>
									</div>
									<a href="../category/edit_create.php">
										<input type="button" class="btn btn-primary ml-5 mb-2" value="Crear nueva categoría"/>
									</a>
								</div>
							</form>
						</div>
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
			let action = "<?php echo $action; ?>";
			if (action == "assign_author" || action == "unassign_author") {
				window.location.href = window.location.href + "r#author-part";
			} else if (action == "assign_category" || action == "unassign_category") {
				window.location.href = window.location.href + "y#category-part";
			}

			let file_image = document.getElementById("file_image");
			let text_file_image = document.getElementById("text_file_image");
			file_image.onchange = function () {
				text_file_image.innerHTML = file_image.files[0].name;
			};

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


			function assign(id_author_category, type_author, checked){
				$.post({
					url: "../../api/book/assign.php",
					type: "post",
					data: {
						type_author: type_author,
						id_book: "<?php echo $id;?>",
						id_author_category: id_author_category,
						checked: checked,
					},
					success: function(data) {
						console.log(data);
						window.location.href = data;
					}
				})
			}


			window.addEventListener('load', function () {
				function check_user(id, type_author, checked) {
					let check = "";
					if (checked) {
						check = "checked";
					}
					return  '<div class="form-check">' +
								'<input onclick="assign(' + id + ', ' + type_author + ', ' + checked + ')" class="form-check-input" type="checkbox" name="testing" ' + check + ' >' +
							'</div>';
				}
				let table_author_assigned = $('#author-assigned').DataTable({
					order: [[1, 'asc']],
					serverSide: true,
					lengthMenu: [[2, 4, -1], [2, 4, 'Todos']],
					language: {
						url: "../../assets/datatables/es.json",
					},
					columns: [
						{
							sorting: false,
							title:'Autor asignado',
							render: function (_, _, row) { return check_user(row.id, true, true) },
							"searchable": false,
						},
						{
							data: 'name',
							title: 'Nombre',
							render: function (_, _, row) { return max_text(row.name) },
						},
						{
							data: 'pseudonym',
							title: 'Pseudónimo',
							render: function (_, _, row) { return max_text(row.pseudonym) },
						},
					],
					ajax: {
						method: 'POST',
						url: "../../api/author/list_authors.php",
						data: function (params) {
							params.id_book = "<?php echo $id; ?>";
							params.assigned = true;
							return params;
						},
						error: function(xhr) {
							if (xhr.status === 401) { // Session expired
								window.location.reload();
							} else {
								console.log(xhr);
							}
						},
					},
				});

				let table_author_not_assigned = $('#author-not-assigned').DataTable({
					order: [[1, 'asc']],
					serverSide: true,
					lengthMenu: [[5, 10, -1], [5, 10, 'Todos']],
					language: {
						url: "../../assets/datatables/es.json",
					},
					columns: [
						{
							sorting: false,
							title:'Autor asignado',
							render: function (_, _, row) { return check_user(row.id, true, false) },
							"searchable": false,
						},
						{
							data: 'name',
							title: 'Nombre',
							render: function (_, _, row) { return max_text(row.name) },
						},
						{
							data: 'pseudonym',
							title: 'Pseudónimo',
							render: function (_, _, row) { return max_text(row.pseudonym) },
						},
					],
					ajax: {
						method: 'POST',
						url: "../../api/author/list_authors.php",
						data: function (params) {
							params.id_book = "<?php echo $id; ?>";
							params.assigned = false;
							return params;
						},
						error: function(xhr) {
							if (xhr.status === 401) { // Session expired
								window.location.reload();
							} else {
								console.log(xhr);
							}
						},
					},
				});

				let table_category_assigned = $('#category-assigned').DataTable({
					order: [[1, 'asc']],
					serverSide: true,
					lengthMenu: [[2, 4, -1], [2, 4, 'Todos']],
					language: {
						url: "../../assets/datatables/es.json",
					},
					columns: [
						{
							sorting: false,
							title:'Autor asignado',
							render: function (_, _, row) { return check_user(row.id, false, true) },
							"searchable": false,
						},
						{
							data: 'name',
							title: 'Nombre',
							render: function (_, _, row) { return max_text(row.name) },
						},
						{
							data: 'description',
							title: 'Descripción',
							searchable: false,
							render: function (_, _, row) { return max_text(row.description) },
						},
					],
					ajax: {
						method: 'POST',
						url: "../../api/category/list_categories.php",
						data: function (params) {
							params.id_book = "<?php echo $id; ?>";
							params.assigned = true;
							return params;
						},
						error: function(xhr) {
							if (xhr.status === 401) { // Session expired
								window.location.reload();
							} else {
								console.log(xhr);
							}
						},
					},
				});

				let table_category_not_assigned = $('#category-not-assigned').DataTable({
					order: [[1, 'asc']],
					serverSide: true,
					lengthMenu: [[5, 10, -1], [5, 10, 'Todos']],
					language: {
						url: "../../assets/datatables/es.json",
					},
					columns: [
						{
							sorting: false,
							title:'Categoría asignada',
							render: function (_, _, row) { return check_user(row.id, false, false) },
							"searchable": false,
						},
						{
							data: 'name',
							title: 'Nombre',
							render: function (_, _, row) { return max_text(row.name) },
						},
						{
							data: 'description',
							title: 'Descripción',
							render: function (_, _, row) { return max_text(row.description) },
						},
					],
					ajax: {
						method: 'POST',
						url: "../../api/category/list_categories.php",
						data: function (params) {
							params.id_book = "<?php echo $id; ?>";
							params.assigned = false;
							return params;
						},
						error: function(xhr) {
							if (xhr.status === 401) { // Session expired
								window.location.reload();
							} else {
								console.log(xhr);
							}
						},
					},
				});

				function refresh_table() {
					table_author_assigned.draw();
					table_author_not_assigned.draw();
					table_category_assigned.draw();
					table_category_not_assigned.draw();
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
			<?php elseif ($action === 'delete'): ?>
				swal({
					title: "Libro eliminado",
					buttonsStyling: false,
					confirmButtonClass: "btn btn-success",
					icon: "success",
					button: "Vale",
				}).catch(swal.noop);
			<?php endif; ?>

			<?php if ($message): ?>
				swal({
					title: '<?php echo $message; ?>',
					buttonsStyling: false,
					confirmButtonClass: "btn btn-success",
					icon: "error",
					button: "Vale",
				}).catch(swal.noop);
			<?php endif; ?>
		</script>
	</body>
</html>
