{extends file="layouts/main.tpl"}

{block name="title"}{$article.title} &mdash; Blogy{/block}

{block name="content"}
    <article class="article">
        <img class="article__image" src="/assets/images/{$article.image}" alt="{$article.title}">

        <h1 class="article__title">{$article.title}</h1>

        <p class="article__meta">
            {$article.date} &middot; {$article.views_count} views
            {if $categories}
                &middot;
                {foreach $categories as $category}
                    <a class="article__category"
                       href="/category/{$category.id}">{$category.name}</a>{if !$category@last}, {/if}
                {/foreach}
            {/if}
        </p>

        <p class="article__lead">{$article.description}</p>

        <div class="article__body">
            {foreach $article.paragraphs as $paragraph}
                <p>{$paragraph}</p>
            {/foreach}
        </div>
    </article>
    {if $similar}
        <section class="similar">
            <h2 class="similar__title">Similar Articles</h2>

            <div class="grid">
                {foreach $similar as $article}
                    {include file="partials/article-card.tpl" article=$article}
                {/foreach}
            </div>
        </section>
    {/if}
{/block}