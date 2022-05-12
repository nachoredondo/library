<?php
require '../../classes/session.php';
require '../../classes/user.php';
require '../../classes/book.php';

Session::check_login_redirect();

$user = User::get_user_from_user($_SESSION['user']);
if (isset($_REQUEST['search'])){
	$search = $_REQUEST['search'];
} else {
	$search = '';
}

if (isset($_REQUEST['my-books'])){
	$personal = true;
} else {
	$personal = false;
}


$show_all = isset($_REQUEST['list-all']) ? true : false;

$books = Book::search_books($search, $show_all, $user->id(), $personal);
if (!$show_all) {
	$total_books = Book::search_books($search, true, $user->id(), $personal);
	$total_books = count($total_books);
} else {
	$total_books = count($books);
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
			<div class="container mb-5">
				<!-- Rules Section Heading-->
				<div class="mr-3">
					<h2 class="text-center text-uppercase text-secondary mt-4 ml-5">
						<?php 
							if ($personal) 
								echo "Libros reservados"; 
							else 
								echo "Catálogo"; 
						?>
					</h2>
					<div class="input-group mb-3 ml-4">
						<h3 class="card-title">Buscador de libros</h3>
						<div class="input-group rounded mr-5">
							<input type="search" id="search" class="form-control rounded" placeholder="Introduce el título o ISBN" aria-label="Search" aria-describedby="search-addon" value="<?php echo $search?>" />
							<span class="input-group-text border-0" id="search-addon">
								<i class="fas fa-search"></i>
							</span>
						</div>
					</div>
					<div class="row ml-4">
						<div class="mr-3 mt-1 <?php if (count($books) == 0) echo 'text-warning'; ?>"><?php 
							if (count($books) == 0)
								echo "No hay ningun libro registrado";
							else if (count($books) == 1)
								echo "Se muestra " . count($books) . " libro";
							else 
								echo "Se muestran " . count($books) . " libros";

							if ($total_books != count($books)) {
								echo " de " . $total_books . " libros";
							}
						?></div>
						<a href="../book/edit_create.php">
							<button type='button' style="margin: 0 auto;" title='Añadir nuevo libro' class='edit-btn btn btn-primary btn-sm'>
										Nuevo libro<i class='fas fa-plus ml-1'></i>
							</button>
						</a>
					</div>
					<div class="row m-2 ml-3">
						<?php 
							foreach ($books as $book) { ?>
							<div class="card p-2 m-2 col-12 col-sm-5 col-md-3 col-lg-2">
								<a href="../book/view.php?id=<?php echo $book['id'];?>">
									<div><?php echo $book['title'] ?></div>
									<img class="p-2" id="image-book" alt="<?php echo $book['title'] ?>" src="../../files/book/<?php echo $book['image']; ?>"/>
								</a>
								<div class="isbn mt-1">ISBN: <?php echo $book['ISBN'] ?></div>
								<form action="../book/edit_create.php" class="mt-1">
									<input type="hidden" value="<?php echo $book['id'];?>" name="id">
									<input class="btn btn-primary btn-sm mb-2" value="Editar" type="submit">
								</form>
								<?php if (is_int($book['id_book_user'])) { ?>
									<button onclick="delete_reservation('<?php echo $book['id_book_user'];?>')" type='button' title='Quitar reserva' class='edit-btn btn btn-warning btn-sm'>
										Quitar reserva<i class='fas fa-minus ml-1'></i>
									</button>
								<?php } else { ?>
									<button onclick="reserve('<?php echo $book['id'];?>')" type='button' title='Reservar a mis libros' class='edit-btn btn btn-info btn-sm'>
										Reservar libro<i class='fas fa-plus ml-1'></i>
									</button>
								<?php } ?>
								</form>
							</div>
						<?php 
							}
						?>
					</div>
					<button type='button' onclick="redirect_all()" style="margin: 0 auto;" title='Añadir nuevo libro' class='edit-btn btn btn-primary btn-sm ml-4'>Ver todos>></button>
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
			let personal = "<?php echo $personal; ?>";
			function search(){
				let search = document.getElementById('search').value;
				let href = "index.php?search=" + search;
				if (personal)
					href += "&my-books"
				location.href = href;
			}

			$("#search-addon").on('click', function(){
				search();
			});

			function redirect_all() {
				let parameters = window.location.search;
				let urlParams = new URLSearchParams(parameters);
				if (urlParams.has('action')) {
					let search = document.getElementById('search').value;
					let href = "index.php";
					if (search && personal) {
						href += "?search=" + search;
						href += "&my-books";
					} else if (search) {
						href += "?search=" + search;
					} else if (personal){
						href += "?my-books";
					}
					href += "&list-all";
					location.href = href;
				} else if (parameters) {
					add_href = "&list-all"
				} else {
					add_href = "?list-all";
				}
				location.href += add_href;
			}

			function reserve(id_book){
				make_request(
					'<?php echo APP_ROOT ?>api/book/reservate.php', 
					{ 
						action: "Reserve",
						id_book: id_book,
						personal: "<?php echo $personal;?>"
					}
				);
			}

			function delete_reservation(id_reservation){
				make_request(
					'<?php echo APP_ROOT ?>api/book/reservate.php', 
					{ 
						action: "Delete",
						id_reservation: id_reservation,
						personal: "<?php echo $personal;?>"
					}
				);
			}

			<?php if ($action === 'reserve'): ?>
				swal({
					title: "Libro reservado",
					buttonsStyling: false,
					confirmButtonClass: "btn btn-success",
					icon: "success",
					button: "Vale",
				}).catch(swal.noop);
			<?php elseif ($action === 'delete'): ?>
				swal({
					title: "Reserva quitada",
					buttonsStyling: false,
					confirmButtonClass: "btn btn-success",
					icon: "success",
					button: "Vale",
				}).catch(swal.noop);
			<?php endif; ?>


		</script>
	</body>
</html>
