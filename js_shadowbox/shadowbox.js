/*
This script adds a canvas shadow around .shadowbox elements after page is loaded and redraws shadows after screen is resized.
Implemented using canvas instead of CSS shadows as it allows to use any shape of shadow
*/
function shadowbox() {
  redrawShadowBoxes(40, 0, 10);
}

document.addEventListener('DOMContentLoaded', function() {
  shadowbox();
});

jQuery(document).ready(function($) {
  $(window).bind('resizeEnd', function() {
    shadowbox();
  });
});

function redrawShadowBoxes(padding, reduce, shadowsize) {
  jQuery('canvas[id="ShadowBox"]').remove();
  const shadowboxes = document.querySelectorAll('.shadowbox');
  [].forEach.call(shadowboxes, function(shadowbox) {
    shadowRect(shadowbox, 1, 'white', padding, reduce, shadowsize);
  });
}

function shadowRect(shadowbox, repeats, color, padding, reduce, shadow) {
  const rect = shadowbox.getBoundingClientRect();
  const shadowsize = shadow;
  const toprighty = -20;
  const bottomrightx = 10;
  const bottomlefty = -20;
  const canvas = document.createElement('canvas');
  canvas.id = 'ShadowBox';
  canvas.width = rect.width + padding * 2 + shadowsize * 2;
  canvas.height = rect.height + padding * 2 + shadowsize * 2;
  canvas.style.zIndex = 8;
  canvas.style.position = 'absolute';
  canvas.style.top = '-' + (padding / 2 - shadowsize) + 'px';
  canvas.style.left = -(shadowsize + padding / 2) + 'px';
  canvas.style.border = '0px solid';
  shadowbox.appendChild(canvas);
  const ctx = canvas.getContext('2d');
  const shadowcolor = 'rgba(0,0,0,0.07)';
  ctx.strokeStyle = shadowcolor;
  ctx.shadowColor = shadowcolor;
  ctx.shadowBlur = 20;
  ctx.fillStyle = color;
  ctx.beginPath();
  ctx.moveTo(padding + 0, padding - reduce);
  ctx.lineTo(
    rect.width + padding / 2 - shadowsize - reduce,
    +padding + toprighty + shadowsize - reduce
  );
  ctx.lineTo(
    rect.width + padding / 2 - shadowsize + bottomrightx - reduce,
    rect.height - padding / 2 - reduce
  );
  ctx.lineTo(
    padding - shadowsize - bottomlefty - padding / 2 - reduce,
    rect.height - padding / 2 - reduce
  );
  ctx.closePath();

  for (let i = 0; i < repeats; i++) {
    ctx.shadowBlur += 6;
    ctx.fill();
  }
  ctx.shadowColor = 'rgba(0,0,0,0)';
  ctx.fill();
}
