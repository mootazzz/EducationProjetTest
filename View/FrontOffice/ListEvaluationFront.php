<?php 
// Include PHPMailer files
require '../../phpmailer/src/PHPMailer.php';
require '../../phpmailer/src/Exception.php';
require '../../phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include your EvaluationController
include 'C:\xampp\htdocs\EducationProjetTest - testlekher\Controller\EvaluationController.php'; 
$evaluationController = new EvaluationController();
$list = $evaluationController->listEvaluations();

// Function to send email using PHPMailer
function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // SMTP server (adjust with your SMTP provider)
        $mail->SMTPAuth = true;
        $mail->Username = 'mootazzarai@gmail.com';  // SMTP username
        $mail->Password = 'xrtr uprq obkw frve';      // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient details
        $mail->setFrom('from-email@example.com', 'Student');
        $mail->addAddress($to); // Add recipient's email

        // Email content
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Send email
        $mail->send();
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}


// Vérifiez si l'email et la suggestion sont soumis
if (isset($_POST['email'], $_POST['suggestion'])) {
    $userEmail = $_POST['email'];
    $userSuggestion = $_POST['suggestion'];

    // Préparez le contenu de l'email
    $subject = 'New Suggestion from a Student';
    $body = "You have received a new suggestion from a student. Here are the details:\n\n";
    $body .= "Suggestion: " . $userSuggestion . "\n\n";

    // Envoyez l'email
    sendEmail($userEmail, $subject, $body);

    // Optionnel : Affichez un message de confirmation sur la page
    //echo '<div class="alert alert-success">Your suggestion has been submitted successfully! Check your email for feedback.</div>';
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Evaluation List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Your existing CSS styles */
        body {
            background: linear-gradient(135deg, rgb(69, 72, 154), rgb(36, 36, 36));
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
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
            max-width: 90%;
            margin-left: auto;
            margin-right: auto;
            color: black;
        }

        h1 {
            color: black;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 4px solid rgb(70, 73, 158);
        }

        table th,
        table td {
            padding: 15px;
            text-align: center;
            border: 4px solid rgb(70, 73, 158);
            font-size: 1rem;
        }

        table th {
            background-color: rgb(36, 36, 36);
            color: rgb(69, 72, 154);
            font-weight: bold;
        }

        table td {
            background-color: rgb(69, 72, 154);
            color: black;
        }

        table tr:nth-child(even) td {
            background-color: #ffffff;
            color: black;
        }

        table tr:hover td {
            background-color: rgb(70, 73, 158);
            color: white;
            transition: background-color 0.3s;
        }

        table td a,
        table td input[type="submit"] {
            font-size: 0.9rem;
            width: 120px;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
            display: inline-block;
            border: 2px solid black;
            color: white;
        }

        .btn-primary {
            background-color: rgb(36, 36, 36);
            color: white;
            border: 2px solid rgb(70, 73, 158);
        }

        .btn-primary:hover {
            background-color: rgb(69, 72, 154);
            border: 2px solid white;
            color: black;
        }

        .btn-primary:active {
            background-color: rgb(70, 73, 158);
            border: 2px solid white;
            color: black;
        }

        .btn-danger {
            background-color: rgb(70, 73, 158);
            color: white;
            border: 2px solid rgb(36, 36, 36);
        }

        .btn-danger:hover {
            background-color: rgb(36, 36, 36);
            border: 2px solid white;
            color: white;
        }

        .btn-danger:active {
            background-color: rgb(69, 72, 154);
            border: 2px solid white;
            color: black;
        }

        .btn-info {
            background-color: rgb(35, 35, 32);
            color: white;
            border: 2px solid rgb(69, 72, 154);
        }

        .btn-info:hover {
            background-color: rgb(36, 36, 36);
            border: 2px solid white;
            color: white;
        }

        .btn-info:active {
            background-color: rgb(70, 73, 158);
            border: 2px solid white;
            color: white;
        }
        

        
    </style>
</head>

<body>
    <div class="container">
        <h1>List Of Evaluations  <a href="GeneratePDF.php" class="btn btn-info btn-sm">Export To PDF</a></h1>
        


        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Deadline</th>
                    <th>Duration (min)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($list as $evaluation): ?>
                    <tr>
                        <td><?= $evaluation->getTitre(); ?></td>
                        <td><?= $evaluation->getDescription(); ?></td>
                        <td><?= $evaluation->getType(); ?></td>
                        <td><?= $evaluation->getDateLimite(); ?></td>
                        <td><?= $evaluation->getDuree(); ?></td>
                        <td>
                            <a href="ViewQuestionFront.php?id=<?= $evaluation->getIdEvaluation(); ?>" class="btn btn-info btn-sm">Select</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        
        <!-- Boutons centrés -->
        <div class="text-center mt-4">
             <!-- Formulaire mis à jour -->
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="email" class="form-label">Teacher Email</label>
                <input type="email" class="form-control" id="email" name="email" required placeholder="Enter the email">
            </div>
            <div class="mb-3">
                <label for="suggestion" class="form-label">Your Suggestion</label>
                <textarea class="form-control" id="suggestion" name="suggestion" rows="4" required placeholder="Enter your suggestion"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit Suggestion</button>
        </form>
            
           
        </div>
        <script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector('form');
        const emailInput = document.getElementById('email');
        const suggestionInput = document.getElementById('suggestion');

        form.addEventListener('submit', function (e) {
            let valid = true;

            // Clear previous errors
            clearErrors();

            // Validation de l'email
            if (!validateEmail(emailInput.value)) {
                valid = false;
                showError(emailInput, "Please enter a valid email address.");
            }

            // Validation de la suggestion
            if (suggestionInput.value.trim() === "") {
                valid = false;
                showError(suggestionInput, "Please enter your suggestion.");
            }

            if (!valid) {
                e.preventDefault(); // Empêche l'envoi du formulaire si des erreurs existent
            }
        });

        // Fonction pour valider un email avec une expression régulière
        function validateEmail(email) {
            const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return regex.test(email);
        }

        // Fonction pour afficher un message d'erreur sous un champ
        function showError(input, message) {
            const errorElement = document.createElement('div');
            errorElement.classList.add('text-danger', 'mt-2');
            errorElement.textContent = message;
            input.classList.add('is-invalid');
            input.parentElement.appendChild(errorElement);
        }

        // Fonction pour effacer les erreurs précédentes
        function clearErrors() {
            const errors = document.querySelectorAll('.text-danger');
            errors.forEach(error => error.remove());
            const invalidInputs = document.querySelectorAll('.is-invalid');
            invalidInputs.forEach(input => input.classList.remove('is-invalid'));
        }
    });
</script>

    
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
