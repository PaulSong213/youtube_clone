<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$country = "PH";
if(isset($_COOKIE['country_code'])) {
  $country = $_COOKIE['country_code'];
}

?>

<div class="" id="thumbnail-discover">
      
    <div :class="thumbnailContainerState" id="thumbnail-list" >
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-1 lg:gap-10">
        <thumbnail-card
          v-for="thumbnail in thumbnails"   
          :key="thumbnail.id"
          :vid-title="thumbnail.snippet.title"
          :channel="thumbnail.snippet.channelTitle"
          :upload-at="thumbnail.snippet.publishedAt"
          :thumbnail-img="thumbnail.snippet.thumbnails.medium.url"
          :channel-id="thumbnail.snippet.channelId"
          :logo-channel="channels[thumbnail.snippet.channelId].default.url"
          :duration="thumbnail.contentDetails.duration"
          :view-count="thumbnail.statistics.viewCount"
          :vid-id="thumbnail.id"
          :next-page-token="nextPageToken"
        ></thumbnail-card>
    </div>
    </div>    
    <div id="thumbnail-loader" :class="loaderContainerState">
        
        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-1 lg:gap-10">
            <vid-loader
            v-for="vidLoader in vidLoaders"    
            :loader-view-state="vidLoader.viewState"    
            ></vid-loader>
        </div>
    </div>
</div>

