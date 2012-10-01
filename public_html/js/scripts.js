var totalpages	= 0,
current_page	= 1,
doscrollevent	= true,
imagesLoaded	= 0,
objectsLoaded	= 0,
imagesToLoad	= 0,
objectsTotal	= 0,
handler	= null;

function wookmarking(){
    $('#tiles li').wookmark({
        autoResize: true,
        container: $('#main'),
        offset: 15
    });
    

    
    $('#tiles li').hover(function() {
        //$(this).stop().animate({'padding': '20px', 'top': '-=20px', 'left': '-=20px'}); 
        $(this).find('.grid-itm').stop().show();
        $(this).css({
            'z-index': 50
        });
    }, function() {
        //$(this).stop().animate({'padding': '0px', 'top': '+=20px', 'left': '+=20px'}); 
        $(this).find('.grid-itm').stop().fadeOut(function() {
            $(this).parent().stop().css({
                'z-index': 5
            });
        });
    });
//fancing()
}

$(window).load(function(){
    if($('canvas').is('*')) {
        webGLStart(); 
    }
   
});

$(document).ready(function() {
    runRemoveBtns();
    
    // popover demo
    $("img[rel=popover]")
    .popover({'trigger': 'hover', 'placement': 'top'})
    .click(function(e) {
        e.preventDefault();
        artworkURL = '/image.html?uri='+$(this).attr('data-img');
        artworkWidth = '256';
        artworkHeight = '256';
    })
    
    $('#btn-build').click(function() {
        mode = 'build';
    });
    $('#btn-place').click(function() {
        mode = 'place';
    });
    $('#btn-preview').click(function() {
        mode = 'preview';
    });
    
    $('#imgloadid').hide();
    
    if($('#mycollection').is('*')) {
    $(document).bind('scroll', scrollLoadImages);
    $('#simplesearchbtn').click(function() {
        run_search(); 
    });
    
    $('#searchfrm').submit(function() {
        run_search(); 
        return false; 
    });
    }
});


function run_search() {
    current_page = 1;
    $('#tiles').fadeOut(function() { 
        $('#tiles').html('').show();
        find_images();
    });
}


