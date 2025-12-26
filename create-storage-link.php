<?php
/**
 * Script pour créer le lien symbolique storage sur Hostinger
 *
 * IMPORTANT: SUPPRIMEZ CE FICHIER APRÈS UTILISATION !
 *
 * Ce fichier crée le lien symbolique public/storage -> storage/app/public
 * nécessaire pour que les images des parfums soient accessibles.
 *
 * Utilisation:
 * 1. Uploadez ce fichier à la racine de votre projet Laravel
 * 2. Visitez https://emmaluxury.store/create-storage-link.php
 * 3. Vérifiez le message de succès
 * 4. SUPPRIMEZ ce fichier immédiatement !
 */

// Couleurs pour le HTML
echo '<!DOCTYPE html>
<html>
<head>
    <title>Création du lien symbolique</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .success {
            padding: 15px;
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            color: #155724;
            margin: 20px 0;
        }
        .error {
            padding: 15px;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            color: #721c24;
            margin: 20px 0;
        }
        .info {
            padding: 15px;
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 5px;
            color: #0c5460;
            margin: 20px 0;
        }
        .warning {
            padding: 15px;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            color: #856404;
            margin: 20px 0;
            font-weight: bold;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: "Courier New", monospace;
        }
        .steps {
            margin: 20px 0;
            padding-left: 20px;
        }
        .steps li {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔗 Création du lien symbolique storage</h1>';

// Chemins
$target = __DIR__ . '/storage/app/public';
$link = __DIR__ . '/public/storage';

echo '<div class="info">';
echo '<strong>Configuration détectée:</strong><br>';
echo '<strong>Dossier racine:</strong> ' . __DIR__ . '<br>';
echo '<strong>Cible (storage):</strong> ' . $target . '<br>';
echo '<strong>Lien (public/storage):</strong> ' . $link . '<br>';
echo '</div>';

// Vérifications
$errors = [];
$warnings = [];

// Vérifier que le dossier target existe
if (!is_dir($target)) {
    $errors[] = "Le dossier <code>storage/app/public</code> n'existe pas !";
} else {
    echo '<div class="success">✓ Le dossier <code>storage/app/public</code> existe</div>';
}

// Vérifier que le dossier public existe
if (!is_dir(__DIR__ . '/public')) {
    $errors[] = "Le dossier <code>public</code> n'existe pas !";
} else {
    echo '<div class="success">✓ Le dossier <code>public</code> existe</div>';
}

// Vérifier si le lien existe déjà
if (file_exists($link)) {
    if (is_link($link)) {
        $current_target = readlink($link);
        echo '<div class="info">';
        echo '⚠️ Le lien symbolique existe déjà !<br>';
        echo '<strong>Cible actuelle:</strong> ' . $current_target . '<br>';

        // Vérifier si c'est le bon lien
        $expected_target = '../storage/app/public';
        if ($current_target === $expected_target || realpath($link) === realpath($target)) {
            echo '<div class="success" style="margin-top: 10px;">✓ Le lien pointe vers le bon emplacement !</div>';
        } else {
            echo '<div class="warning" style="margin-top: 10px;">';
            echo '⚠️ Le lien pointe vers un mauvais emplacement !<br>';
            echo 'Attendu: <code>' . $expected_target . '</code><br>';
            echo 'Actuel: <code>' . $current_target . '</code>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        $warnings[] = "Un fichier/dossier <code>public/storage</code> existe déjà mais ce n'est PAS un lien symbolique !";
    }
} else {
    // Créer le lien
    echo '<div class="info">📝 Tentative de création du lien symbolique...</div>';

    try {
        if (@symlink('../storage/app/public', $link)) {
            echo '<div class="success">';
            echo '<h2>✅ SUCCÈS !</h2>';
            echo 'Le lien symbolique a été créé avec succès !<br><br>';
            echo '<strong>Vérification:</strong><br>';

            if (is_link($link)) {
                echo '✓ Le lien existe: <code>' . $link . '</code><br>';
                echo '✓ Pointe vers: <code>' . readlink($link) . '</code><br>';
            }

            echo '</div>';

            echo '<div class="warning">';
            echo '⚠️ <strong>ACTION REQUISE:</strong> SUPPRIMEZ CE FICHIER IMMÉDIATEMENT !<br>';
            echo 'Supprimez <code>create-storage-link.php</code> de votre serveur pour des raisons de sécurité.';
            echo '</div>';

        } else {
            $errors[] = "Impossible de créer le lien symbolique. Votre hébergeur n'autorise peut-être pas cette opération.";
        }
    } catch (Exception $e) {
        $errors[] = "Erreur lors de la création du lien: " . $e->getMessage();
    }
}

// Afficher les erreurs
if (!empty($errors)) {
    echo '<div class="error">';
    echo '<h3>❌ Erreurs détectées:</h3>';
    echo '<ul>';
    foreach ($errors as $error) {
        echo '<li>' . $error . '</li>';
    }
    echo '</ul>';

    echo '<h4>Solutions alternatives:</h4>';
    echo '<ol class="steps">';
    echo '<li>Connectez-vous en SSH et exécutez: <code>php artisan storage:link</code></li>';
    echo '<li>Ou créez le lien manuellement: <code>ln -s ../storage/app/public public/storage</code></li>';
    echo '<li>Contactez le support Hostinger si ces méthodes ne fonctionnent pas</li>';
    echo '</ol>';
    echo '</div>';
}

// Afficher les warnings
if (!empty($warnings)) {
    echo '<div class="warning">';
    echo '<h3>⚠️ Avertissements:</h3>';
    echo '<ul>';
    foreach ($warnings as $warning) {
        echo '<li>' . $warning . '</li>';
    }
    echo '</ul>';
    echo '</div>';
}

// Instructions de test
echo '<div class="info">';
echo '<h3>🧪 Tests à effectuer:</h3>';
echo '<ol class="steps">';
echo '<li>Vérifiez qu\'une image de parfum s\'affiche dans votre navigateur:<br>';
echo '<code>https://emmaluxury.store/storage/perfumes/[nom-image].jpg</code></li>';
echo '<li>Testez l\'API:<br>';
echo '<code>https://emmaluxury.store/api/perfumes</code><br>';
echo 'Les URLs d\'images devraient être: <code>https://emmaluxury.store/storage/perfumes/...</code></li>';
echo '<li>Relancez votre application mobile Flutter et vérifiez que les images s\'affichent</li>';
echo '</ol>';
echo '</div>';

echo '</div></body></html>';
?>
