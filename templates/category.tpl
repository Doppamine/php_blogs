{extends file="layouts/main.tpl"}

{block name="title"}{$category.name} &mdash; Blogy{/block}

{block name="content"}
    <div class="category-head">
        <h1 class="category-head__title">{$category.name}</h1>
        {if $category.description}
            <p class="category-head__description">{$category.description}</p>
        {/if}
    </div>
    <div class="sort">
        <span class="sort__label">Sort by</span>
        <a class="sort__link{if $sort === 'date'} sort__link--active{/if}"
           href="/category/{$category.id}?sort=date">Newest</a>
        <a class="sort__link{if $sort === 'views'} sort__link--active{/if}"
           href="/category/{$category.id}?sort=views">Most viewed</a>
    </div>
    <div class="grid">
        {foreach $articles as $article}
            {include file="partials/article-card.tpl" article=$article}
            {foreachelse}
            <p>No articles in this category yet.</p>
        {/foreach}
    </div>
    {if $paginator.last_page > 1}
        {include file="partials/pagination.tpl"
        paginator=$paginator
        baseUrl="/category/`$category.id`?sort=`$sort`"}
    {/if}
{/block}