<script>
    function play(id){
       var html  = '';     
       html += '<iframe class="w-full h-full rounded-lg" src="http://www.youtube.com/embed/'+id+'?autoplay=1&mute=1" frameborder="0" allowfullscreen ><div class="close-video ml-auto bg-black cursor-pointer rounded-bottom-full w-min p-2 hover:shadow-lg">Close Player</div></iframe>';
       return html;
    };
    
    var thumbnailInstance = null;
    var isAvailableForNewVid = true;
    
    function removeZeroOnTime(timeString){
        if(timeString === "00:"){
            return "";
        }
        return timeString;
    }
    
    //https://developers.google.com/youtube/v3/guides/implementation/pagination
    function listPopularVideos(pageToken = "",firstload = true, selectedCountryCode = "<?=$country?>"){
        var youtubeVidPath = "https://youtube.googleapis.com/youtube/v3/videos?part=snippet&part=contentDetails&part=statistics&chart=mostPopular&";
        var region = "&regionCode="+selectedCountryCode+"&";
        var apiKey = "key=AIzaSyCb8s_D255oFid-LdUzu_NYLVHXsBoSf7o&";
        var maxResult = "maxResults=16&";
        var vidHttpUrl = youtubeVidPath + region + apiKey + pageToken + maxResult;
        var youtubeChannelPath = "https://youtube.googleapis.com/youtube/v3/channels?part=snippet&";
        
        $.get(vidHttpUrl,function(vidResponse){
            
            var isFirstload = firstload;
            var channelIds = "";
            for(var i = 0; i < vidResponse.items.length; i++){
                channelIds = channelIds + vidResponse.items[i].snippet.channelId + "," ;
            }
            var chanelHttpUrl = youtubeChannelPath + "&id=" + channelIds + "&" + apiKey;
            //loadAnotherVideosIfAlmostBottom(vidResponse.nextPageToken);
            $.get(chanelHttpUrl,function(channelResponse){
               var channelLogos = []; 
               for(var i = 0; i < channelResponse.items.length; i++){
                   channelLogos[channelResponse.items[i].id] = channelResponse.items[i].snippet.thumbnails;
               }
                if(isFirstload){
                    return showThumbnails(vidResponse,channelLogos);
                }else{
                    return addNewLoadedVideos(vidResponse,channelLogos);
                }
            });
        });    
    }
    

    
    
    function showThumbnails(data,channelLogos){
        //console.log(channelLogos);
        console.log(data);
        const app = Vue.createApp({
            data() {
                return {
                  thumbnails: data.items,
                  channels: channelLogos,
                  thumbnailContainerState: "hidden",
                  loaderContainerState: "block",
                  nextPageToken: data.nextPageToken
                }
            },
            mounted(){
                this.thumbnailContainerState = "block";
                this.loaderContainerState = "hidden";
                hideCountryLoader();
            },
            created () {
              window.addEventListener('scroll', this.handleScroll);
            },
            unmounted() {
              window.removeEventListener('scroll', this.handleScroll);
            },
            methods: {
                handleScroll (event) {
                    let almostBottomOfWindow = document.documentElement.scrollTop + window.innerHeight > document.documentElement.offsetHeight - 200;
                    if (almostBottomOfWindow && isAvailableForNewVid && this.nextPageToken) {
                        var nextPageToken = "pageToken="  + this.nextPageToken + "&";
                        listPopularVideos(nextPageToken, false);
                        isAvailableForNewVid = false;
                        thumbnailInstance.loaderContainerState = "block";
                    }
                }
            }
        })
        app.component('thumbnail-card', {
          props: ['vidTitle','channel','uploadAt','thumbnailImg','channelId','logoChannel',
                'duration','viewCount','vidId','nextPageToken'],
          template:
            `<div class="grid grid-cols-10 gap-5 cursor-pointer p-2 hover:shadow-md transition-all"
                @click="setYoutubeVideo(vidId)">
             <div class="col-span-full mx-auto rounded-t-lg overflow-hidden">   
             <img :src="thumbnailImg" alt="preview" class="rounded-t-lg" />
             <h3 class="vid-duration bg-blue-500 text-white  opacity-80 rounded-md ml-auto px-1" 
                 style="width:min-content;margin-top: -22px;margin-right:2px;">{{ formatDuration(duration) }} </h3>
             </div> 
             <div class="col-span-2">
             <div class=" rounded-full bg-gray-300">            
             <img :src="logoChannel" :alt="logoChannel" class="rounded-full" />
             </div>    
             </div>
             <div class="col-span-8">            
             <h1  :title="vidTitle" class="limited-text">{{ vidTitle }}</h1>
             <h2 class="text-gray-600 hover:text-gray-800 mt-2" style="width:fit-content"> {{channel}}</h2>
             <div  style="margin-top:-5px">            
             <h2 class="text-gray-600 inline-block pr-2"> {{ formatViewCount(viewCount) }}</h2>
             <h2 class="text-gray-600 inline-block">{{ getUploadDuration(uploadAt) }}</h2>
             </div>            
             </div>            
            </div>`,
            methods:{
                getUploadDuration(uploadDate){
                    const _MS_PER_DAY = 1000 * 60 * 60 * 24;
                    const _MS_PER_HOUR = 1000 * 60 * 60;
                    const upload = new Date(uploadDate);
                    const today = new Date();
                    const utcToday = Date.UTC(today.getFullYear(), today.getMonth(), today.getDate());
                    const utcUpload = Date.UTC(upload.getFullYear(), upload.getMonth(), upload.getDate());
                    const daysUpload =  Math.floor((utcToday - utcUpload) / _MS_PER_DAY);
                    const hoursUpload = Math.floor((utcToday - utcUpload) / _MS_PER_HOUR);
                    if(daysUpload > 0){
                        return  daysUpload + " days ago";
                    }else{
                       return  "1 day ago";
                    }   
                },
                formatDuration(duration) {
                    let totalSeconds = eval(duration.replace('PT','').replace('H','*3600+').replace('M','*60+').replace('S', '+').slice(0, -1));
                    let hours = Math.floor(totalSeconds / 3600);
                    totalSeconds %= 3600;
                    let minutes = Math.floor(totalSeconds / 60);
                    let seconds = totalSeconds % 60;
                    minutes = String(minutes).padStart(2, "0") + ":";
                    hours = String(hours).padStart(2, "0") + ":";
                    seconds = String(seconds).padStart(2, "0");
                    var formatedDuration = removeZeroOnTime(hours) + removeZeroOnTime(minutes) 
                            + removeZeroOnTime(seconds);
                    return formatedDuration;
                },
                formatViewCount(viewCount){
                    let viewLength = viewCount.length;
                    let thousandRemove = viewLength - 6;
                    let hundredRemove = viewLength - 3;
                    let viewCountRepresentative = "";
                    if(viewLength >= 7 && viewLength <= 9 ){
                        viewCountRepresentative = String(viewCount).substring(thousandRemove,-viewLength) + "M views • ";
                    }else if(viewLength >= 4 && viewLength <= 6 ){
                        viewCountRepresentative = String(viewCount).substring(hundredRemove,-viewLength) + "K views • ";
                    }
                    return viewCountRepresentative;
                },
                setYoutubeVideo(vidID){
                   const player = $("#player");
                   
                   player.parent().parent().show("fast",function(){
                       $(".main-content").removeClass("md:col-span-10");
                   });
                   player.parent().parent().attr('style','left:0%;max-width:30rem;transition:450ms ease-out;');
                   player.attr('style','height:40vh');
                   player.html(play(vidID));
                   
                }
            },
                  
        })
        thumbnailInstance = app.mount('#thumbnail-discover');
    }
    
    function loadVideoLoader(){
        const loaderModel = Vue.createApp({
            data(){
                return{
                    vidLoaders: [
                    {viewState: 'block'},{loader: 'block'},{viewState: 'block'},{viewState: 'block'},
                    {viewState: 'hidden sm:block'},{viewState: 'hidden sm:block'},{viewState: 'hidden sm:block'},{viewState: 'hidden sm:block'},
                    {viewState: 'hidden md:block'},{viewState: 'hidden md:block'},{viewState: 'hidden md:block'},{viewState: 'hidden md:block'},
                    {viewState: 'hidden lg:block'},{viewState: 'hidden lg:block'},{viewState: 'hidden lg:block'},{viewState: 'hidden lg:block'},
                    ]
                }
            }
        })
        loaderModel.component('vid-loader',{
            props: ['loaderViewState'],
            template:`
                <div :class="loaderViewState">
                <div class=" w-full h-80 animate-pulse" >
                    <div class="w-full bg-gray-300 h-4/6 rounded-lg">
                        <!-- image loader-->
                    </div>
                    <div class=" w-full h-2/6 flex mt-2 justify-auto">
                        <div class="bg-gray-300 rounded-full w-2/6 h-full">
                        <!--channel logo loader-->
                        </div>
            
                        <div class="w-full h-2/6 bg-gray-300 rounded-full my-auto mx-2">
                        </div>
                    </div>
                </div>
                </div>
            `,
        })
        loaderModel.mount('#thumbnail-loader');
    }
    
    loadVideoLoader();
    
    function addNewLoadedVideos(data,channelLogos){
        for (var i = 0; i < data.items.length; i++) {
            var channelId = data.items[i].snippet.channelId;
            thumbnailInstance.channels[channelId] = channelLogos[channelId];
            thumbnailInstance.thumbnails.push(data.items[i]);
        }
        thumbnailInstance.nextPageToken = data.nextPageToken;
        thumbnailInstance.loaderContainerState = "hidden";
        isAvailableForNewVid = true;  
    }
    
    $(document).ready(function() {
        listPopularVideos();

    });
    
</script>
