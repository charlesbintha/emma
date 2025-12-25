<?php
/**
 * Script temporaire pour créer le lien symbolique storage
 *
 * IMPORTANT: SUPPRIMEZ CE FICHIER IMMÉDIATEMENT APRÈS UTILISATION !
 *
 * Ce script crée un lien symbolique de public/storage vers storage/app/public
 * Équivalent à la commande: php artisan storage:link
 *
 * Usage:
 * 1. Uploadez ce fichier à la racine de votre projet Laravel
 * 2. Visitez: https://emmaluxury.store/create-storage-link.php
 * 3. Suivez les instructions affichées
 * 4. SUPPRIMEZ ce fichier immédiatement après
 */

// Sécurité: Limitez l'accès (décommentez et ajustez si nécessaire)
// $allowed_ip = 'VOTRE_IP_ICI';
// if ($_SERVER['REMOTE_ADDR'] !== $allowed_ip) {
//     die('Accès refusé');
// }

$target = __DIR__ . '/storage/app/public';
$link = __DIR__ . '/public/storage';

echo "<h1>Création du lien symbolique Storage</h1>";
echo "<hr>";

// Vérifications
echo "<h2>Vérifications:</h2>";
echo "<ul>";

// Vérifier que le dossier target existe
if (is_dir($target)) {
    echo "<li>✅ Le dossier cible existe: <code>$target</code></li>";
} else {
    echo "<li>❌ Le dossier cible n'existe pas: <code>$target</code></li>";
    echo "<li>Créez d'abord le dossier: <code>mkdir -p storage/app/public</code></li>";
    exit;
}

// Vérifier si le lien existe déjà
if (file_exists($link)) {
    if (is_link($link)) {
        $current_target = readlink($link);
        echo "<li>⚠️ Le lien symbolique existe déjà</li>";
        echo "<li>Lien actuel: <code>$link</code> → <code>$current_target</code></li>";

        if (realpath($current_target) === realpath($target)) {
            echo "<li>✅ Le lien pointe vers le bon dossier!</li>";
            echo "</ul>";
            echo "<h2 style='color: green;'>Le lien symbolique est déjà correctement configuré!</h2>";
            echo "<hr>";
            echo "<p><strong>⚠️ SUPPRIMEZ CE FICHIER MAINTENANT:</strong></p>";
            echo "<ol>";
            echo "<li>Via SSH: <code>rm create-storage-link.php</code></li>";
            echo "<li>Via FTP: Supprimez le fichier <code>create-storage-link.php</code></li>";
            echo "</ol>";
            exit;
        } else {
            echo "<li>⚠️ Le lien pointe vers le mauvais dossier</li>";
            echo "<li>Suppression de l'ancien lien...</li>";
            unlink($link);
        }
    } else {
        echo "<li>❌ Un fichier/dossier existe déjà à cet emplacement: <code>$link</code></li>";
        echo "<li>Veuillez le supprimer manuellement avant de continuer</li>";
        exit;
    }
}

echo "</ul>";

// Créer le lien symbolique
echo "<h2>Création du lien:</h2>";
echo "<ul>";

try {
    if (symlink($target, $link)) {
        echo "<li>✅ Lien symbolique créé avec succès!</li>";
        echo "<li>De: <code>$link</code></li>";
        echo "<li>Vers: <code>$target</code></li>";

        // Vérifier que le lien fonctionne
        if (is_link($link) && is_dir($link)) {
            echo "<li>✅ Le lien fonctionne correctement!</li>";
        } else {
            echo "<li>⚠️ Le lien a été créé mais ne semble pas fonctionner</li>";
        }

        echo "</ul>";
        echo "<hr>";
        echo "<h2 style='color: green;'>✅ Succès!</h2>";
        echo "<p>Le lien symbolique a été créé. Vos images devraient maintenant être accessibles via:</p>";
        echo "<p><code>https://emmaluxury.store/storage/perfumes/nom-image.jpg</code></p>";
        echo "<hr>";
        echo "<h2 style='color: red;'>⚠️ ACTION REQUISE:</h2>";
        echo "<p><strong>SUPPRIMEZ CE FICHIER IMMÉDIATEMENT pour des raisons de sécurité!</strong></p>";
        echo "<ol>";
        echo "<li>Via SSH: <code>rm create-storage-link.php</code></li>";
        echo "<li>Via FTP: Supprimez le fichier <code>create-storage-link.php</code></li>";
        echo "<li>Via File Manager: Supprimez le fichier depuis le panneau Hostinger</li>";
        echo "</ol>";

    } else {
        echo "<li>❌ Erreur lors de la création du lien symbolique</li>";
        echo "<li>Raisons possibles:</li>";
        echo "<ul>";
        echo "<li>Les permissions PHP ne permettent pas la création de liens symboliques</li>";
        echo "<li>Votre hébergement ne supporte pas les liens symboliques</li>";
        echo "<li>La fonction symlink() est désactivée</li>";
        echo "</ul>";
        echo "</ul>";
        echo "<hr>";
        echo "<h2>Solution alternative:</h2>";
        echo "<p>Créez le lien manuellement via SSH:</p>";
        echo "<pre>";
        echo "cd " . __DIR__ . "\n";
        echo "php artisan storage:link\n";
        echo "# OU\n";
        echo "cd public\n";
        echo "ln -s ../storage/app/public storage\n";
        echo "</pre>";
    }
} catch (Exception $e) {
    echo "<li>❌ Exception: " . htmlspecialchars($e->getMessage()) . "</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<h2>Informations système:</h2>";
echo "<ul>";
echo "<li>PHP Version: " . PHP_VERSION . "</li>";
echo "<li>OS: " . PHP_OS . "</li>";
echo "<li>Fonction symlink disponible: " . (function_exists('symlink') ? 'Oui' : 'Non') . "</li>";
echo "<li>Répertoire de travail: " . __DIR__ . "</li>";
echo "</ul>";
?>
