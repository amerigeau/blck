(function () {
  var root = document.querySelector(".blckpaid");
  if (!root) return;

  var stage = root.querySelector("[data-blckpaid-stage]");
  var note = root.querySelector("[data-blckpaid-note]");
  var bar = root.querySelector("[data-blckpaid-bar]");
  var link = root.querySelector("[data-blckpaid-bar-link]");
  var warn = root.querySelector("[data-blckpaid-contrast]");
  var toggle = root.querySelector("[data-blckpaid-toggle='bar']");
  var panel = root.querySelector("[data-blckpaid-panel='bar']");
  var text = root.querySelector("[data-blckpaid-text]");
  var url = root.querySelector("[data-blckpaid-url]");
  var bg = root.querySelector("[data-blckpaid-hex='bg']");
  var fg = root.querySelector("[data-blckpaid-hex='fg']");
  var bgPick = root.querySelector("[data-blckpaid-picker='bg']");
  var fgPick = root.querySelector("[data-blckpaid-picker='fg']");

  function hexOk(value) {
    return /^#[0-9A-Fa-f]{6}$/.test(value);
  }

  function channel(hex, index) {
    var n = parseInt(hex.slice(1), 16);
    var c = index === 0 ? (n >> 16) & 255 : index === 1 ? (n >> 8) & 255 : n & 255;
    c /= 255;
    return c <= 0.03928 ? c / 12.92 : Math.pow((c + 0.055) / 1.055, 2.4);
  }

  function contrast(a, b) {
    var la = 0.2126 * channel(a, 0) + 0.7152 * channel(a, 1) + 0.0722 * channel(a, 2);
    var lb = 0.2126 * channel(b, 0) + 0.7152 * channel(b, 1) + 0.0722 * channel(b, 2);
    var hi = Math.max(la, lb);
    var lo = Math.min(la, lb);
    return (hi + 0.05) / (lo + 0.05);
  }

  function paint() {
    var on = toggle.checked;
    var sentence = text.value.trim();
    panel.hidden = !on;
    if (!on) {
      bar.hidden = true;
      warn.hidden = true;
      note.hidden = false;
      note.textContent = stage.getAttribute("data-off");
      return;
    }
    if (!sentence) {
      bar.hidden = true;
      warn.hidden = true;
      note.hidden = false;
      note.textContent = stage.getAttribute("data-empty");
      return;
    }
    note.hidden = true;
    bar.hidden = false;
    link.textContent = sentence;
    link.setAttribute("href", url.value.trim() || "#");
    if (hexOk(bg.value) && hexOk(fg.value)) {
      bar.style.background = bg.value;
      bar.style.color = fg.value;
      var ratio = contrast(bg.value, fg.value);
      warn.hidden = ratio >= 4.5;
      warn.textContent = stage.getAttribute("data-contrast");
    }
  }

  function bindPair(picker, field) {
    picker.addEventListener("input", function () {
      field.value = picker.value.toUpperCase();
      paint();
    });
    field.addEventListener("input", function () {
      if (hexOk(field.value)) picker.value = field.value;
      paint();
    });
  }

  link.addEventListener("click", function (event) {
    event.preventDefault();
  });
  bindPair(bgPick, bg);
  bindPair(fgPick, fg);
  toggle.addEventListener("change", paint);
  text.addEventListener("input", paint);
  url.addEventListener("input", paint);
  paint();
})();
