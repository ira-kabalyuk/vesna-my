<div id="dropmenu" class="dropmenu">
{row:ADMIN_MENU}
{if:!MOD}<a href="{HREF}" {CLASS}>{ATITLE}</a>{/if}
{if:MOD}<a href="#" onclick="Smart.load_curl('#div_content','{HREF}')"{CLASS}>{ATITLE}</a>{/if}
{/row}
</div>