{extends file="layouts/main.tpl"}

{block name="title"}Blogy{/block}

{block name="content"}
    {foreach $categories as $category}
        <section class="category-section">
            <div class="category-section__head">
                <h2 class="category-section__title">{$category.name}</h2>
                <a class="link" href="/category/{$category.id}">View All</a>
            </div>

            <div class="grid">
                {foreach $category.articles as $article}
                    {include file="partials/article-card.tpl" article=$article}
                {/foreach}
            </div>
        </section>
        {foreachelse}
        <p>No articles published yet.</p>
    {/foreach}
{/block}