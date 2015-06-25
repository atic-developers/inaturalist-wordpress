// $( document ).ready() block.
 jQuery( document ).ready(function() {
     console.log( "ready!" );
       Finestra = window.open('http://www.inaturalist.org/logout','finestra');
       alert('Tu sessión en Inaturalits.org ha sido cerrada');
       Finestra.close();
     });
