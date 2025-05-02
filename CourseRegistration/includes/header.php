<?php
$pageTitle = $pageTitle ?? 'Course Registration System';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= htmlspecialchars(getRelativePath('styles/main.css')) ?>">
</head>
<body>
    <div class="container">
        <header>
            <h1><?= htmlspecialchars($pageTitle) ?></h1>
        </header>
        <main>