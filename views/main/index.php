<?php
require '../../classes/session.php';
require '../../classes/user.php';
require '../../classes/book.php';

Session::check_login_redirect();

$user = User::get_user_from_user($_SESSION['user']);
if (isset($_REQUEST['search'])){
	$search = $_REQUEST['search'];
}else {
	$search = '';
}
$books = Book::search_books($search);

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
						Catálogo
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
					<div class="row">
						<button type='button' style="margin: 0 auto;" title='Añadir a mis libros' class='edit-btn btn btn-primary btn-sm ml-5'>
									Nuevo libro<i class='fas fa-plus ml-1'></i>
						</button>
					</div>
					<div class="row m-2 ml-3">
						<?php foreach ($books as $book) { ?>
							<div class="card p-2 m-2 col-12 col-sm-5 col-md-3 col-lg-2">
								<a href="../book/view.php?id=<?php echo $book['id'];?>">
									<div><?php echo $book['title'] ?></div>
									<img class="p-2" id="image-book" alt="<?php echo $book['title'] ?>" src="../../files/book/book.jpg"/>
								</a>
								<div class="isbn mt-1">ISBN: <?php echo $book['ISBN'] ?></div>
								<form action="../book/edit_create.php" class="mt-1">
									<input type="hidden" value="<?php echo $book['id'];?>" name="id">
									<input class="btn btn-primary btn-sm mb-2" value="Editar" type="submit">
								</form>
								<button type='button' title='Reservar a mis libros' class='edit-btn btn btn-info btn-sm'>
									Reservar libro<i class='fas fa-plus ml-1'></i>
								</button>
								</form>
							</div>
						<?php } ?>
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
			function search(){
				let search = document.getElementById('search').value;
				location.href = "index.php?search=" + search;
			}

			$("#search-addon").on('click', function(){
				search();
			});
		</script>
	</body>
</html>
