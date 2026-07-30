{extends file="layouts/main.tpl"}

{block name="title"}Page not found &mdash; Blogy{/block}

{block name="content"}
    <div class="error-page">
        <h1 class="error-page__code">404</h1>
        <p class="error-page__text">The page you are looking for does not exist.</p>
        <a class="link" href="/">Back to home</a>
    </div>
{/block}