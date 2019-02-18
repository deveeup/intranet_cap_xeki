/**!
 * xeki - A easy to use message plugin for jQuery
 * @version 1.6.3
 * @license MIT
 * @copyright Copyright 2011-2018 Luis Eduardo Patt
 */
(function () {
    'use strict';
    function xeki_admin(data, options) {
        var _this = this.initContent();
    }


    xeki_admin.prototype = {

        options: {},
        template: '<div class="admin_float_box"><div class="close"><i class="fa fa-times-circle" aria-hidden="true"></i></div> <div class="loading"><i class="fa fa-cog fa-spin" aria-hidden="true"></i> Loading ...</div><div class="box_content"></div></div> </div>',
        content: '<div></div>',
        loading_visible: false,

        visible: false,
        //
        initContent: function () {
            $(".admin_float_box").remove();
            $("body").append(this.template);
            var adjust_height_float_box = function(){
                $(".admin_float_box").addClass("active");
                var height = $(window).height() - 58;
                $(".admin_float_box").height(height);
            };
            $(".admin_float_box .close").click(function(){
                $(".admin_float_box").removeClass("active");
            });
            setTimeout(function () {
                adjust_height_float_box();
                $( window ).resize(function() {
                    adjust_height_float_box();
                });

            }, 0);
            this.visible = true;
        },
        close:function(){
            setTimeout(function () {
                $(".admin_float_box").removeClass("active");
                window.table.ajax.reload();
            }, 0);
        },
        toggleOpenBox: function () {

        },
        toggleLoading: function () {
            var main = this;
            if (main.loading_visible) {
                main.loading_visible = false;
                $(".admin_float_box .loading").fadeOut();
            }
            else {
                main.loading_visible = true;
                $(".admin_float_box .loading").fadeIn();

            }
        },
        handleForm: function (html) {
            var main = this;
            $(".admin_float_box .box_content").empty();
            $(".admin_float_box .box_content").append(html);

            setTimeout(function () {
                main.run_third_scripts();
            }, 10);
        },
        // this is for get queries
        send_query: function (url,data, callback) {
            url:url==null?"":url;
            data:data==null?[]:data;
            callback:callback==null?"function(){}":callback;
            var main = this;
            main.toggleLoading();
            $.ajax({
                url: url,
                data: data,
                cache: false,
                type: "GET",
                success: function (response) {
                    try {
                        response = JSON.parse(response);
                        callback(response);
                    } catch(e) {
                        alert("An error has occurred, try again");
                        // console.log("error");
                        // console.log(e);
                        // console.log(response);
                    }
                    window.table.ajax.reload();
                    main.toggleLoading();
                },
                error: function (request, status, error) {
                    console.error(request);
                    console.error(status);
                    console.error(xhr);
                },
            });
        },
        // this is for post with callback
        send_request: function (url,data) {
            url:url==null?"":url;
            data:data==null?[]:data;
            var main = this;
            main.toggleLoading();
            // console.log(data);
            // console.log(url);
            $.ajax({
                url : "",
                type: "POST",
                data : data,
                cache: false,
                mimeType: "multipart/form-data",
                processData: false,
                contentType: false,
                async: false,
                success:function(response, textStatus, jqXHR)
                {
                    //data: return data from server

                    try {
                        response = JSON.parse(response);
                        // console.log(response);

                    } catch(e) {
                        // console.log("error");
                        alert("An error has occurred, try again");
                        // console.log(response);
                    }

                    // check if have callback
                    if (typeof response.callback !== 'undefined') {

                        var str_callback="js_admin = new xeki_admin();"+response.callback;
                        eval(str_callback);
                    }

                    // we hope call back of something
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    console.error(jqXHR);
                    console.error(textStatus);
                    console.error(errorThrown);
                    alert("Error detected, the page will be reloaded");
                    //if fails
                }
            });
            // $.ajax({
            //     url: url,
            //     data: data,
            //     cache: false,
            //     type: "GET",
            //     success: function (response) {
            // //         console.log(response);
            //         try {
            //             response = JSON.parse(response);
            //         } catch(e) {
            //             alert("An error has occurred, try again");
            //         }
            //
            //         callback(response);
            //         window.table.ajax.reload();
            //         main.toggleLoading();
            //     },
            //     error: function (request, status, error) {
            //         console.error(request);
            //         console.error(status);
            //         console.error(xhr);
            //     },
            // });
        },


        // slug parse

        // end slug parse
    };


    //----------------------------
    // FORMS HANDLINGS

    // for new item, just sent id_table
    xeki_admin.prototype.add_new= function (id_form) {
        var main = this;
        // get form of new
        // set query
        var url = "form_new_" + id_form;
        // send query
        main.send_query(url,{}, function (data) {
            main.handleForm(data['html']);
        });
    },

    xeki_admin.prototype.launch_control= function (id_form,id_item) {
        var main = this;
        // get form of new
        // set query

        var data = {
            'id':id_item,
            'id_form':id_form
        };
        main.send_query(id_form,data, function (data) {
            main.handleForm(data['html']);
        });
    },

        //
    xeki_admin.prototype.edit_item = function (id_form,id_item){
        var main = this;
        // get form of new
        // set query
        var url = "form_edit_" + id_form;
        var data = {
            'id':id_item
        };
        var params = getAllUrlParams();
        if(typeof params.form !== 'undefined' ){

        }
        if(typeof params.action !== 'undefined' ){

        }
        if(typeof  params.id !== 'undefined'){

        }
        if(typeof  params.lang !== 'undefined'){
            data.lang=params.lang;
        }

        //
        // send query
        main.send_query(url,data, function (data) {
            main.handleForm(data['html']);
        });
    };

    // -----------------------------------------------------------------------------
    //
    // Run third libs scripts for forms, we run all third scripts also not apply
    // -----------------------------------------------------------------------------
    xeki_admin.prototype.run_third_scripts = function () {
        var main = this;

        $(".open_sub_items").click(function(){
            var items = $(this).find('.sub_items');
            if(items.css('display')=="none"){
                items.fadeIn();
            }
            else{
                items.fadeOut();
            }

        });

        var count_local_inners=0;
        $(".admin_float_box .new-box").unbind( "click" );
        $('.admin_float_box .new-box').click(function () {
            var id = this.id;
            tinymce.execCommand('mceRemoveControl', true, id);

            $(this).children('.new-item-box').each(function(){

                }
            );
            // copy html
            var html = $(this).children('.new-item-box').html();

            html = html.replace(new RegExp(id, 'g'),Date.now()+"_"+id );
            $( this ).after( html );
            setTimeout(function(){ main.run_third_scripts(); }, 2000);

            // add before
        });


        // $(".inner_item_box_controlors .remove-item-box").unbind( "click" );
        // $('.inner_item_box_controlors .remove-item-box').click(function() {
        //     $(this).parents().eq(1).remove();
        // });
        //
        $(".inner_item_box_controlors .items_array_delete").unbind( "click" );
        $('.inner_item_box_controlors .items_array_delete').click(function() {
            // console.log($(this).parents());
            var item_to_delete=$(this).attr("id_to_delete");
            $("#"+item_to_delete).remove();
            $(this).parents().eq(2).remove();

            $(this).parents().each(function() {
                // console.log($(this));
                if($(this).is("form")){
                    $(this).submit();
                }
            });
            $('.modal-backdrop').remove();


        });

        $(".box_content .submit_top").unbind( "click" );
        $('.box_content .submit_top').click(function() {
            // console.log($(this).parents());
            var items = $(this).parents();
            items.each(function() {
                // console.log($(this));
                if($(this).is("form")){
                    $(this).submit();
                }
            });
            $('.modal-backdrop').remove();

            //
        });



        $( ".admin_float_box .image-input-preview").unbind( "change" );
        $('.admin_float_box .image-input-preview').change(function () {
            // console.log("CHANGE");

            var input = $(this)['0'];
            var preview = "_preview_"+$(this)['0'].id;
            // console.log(input.files);
            // console.log(input.files[0]);
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    // console.log(e);
                    // console.log('#'+preview);
                    $('#'+preview).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }

        });
        // ------------------
        // links form handling
        // ------------------
        $('.admin_float_box .admin-btn').each(function () {
            $(this).validate();

            $(this).click(function(){
                var id_form = $( this ).attr( "id_form" );
                var id_item = $( this ).attr( "id_item" );
                // console.log(id_form);
                var data = {
                    'id':id_item,
                    'id_form':id_form
                };
                main.send_query(id_form,data, function (data) {
                    main.handleForm(data['html']);
                });

            });
        });

        // ----------------
        // form handling
        // ----------------
        $('.admin_float_box form').each(function () {
            $('.modal-backdrop').remove();
            // console.log($(this));
            var temp_form =$(this);
            temp_form.submit(function (event) {
                tinyMCE.triggerSave();
                event.preventDefault();
                temp_form.validate();
                // console.log(temp_form.valid());
                if(temp_form.valid()){
                    var postData = new FormData(temp_form[0]);
                    // console.log(postData);
                    main.send_request("",postData);
                }

                // $(this).unbind(); //unbind. to stop multiple form submit.
            });



        });
        // dropimage






        // handling form
        $('.admin_slug').each(function () {
            // for keyup
            $(this).keyup(function () {
                var slug = $(this).val();
                slug = main.parse_to_slug(slug);
                $(this).val(slug);
            });
            // for unfocus
            $(this).blur(function(){
                var slug = $(this).val();
                slug = main.parse_to_slug(slug);
                $(this).val(slug);
            });


        });
        $.datepicker.initialized = false;
        $('.datepicker').each(function () {
            try{
                $( this ).removeClass('hasDatepicker');
                $( this ).attr('id','');
                $( this ).unbind();
                $( this ).datepicker('destroy');
            }
            catch (err){console.log(err);}



            $( this ).datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'yy-mm-dd',
                afterShow: function(input, inst) {
                    $('.ui-datepicker-div').css('zIndex', 999999);
                    inst.dpDiv.css({"z-index":99999});
                }
            });
            $( this ).datepicker("refresh");
        });

        $('.timepicker').each(function () {
            try{
                $( this ).removeClass('hasDatepicker');
                $( this ).attr('id','');
                $( this ).unbind();
                $( this ).datepicker('destroy');
            }
            catch (err){console.log(err);}

            $( this ).timepicker({
                afterShow: function(input, inst) {
                    $('.ui-datepicker-div').css('zIndex', 999999);
                    inst.dpDiv.css({"z-index":99999});
                }
            });

        });
        // $( this ).datepicker("refresh");

        $('.change_get_value_form').each(function(){
            $(this).on('change', function() {
                // load value
                var params = {};
                var param = $(this).attr("change-value");
                var value = $(this).val();
                params[param]=value;

                var param = "action";
                var value = $(this).attr("action");
                params[param]=value;

                var param = "form";
                var value = $(this).attr("form");
                params[param]=value;

                var param = "id";
                var value = $(this).attr("id");
                params[param]=value;
                // console.log(params);
                // load get value to change url
                // change url
                // var ret = [];
                // for (var d in params)
                //     ret.push(d + '=' + params[d]);
                // var querystring = ret.join('&');
                // // console.log(querystring);
                // // push(querystring);
                location.search = $.param(params);
            });
        });
        $('.change_get_value').each(function(){
            $(this).on('change', function() {
                // load value
                var param = $(this).attr("change-value");
                var value = $(this).val();
                // load get value to change url 
                // change url
                var params = addParam(param,value);
                push(params);
            });
        });

        // array of get params 
        function getParams(){
            var p = window.location.search.substring(1);
            var result = {};
            var cases = p.split('&');
            for (var i=0; i<cases.length; i++) {
                var pair = cases[i].split('=');
                var inners = [];
                var name = decodeURIComponent(pair[0]), value = decodeURIComponent(pair[1]);
                var name = name.replace(/\[([^\]]*)\]/g, function(k, inc) { inners.push(inc); return ""; });
                inners.unshift(name);
                var o = result;
                for (var j=0; j<inners.length-1; j++) {
                    var inc = inners[j];
                    var nextinc = inners[j+1];
                    if (!o[inc]) {
                        if ((nextinc == "") || (/^[0-9]+$/.test(nextinc)))
                            o[inc] = [];
                        else
                            o[inc] = {};
                    }
                    o = o[inc];
                }
                inc = inners[inners.length-1];
                if (inc == "")
                    o.push(value);
                else
                    o[inc] = value;
            }
            return result;
        }
        // add some param get TODO create new for inners arrays
        function addParam(param,value,attr){
            var params = getParams();
            if(!attr){                                    
                params[param] = value;    
                
            }
            else{
                try {
                    if(params['attr'].length==0){
                    params['attr']=[];
                    }
                }
                catch(err) {
                    params['attr']=[];
                }
                var info =param+":"+value;
                var boolAdd=true;                                    
                for(var i = params['attr'].length - 1; i >= 0; i--) {
                    if(params['attr'][i] === info) {
                    // console.log(params['attr'][i]);
                    params['attr'].splice(i, 1);
                    boolAdd = false;
                    }
                }

                if(boolAdd){                                        
                    params['attr'].push(info);
                }
            }
            // push(params);
            return params;
        }

        // Add new parameters or update existing ones                            
        function push(params){
            location.search = $.param(params); 
        }



        try {
            tinymce.remove();

        }
        catch (err) {
            // console.log('err')
        }
        // Blog picker
        tinymce.init({
            selector: '.admin_blog',
            height: 350,
            theme: "modern",
            skin: 'light',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor  colorpicker textpattern imagetools codesample toc code responsivefilemanager'
            ],
            toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent |',
            toolbar2: 'responsivefilemanager link image | code print preview media | forecolor backcolor emoticons',
            // toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
            // toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
            image_advtab: true,
            templates: [
                {title: 'Test template 1', content: 'Test 1'},
                {title: 'Test template 2', content: 'Test 2'}
            ],

            external_filemanager_path : window.file_manager_path,
            filemanager_title : "Filemanager" ,
            external_plugins: { "filemanager" : "plugins/responsivefilemanager/plugin.min.js"}
            // content_css: [
            //     '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            //     '//www.tinymce.com/css/codepen.min.css'
            // ]
        });
    };
    xeki_admin.prototype.parse_to_slug = function (slug) {
        slug = slug.toLowerCase();
        slug = slug.replace(/ /g, "-");
        var defaultDiacriticsRemovalMap = [
            {
                'base': 'A',
                'letters': '\u0041\u24B6\uFF21\u00C0\u00C1\u00C2\u1EA6\u1EA4\u1EAA\u1EA8\u00C3\u0100\u0102\u1EB0\u1EAE\u1EB4\u1EB2\u0226\u01E0\u00C4\u01DE\u1EA2\u00C5\u01FA\u01CD\u0200\u0202\u1EA0\u1EAC\u1EB6\u1E00\u0104\u023A\u2C6F'
            },
            {'base': 'AA', 'letters': '\uA732'},
            {'base': 'AE', 'letters': '\u00C6\u01FC\u01E2'},
            {'base': 'AO', 'letters': '\uA734'},
            {'base': 'AU', 'letters': '\uA736'},
            {'base': 'AV', 'letters': '\uA738\uA73A'},
            {'base': 'AY', 'letters': '\uA73C'},
            {'base': 'B', 'letters': '\u0042\u24B7\uFF22\u1E02\u1E04\u1E06\u0243\u0182\u0181'},
            {'base': 'C', 'letters': '\u0043\u24B8\uFF23\u0106\u0108\u010A\u010C\u00C7\u1E08\u0187\u023B\uA73E'},
            {
                'base': 'D',
                'letters': '\u0044\u24B9\uFF24\u1E0A\u010E\u1E0C\u1E10\u1E12\u1E0E\u0110\u018B\u018A\u0189\uA779\u00D0'
            },
            {'base': 'DZ', 'letters': '\u01F1\u01C4'},
            {'base': 'Dz', 'letters': '\u01F2\u01C5'},
            {
                'base': 'E',
                'letters': '\u0045\u24BA\uFF25\u00C8\u00C9\u00CA\u1EC0\u1EBE\u1EC4\u1EC2\u1EBC\u0112\u1E14\u1E16\u0114\u0116\u00CB\u1EBA\u011A\u0204\u0206\u1EB8\u1EC6\u0228\u1E1C\u0118\u1E18\u1E1A\u0190\u018E'
            },
            {'base': 'F', 'letters': '\u0046\u24BB\uFF26\u1E1E\u0191\uA77B'},
            {
                'base': 'G',
                'letters': '\u0047\u24BC\uFF27\u01F4\u011C\u1E20\u011E\u0120\u01E6\u0122\u01E4\u0193\uA7A0\uA77D\uA77E'
            },
            {
                'base': 'H',
                'letters': '\u0048\u24BD\uFF28\u0124\u1E22\u1E26\u021E\u1E24\u1E28\u1E2A\u0126\u2C67\u2C75\uA78D'
            },
            {
                'base': 'I',
                'letters': '\u0049\u24BE\uFF29\u00CC\u00CD\u00CE\u0128\u012A\u012C\u0130\u00CF\u1E2E\u1EC8\u01CF\u0208\u020A\u1ECA\u012E\u1E2C\u0197'
            },
            {'base': 'J', 'letters': '\u004A\u24BF\uFF2A\u0134\u0248'},
            {
                'base': 'K',
                'letters': '\u004B\u24C0\uFF2B\u1E30\u01E8\u1E32\u0136\u1E34\u0198\u2C69\uA740\uA742\uA744\uA7A2'
            },
            {
                'base': 'L',
                'letters': '\u004C\u24C1\uFF2C\u013F\u0139\u013D\u1E36\u1E38\u013B\u1E3C\u1E3A\u0141\u023D\u2C62\u2C60\uA748\uA746\uA780'
            },
            {'base': 'LJ', 'letters': '\u01C7'},
            {'base': 'Lj', 'letters': '\u01C8'},
            {'base': 'M', 'letters': '\u004D\u24C2\uFF2D\u1E3E\u1E40\u1E42\u2C6E\u019C'},
            {
                'base': 'N',
                'letters': '\u004E\u24C3\uFF2E\u01F8\u0143\u00D1\u1E44\u0147\u1E46\u0145\u1E4A\u1E48\u0220\u019D\uA790\uA7A4'
            },
            {'base': 'NJ', 'letters': '\u01CA'},
            {'base': 'Nj', 'letters': '\u01CB'},
            {
                'base': 'O',
                'letters': '\u004F\u24C4\uFF2F\u00D2\u00D3\u00D4\u1ED2\u1ED0\u1ED6\u1ED4\u00D5\u1E4C\u022C\u1E4E\u014C\u1E50\u1E52\u014E\u022E\u0230\u00D6\u022A\u1ECE\u0150\u01D1\u020C\u020E\u01A0\u1EDC\u1EDA\u1EE0\u1EDE\u1EE2\u1ECC\u1ED8\u01EA\u01EC\u00D8\u01FE\u0186\u019F\uA74A\uA74C'
            },
            {'base': 'OI', 'letters': '\u01A2'},
            {'base': 'OO', 'letters': '\uA74E'},
            {'base': 'OU', 'letters': '\u0222'},
            {'base': 'OE', 'letters': '\u008C\u0152'},
            {'base': 'oe', 'letters': '\u009C\u0153'},
            {'base': 'P', 'letters': '\u0050\u24C5\uFF30\u1E54\u1E56\u01A4\u2C63\uA750\uA752\uA754'},
            {'base': 'Q', 'letters': '\u0051\u24C6\uFF31\uA756\uA758\u024A'},
            {
                'base': 'R',
                'letters': '\u0052\u24C7\uFF32\u0154\u1E58\u0158\u0210\u0212\u1E5A\u1E5C\u0156\u1E5E\u024C\u2C64\uA75A\uA7A6\uA782'
            },
            {
                'base': 'S',
                'letters': '\u0053\u24C8\uFF33\u1E9E\u015A\u1E64\u015C\u1E60\u0160\u1E66\u1E62\u1E68\u0218\u015E\u2C7E\uA7A8\uA784'
            },
            {
                'base': 'T',
                'letters': '\u0054\u24C9\uFF34\u1E6A\u0164\u1E6C\u021A\u0162\u1E70\u1E6E\u0166\u01AC\u01AE\u023E\uA786'
            },
            {'base': 'TZ', 'letters': '\uA728'},
            {
                'base': 'U',
                'letters': '\u0055\u24CA\uFF35\u00D9\u00DA\u00DB\u0168\u1E78\u016A\u1E7A\u016C\u00DC\u01DB\u01D7\u01D5\u01D9\u1EE6\u016E\u0170\u01D3\u0214\u0216\u01AF\u1EEA\u1EE8\u1EEE\u1EEC\u1EF0\u1EE4\u1E72\u0172\u1E76\u1E74\u0244'
            },
            {'base': 'V', 'letters': '\u0056\u24CB\uFF36\u1E7C\u1E7E\u01B2\uA75E\u0245'},
            {'base': 'VY', 'letters': '\uA760'},
            {'base': 'W', 'letters': '\u0057\u24CC\uFF37\u1E80\u1E82\u0174\u1E86\u1E84\u1E88\u2C72'},
            {'base': 'X', 'letters': '\u0058\u24CD\uFF38\u1E8A\u1E8C'},
            {
                'base': 'Y',
                'letters': '\u0059\u24CE\uFF39\u1EF2\u00DD\u0176\u1EF8\u0232\u1E8E\u0178\u1EF6\u1EF4\u01B3\u024E\u1EFE'
            },
            {
                'base': 'Z',
                'letters': '\u005A\u24CF\uFF3A\u0179\u1E90\u017B\u017D\u1E92\u1E94\u01B5\u0224\u2C7F\u2C6B\uA762'
            },
            {
                'base': 'a',
                'letters': '\u0061\u24D0\uFF41\u1E9A\u00E0\u00E1\u00E2\u1EA7\u1EA5\u1EAB\u1EA9\u00E3\u0101\u0103\u1EB1\u1EAF\u1EB5\u1EB3\u0227\u01E1\u00E4\u01DF\u1EA3\u00E5\u01FB\u01CE\u0201\u0203\u1EA1\u1EAD\u1EB7\u1E01\u0105\u2C65\u0250'
            },
            {'base': 'aa', 'letters': '\uA733'},
            {'base': 'ae', 'letters': '\u00E6\u01FD\u01E3'},
            {'base': 'ao', 'letters': '\uA735'},
            {'base': 'au', 'letters': '\uA737'},
            {'base': 'av', 'letters': '\uA739\uA73B'},
            {'base': 'ay', 'letters': '\uA73D'},
            {'base': 'b', 'letters': '\u0062\u24D1\uFF42\u1E03\u1E05\u1E07\u0180\u0183\u0253'},
            {
                'base': 'c',
                'letters': '\u0063\u24D2\uFF43\u0107\u0109\u010B\u010D\u00E7\u1E09\u0188\u023C\uA73F\u2184'
            },
            {
                'base': 'd',
                'letters': '\u0064\u24D3\uFF44\u1E0B\u010F\u1E0D\u1E11\u1E13\u1E0F\u0111\u018C\u0256\u0257\uA77A'
            },
            {'base': 'dz', 'letters': '\u01F3\u01C6'},
            {
                'base': 'e',
                'letters': '\u0065\u24D4\uFF45\u00E8\u00E9\u00EA\u1EC1\u1EBF\u1EC5\u1EC3\u1EBD\u0113\u1E15\u1E17\u0115\u0117\u00EB\u1EBB\u011B\u0205\u0207\u1EB9\u1EC7\u0229\u1E1D\u0119\u1E19\u1E1B\u0247\u025B\u01DD'
            },
            {'base': 'f', 'letters': '\u0066\u24D5\uFF46\u1E1F\u0192\uA77C'},
            {
                'base': 'g',
                'letters': '\u0067\u24D6\uFF47\u01F5\u011D\u1E21\u011F\u0121\u01E7\u0123\u01E5\u0260\uA7A1\u1D79\uA77F'
            },
            {
                'base': 'h',
                'letters': '\u0068\u24D7\uFF48\u0125\u1E23\u1E27\u021F\u1E25\u1E29\u1E2B\u1E96\u0127\u2C68\u2C76\u0265'
            },
            {'base': 'hv', 'letters': '\u0195'},
            {
                'base': 'i',
                'letters': '\u0069\u24D8\uFF49\u00EC\u00ED\u00EE\u0129\u012B\u012D\u00EF\u1E2F\u1EC9\u01D0\u0209\u020B\u1ECB\u012F\u1E2D\u0268\u0131'
            },
            {'base': 'j', 'letters': '\u006A\u24D9\uFF4A\u0135\u01F0\u0249'},
            {
                'base': 'k',
                'letters': '\u006B\u24DA\uFF4B\u1E31\u01E9\u1E33\u0137\u1E35\u0199\u2C6A\uA741\uA743\uA745\uA7A3'
            },
            {
                'base': 'l',
                'letters': '\u006C\u24DB\uFF4C\u0140\u013A\u013E\u1E37\u1E39\u013C\u1E3D\u1E3B\u017F\u0142\u019A\u026B\u2C61\uA749\uA781\uA747'
            },
            {'base': 'lj', 'letters': '\u01C9'},
            {'base': 'm', 'letters': '\u006D\u24DC\uFF4D\u1E3F\u1E41\u1E43\u0271\u026F'},
            {
                'base': 'n',
                'letters': '\u006E\u24DD\uFF4E\u01F9\u0144\u00F1\u1E45\u0148\u1E47\u0146\u1E4B\u1E49\u019E\u0272\u0149\uA791\uA7A5'
            },
            {'base': 'nj', 'letters': '\u01CC'},
            {
                'base': 'o',
                'letters': '\u006F\u24DE\uFF4F\u00F2\u00F3\u00F4\u1ED3\u1ED1\u1ED7\u1ED5\u00F5\u1E4D\u022D\u1E4F\u014D\u1E51\u1E53\u014F\u022F\u0231\u00F6\u022B\u1ECF\u0151\u01D2\u020D\u020F\u01A1\u1EDD\u1EDB\u1EE1\u1EDF\u1EE3\u1ECD\u1ED9\u01EB\u01ED\u00F8\u01FF\u0254\uA74B\uA74D\u0275'
            },
            {'base': 'oi', 'letters': '\u01A3'},
            {'base': 'ou', 'letters': '\u0223'},
            {'base': 'oo', 'letters': '\uA74F'},
            {'base': 'p', 'letters': '\u0070\u24DF\uFF50\u1E55\u1E57\u01A5\u1D7D\uA751\uA753\uA755'},
            {'base': 'q', 'letters': '\u0071\u24E0\uFF51\u024B\uA757\uA759'},
            {
                'base': 'r',
                'letters': '\u0072\u24E1\uFF52\u0155\u1E59\u0159\u0211\u0213\u1E5B\u1E5D\u0157\u1E5F\u024D\u027D\uA75B\uA7A7\uA783'
            },
            {
                'base': 's',
                'letters': '\u0073\u24E2\uFF53\u00DF\u015B\u1E65\u015D\u1E61\u0161\u1E67\u1E63\u1E69\u0219\u015F\u023F\uA7A9\uA785\u1E9B'
            },
            {
                'base': 't',
                'letters': '\u0074\u24E3\uFF54\u1E6B\u1E97\u0165\u1E6D\u021B\u0163\u1E71\u1E6F\u0167\u01AD\u0288\u2C66\uA787'
            },
            {'base': 'tz', 'letters': '\uA729'},
            {
                'base': 'u',
                'letters': '\u0075\u24E4\uFF55\u00F9\u00FA\u00FB\u0169\u1E79\u016B\u1E7B\u016D\u00FC\u01DC\u01D8\u01D6\u01DA\u1EE7\u016F\u0171\u01D4\u0215\u0217\u01B0\u1EEB\u1EE9\u1EEF\u1EED\u1EF1\u1EE5\u1E73\u0173\u1E77\u1E75\u0289'
            },
            {'base': 'v', 'letters': '\u0076\u24E5\uFF56\u1E7D\u1E7F\u028B\uA75F\u028C'},
            {'base': 'vy', 'letters': '\uA761'},
            {'base': 'w', 'letters': '\u0077\u24E6\uFF57\u1E81\u1E83\u0175\u1E87\u1E85\u1E98\u1E89\u2C73'},
            {'base': 'x', 'letters': '\u0078\u24E7\uFF58\u1E8B\u1E8D'},
            {
                'base': 'y',
                'letters': '\u0079\u24E8\uFF59\u1EF3\u00FD\u0177\u1EF9\u0233\u1E8F\u00FF\u1EF7\u1E99\u1EF5\u01B4\u024F\u1EFF'
            },
            {
                'base': 'z',
                'letters': '\u007A\u24E9\uFF5A\u017A\u1E91\u017C\u017E\u1E93\u1E95\u01B6\u0225\u0240\u2C6C\uA763'
            }
        ];

        var diacriticsMap = {};
        for (var i = 0; i < defaultDiacriticsRemovalMap.length; i++) {
            var letters = defaultDiacriticsRemovalMap [i].letters;
            for (var j = 0; j < letters.length; j++) {
                diacriticsMap[letters[j]] = defaultDiacriticsRemovalMap [i].base;
            }
        }

        // "what?" version ... http://jsperf.com/diacritics/12
        function removeDiacritics(str) {
            return str.replace(/[^\u0000-\u007E]/g, function (a) {
                return diacriticsMap[a] || a;
            });
        }

        return removeDiacritics(slug);
    };

    // Preserve backward compatibility
    window.xeki_admin = xeki_admin;


    // get params
    // console.log("get params");
    var params = getAllUrlParams();
    // console.log(params);
    if(typeof params.form !== 'undefined' && typeof params.action !== 'undefined' && typeof params.id !== 'undefined'){
        // console.log("works");
        var js_admin = new xeki_admin();
        js_admin.edit_item(params.form,params.id);
    }

})();
// vim: expandtab shiftwidth=4 tabstop=4 softtabstop=4:

