<?php
session_start();

// Connexion à la base de données MySQL (WAMP)
$host = "db";
$user = "user";
$password = "userpassword"; // Par défaut, pas de mot de passe sous WAMP
$database = "trombinoscope";

$conn = new mysqli($host, $user, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

// Gestion de la connexion utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->fetch();
    $stmt->close();

    // Vérifier si le mot de passe est correct
    if ($db_password && $password === $db_password) {
        $_SESSION['user'] = $email;
        header("Location: index.php");
        exit();
    } else {
        $error = "Email ou mot de passe incorrect";
    }
}

// Gestion de la déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trombinoscope</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #252424;
            color: #ffffff;
        }
        .card {
            background-color: #1e1e2f;
            border: 1px solid #bebebe;
            cursor: pointer;
        }
        .card-title {
            color: #ffffff;
        }
        .modal-content {
            background-color: #1e1e2f;
            color: #ffffff;
        }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['user'])): ?>
    <!-- Formulaire de connexion -->
    <div class="container py-5 text-center">
        <h2>Connexion</h2>
        <form method="POST" class="w-50 mx-auto">
            <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
            <input type="password" name="password" class="form-control mb-2" placeholder="Mot de passe" required>
            <button type="submit" name="login" class="btn btn-primary">Se connecter</button>
        </form>
        <?= isset($error) ? "<p class='text-danger'>$error</p>" : "" ?>
    </div>
<?php else: ?>
    <!-- Affichage du trombinoscope avec recherche -->
    <div class="container py-5">
        <h1 class="text-center mb-4">Trombinoscope</h1>
        <a href="?logout" class="btn btn-danger mb-4">Déconnexion</a>
        <input type="text" id="search" class="form-control mb-4" placeholder="Rechercher une personne...">
        
        <div class="row" id="profile-container">
            <div class="col-md-4 mb-4 profile-card" data-name="Billy JEAN BAPTISTE" data-description="Développeur passionné par l'IA et les nouvelles technologies." data-img="BILLY.jpg">
                <div class="card text-center">
                    <img src="BILLY.jpg" class="card-img-top" alt="Photo de profil">
                    <div class="card-body">
                        <h5 class="card-title">Billy JEAN BAPTISTE</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 profile-card" data-name="Tony CASISA" data-description="Expert en cybersécurité et réseaux." data-img="TONY.jpg">
                <div class="card text-center">
                    <img src="TONY.jpg" class="card-img-top" alt="Photo de profil">
                    <div class="card-body">
                        <h5 class="card-title">Tony CASISA</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 profile-card" data-name="Victor TASSART" data-description="Spécialiste en développement mobile et UX/UI design." data-img="VICTOR.jpg">
                <div class="card text-center">
                    <img src="VICTOR.jpg" class="card-img-top" alt="Photo de profil">
                    <div class="card-body">
                        <h5 class="card-title">Victor TASSART</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 profile-card" data-name="Abass YOUSSOUF ADAWEH" data-description="Ingénieur en intelligence artificielle et data science." data-img="ABASS.jpg">
                <div class="card text-center">
                    <img src="ABASS.jpg" class="card-img-top" alt="Photo de profil">
                    <div class="card-body">
                        <h5 class="card-title">Abass YOUSSOUF ADAWEH</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour afficher les détails du profil -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileName"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="profileDescription"></p>
                    <a href="#" id="profileImageLink" target="_blank">
                        <img id="profileImage" src="" class="img-fluid" alt="Image du profil">
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const profileCards = document.querySelectorAll(".profile-card");
            const modal = new bootstrap.Modal(document.getElementById("profileModal"));
            const profileName = document.getElementById("profileName");
            const profileDescription = document.getElementById("profileDescription");
            const profileImage = document.getElementById("profileImage");
            const profileImageLink = document.getElementById("profileImageLink");
            const searchInput = document.getElementById("search");

            profileCards.forEach(card => {
                card.addEventListener("click", function () {
                    profileName.textContent = this.dataset.name;
                    profileDescription.textContent = this.dataset.description;
                    profileImage.src = this.dataset.img;
                    profileImageLink.href = this.dataset.img;
                    modal.show();
                });
            });

            searchInput.addEventListener("input", function () {
                const searchValue = this.value.toLowerCase();
                profileCards.forEach(card => {
                    const name = card.dataset.name.toLowerCase();
                    if (name.includes(searchValue)) {
                        card.style.display = "block";
                    } else {
                        card.style.display = "none";
                    }
                });
            });
        });
    </script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
