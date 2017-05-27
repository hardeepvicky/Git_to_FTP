 /* 
 * @author     Hardeep
 */
jQuery.fn.extend({
    sentance: function ()
    {
        return this.each(function ()
        {
            if ($(this).tagName() == "input")
            {
                var v = $(this).val();

                if (v.length > 0)
                {
                    $(this).val(v.charAt(0).toUpperCase() + v.slice(1));
                }
            }
            else
            {
                var v = $(this).html();

                if (v.length > 0)
                {
                    $(this).html(v.charAt(0).toUpperCase() + v.slice(1));
                }
            }
        });
    },
    tagName: function ()
    {
        return this.prop("tagName").toLowerCase();
    },
    chkSelectAll: function ()
    {
        return this.each(function()
        {
            if ($(this).hasClass("chkSelectAll-applied"))
            {
                return true;
            }
            
            $(this).addClass("chkSelectAll-applied");
            
            var _this = $(this);
            var target = $(this).attr("data-href");
            
            $(this).change(function ()
            {
                $(target).prop("checked", this.checked);
            });
            
            $(target).change(function ()
            {
                var checked = $(target).length == $(target + ":checked").length;
                
                _this.prop("checked", checked);
            });
            
            var checked = $(target).length == $(target + ":checked").length;
            _this.prop("checked", checked);
        });
    },
    cssToggler : function()
    {
        return this.each(function()
        {
            if ($(this).hasClass("cssToggler-applied"))
            {
                return true;
            }
            
            $(this).addClass("cssToggler-applied");
            
            $(this).click(function()
            {
                var obj = $($(this).data("toggler-target"));
                var css = $(this).data("toggler-class");
                obj.toggleClass(css);
            });
        });
    },
    ajaxLoader : function()
    {
        return this.each(function()
        {
            if ($(this).hasClass("ajaxLoader-applied"))
            {
                return true;
            }
            
            $(this).addClass("ajaxLoader-applied");
            
            $(this).click(function()
            {
                var obj = $($(this).data("loader-target"));
                
                if ($(obj).hasClass("ajaxLoader-load"))
                {
                    return;
                }
                
                var href = $(this).data("loader-href");
                $(obj).load(href, function()
                {
                    $(obj).addClass("ajaxLoader-load");
                    $(obj).find("script").each(function()
                    {
                        eval($(this).html());
                    });
                });
                
            });
            
            var autoload = $(this).data("loader-autoload");
            
            if (autoload == "1")
            {
                $(this).trigger("click", {trigger : 1});
            }
        });
    },
    moreText : function()
    {
        return this.each(function()
        {
            if ($(this).hasClass("moreText-applied"))
            {
                return true;
            }
            
            $(this).addClass("moreText-applied");
            
            var charlen = $(this).data("more-text-char-len");
            
            var content = $(this).html();
            
            if(content.length > charlen) 
            {
                var c = content.substr(0, charlen);

                var html = '<span class="less-text-block">' + c + '<br/><a class="more-text-opener">...More</a></span>';
                html += "<span class='more-text-block hidden'>" + content + "<br/><a class='less-text-opener'>..Less</a></span>";

                $(this).html(html);
                
                $(this).find(".more-text-opener").click(function()
                {
                    $(this).parent().parent().find(".more-text-block").removeClass("hidden");
                    $(this).parent().addClass("hidden");
                });

                $(this).find(".less-text-opener").click(function()
                {
                    $(this).parent().parent().find(".less-text-block").removeClass("hidden");
                    $(this).parent().addClass("hidden");
                });
            }
        });        
    },    
    tableTemplate : function(opt)
    {
        return this.each(function()
        {
            var _table = $(this);
            
            var minimum_row = _table.data("template-min-row");
            if (jQuery.type(minimum_row) == "undefined")
            {
                minimum_row = 0;
            }
            
            $(this).bind("refresh", function ()
            {
                _table.find("tbody tr .row-deleter").show();
                
                _table.find("tbody tr").not(".template-row").each(function (a, tr)
                {
                    if (a < minimum_row)
                    {
                        $(tr).find(".row-deleter").hide();
                    }
                });
            });
            
            $(this).on("click", ".row-adder", function(e, e_opt)
            {
                var $tr = _table.find("tbody tr:last");
                var last_id = $tr.attr("data-row-id");
                if (typeof last_id == "undefined")
                {
                    last_id = 0;
                }
                else
                {
                    last_id = parseInt(last_id);
                }
                last_id += 1;
                
                var last_i = $tr.attr("data-row-i");
                if (typeof last_i == "undefined")
                {
                    last_i = 0;
                }
                else
                {
                    last_i = parseInt(last_i);
                }
                last_i += 1;
                
                if (typeof opt != "undefined" && typeof opt.beforeRowAdd == "function")
                {
                    var result = opt.beforeRowAdd(last_tr, {
                        event_opt : e_opt,
                        id : last_id,
                        i : last_i
                    });
                    
                    if (result == false)
                    {
                        return result;
                    }
                }
                
                var template_row = _table.find("tr.template-row").html();
                
                var tr_html = String.replaceAll("{{id}}", last_id, template_row);
                tr_html = String.replaceAll("{{i}}", last_i, tr_html);
                
                var html = "<tr data-row-id=" + last_id + " data-row-i=" + last_i + ">";
                html += tr_html;
                html += "</tr>";
                
                _table.append(html);
                
                _table.trigger("refresh");
                
                var last_tr = _table.find("tbody tr:last").not(".template-row");
                
                if (typeof opt != "undefined" && typeof opt.onRowAdd == "function" && last_tr.length > 0)
                {
                    opt.onRowAdd(last_tr, {
                        event_opt : e_opt,
                        id : last_id,
                        i : last_i
                    });
                }
            });
            
            $(this).on("click", ".row-deleter", function(e, e_opt)
            {
                var tr = $(this).parents("tr");

                var last_id = tr.attr("data-row-id");
                if (typeof last_id == "undefined")
                {
                    last_id = 0;
                }
                else
                {
                    last_id = parseInt(last_id);
                }
                last_id += 1;

                var last_i = tr.attr("data-row-i");
                if (typeof last_i == "undefined")
                {
                    last_i = 0;
                }
                else
                {
                    last_i = parseInt(last_i);
                }
                last_i += 1;

                var result = true;
                if (typeof opt != "undefined" && typeof opt.beforeRowDel == "function")
                {
                    result = opt.beforeRowDel(tr, {
                        event_opt : e_opt,
                        id : last_id,
                        i : last_i
                    });
                }

                if (result != false)
                {
                    tr.remove();
                    
                    if (typeof opt != "undefined" && typeof opt.onRowDel == "function")
                    {
                        opt.onRowDel({
                            id : last_id,
                            i : last_i
                        });
                    }
                }
            });
            
            var tr_count = _table.find("tbody tr").not(".template-row").length;
            
            if (tr_count < minimum_row)
            {
                for (var i = tr_count; i < minimum_row; i++)
                {
                    _table.find(".row-adder").trigger("click", {trigger : 1});
                }
            }
            else
            {
                _table.trigger("refresh");
            }
        });
    },
    valueCal : function()
    {
        return this.each(function()
        {
            var _this = $(this);
            var exp = $(this).data("expression");
            
            //console.log(exp);
            if (jQuery.type(exp) == "string")
            {
                var oprands = String.containsBetween(exp, "[", "]");
                
                for(var i in oprands)
                {
                    if ($(oprands[i]).length > 0)
                    {
                        var tag = $(oprands[i]).tagName();
                        var has_cls = $(oprands[i]).hasClass("valueCal-event-applied");
                        if (!has_cls)
                        {
                            $(oprands[i]).addClass("valueCal-event-applied");
                            if (tag == "input")
                            {
                                $(oprands[i]).blur(function ()
                                {
                                    _this.valueCal();
                                });
                            }

                            if (tag == "select")
                            {
                                $(oprands[i]).change(function ()
                                {
                                    _this.valueCal();
                                });
                            }
                        }
                        
                        var v = $(oprands[i]).val();
                        if (!$.isNumeric(v))
                        {
                            v = 0;
                        }
                        
                        var v = parseInt(v);
                        exp = exp.replace("[" + oprands[i] + "]", v);
                    }
                    else
                    {
                        exp = exp.replace("[" + oprands[i] + "]", 0);
                    }
                }
            }
            
            //console.log(exp);
            //console.log(eval(exp));
            
            if ($(this).tagName() == "input")
            {
                $(this).val(eval(exp));
            }
            else
            {
                $(this).html(eval(exp));
            }
        });
    }
});

Object.size = function(obj) 
{
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

String.replaceAll = function (search, replace, str)
{
    return str.replace(new RegExp(search, 'g'), replace);
};

String.contains = function (str, sub)
{
    return str.indexOf(sub) >= 0;
};

String.containsBetween = function (str, start_cap, end_cap)
{
    var arr = [];
    
    while(str.length > 0)
    {
        var s_ind = str.indexOf(start_cap);        
        var e_ind = str.indexOf(end_cap);
        if (s_ind >= 0 && e_ind >= 0)
        {
            var sub = str.substr(s_ind, (e_ind - s_ind + 1));
            arr.push(sub.replace(start_cap, "").replace(end_cap, ""));
            str = str.replace(sub, "");
        }
        else
        {
            return arr;
        }
    }
    
    return arr;
};