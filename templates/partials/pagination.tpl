<nav class="pagination">
    {if $paginator.previous_page}
        <a class="pagination__link" href="{$baseUrl}&amp;page={$paginator.previous_page}">Prev</a>
    {/if}

    {foreach $paginator.pages as $page}
        {if $page === $paginator.current_page}
            <span class="pagination__link pagination__link--active">{$page}</span>
        {else}
            <a class="pagination__link" href="{$baseUrl}&amp;page={$page}">{$page}</a>
        {/if}
    {/foreach}

    {if $paginator.next_page}
        <a class="pagination__link" href="{$baseUrl}&amp;page={$paginator.next_page}">Next</a>
    {/if}
</nav>