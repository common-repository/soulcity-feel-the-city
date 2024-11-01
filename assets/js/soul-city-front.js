/**
 * Created by gduvaux on 26/01/2016.
 */

(function($){

    var $soulCityIframe = $('.iframe-soul-city');

    var place_info = {
        place_id: $soulCityIframe.data('place-id'),
        place_about: $soulCityIframe.data('place-name'),
        url_article: location.href,
        page_title: $(document).attr('title'),

        post_title: $soulCityIframe.data('post-title'),
        post_author: $soulCityIframe.data('post-author'),
        post_date: $soulCityIframe.data('post-date'),
        site_name: $soulCityIframe.data('site-name'),
        site_domain: $soulCityIframe.data('site-domain')
    };

    $soulCityIframe.iFrameResize({
        log: false,
        checkOrigin: false,
        heightCalculationMethod: 'documentElementOffset',
        messageCallback         : function(messageData){ // Callback fn when message is received

            if(messageData.message){
            }else {
                document.getElementsByClassName('iframe-soul-city')[0].iFrameResizer.sendMessage(place_info);
            }
        }
    });

})(jQuery);