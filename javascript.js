        //remplazar mas de un caracter dentro de un html
             $(".smfPagination,.pagelinks").html(

                function(i, val) {
                  xd = val.replace(/\[|\]/g, '');
                       console.log(val);
                     return "<span class='smfPagination-label'>" + xd + "</span>"; 
                 }

            ); 
