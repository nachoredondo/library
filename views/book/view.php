<?php
require '../../classes/session.php';
require '../../classes/book.php';
require '../../classes/user.php';

Session::check_login_redirect();

if (isset($_REQUEST['id']))
	$id_book = $_REQUEST['id'];
else
	header("Location: ../main/");

$book = Book::get_book($id_book);
$authors = Book::get_authors($id_book);
$categories = Book::get_categories($id_book);


?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include "../general/header.php" ?>
		<title>Librería</title>
		<style type="text/css">
			.card-book-view {
				margin: 0 auto;
			    max-width:700px;
			}
		</style>
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
						<?php echo $book->title() ?>
					</h2>
					<div class="card p-4 card-book-view" >
						<?php if ($book->description) { ?>
							Descripción: <?php echo $book->description; ?>
						<?php } else { ?>
							Sin descripción
						<?php } ?>

						<img class="mt-2 mb-2" id="image-book" alt="<?php echo $book->title(); ?>" src="../../files/book/<?php echo $book->image; ?>"/>
						<div class="isbn mt-1">ISBN: <?php echo $book->ISBN(); ?></div>
						<div class="mt-2">
							<?php if (count($authors) == 0){ ?>
								No hay ningún autor asociado.
							<?php 
							} else {
								if (count($authors) == 1)
									echo "Autor: ";
								else
									echo "Autores: ";
								$num_author = 1;
								foreach ($authors as $author) {
									echo $author['name'];
									if ($num_author != count($authors))
										echo ", ";
									$num_author ++;
								}

							} ?>
						</div>
						<div class="mt-2">
							<?php if (count($categories) == 0){ ?>
								<div class="ml-4 mt-2">
									No hay ninguna categoría asociada.
								</div>
							<?php 
							} else {
								if (count($categories) == 1)
									echo "Categoría: ";
								else
									echo "Categorías: ";
								$num_category = 1;
								foreach ($categories as $category) {
									echo $category['name'];
									if ($num_category != count($categories))
										echo ", ";
									$num_category ++;
								}

							} ?>
						</div>
						<form action="../book/edit_create.php" class="mt-1">
							<input type="hidden" value="<?php echo $id_book;?>" name="id">
							<input class="btn btn-primary btn-sm mb-2 mt-3 ml-5" value="Editar" type="submit">
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
	</body>
</html>
