<?php
require '../../classes/session.php';
require '../../classes/user.php';

Session::check_login_redirect();
$message = $_REQUEST['message'] ?? '';

$action = $_REQUEST['action'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include "../general/header.php" ?>
        <title>Autores</title>
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <?php include '../general/headerbar.php' ?>
        <!-- Contact Section-->
        <section class="page-section" id="contact">
            <div class="container">
                <!-- Contact Section Heading-->
                <h2 class="text-center text-uppercase text-secondary mt-4">
                    Listado de autores
                </h2>
                <!-- Contact Section Form-->
                <div class="row">
                    <div class="col-lg-11 mx-auto">
                        <div class="card p-3 mt-3">
                                <a href="edit_create.php">
                                    <input type="button" class="btn btn-primary ml-5 mb-2" value="Crear nuevo autor"/>
                                </a>
                            <div class="form-group floating-label-form-group controls mb-0 pb-2">
                                <div class="table-responsive">
                                    <table id="table-author" class="table table-striped compact nowrap" style="min-width:100%">
                                        <thead><!-- Leave empty. Column titles are automatically generated --></thead>
                                    </table>
                                </div>
                            </div>
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
            function make_request(path, params, method) {
                method = method || "post"; // Set method to post by default if not specified.

                var form = document.createElement("form");
                form.setAttribute("method", method);
                form.setAttribute("action", path);

                for (var key in params) {
                    if (params.hasOwnProperty(key)) {
                        var hiddenField = document.createElement("input");
                        hiddenField.setAttribute("type", "hidden");
                        hiddenField.setAttribute("name", key);
                        hiddenField.setAttribute("value", params[key]);

                        form.appendChild(hiddenField);
                    }
                }

                document.body.appendChild(form);
                form.submit();
            }

            function control_date(date){
                if (date=="0000-00-00") {
                    return "empty";
                }
            }

            window.addEventListener('load', function () {
                let table_author = $('#table-author').DataTable({
                    order: [[1, 'asc']],
                    serverSide: true,
                    lengthMenu: [[5, 10, -1], [5, 10, 'Todos']],
                    language: {
                        url: "../../assets/datatables/es.json",
                    },
                    columns: [
                        {
                            sorting: false,
                            defaultContent:
                                '<button type="button" title="Editar" class="edit-btn btn btn-success btn-sm mr-2"><i class="fas fa-edit"></i></button>' +
                                '<button type="button" title="Eliminar" class="remove-btn btn btn-info btn-sm"><i class="fas fa-trash-alt"></i></button>',
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
                        {
                            data: 'birthdate',
                            title: 'Fecha de nacimiento',
                            searchable: false,
                        },
                        {
                            data: 'death_date',
                            title: 'Fecha de muerte',
                            searchable: false,
                        },
                    ],
                    ajax: {
                        method: 'POST',
                        url: "../../api/author/list_all_authors.php",
                        data: function (params) {
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

                $('#table-author tbody').on('click', 'button', function () {
                    let data = table_author.row($(this).parents('tr')).data();
                    if (this.classList.contains('edit-btn')) {
                        make_request('<?php echo APP_ROOT ?>views/author/edit_create.php', { id: data["id"] });
                    } else if (this.classList.contains('remove-btn')) {
                        swal({
                            title: "¿Estás seguro de que quieres borrar la opción?",
                            icon: "warning",
                            buttonsStyling: false,
                            buttons: ["No", "Si"],
                        })
                        .then((willDelete) => {
                            if (willDelete) {
                                make_request(
                                    '<?php echo APP_ROOT ?>/views/author/control.php',
                                    {
                                        id: data["id"],
                                        form: "delete"
                                    }
                                );
                            } else {
                                swal("La opción no ha sido borrada");
                            }
                        })
                        .catch(function() { writeToScreen('err: Hubo un error al borrar la opción.', true)});
                    } else {
                        console.error("Botón pulsado desconocido!");
                    }
                });
            });

            <?php if ($action === 'update'): ?>
                swal({
                    title: "Autor actualizado",
                    buttonsStyling: false,
                    confirmButtonClass: "btn btn-success",
                    icon: "success",
                    button: "Vale",
                }).catch(swal.noop);
            <?php elseif ($action === 'create'): ?>
                swal({
                    title: "Autor creado",
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