jQuery.extend(xeki_admin, {

    alert: function (data, callback, options) {

    },

    ask: function (data, callback, options) {

    },

    img: function (src, options) {

    },

    load: function (url, options) {


    }

});




function getAllUrlParams(url) {

    // get query string from url (optional) or window
    var queryString = url ? url.split('?')[1] : window.location.search.slice(1);

    // we'll store the parameters here
    var obj = {};

    // if query string exists
    if (queryString) {

        // stuff after # is not part of query string, so get rid of it
        queryString = queryString.split('#')[0];

        // split our query string into its component parts
        var arr = queryString.split('&');

        for (var i=0; i<arr.length; i++) {
            // separate the keys and the values
            var a = arr[i].split('=');

            // in case params look like: list[]=thing1&list[]=thing2
            var paramNum = undefined;
            var paramName = a[0].replace(/\[\d*\]/, function(v) {
                paramNum = v.slice(1,-1);
                return '';
            });

            // set parameter value (use 'true' if empty)
            var paramValue = typeof(a[1])==='undefined' ? true : a[1];

            // (optional) keep case consistent
            paramName = paramName.toLowerCase();
            paramValue = paramValue.toLowerCase();

            // if parameter name already exists
            if (obj[paramName]) {
                // convert value to array (if still string)
                if (typeof obj[paramName] === 'string') {
                    obj[paramName] = [obj[paramName]];
                }
                // if no array index number specified...
                if (typeof paramNum === 'undefined') {
                    // put the value on the end of the array
                    obj[paramName].push(paramValue);
                }
                // if array index number specified...
                else {
                    // put the value at that index number
                    obj[paramName][paramNum] = paramValue;
                }
            }
            // if param name doesn't exist yet, set it
            else {
                obj[paramName] = paramValue;
            }
        }
    }

    return obj;
}
