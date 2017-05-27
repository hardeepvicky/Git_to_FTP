/* 
 * Techformation Lib loader
 * 
 * @author  : Sunny
 * @created : 10-11-2016
 */

jQuery.fn.extend(
{
    /**
     * hide loader
     */
    stopLoader : function()
    {
        return this.each(function()
        {
            var me = $(this);
            $(this).find(".tf-loader-container:first").fadeOut("slow", function ()
            {
                me.removeClass("tf-loader-wrapper");
                me.removeClass("tf-pre-loader-wrapper");
            });
        });
    },
    /**
     * loader for particular div
     */
    beginLoader : function()
    {
        return this.each(function()
        {
            var loader_container = $(this).find(".tf-loader-container:first");
            
            //check loader div exist or not
            if (loader_container.length === 0)
            {
                console.error("missing div with loader-container class");
                return;
            }
            
            //check class exist
            if (!$(this).hasClass("tf-loader-wrapper"))
            {
                //adding class
                $(this).addClass("tf-loader-wrapper");
            }

            var attr = "";

            var radius = parseInt(loader_container.data("loader-radius"));

            console.log(radius);
            
            //data given in loader-container
            if (radius > 0)
            {
                var style = [];
                
                var border = radius;
                
                style.push("border-top-width:" + border + "px;");
                style.push("border-bottom-width:" + border + "px;");
                style.push("border-left-width:" + border + "px;");
                style.push("border-right-width:" + border + "px;");

                var size = radius * radius;

                style.push("margin-left:-" + (size / 2) + "px;");
                style.push("margin-top:-" + (size / 2) + "px;");

                style.push("height:" + size + "px;");
                style.push("width:" + size + "px;");

                attr = 'style="' + style.join(" ") + '"';
            }

            //adding main loader
            loader_container.html('<div ' + attr + ' class="tf-loader-block"></div>').fadeIn("fast");
        });
    },    
    /**
     * attach loader and hide loader on window load
     */
    preLoader : function()
    {
        return this.each(function()
        {
            var current_obj = $(this);
            
            $(this).beginLoader();
            
            $(window).load(function()
            {
                current_obj.stopLoader();
            });
        });
    }
});