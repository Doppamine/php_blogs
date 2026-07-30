<article class="card">
    <a class="card__media" href="/article/{$article.id}">
        <img src="/assets/images/{$article.image}" alt="{$article.title}">
    </a>

    <h3 class="card__title">
        <a href="/article/{$article.id}">{$article.title}</a>
    </h3>

    <p class="card__date">{$article.date}</p>
    <p class="card__excerpt">{$article.description}</p>

    <a class="link" href="/article/{$article.id}">Continue Reading</a>
</article>