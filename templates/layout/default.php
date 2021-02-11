<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */
$country = "PH";
if(isset($_COOKIE['country_code'])) {
  $country = $_COOKIE['country_code'];
}
$cakeDescription = 'CakePHP: the rapid development php framework';

?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta(
        'songalia-icon.png',
        '/img/songalia-icon.png',
        ['type' => 'icon']
    ); ?>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/vue@next"></script>
    <?= $this->Html->css(['normalize.min', 'milligram.min', 'cake','tailwindcss/dist/tailwind']) ?>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.4.0/dist/ionicons/ionicons.esm.js"></script>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    
    <nav class="bg-gray-900 shadow-xl mb-5 flex justify-between" style="padding: 1rem 5vw">
        <div class=" h-full my-auto" id="toogle-side-nav">
        <ion-icon name="grid" title="Toogle Side Navigation"
            class="text-5xl md:text-7xl text-gray-100 hover:text-yellow-200 transtion-colors cursor-pointer"></ion-icon>
        </div>
        <div class="flex justify-center h-full">
            <h1 class="text-gray-300 h-full mr-2 block my-auto">Youtube Lite<br/> 
            <span class="text-yellow-300 tracking-wide">Redisgned</span> </h1>
             <?= $this->Html->image('yt-logo.png',[
                 'class' => 'hidden md:block'
             ]) ?>
        </div>
    </nav>
    
    <div class="lg:grid grid-cols-10 px-5 mt-5">
        <section class="side-nav-container bg-gray-900"
                 style="max-width: 30rem; left: -100%;" id="main-side-nav">
            <div>
                <svg class="country-loader animate-spin h-8 w-8  border-4 border-blue-500 
                 rounded-full relative top-0 ml-auto mr-2 bg-gray-900" viewBox="0 0 24 24"
                 style="border-top-color: #ffffff; margin-bottom: -2rem">
                </svg>
                <div class="flex justify-center p-2">
                    <h1 class="text-gray-200 text-right text-xl">Current List are<br/>
                        <span class="text-yellow-300"> Popular Videos in <span></h1>
                    <select class="text-gray-200  text-xl w-min pl-1 ml-2"
                             id="country-code-selection">
                        
                    </select>
                </div>
                
            </div>
            <div>
                <div id="player"></div>
            </div>
            <div>
                <?= $this->Html->image('dev-image.png',[
                    'style' => 'margin-left: -1rem',
                ]) ?>
                <a href="http://paulsong1.rf.gd/" target="_blank"
                   class="text-gray-300 text-center">
                    <span class="side-nav-link">
                    Give Feedback
                    </span>    
                </a>
            </div>
            
            <div class="flex flex-col justify-center">
                <a href="http://paulsong1.rf.gd/" target="_blank"
                   class="text-gray-300 text-center">
                    <span class="side-nav-link">
                    About Developer
                    </span>    
                </a>
                
            <h6 class="text-gray-200 text-2xl text-center" style="font-weight: 100">
                Powered by <a href="https://developers.google.com/youtube/v3/getting-started" 
                    target="_blank" class="text-yellow-300"> Google's Youtube Data API </a> </h6>
            </div>
        </section>
        
        <main class="main-content cols-span-10 md:col-span-8 z-10">
            <div class="container pb-20">
                <?= $this->Flash->render() ?>
                <section id="content"> 
                    
                </section>
                
            </div>
        </main>
    </div>
    
    <footer>
    </footer>
</body>
</html>
<script>

function hideCountryLoader(){
    $(".country-loader").hide();
}

function setMainContent(){
    $(".country-loader").show();
    var currentLocation = window.location.href;
    $.get(currentLocation, function(html){
        $("#content").html(html);
    });
}

function debouncer( func , timeout ) {
   var timeoutID , timeout = timeout || 200;
   return function () {
      var scope = this , args = arguments;
      clearTimeout( timeoutID );
      timeoutID = setTimeout( function () {
          func.apply( scope , Array.prototype.slice.call( args ) );
      } , timeout );
   }
}

function toggleSideNav(){
   const sideNav = $("#main-side-nav");
   const content = $(".main-content");
   var isScreenLarge = $(window).width()  > 1023;
   $("#toogle-side-nav").click(function(){
       isScreenLarge = $(window).width()  > 1023;
        if(!isScreenLarge){
            if(sideNav.position().left < 0){
                sideNav.attr('style','left:0%;max-width:30rem;transition:450ms ease-out;');
            }else{
                sideNav.attr('style','left:-100%;max-width:30rem;transition:250ms ease-in;');
            }
        }else{
            sideNav.toggle('fast',function(){
                if($(this).is(":visible")){
                    content.removeClass("md:col-span-10");
                }else{
                    content.addClass("md:col-span-10");
                }
            });
        }
   });
   content.click(function(){
        if(sideNav.position().left >= 0 && sideNav.is(":visible")){
            sideNav.attr('style','left:-100%;max-width:30rem;transition:250ms ease-in;');
        }
   });
   
   $(window).resize( debouncer( function ( e ){
       isScreenLarge = $(window).width()  > 1023;
       if( !isScreenLarge && !sideNav.is(":visible") ){
          sideNav.show();
       }
       if(isScreenLarge && content.hasClass('md:col-span-10')){
           content.removeClass('md:col-span-10');
       }
       
   }));
}
 
    function filterCountry(){
        $('#country-code-selection').each(function() {
            
         var option = $(this);
         
         // Save current value of element
         option.data('oldVal', option.val());
         // Look for changes in the value
         option.bind("propertychange change click keyup input paste", function(event){
             console.log(option.val());
            // If value has changed...
            if (option.data('oldVal') != option.val()) {
                // Updated stored value
                option.data('oldVal', option.val());
                document.cookie = "country_code="+option.val()+"; expires=Thu, 18 Dec 2199 12:00:00 UTC; path=/";
                setMainContent();
            }
         });
            
       });
    }

$(document).ready(function() {
    setMainContent();
    toggleSideNav();
    $.get('https://youtube.googleapis.com/youtube/v3/i18nRegions?part=snippet&key=AIzaSyCb8s_D255oFid-LdUzu_NYLVHXsBoSf7o',function(data){
        const selection = $("#country-code-selection");
        var region = data.items;
        console.log(data);
        for(var i = 0; i < region.length; i++){
            var name = region[i].snippet.name;
            var shortenedName =  name.split(" ");
            var option = $("<option class='text-gray-700'></option>");
            option.html(name);
            option.val(region[i].snippet.gl);
            selection.append(option)
        }
        $("#country-code-selection").val("<?= $country ?>");
        filterCountry();
    });
    
    
});
    
</script>