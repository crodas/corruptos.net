$(function(e) {
    $(delayed || []).each(function(key, value) {
        if (typeof value == "function") {
            value(e);
        }
    });
    window.delayed = [];
    window.delayed.push = $.ready;
});

        var gotoHashTab = function (customHash) {
            var hash = customHash || location.hash;
            var hashPieces = hash.split('?'),
                activeTab = $('[href=' + hashPieces[0] + ']');
            activeTab && activeTab.tab('show');
        }

        // onready go to the tab requested in the page hash
        gotoHashTab();


        // public/js/script.js
        $(document).ready(function() {
            $('.get_title').click(function (e) {
                // prevent the links default action
                // from firing
                e.preventDefault();

                console.log('buttom clicked');


                var linkk = $('input[name=link]').val();

                $.get(BASE+'/ajax/link/title/?link='+linkk, function(data) {

                    console.log('got the data');
                    $('input[name=title]').val(data);
                });

                console.log('finished');
            

            });
        });

        // when the nav item is selected update the page hash
        $('.nav a').on('shown', function (e) {
            window.location.hash = e.target.hash;
        })

        // when a link within a tab is clicked, go to the tab requested
        $('.tab-pane a').click(function (event) {
            if (event.target.hash) {
                gotoHashTab(event.target.hash);
            }
        });

        jQuery('.backtotop').click(function(){ jQuery('html, body').animate({scrollTop:0}, 'slow'); });


        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = 'websterfolksdemo'; // required: replace example with your forum shortname

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function () {
            var s = document.createElement('script'); s.async = true;
            s.type = 'text/javascript';
            s.src = '//' + disqus_shortname + '.disqus.com/count.js';
            (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
        }());


        
  var editor = new wysihtml5.Editor("textarea", {
    toolbar:        "toolbar",
    stylesheets:    "css/stylesheet.css",
    parserRules:    wysihtml5ParserRules
  });
  
  var log = document.getElementById("log");
  
  editor
    .on("load", function() {
      log.innerHTML += "<div>load</div>";
    })
    .on("focus", function() {
      log.innerHTML += "<div>focus</div>";
    })
    .on("blur", function() {
      log.innerHTML += "<div>blur</div>";
    })
    .on("change", function() {
      log.innerHTML += "<div>change</div>";
    })
    .on("paste", function() {
      log.innerHTML += "<div>paste</div>";
    })
    .on("newword:composer", function() {
      log.innerHTML += "<div>newword:composer</div>";
    })
    .on("undo:composer", function() {
      log.innerHTML += "<div>undo:composer</div>";
    })
    .on("redo:composer", function() {
      log.innerHTML += "<div>redo:composer</div>";
    });

