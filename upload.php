<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestand Uploaden</title>
</head>
<body>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #333;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="file"], input[type="submit"] {
            padding: 10px;
            margin: 5px 0;
        }
        a {
            display: inline-block;
            margin-top: 10px;
            color: #007BFF;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Kies een bestand om te uploaden</h1>
        <p>Klik op "Kies bestand" en selecteer het TXT-bestand dat je wilt uploaden. Druk vervolgens op "Upload bestand". Het laatst geüploade bestand kan worden verwerkt en opgeslagen.</p>

        <form action="" method="post" enctype="multipart/form-data">
            Selecteer bestand om te uploaden:
            <input type="file" name="fileToUpload" id="fileToUpload" accept=".php">
            <input type="submit" value="Upload bestand" name="submit">
        </form>

        <div>
            <a href="uploads/" target="_blank">Open uploadmap</a>
        </div>

        <?php
        // Logica voor bestand uploaden

    if (isset($_POST['submit'])) {
        $target_dir = __DIR__ . '/uploads/';
        $target_file = $target_dir . time() . '_' . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Controleer bestandsgrootte
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Sorry, uw bestand is te groot.<br>";
            $uploadOk = 0;
        }

        // Controleer op bestandstype
        if ($imageFileType != "txt") {
            echo "Sorry, alleen txt-bestanden zijn toegestaan.<br>";
            $uploadOk = 0;
        }

        // Probeer het bestand te uploaden
        if ($uploadOk == 0) {
            echo "Sorry, uw bestand is niet geüpload.<br>";
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "Het bestand " . basename($_FILES["fileToUpload"]["name"]) . " is geüpload als " . basename($target_file) . ".<br>";
                echo '<a href="?file=' . urlencode(basename($target_file)) . '">Bewerk dit bestand</a><br>'; // Link naar bewerken
            } else {
                echo "Sorry, er was een fout bij het uploaden van uw bestand.<br>";
            }
        }
    }

    // Controleer of er een bestandsnaam is opgegeven in de URL voor bewerken
    if (isset($_GET['file'])) {
        $file = __DIR__ . '/uploads/' . basename($_GET['file']);
        
        // Controleer of het bestand bestaat
        if (file_exists($file)) {
            $content = file_get_contents($file); // Lees de inhoud van het bestand
        } else {
            echo "Bestand bestaat niet.<br>";
            exit;
        }
    }

    // Opslaan van wijzigingen
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($content)) {
        $new_content = $_POST['content'];
        file_put_contents($file, $new_content); // Sla de nieuwe inhoud op
        echo "Bestand is bijgewerkt.<br>";
    }

    // Formulier voor bewerken
    if (isset($content)): ?>
        <div>
            <form method="post">
                <textarea name="content" rows="10" cols="30"><?php echo isset($content) ? htmlspecialchars($content) : ''; ?></textarea><br>
                <input type="submit" value="Opslaan">
            </form>
        </div>
    <?php endif; ?>
</body>
</html>
