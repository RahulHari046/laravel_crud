<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Welcome</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</head>

<body>
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-6">
                <div class="card mt-5">
                    <div class="card-body ">

                        <input type="text" id="name" class="form-control mt-2 " placeholder="name">
                        <span id="namee" class="text-danger"></span>

                        <input type="email" id="email" class="form-control mt-2" placeholder="email">
                        <span id="emaile" class="text-danger"></span>

                        <input type="number" id="age" class="form-control mt-2" placeholder="age">
                        <span id="agee" class="text-danger"></span>

                        <div class="d-flex align-items-center justify-content-center ">
                            <button id="submit" class=" mt-2 btn btn-primary">submit</button>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <div>
            <table class="table text-center" id="userList">
                <thead>
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col">name</th>
                        <th scope="col">email</th>
                        <th scope="col">Age</th>
                        <th scope="col">Delete</th>
                        <th scope="col">Update</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id1">

                        <input type="text" id="name1" class="form-control" placeholder="name">
                        <span id="namee2" class="text-danger"></span>

                        <input type="email" class="form-control mt-3" id="email1" placeholder="email">
                        <span id="emaile2" class="text-danger"></span>

                        <input type="number" id="age1" class="form-control mt-3" placeholder="Age">
                        <span id="agee2" class="text-danger"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="save" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>

        </div>
</body>

</html>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $(document).ready(function() {
        function ajax() {
            $.ajax({
                url: "/getdetails",
                method: "get",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {

                    let tableBody = $("#userList tbody");
                    tableBody.empty();

                    $.each(response.message, function(index, user) {
                        let row = `<tr>
                                <td>${user.id}</td>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td>${user.age}</td>
                                 <td><button class="btn btn-danger" id="delete" data-id="${user.id}">delete</button></td>
                    <td><button class="btn btn-warning" id="edit" data-id="${user.id}">Edit</button></td>
                           </tr>`;
                        tableBody.append(row);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        }

        ajax();

        $("#submit").click(function() {
            let name = $("#name").val()
            let email = $("#email").val()
            let age = $("#age").val()

            if (name == "") {
                $("#namee").text("name required");
                $("#name").focus();
                return false
            } else {
                $("#namee").text("");
            }
            if (email == "") {
                $("#emaile").text("email required");
                $("#email").focus();
                return false
            } else {
                $("#emaile").text("");
            }
            if (age == "") {
                $("#agee").text("age required");
                $("#age").focus();
                return false
            } else {
                $("#agee").text("");
            }

            $.ajax({
                url: "/store",
                method: "post",
                data: {
                    'name': name,
                    'email': email,
                    'age': age,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    if (response.status === true) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Inserted successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });

                        $("#name").val('');
                        $("#email").val('');
                        $("#age").val('');
                        $("#gender").val('');
                        ajax();

                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message || 'Not inserted.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            })
        });

        $(document).on("click", "#delete", function() {
            let userId = $(this).data("id");
            if (confirm("Are you sure you want to delete this user?")) {
                $.ajax({
                    url: "/deletedetails",
                    type: "POST",
                    data: {
                        id: userId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        console.log(response);
                        ajax();
                    },
                    error: function(xhr, status, error) {
                        console.log("Error: " + error);
                        console.log(xhr);
                        console.log(status);
                    }
                });
            }
        });

        $(document).on("click", "#edit", function() {
            let userId = $(this).data("id");
            if (confirm("Are you sure you want to update this user?")) {
                $.ajax({
                    url: "/editdetails",
                    type: "POST",
                    data: {
                        id: userId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        $("#exampleModal").modal("show");
                        $("#id1").val(response.message.id)
                        $("#name1").val(response.message.name)
                        $("#email1").val(response.message.email)
                        $("#age1").val(response.message.age)
                    },
                    error: function(xhr, status, error) {
                        console.log("Error: " + error);
                        console.log(xhr);
                        console.log(status);
                    }
                });
            }
        });

        $(document).on("click", "#save", function() {
            let id2 = $("#id1").val()
            let name2 = $("#name1").val()
            let email2 = $("#email1").val()
            let age2 = $("#age1").val()

            if (name1 == "") {
                $("#name2").text("name required")
                $("#name1").focus();
                return false
            } else {
                $("#namee2").text("")
            }
            if (email1 == "") {
                $("#emaile2").text("email required")
                $("#emaile1").focus();
                return false
            } else {
                $("#emaile2").text("")
            }
            if (age1 == "") {
                $("#agee2").text("age required");
                $("#age1").focus();
                return false
            } else {
                $("#age2").text("");
            }
            $.ajax({
                url: "/updatedetails",
                type: "POST",
                data: {
                    'id': id2,
                    'name': name2,
                    'email': email2,
                    'age': age2,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
               success: function(response) {
                    console.log(response);
                    $("#exampleModal").modal("hide");

                    // Show success Swal, then call ajax()
                    Swal.fire({
                        title: 'Success!',
                        text: 'User updated successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        ajax();
                    });
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                    console.log(xhr);
                    console.log(status);
                }
            })
        })
    })
</script>
