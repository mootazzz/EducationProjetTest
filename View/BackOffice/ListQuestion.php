<?php
include '../../controller/QuestionController.php'; // Inclure le contrôleur des questions
$questionController = new QuestionController();
$list = $questionController->listQuestions(); // Récupérer la liste des questions

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Question List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0052cc, #66b2ff);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 0;
            margin: 0;
            height: 100vh;
        }

        .container {
            background-color: #ffffff;
            margin-top: 50px;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            max-width: 90%;
            margin-left: auto;
            margin-right: auto;
        }

        h1 {
            color: #0052cc;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
            font-size: 1rem;
        }

        table th {
            background-color: #4e54c8;
            color: #ffffff;
        }

        table td {
            background-color: #f5f5f5;
        }

        table td a,
        table td input[type="submit"] {
            font-size: 0.9rem;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 6px;
            transition: 0.3s;
        }

        .btn-warning {
            background-color: #ffb74d;
            color: #fff;
            font-weight: bold;
        }

        .btn-warning:hover {
            background-color: #ff9800;
        }

        .btn-danger {
            background-color: #e57373;
            color: #fff;
            font-weight: bold;
        }

        .btn-danger:hover {
            background-color: #f44336;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>List Of Questions</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Question ID</th>
                    <th>Question Text</th>
                    <th>Question Type</th>
                    <th>Evaluation ID</th>
                    <th colspan="2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $question): ?>
                    <tr>
                        <td><?= $question->getIdQuestion(); ?></td>
                        <td><?= $question->getContenu(); ?></td>
                        <td><?= $question->getType(); ?></td>
                   
                        <td>
                            <form method="POST" action="updateQuestion.php">
                                <input type="submit" name="update" value="Update" class="btn btn-warning btn-sm">
                                <input type="hidden" value="<?= $question->getIdQuestion(); ?>" name="id">
                            </form>
                        </td>
                        <td>
                            <a href="#" onclick="confirmDelete(<?= $question->getIdQuestion(); ?>)" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Function to confirm deletion
        function confirmDelete(id) {
            const userConfirmed = confirm("Are you sure you want to delete this question?");
            if (userConfirmed) {
                // Redirect to the delete URL if confirmed
                window.location.href = `DeleteQuestion.php?id=${id}`;
            }
        }

        // Example validation for the update form (if fields are editable in the form)
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(event) {
                const inputs = form.querySelectorAll('input, select, textarea');
                let isValid = true;

                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                        alert(`Please fill the ${input.name} field.`);
                        input.focus();
                    }
                });

                if (!isValid) {
                    event.preventDefault(); // Prevent form submission if validation fails
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