function find_images() {
    $('#imgloadid').show();
    doscrollevent = false;
    $.ajax({
        'url': '/system/search.html?startpage='+current_page+'&term='+$('#simplesearch').val(),
        'dataType': 'json',
        'success': function(data) {
            current_page++;
            objectsTotal = data.totalResults
            totalpages = Math.ceil(data.totalResults / data.itemsPerPage) - 1
            $("#count").html(data.totalResults)
            $.each(data.items, function(i){
                
                $.getJSON("/system/requestobject.html?uri="+encodeURIComponent(data.items[i].link.replace('http://www.europeana.eu/portal/record/', '')), function(item){
                    objectsLoaded++
                    
                    if(item['europeana:object'] != undefined){
                        newimg = new Image()
                        newimg.src = "http://social.apps.lv/image.php?w=200&zc=2&src="+encodeURIComponent(item['europeana:object'])
                        newimg.onload = function(){
                            imagesLoaded++
                            var subjects = []
                            if(typeof(item['dc:subject']) == "object"){
                                $.each(item['dc:subject'], function(i){
                                    subjects.push("<a href='/search?q="+encodeURIComponent(item['dc:subject'][i].replace("'","%27"))+"'>"+item['dc:subject'][i]+"</a>")
                                })
                            } else if(typeof(item['dc:subject']) == "string"){
                                subjects.push("<a href='/search?q="+encodeURIComponent(item['dc:subject'].replace("'","%27"))+"'>"+item['dc:subject']+"</a>")
                            } else {
                                subjects.push("undefined")
                            }
                            imagesLoaded++
                                
                            var object_title = item['dc:title'];
                            var plain_text_title = item['dc:title'];
                            //figure out title
                            if($.isArray(item['dc:title'])) {
                                object_title = item['dc:title'][0];
                                plain_text_title = item['dc:title'][0];
                            }
                            
                            if(item['dc:title']) {
                                object_title = '<h2>'+object_title+'</h2>';
                            } else {
                                object_title = '';
                            }
                            
                            var created = item['dcterms:created'];
                            if(created != 'undefined') {
                                object_title = object_title+'<h3>'+created+'</h3>';
                            }
                            
                            var creator= item['dc:creator'];
                            if(creator != 'undefined') {
                                object_title = ' '+object_title+'<h4>'+creator+'</h4>';
                            }
                            
                            
                            //Thumb Img
                            var imgsrc;
                            if(item['europeana:isShownBy']) {
                                imgsrc = item['europeana:isShownBy'];
                            } else {
                                imgsrc = item['europeana:object'];
                            }
                            imgsrc = encodeURIComponent(imgsrc.replace(/\s/g,"%20").replace("'","%27"));
                                
                            var moreinfo = '';
                            if(item['europeana:isShownAt']) {
                                moreinfo = "<a href='"+item['europeana:isShownAt']+"' class='btn btn-inverse btn-small' target='_blank'>Info</a>";
                            }
                                
                            $("#tiles").append(
                                "<li><a class='imagepopup' href='#popup'><img width='"+this.width+
                                "' height='"+this.height+
                                "' src='http://social.apps.lv/image.php?w=200&zc=3&src="+imgsrc+
                                "' /></a>"+
                                "<div class='grid-itm'>"+object_title+"<a href='javascript:;' class='btn btn-inverse btn-inverse btn-small'>Add</a> "+moreinfo+"</div>"+
                                "</li>");
                            $("#tiles li:last").data('europeana', item);
                            $("#tiles li:last").click(function() {
                                var item = $(this).data('europeana');
                                var plain_title = '';
                                if(item['dc:title']) {
                                    plain_title = item['dc:title'];
                                } else {
                                    plain_title = '';
                                }
                                
                                var description = '';
                                if(item['dcterms:alternative']) {
                                    description = item['dcterms:alternative'][1];
                                }
                                
                                $.ajax({
                                    'url': '/system/save.html',
                                    'type': 'POST',
                                    'data': item,
                                    'success': function(res) {
                                       
                                    }
                                });
                                
                                var rmv = 'a'+hex_md5(item['europeana:object']);
                                
                                $('#mycollection').append(
                                    '<li>'+
                                    '<div class="pull-left" style="margin-right: 5px;"><img '+
                                    "src='http://social.apps.lv/image.php?w=100&zc=3&src="+encodeURIComponent(item['europeana:object'].replace(/\s/g,"%20").replace("'","%27"))+"' /></div>"+
                                    '<div class="pull-left" style="width: 179px; padding: 3px;">'+
                                    '<input class="span12" type="text" placeholder="Title" value="'+plain_title+'" />'+
                                    '<textarea placeholder="Description" class="span12">'+description+'</textarea>'+
                                    '<button class="btn btn-danger btn-small" title="'+rmv+'">Remove</button>'+
                                    '</div><div class="clearfix"></div></li>'
                                    );
                                runRemoveBtns();
                            });
                            $("#tiles li:last").hide();
                            $("#tiles li:last").fadeIn();
                            if(handler) {  
                                handler.wookmarkClear();
                            }
                            wookmarking();
                            
                            $("img").error(function(){
                                console.log($(this))
                                $(this).remove()
                            });
                        }
                    }
                });
            });
            doscrollevent = true;
            $('#imgloadid').show();
        }
    });
   
}


function scrollLoadImages(event){
    if(doscrollevent){
        var closeToBottom = ($(window).scrollTop() + $(window).height() > $(document).height() - 1)
        if(closeToBottom){
            find_images()
            if(handler){
                handler.wookmarkClear()
            }
            wookmarking()
        }
    }
}

function runRemoveBtns() {
    $('#mycollection button').click(function() {
        var calc = $(this).attr('title');
        if($(this).attr('title') != '') {
            var rent = $(this).parent().parent();
            $.ajax({
                'url': '/system/remove.html',
                'type': 'POST',
                'data': {
                    'code': calc
                },
                'success': function(res) {
                    rent.remove();
                }
            });
            $(this).attr('title', '');
        }
    });
}