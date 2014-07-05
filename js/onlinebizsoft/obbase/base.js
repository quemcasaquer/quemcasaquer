function initBase(){
    if(_section == 'storeview'){
       $('storeview_extensions').update($('onlinebiz_store').innerHTML)
    }
}
Event.observe(window, 'load', function() {
   initBase();
});
