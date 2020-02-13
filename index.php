
 <!DOCTYPE html>

 <html>
    <head>
        <meta charset="utf-8">
        <!-- Nous chargeons les fichiers CDN de Leaflet. Le CSS AVANT le JS -->
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
        integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
        crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
        integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
        crossorigin=""></script>
        <style type="text/css">
            #map{ /* la carte DOIT avoir une hauteur sinon elle n'apparaît pas */
                height:400px;
            }
        </style>
        <title>Carte</title>
    </head>
    <body>
      


  <div id="infoposition"></div>
  <link rel="stylesheet" href="style.css">
<form method="get" action="">  
<input id="ville" type="text" name="ville"  value="<?php echo $ville=isset($_GET['ide'])? $_GET['ide']: $ville=""; ?>" placeholder="<?php echo $ville; ?>" />
<input id="cp" type="hidden" name="cp" class="cp"  value="<?php echo $ville=isset($_GET['cp'])? $_GET['cp']: $ville=""; ?>" placeholder="<?php echo $ville; ?>" />
<input type="submit" />
<div class="flex">
  <div id="villesDeFrance" class="villes"></div>
</div>

</form>
<div id="map"></div>

<script>

function myFunction(arr, value, villecp, div, linkinfo) {
        console.log(arr)
        
    document.getElementById(div).classList.add('disable')
    
        document.getElementById(div).innerHTML=""
        tableau_ville=[]
        tableau_code=[]
        for (i=0; i<arr.length; i++){
         
          if(arr[i].postal.includes('-')){
            villestirets=arr[i].postal.split("-")
           console.log(villestirets)
           for(i=0; i<villestirets.length; i++){
              tableau_code.push(villestirets[i])
            }
                 
           }
          
        }
           for (i=0; i<arr.length; i++){
          
            tableau_ville.push(arr[i].ville_nom)
            tableau_code.push(arr[i].postal) 
          }
      var autocomplete=document.getElementById(div)
      element=[];   
      lien=[]
      for(i=0; i<tableau_ville.length; i++){
        essai1=document.createElement('a')
        essai1.setAttribute("href", "index.php?ide="+tableau_ville[i]+"&cp="+tableau_code[i])
   
        lien.push(essai1)
      }
      sauts=[]
      for(i=0; i<tableau_ville.length; i++){
        saut=document.createElement('br')
        sauts.push(saut)
      }
                
      for(i=0; i<tableau_ville.length && i<lien.length && i<sauts.length && tableau_code.length; i++){
      lien[i].textContent=tableau_ville[i]
      autocomplete.appendChild(lien[i]).append(' '+tableau_code[i])
      autocomplete.append(sauts[i])
    }

    if(value=="32" || document.getElementById(villecp).value==="" ){
    
        erase_childs(document.getElementById(div))
       
    }
  
    if(document.getElementById(villecp).value==="" ){
      document.getElementById(div).classList.add('disable2')
    }else{
      document.getElementById(div).classList.remove('disable2')
    }


    if(mot!=""){
        mot=document.getElementById(villecp).value.substring(0, mot.length); 
    }   
    console.log(document.getElementById(div))        
    }   
    
    function erase_childs(node){
	    if(node.childNodes){
        
		  var childs=node.childNodes;
		  for(var i=0;i<childs.length;i++){
			node.removeChild(childs[i]);
      console.log('ok')
		  }
	  }
    
}

var input=document.getElementById('ville');

mot=""

input.addEventListener('keyup', function(e){

   if(e.keyCode!=8){
      var evenement=e.keyCode
     
     if(mot+=new String(String.fromCharCode(e.keyCode))){
        console.log(mot)
        villeFrance=document.getElementById("ville").value
        mot=villeFrance.substring(0, villeFrance.length)
        var xhttp = new XMLHttpRequest();
        xhttp.open("GET", "bdd.php?id="+mot, true);
        xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var myArr = JSON.parse(this.responseText);

          myFunction(myArr, evenement, "ville", "villesDeFrance")  
          }
      }
    xhttp.send();
           
    }
    } else if(e.keyCode==8) {
 
      var evenement=e.keyCode
    villeFrance=document.getElementById("ville").value
    
    mot=villeFrance.substring(0, villeFrance.length)
     
    console.log(villeFrance);
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "bdd.php?id="+villeFrance, true);
    xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        var myArr = JSON.parse(this.responseText);
        myFunction(myArr, evenement, "ville", "villesDeFrance") 
	}

      }
    xhttp.send(); 
 }
})  
    

<?php 
$ville=isset($_GET['ide'])? $_GET['ide']: $ville="";

if($ville!=""){?>
  window.onload = function(){
       

    villeFrance=document.getElementById("cp").value

        ville=villeFrance
        console.log(mot)
        var xhttp = new XMLHttpRequest();
        xhttp.open("GET", "bdd.php?carte="+ville, true);
        xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var myArr = JSON.parse(this.responseText);
            console.log(myArr)
            
            for(i=0; i<myArr.length; i++){
          
                latitude=myArr[i].latitude
                
                longitude=myArr[i].longitude
            }
              console.log(longitude)
              console.log(latitude)    
          
              initMap(latitude, longitude)
               
            
        }}
    xhttp.send();

            }; 
            function initMap() {
    var lat = latitude;
var lon = longitude;
         

var macarte = null;
// Fonction d'initialisation de la carte

          // Créer l'objet "macarte" et l'insèrer dans l'élément HTML qui a l'ID "map"
          macarte = L.map('map').setView([lat, lon], 11);
          
          // Leaflet ne récupère pas les cartes (tiles) sur un serveur par défaut. Nous devons lui préciser où nous souhaitons les récupérer. Ici, openstreetmap.fr
          L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
              // Il est toujours bien de laisser le lien vers la source des données
              attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
              minZoom: 1,
              maxZoom: 20
          }).addTo(macarte);
          var marker = L.marker([lat, lon]).addTo(macarte);
      }
    <?php } ?>
      </script>
    </body>
</html>



