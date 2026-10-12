{if isset($product) && $product}
<div class="blck-sticky is-hidden" data-blck-sticky>
  <div>
    <p class="blck-sticky__name">{$product.name}</p>
    <p class="blck-sticky__price">{$product.price}</p>
  </div>
  <button type="button" class="blck-sticky__add" data-blck-sticky-add{if !$product.add_to_cart_url} disabled{/if}>
    {l s='Add to cart' d='Shop.Theme.Actions'}
  </button>
</div>
<script>
{literal}
(function () {
  var bar = document.querySelector("[data-blck-sticky]");
  var real = document.querySelector("#add-to-cart-or-refresh [data-button-action='add-to-cart']");
  if (!bar || !real) return;
  var add = bar.querySelector("[data-blck-sticky-add]");
  if (add) {
    add.addEventListener("click", function () { real.click(); });
  }
  if (!("IntersectionObserver" in window)) {
    bar.classList.remove("is-hidden");
    return;
  }
  var watcher = new IntersectionObserver(function (entries) {
    bar.classList.toggle("is-hidden", entries[0].isIntersecting);
  }, { threshold: 0.5 });
  watcher.observe(real);
})();
{/literal}
</script>
{/if}
