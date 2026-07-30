<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{block name="title"}Blogy{/block}</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body class="page">

<header class="site-header">
    <div class="container">
        <a class="site-header__logo" href="/">Blogy.</a>
    </div>
</header>

<main class="site-main">
    <div class="container">
        {block name="content"}{/block}
    </div>
</main>

<footer class="site-footer">
    <div class="container">
        <p class="site-footer__text">Beksultan</p>
    </div>
</footer>

</body>
</html>