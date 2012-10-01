<div class="span3">
    <div class="row-fluid">
        <div class="span12">
            <div class="well" id="wellfindadd">
                <form id="searchfrm">
                    <fieldset>
                        <div class="input-append">
                            <input class="span9" id="simplesearch" x-webkit-speech speech size="32" type="text"><button class="btn btn-inverse" type="button" id="simplesearchbtn">Search!</button>
                        </div>
                    </fieldset>
                </form>
                <div id="mycollection-wrap">
                    <ul id="mycollection">
                        <?php
                        //echo '<pre>';
                        //$this->session->collection = array();
                        foreach ($this->session->collection as $item) {
                            
                         echo '<li>'.
                                    '<div class="pull-left" style="margin-right: 5px;"><img '.
                                    "src='http://social.apps.lv/image.php?w=100&zc=3&src=".urlencode($item['europeana:object'])."' /></div>".
                                    '<div class="pull-left" style="width: 179px; padding: 3px;">'.
                                    '<input class="span12" type="text" placeholder="Title" value="'.$item['plain_title'].'" />'.
                                    '<textarea placeholder="Description" class="span12">'.$item['description'].'</textarea>'.
                                    '<button class="btn btn-danger btn-small" title="'.md5($item['europeana:object']).'">Remove</button>'.
                                    '</div><div class="clearfix"></div></li>';   
                        }
                        ?>
                    </ul>
                </div>

                <a class="btn pull-right btn-inverse nextstep" href="/build.html">Next Step</a>
            </div>
        </div>
    </div><!--/.well -->
</div>
<div class="span9">
    <h1>Build your collection <span id="imgloadid"><img src="/img/blue-load.gif" alt="Loading..."></span></h1>
    <div id="main" class="clearfix"><ul id="tiles"></ul></div>
</div>