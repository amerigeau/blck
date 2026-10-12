<div class="blck-announce" data-blck-announce data-key="{$blckpaid_bar_key|escape:'html':'UTF-8'}">
  {if $blckpaid_bar.bar_url}
    <a href="{$blckpaid_bar.bar_url|escape:'html':'UTF-8'}"{if $blckpaid_bar.bar_blank} target="_blank" rel="noopener noreferrer"{/if}>{$blckpaid_bar.bar_text|escape:'html':'UTF-8'}</a>
  {else}
    <span>{$blckpaid_bar.bar_text|escape:'html':'UTF-8'}</span>
  {/if}
  <button type="button" class="blck-announce__close" data-blck-announce-close aria-label="{l s='Close' d='Shop.Theme.Global'}">
    <svg width="12" height="12" viewBox="0 0 12 12" aria-hidden="true">
      <path d="M1 1l10 10M11 1L1 11" stroke="currentColor" stroke-width="1.5"/>
    </svg>
  </button>
</div>
<script>
{literal}
(function () {
  var bar = document.querySelector("[data-blck-announce]");
  if (!bar) return;
  var key = "blckpaid:" + (bar.getAttribute("data-key") || "");
  try {
    if (window.sessionStorage && sessionStorage.getItem(key) === "1") {
      bar.remove();
      return;
    }
  } catch (e) {}
  var close = bar.querySelector("[data-blck-announce-close]");
  if (!close) return;
  close.addEventListener("click", function () {
    try { sessionStorage.setItem(key, "1"); } catch (e) {}
    bar.remove();
  });
})();
{/literal}
</script>
