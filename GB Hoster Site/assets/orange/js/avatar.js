jQuery.event.props.push('dataTransfer');

(function() {

  var s;
  var Avatar = {

    settings: {
      bod: $("body"),
      img: $("#edit_avatar"),
      fileInput: $("#edit_avataru")
    },

    init: function() {
      s = Avatar.settings;
      Avatar.bindUIActions();
    },

    bindUIActions: function() {

      var timer;

      s.bod.on("dragover", function(event) {
        clearTimeout(timer);
        if (event.currentTarget == s.bod[0]) {
          Avatar.showDroppableArea();
        }

        return false;
      });

      s.bod.on('dragleave', function(event) {
        if (event.currentTarget == s.bod[0]) {
          timer = setTimeout(function() {
            Avatar.hideDroppableArea();
          }, 200);
        }
      });

      s.bod.on('drop', function(event) {
        event.preventDefault();

        Avatar.handleDrop(event.dataTransfer.files);
      });

      s.fileInput.on('change', function(event) {
        Avatar.handleDrop(event.target.files);
      });
    },

    showDroppableArea: function() {
      s.bod.addClass("droppable");
    },

    hideDroppableArea: function() {
      s.bod.removeClass("droppable");
    },

    handleDrop: function(files) {

      Avatar.hideDroppableArea();

      var file = files[0];

      if (file.type.match('image.*')) {

        Avatar.resizeImage(file, 256, function(data) {
          Avatar.placeImage(data);
        });

      } else {

        //alert("Ovaj fajl nije slika.");
		$.poruka('', 'Ovaj fajl nije u dozvoljenom formatu!');

      }

    },

    resizeImage: function(file, size, callback) {

      var fileTracker = new FileReader;
      fileTracker.onload = function() {
        Resample(
         this.result,
         size,
         size,
         callback
       );
      }
      fileTracker.readAsDataURL(file);

      fileTracker.onabort = function() {
		$.poruka('', 'Upload je prekinut!');
      }
      fileTracker.onerror = function() {
		$.poruka('', 'Nepoznata greska pri upload avatara!');
      }

    },

    placeImage: function(data) {
      s.img.attr("src", data);
    }

  }

  Avatar.init();
})();

var Resample = (function (canvas) {

 function Resample(img, width, height, onresample) {
  var

   load = typeof img == "string",
   i = load || img;

  if (load) {
   i = new Image;
   i.onload = onload;
   i.onerror = onerror;
  }

  i._onresample = onresample;
  i._width = width;
  i._height = height;
  load ? (i.src = img) : onload.call(img);
 }

 function onerror() {
  throw ("not found: " + this.src);
 }

 function onload() {
  var
   img = this,
   width = img._width,
   height = img._height,
   onresample = img._onresample
  ;
  var minValue = Math.min(img.height, img.width);
  width == null && (width = round(img.width * height / img.height));
  height == null && (height = round(img.height * width / img.width));

  delete img._onresample;
  delete img._width;
  delete img._height;

  canvas.width = width;
  canvas.height = height;

  context.drawImage(
  
   img,
   0,
   0,
   minValue,
   minValue,
   0,
   0,
   width,
   height
  );
  onresample(canvas.toDataURL("image/png"));
 }

 var context = canvas.getContext("2d"),
  round = Math.round
 ;

 return Resample;

}(
 this.document.createElement("canvas"))
);  