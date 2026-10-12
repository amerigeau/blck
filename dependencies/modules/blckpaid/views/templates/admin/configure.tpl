<div class="blckpaid">
  <header class="blckpaid-head">
    <h1>{$blckpaid_labels.title}</h1>
    <p>{$blckpaid_labels.intro}{if $blckpaid_config_link} <a href="{$blckpaid_config_link}">{$blckpaid_labels.config_link}</a>{/if}</p>
  </header>

  {if $blckpaid_error}
    <p class="blckpaid-error" role="alert">{$blckpaid_error}</p>
  {/if}

  <form method="post" action="{$blckpaid_action}" id="blckpaid-form">
    <div
      class="blckpaid-stage"
      data-blckpaid-stage
      data-off="{$blckpaid_labels.preview_off|escape:'html':'UTF-8'}"
      data-empty="{$blckpaid_labels.preview_empty|escape:'html':'UTF-8'}"
      data-contrast="{$blckpaid_labels.contrast|escape:'html':'UTF-8'}"
    >
      <p class="blckpaid-stage__note" data-blckpaid-note></p>
      <div class="blck-announce blckpaid-stage__bar" data-blckpaid-bar hidden>
        <a href="#" data-blckpaid-bar-link>{$blckpaid.bar_text|escape:'html':'UTF-8'}</a>
      </div>
      <p class="blckpaid-stage__warn" data-blckpaid-contrast hidden></p>
    </div>

    <section class="blckpaid-block">
      <div class="blckpaid-block__top">
        <div>
          <h2>{$blckpaid_labels.bar}</h2>
          <p>{$blckpaid_labels.bar_help}</p>
        </div>
        <label class="blckpaid-switch">
          <input type="checkbox" name="BLCKPAID_BAR_ENABLED" value="1" data-blckpaid-toggle="bar"{if $blckpaid.bar_enabled} checked{/if}>
          <span class="blckpaid-switch__ui"></span>
          <span>{$blckpaid_labels.show_bar}</span>
        </label>
      </div>
      <div class="blckpaid-fields" data-blckpaid-panel="bar"{if !$blckpaid.bar_enabled} hidden{/if}>
        <label>
          <span>{$blckpaid_labels.sentence}</span>
          <input type="text" name="BLCKPAID_BAR_TEXT" maxlength="180" value="{$blckpaid.bar_text|escape:'html':'UTF-8'}" data-blckpaid-text>
        </label>
        <label>
          <span>{$blckpaid_labels.link}</span>
          <input type="text" name="BLCKPAID_BAR_URL" maxlength="255" value="{$blckpaid.bar_url|escape:'html':'UTF-8'}" placeholder="https://" data-blckpaid-url>
        </label>
        <label class="blckpaid-check">
          <input type="checkbox" name="BLCKPAID_BAR_BLANK" value="1"{if $blckpaid.bar_blank} checked{/if}>
          <span>{$blckpaid_labels.blank}</span>
        </label>
        <div class="blckpaid-colors">
          <label>
            <span>{$blckpaid_labels.background}</span>
            <span class="blckpaid-color">
              <input type="color" value="{$blckpaid.bar_bg|escape:'html':'UTF-8'}" data-blckpaid-picker="bg">
              <input type="text" name="BLCKPAID_BAR_BG" value="{$blckpaid.bar_bg|escape:'html':'UTF-8'}" maxlength="7" spellcheck="false" data-blckpaid-hex="bg">
            </span>
          </label>
          <label>
            <span>{$blckpaid_labels.text}</span>
            <span class="blckpaid-color">
              <input type="color" value="{$blckpaid.bar_color|escape:'html':'UTF-8'}" data-blckpaid-picker="fg">
              <input type="text" name="BLCKPAID_BAR_COLOR" value="{$blckpaid.bar_color|escape:'html':'UTF-8'}" maxlength="7" spellcheck="false" data-blckpaid-hex="fg">
            </span>
          </label>
        </div>
      </div>
    </section>

    <section class="blckpaid-block">
      <div class="blckpaid-block__top">
        <div>
          <h2>{$blckpaid_labels.hover}</h2>
          <p>{$blckpaid_labels.hover_help}</p>
        </div>
        <label class="blckpaid-switch">
          <input type="checkbox" name="BLCKPAID_HOVER" value="1"{if $blckpaid.hover} checked{/if}>
          <span class="blckpaid-switch__ui"></span>
          <span>{$blckpaid_labels.hover_on}</span>
        </label>
      </div>
    </section>

    <section class="blckpaid-block">
      <div class="blckpaid-block__top">
        <div>
          <h2>{$blckpaid_labels.sticky}</h2>
          <p>{$blckpaid_labels.sticky_help}</p>
        </div>
        <label class="blckpaid-switch">
          <input type="checkbox" name="BLCKPAID_STICKY" value="1"{if $blckpaid.sticky} checked{/if}>
          <span class="blckpaid-switch__ui"></span>
          <span>{$blckpaid_labels.sticky_on}</span>
        </label>
      </div>
    </section>

    <section class="blckpaid-block">
      <div class="blckpaid-block__top">
        <div>
          <h2>{$blckpaid_labels.checkout}</h2>
          <p>{$blckpaid_labels.checkout_help}</p>
        </div>
        <label class="blckpaid-switch">
          <input type="checkbox" name="BLCKPAID_OPC" value="1"{if $blckpaid.opc} checked{/if}>
          <span class="blckpaid-switch__ui"></span>
          <span>{$blckpaid_labels.checkout_on}</span>
        </label>
      </div>
    </section>

    <div class="blckpaid-save">
      <p data-blckpaid-saved{if !$blckpaid_saved} hidden{/if}>{$blckpaid_labels.saved}</p>
      <button type="submit" name="submitBlckpaid" value="1">{$blckpaid_labels.save}</button>
    </div>
  </form>
</div>
